<?php

namespace Modules\Contact\Database\Seeders\Backend\Shipment;

use Illuminate\Database\Eloquent\Model;

/**
 * @class Backend/Shipment/InvoiceTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Shipment
 */
class Backend/Shipment/InvoiceTableSeeder extends Seeder
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
