<?php

namespace Database\Factories\Backend\Common;

use App\Models\Backend\Common\Address;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Factories\Factory;
use function config;

class AddressFactory extends Factory
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
            'type' => 'home',
            'phone'=> str_replace('+', '0', $this->faker->unique()->e164PhoneNumber()),
            'name'=> $this->faker->name(),
            'street_1'=> $this->faker->streetAddress(),
            'street_2' => $this->faker->streetName(),
            'url' => $this->faker->url(),
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
            'post_code' => $this->faker->postcode(),
            'fallback' => Constant::DISABLED_OPTION,
            'enabled' => Constant::ENABLED_OPTION,
            'remark' => $this->faker->paragraph(2),
            'city_id' => config('contact.default.city'),
            'state_id' => config('contact.default.state'),//Dhaka
            'country_id' => config('contact.default.country') //Bangladesh
        ];
    }
}
