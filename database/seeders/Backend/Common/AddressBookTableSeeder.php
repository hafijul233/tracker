<?php

namespace Database\Seeders\Backend\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class Backend/Common/AddressBookTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Common
 */
class AddressBookTableSeeder extends Seeder
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
