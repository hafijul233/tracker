<?php

namespace Database\Factories\Backend\Common;

use App\Models\Backend\Common\AddressBook;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressBookFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = AddressBook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'type' => 'bill',
            'phone' => str_replace('+', '', $this->faker->unique()->e164PhoneNumber()),
            'name' => $this->faker->name(),
            'address' => $this->faker->streetAddress(),
            'post_code' => $this->faker->postcode(),
            'remark' => $this->faker->paragraph(2),
            'enabled' => Constant::ENABLED_OPTION,
            'city_id' => null,
            'state_id' => 771,//Dhaka
            'country_id' => 18 //Bangladesh
        ];
    }
}
