<?php

namespace Database\Seeders\Setting;

use App\Models\Backend\Common\AddressBook;
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
        User::factory(10)
            ->has(AddressBook::factory()
                ->count(2), 'addressBooks')
            ->create();
    }
}
