<?php

namespace Modules\Contact\Database\Seeders\Backend\Setting;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Backend/Setting/BarcodeTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Setting
 */
class Backend/Setting/BarcodeTableSeeder extends Seeder
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
