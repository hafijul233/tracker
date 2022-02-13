<?php

namespace Database\Seeders\Backend\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class Backend/Setting/BarcodeTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Setting
 */
class BarcodeTableSeeder extends Seeder
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
