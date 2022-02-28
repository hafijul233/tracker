<?php

namespace Database\Seeders\Backend\Transpoprt;

use App\Models\Backend\Setting\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class Backend/Transport/DriverTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Transport
 */
class DriverSeeder extends Seeder
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
            ->asDriver()
            ->create();
    }
}
