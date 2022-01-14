<?php

namespace Modules\Contact\Database\Seeders\Backend\Common;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Backend/Common/AddressBookTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Common
 */
class Backend/Common/AddressBookTableSeeder extends Seeder
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
