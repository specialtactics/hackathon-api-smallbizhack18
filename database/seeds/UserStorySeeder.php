<?php

use App\Models\Role;
use App\Models\User;

class UserStorySeeder extends BaseSeeder
{
    public function runFake() {
        // Grab all roles for reference
        $roles = Role::all();

        // Create an admin user
        factory(App\Models\User::class)->create([
            'name'         => 'Admin',
            'email'        => 'admin@admin.com',
            'primary_role' => $roles->where('name', Role::ROLE_ADMIN)->first()->role_id,
        ]);


    }
}
