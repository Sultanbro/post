<?php

namespace Database\Seeders;

use App\Models\Client\Client;
use App\Models\Client\Department;
use App\Models\Permission;
use App\Models\Post\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local', 'testing')) {
            throw new RuntimeException('Demo data is only available in local and testing environments.');
        }

        DB::transaction(function () {
            $company = Client::firstOrCreate(['short_name' => 'Demo Company'], [
                'full_name' => 'Demo Company', 'juridical_bool' => true,
                'type_id' => 1, 'created_by' => 0, 'updated_by' => 0,
            ]);
            Department::firstOrCreate(['id' => $company->id], [
                'parent_id' => 0, 'short_name' => 'Demo Company', 'full_name' => 'Demo Company',
                'is_company' => true, 'created_by' => 0, 'updated_by' => 0,
            ]);

            foreach (['editor', 'reader'] as $username) {
                $client = Client::firstOrCreate(['short_name' => 'Demo ' . $username], [
                    'full_name' => 'Demo ' . ucfirst($username), 'first_name' => ucfirst($username),
                    'last_name' => 'Demo', 'juridical_bool' => false, 'type_id' => 1,
                    'created_by' => 0, 'updated_by' => 0,
                ]);
                $user = User::firstOrCreate(['username' => $username], [
                    'client_id' => $client->id, 'email' => $username . '@example.invalid',
                    'password' => Hash::make('local-demo-only'), 'type_id' => 1,
                    'department_id' => $company->id, 'company_id' => $company->id,
                    'duty_id' => 0, 'created_by' => 0, 'updated_by' => 0,
                ]);
                $role = Role::firstOrCreate(['slug' => 'demo-' . $username], [
                    'name' => 'Demo ' . $username, 'company_id' => $company->id,
                ]);
                $slugs = $username === 'editor'
                    ? ['show_post', 'create_post', 'update_post', 'delete_post']
                    : ['show_post'];
                foreach ($slugs as $slug) {
                    $permission = Permission::firstOrCreate(['slug' => $slug], ['name' => $slug]);
                    $role->permissions()->syncWithoutDetaching([$permission->id]);
                }
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

            $editor = User::where('username', 'editor')->firstOrFail();
            Post::firstOrCreate(['content' => 'Welcome to the local demo.'], [
                'user_id' => $editor->id, 'company_id' => $company->id, 'group_id' => 1,
                'created_by' => $editor->id, 'updated_by' => $editor->id,
            ]);
        });
    }
}
