<?php

namespace Modules\Contact\Database\Seeders\Backend\Organization;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Backend/Organization/EmployeeTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Organization
 */
class Backend/Organization/EmployeeTableSeeder extends Seeder
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
