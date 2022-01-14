<?php

namespace Database\Seeders;

use Database\Seeders\Setting\CountrySeeder;
use Database\Seeders\Setting\OccupationSeeder;
use Database\Seeders\Setting\PermissionSeeder;
use Database\Seeders\Setting\RolePermissionSeeder;
use Database\Seeders\Setting\RoleSeeder;
use Database\Seeders\Setting\StateSeeder;
use Database\Seeders\Setting\UserSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(OccupationSeeder::class);

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UserRegisterSeeder::class);
    }
}
