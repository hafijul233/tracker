<?php

namespace Database\Factories\Backend\Shipment;

use App\Models\Backend\Shipment\Item;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ItemFactory
 * @package Database\Factories\Backend\Shipment
 */
class ItemFactory extends Factory
{

    /**
     * @var Item $model
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'dimension' => mt_rand(100, 999) .'X' . mt_rand(100, 999) .'X' . mt_rand(100, 999),
            'rate' => mt_rand(20, 500),
            'currency' => 'BDT',
            'tax' => null,
            'description' => $this->faker->paragraph(2),
            'enabled' => Constant::ENABLED_OPTION
        ];
    }
}
