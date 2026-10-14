<?php

namespace Tests\Feature;

use App\Models\Post\Post;
use App\Models\User;
use App\Models\UserToken;
use App\Providers\PermissionServiceProvider;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase { refreshDatabase as refreshIsolatedDatabase; }

    public function refreshDatabase()
    {
        // Never allow migrate:fresh against a developer's application database.
        $connection = config('database.default');
        if (!app()->environment('testing') || config("database.connections.$connection.database") !== 'post_test') {
            throw new \RuntimeException('API tests require APP_ENV=testing and the post_test database.');
        }
        $this->refreshIsolatedDatabase();
    }

    private int $identityStatus = 200;
    private array $identityBody = ['sub' => 'demo-user'];
    private bool $identityDisconnected = false;
    private int $tokenStatus = 200;
    private array $tokenBody = ['access_token' => 'demo-access', 'refresh_token' => 'demo-refresh'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
        // Permissions are loaded from the database, which was just migrated/seeded.
        (new PermissionServiceProvider($this->app))->boot();
        config(['keycloak.token_url' => 'https://identity.example.invalid/token',
                'keycloak.userinfo_url' => 'https://identity.example.invalid/userinfo']);
        Http::fake(function ($request) {
            if ($request->url() === 'https://identity.example.invalid/token') {
                return Http::response($this->tokenBody, $this->tokenStatus);
            }
            if ($request->url() === 'https://identity.example.invalid/userinfo') {
                if ($this->identityDisconnected) {
                    throw new \Illuminate\Http\Client\ConnectionException('Identity provider unavailable');
                }
                return Http::response($this->identityBody, $this->identityStatus);
            }
            throw new \RuntimeException('Unexpected HTTP request during API tests.');
        });
    }

    private function tokenFor(string $username): string
    {
        $user = User::where('username', $username)->firstOrFail();
        $token = 'demo-' . $username;
        UserToken::create(['user_id' => $user->id, 'access_token' => $token,
                          'refresh_token' => 'demo-refresh', 'role_id' => 1]);
        return $token;
    }

    private function postData(): array
    {
        return ['content' => 'A test publication',
                'company_id' => User::where('username', 'editor')->value('company_id'), 'group_id' => 1];
    }

    public function test_anonymous_requests_are_rejected(): void
    {
        $this->getJson('/api/posts')->assertUnauthorized();
        Http::assertNothingSent();
    }

    public function test_unknown_tokens_are_rejected_without_contacting_identity_provider(): void
    {
        $this->withToken('unknown-token')->getJson('/api/posts')->assertUnauthorized();
        Http::assertNothingSent();
    }

    public function test_login_stores_the_token_and_requests_openid_scope(): void
    {
        $this->postJson('/api/login', ['username' => 'editor', 'password' => 'local-demo-only'])
            ->assertOk()->assertJsonPath('access_token', 'demo-access');
        $this->assertDatabaseHas('user_tokens', ['access_token' => 'demo-access']);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://identity.example.invalid/token'
                && ($request['scope'] ?? null) === 'openid'
                && $request['username'] === 'editor';
        });
    }

    public function test_failed_login_does_not_save_a_token(): void
    {
        $this->tokenBody = ['error' => 'invalid_grant'];
        $this->tokenStatus = 401;
        $this->postJson('/api/login', ['username' => 'editor', 'password' => 'incorrect'])
            ->assertForbidden();
        $this->assertDatabaseCount('user_tokens', 0);
    }

    public function test_reader_can_list_posts_but_cannot_create_them(): void
    {
        $this->withToken($this->tokenFor('reader'));
        $this->getJson('/api/posts')->assertOk()->assertJsonPath('data.0.content', 'Welcome to the local demo.');
        $this->postJson('/api/posts', $this->postData())->assertForbidden();
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_token_endpoint_failure_does_not_create_a_session(): void
    {
        $this->tokenStatus = 503;
        $this->tokenBody = ['message' => 'Unavailable'];
        $this->postJson('/api/login', ['username' => 'editor', 'password' => 'local-demo-only'])
            ->assertForbidden();
        $this->assertDatabaseCount('user_tokens', 0);
    }

    public function test_editor_can_create_update_and_delete_a_post(): void
    {
        $editor = User::where('username', 'editor')->firstOrFail();
        $this->withToken($this->tokenFor('editor'));
        $response = $this->postJson('/api/posts', $this->postData())
            ->assertCreated()->assertJsonPath('data.content', 'A test publication');
        $id = $response->json('data.id');
        $this->assertDatabaseHas('posts', ['id' => $id, 'user_id' => $editor->id]);
        $this->putJson('/api/posts/' . $id, ['content' => 'Edited publication'])->assertOk();
        $this->assertDatabaseHas('posts', ['id' => $id, 'content' => 'Edited publication']);
        $this->deleteJson('/api/posts/' . $id)->assertOk();
        $this->assertDatabaseMissing('posts', ['id' => $id]);
    }

    public function test_post_content_is_required(): void
    {
        $data = $this->postData();
        unset($data['content']);
        $this->withToken($this->tokenFor('editor'))->postJson('/api/posts', $data)
            ->assertUnprocessable()->assertJsonValidationErrors('content');
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_reader_cannot_modify_another_users_post(): void
    {
        $id = Post::firstOrFail()->id;
        $this->withToken($this->tokenFor('reader'));
        $this->putJson('/api/posts/' . $id, ['content' => 'Changed'])->assertForbidden();
        $this->deleteJson('/api/posts/' . $id)->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $id, 'content' => 'Welcome to the local demo.']);
    }

    public function test_identity_provider_failure_does_not_grant_access(): void
    {
        $token = $this->tokenFor('editor');
        $this->identityBody = ['message' => 'Unavailable'];
        $this->identityStatus = 503;
        $this->withToken($token)->getJson('/api/posts')->assertUnauthorized();
    }

    public function test_expired_token_cannot_be_used_to_refresh_a_session_implicitly(): void
    {
        $token = $this->tokenFor('editor');
        $this->identityBody = ['error' => 'invalid_token'];
        $this->identityStatus = 401;
        $this->withToken($token)->getJson('/api/posts')->assertUnauthorized();
        Http::assertSentCount(1);
    }

    public function test_identity_response_without_a_subject_is_rejected(): void
    {
        $this->identityBody = [];
        $this->withToken($this->tokenFor('editor'))->getJson('/api/posts')->assertUnauthorized();
    }

    public function test_identity_connection_failure_does_not_grant_access(): void
    {
        $this->identityDisconnected = true;
        $this->withToken($this->tokenFor('editor'))->getJson('/api/posts')->assertUnauthorized();
    }

    public function test_logout_removes_the_local_session(): void
    {
        $this->withToken($this->tokenFor('editor'))->postJson('/api/logout')->assertOk();
        $this->assertDatabaseCount('user_tokens', 0);
        $this->getJson('/api/posts')->assertUnauthorized();
    }

    public function test_demo_seed_is_repeatable(): void
    {
        $this->seed(DemoSeeder::class);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseCount('roles', 2);
    }
}
