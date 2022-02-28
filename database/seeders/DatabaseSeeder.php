<?php

namespace Database\Seeders;

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
        /*$this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(OccupationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);*/

        //this are system user
/*        $this->call(UserSeeder::class);*/
        $this->call(UserRegisterSeeder::class);

    }
}
