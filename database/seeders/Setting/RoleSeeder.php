<?php

namespace Database\Seeders\Setting;

use App\Models\Backend\Setting\Role;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'name' => Constant::SUPER_ADMIN_ROLE,
            'remarks' => 'Role which will have all privileges.'
        ]);

        Role::create([
            'id' => 2,
            'name' => 'Administrator',
            'remarks' => 'Role which will have visible privileges.'
        ]);

        Role::create([
            'id' => 3,
            'name' => 'Manager',
            'remarks' => 'Role which will have basic pre-villages.'
        ]);

        Role::create([
            'id' => 4,
            'name' => 'Accountant',
            'remarks' => 'Role which will have all privileges.'
        ]);

        Role::create([
            'id' => 5,
            'name' => 'Driver',
            'remarks' => 'Role which will have all privileges.'
        ]);

        Role::create([
            'id' => 6,
            'name' => 'Helper',
            'remarks' => 'Role which will have all privileges.'
        ]);

        Role::create([
            'id' => 7,
            'name' => 'Sender',
            'remarks' => 'Role which will no privileges.'
        ]);

        Role::create([
            'id' => 8,
            'name' => 'Recipient',
            'remarks' => 'Role which will no privileges.'
        ]);
    }
}
