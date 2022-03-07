<?php

namespace Database\Seeders\Backend\Shipment;

use App\Models\Backend\Shipment\Item;
use App\Repositories\Eloquent\Backend\Setting\UserRepository;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * @class Backend/Shipment/ItemTableSeeder
 * @package Modules\Contact\Database\Seeders\Backend\Shipment
 */
class ItemSeeder extends Seeder
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ItemSeeder constructor.
     */
    public function __construct()
    {

        $this->userRepository = new UserRepository();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        Model::unguard();

        $users = $this->userRepository->getWith(['role' => [Constant::SENDER_ROLE_ID, Constant::RECEIVER_ROLE_ID]]);

        foreach ($users as $user) {
            Item::factory()->count(mt_rand(2, 10))->for($user)->create();
        }
    }
}
