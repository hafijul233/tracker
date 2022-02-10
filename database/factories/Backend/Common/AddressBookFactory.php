<?php

namespace Database\Factories\Backend\Common;

use App\Models\Backend\Common\Address;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressBookFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Address::class;

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
            'city_id' => config('contact.default.city'),
            'state_id' => config('contact.default.state'),//Dhaka
            'country_id' => config('contact.default.country') //Bangladesh
        ];
    }
}
