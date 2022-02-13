<?php

namespace Database\Seeders\Backend\Setting;

use App\Models\Backend\Common\Address;
use App\Models\Backend\Setting\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception|\Throwable
     */
    public function run()
    {
        User::factory()
            ->count(15)
            ->for(Address::factory()
                ->count(1)
                ->state(function (array $attributes, User $user) {
                    return ['type' => 'home'];
                }), 'addresses')
            ->create();
    }
}
