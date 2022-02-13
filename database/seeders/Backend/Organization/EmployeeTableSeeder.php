<?php

namespace Database\Seeders\Backend\Organization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class Backend/Organization/EmployeeTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Organization
 */
class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
