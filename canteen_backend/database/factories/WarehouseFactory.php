<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 2,
            'item_name' => $this->faker->word,
            'item_code' => $this->faker->numberBetween(100000, 999999999),
            'type' => $this->faker->word,
            'batch_no' => strtoupper($this->faker->bothify('BATCH###')),
            'location' => $this->faker->city,
            'branch' => $this->faker->company,
            'quantity' => $this->faker->numberBetween(1, 1000),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'status' => 'active',
            'expiry' => $this->faker->date('Y-m-d', '2025-12-31'),
        ];
    }
}
