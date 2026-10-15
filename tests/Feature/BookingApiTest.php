<?php

namespace Tests\Feature;

use App\Models\Booking\Booking;
use App\Models\Booking\Room;
use App\Models\Client\Client;
use App\Models\User;
use App\Models\UserToken;
use App\Providers\PermissionServiceProvider;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase { refreshDatabase as refreshIsolatedDatabase; }

    public function refreshDatabase()
    {
        $connection = config('database.default');
        if (!app()->environment('testing') || config("database.connections.$connection.database") !== 'post_test') {
            throw new \RuntimeException('Booking tests require the isolated post_test database.');
        }
        $this->refreshIsolatedDatabase();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
        (new PermissionServiceProvider($this->app))->boot();
        config(['keycloak.userinfo_url' => 'https://identity.example.invalid/userinfo']);
        Http::fake(['https://identity.example.invalid/userinfo' => Http::response(['sub' => 'demo-user'])]);
        $this->loginAs('editor');
    }

    private function loginAs($name)
    {
        $user = User::where('username', $name)->firstOrFail();
        UserToken::firstOrCreate(['access_token' => 'demo-' . $name], [
            'user_id' => $user->id, 'refresh_token' => 'demo-refresh', 'role_id' => 1,
        ]);
        $this->withToken('demo-' . $name);
    }

    private function data(array $extra = []): array
    {
        return array_merge(['room_id' => Room::firstOrFail()->id,
            'begin' => '2030-01-15 10:00:00', 'end' => '2030-01-15 11:00:00', 'description' => 'Demo meeting'], $extra);
    }

    private function createBooking(): int
    {
        return $this->postJson('/api/booking', $this->data())->assertOk()->assertJsonPath('success', true)->json('data.id');
    }

    public function test_booking_lifecycle_and_author_fields(): void
    {
        $id = $this->postJson('/api/booking', $this->data(['created_by' => 999, 'updated_by' => 999]))
            ->assertOk()->json('data.id');
        $this->assertDatabaseHas('bookings', ['id' => $id, 'created_by' => User::where('username', 'editor')->value('id')]);
        $this->getJson('/api/booking')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/booking/' . $id)->assertOk()->assertJsonPath('data.id', $id);
        $this->patchJson('/api/booking/' . $id, ['description' => 'Updated'])->assertOk()->assertJsonPath('data.description', 'Updated');
        $this->deleteJson('/api/booking/' . $id)->assertOk();
        $this->assertDatabaseMissing('bookings', ['id' => $id]);
    }

    /** @dataProvider overlappingIntervals */
    public function test_overlapping_intervals_are_rejected($begin, $end): void
    {
        $this->createBooking();
        $this->postJson('/api/booking', $this->data(['begin' => $begin, 'end' => $end]))
            ->assertStatus(409)->assertJsonPath('success', false);
        $this->assertDatabaseCount('bookings', 1);
    }

    public function overlappingIntervals(): array
    {
        return [
            ['2030-01-15 09:30:00', '2030-01-15 10:30:00'],
            ['2030-01-15 10:30:00', '2030-01-15 11:30:00'],
            ['2030-01-15 10:15:00', '2030-01-15 10:45:00'],
            ['2030-01-15 09:00:00', '2030-01-15 12:00:00'],
            ['2030-01-15 10:00:00', '2030-01-15 11:00:00'],
            ['2030-01-14 23:00:00', '2030-01-16 01:00:00'],
        ];
    }

    public function test_adjacent_intervals_and_other_rooms_are_allowed(): void
    {
        $this->createBooking();
        $this->postJson('/api/booking', $this->data(['begin' => '2030-01-15 11:00:00', 'end' => '2030-01-15 12:00:00']))->assertOk();
        $room = Room::firstOrFail()->replicate();
        $room->name = 'Second demo room';
        $room->save();
        $this->postJson('/api/booking', $this->data(['room_id' => $room->id]))->assertOk();
        $this->assertDatabaseCount('bookings', 3);
    }

    public function test_invalid_time_and_missing_room_are_rejected(): void
    {
        foreach ([['end' => '2030-01-15 09:00:00'], ['end' => '2030-01-15 10:00:00'],
            ['begin' => 'not-a-date'], ['room_id' => 999999], ['room_id' => null]] as $data) {
            $this->postJson('/api/booking', $this->data($data))->assertUnprocessable();
        }
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_update_checks_conflicts_without_conflicting_with_itself(): void
    {
        $id = $this->createBooking();
        $this->putJson('/api/booking/' . $id, $this->data())->assertOk();
        $this->postJson('/api/booking', $this->data(['begin' => '2030-01-15 12:00:00', 'end' => '2030-01-15 13:00:00']))->assertOk();
        $this->patchJson('/api/booking/' . $id, ['end' => '2030-01-15 12:30:00'])->assertStatus(409);
        $this->assertDatabaseHas('bookings', ['id' => $id, 'end' => '2030-01-15 11:00:00']);
    }

    public function test_reader_can_view_but_cannot_write_bookings(): void
    {
        $id = $this->createBooking();
        $this->loginAs('reader');
        $this->getJson('/api/booking')->assertOk();
        $this->postJson('/api/booking', $this->data())->assertForbidden();
        $this->patchJson('/api/booking/' . $id, ['description' => 'Denied'])->assertForbidden();
        $this->deleteJson('/api/booking/' . $id)->assertForbidden();
    }

    public function test_another_companys_bookings_are_not_accessible(): void
    {
        $company = Client::firstOrFail()->replicate();
        $company->short_name = 'Other company';
        $company->save();
        $room = Room::firstOrFail()->replicate();
        $room->company_id = $company->id;
        $room->save();
        $booking = Booking::create(array_merge($this->data(['room_id' => $room->id]), ['created_by' => 1, 'updated_by' => 1]));
        $this->getJson('/api/booking')->assertOk()->assertJsonCount(0, 'data');
        $this->getJson('/api/booking/' . $booking->id)->assertNotFound();
        $this->postJson('/api/booking', $this->data(['room_id' => $room->id]))->assertNotFound();
        $this->patchJson('/api/booking/' . $booking->id, ['description' => 'Denied'])->assertNotFound();
        $this->deleteJson('/api/booking/' . $booking->id)->assertNotFound();
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    public function test_missing_booking_returns_404(): void
    {
        $this->getJson('/api/booking/999999')->assertNotFound();
        $this->patchJson('/api/booking/999999', ['description' => 'Missing'])->assertNotFound();
        $this->deleteJson('/api/booking/999999')->assertNotFound();
    }
}
