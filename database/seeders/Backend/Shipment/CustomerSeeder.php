<?php

namespace Database\Seeders\Backend\Shipment;

use App\Models\Backend\Setting\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class Backend/Shipment/CustomerTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Shipment
 */
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        User::factory()
            ->count(25)
            ->asCustomer()
            ->create();
    }
}
