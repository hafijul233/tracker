<?php

namespace Modules\Contact\Database\Seeders\Backend\Shipment;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Backend/Shipment/TransactionTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Shipment
 */
class Backend/Shipment/TransactionTableSeeder extends Seeder
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
