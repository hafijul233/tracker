<?php

namespace Database\Seeders;

use Database\Seeders\Backend\Setting\CitySeeder;
use Database\Seeders\Backend\Setting\CountrySeeder;
use Database\Seeders\Backend\Setting\OccupationSeeder;
use Database\Seeders\Backend\Setting\PermissionSeeder;
use Database\Seeders\Backend\Setting\RolePermissionSeeder;
use Database\Seeders\Backend\Setting\RoleSeeder;
use Database\Seeders\Backend\Setting\StateSeeder;
use Database\Seeders\Backend\Setting\UserSeeder;
use Database\Seeders\Backend\Shipment\CustomerSeeder;
use Database\Seeders\Backend\Shipment\ItemSeeder;
use Database\Seeders\Backend\Transpoprt\DriverSeeder;
use Database\Seeders\Backend\UserRegisterSeeder;
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
        $this->call(CitySeeder::class);
        $this->call(OccupationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(DriverSeeder::class);
        $this->call(UserRegisterSeeder::class);

    }
}
