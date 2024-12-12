<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{WeatherHistory, City};

/**
 * @extends Factory<WeatherHistory>
 */
class WeatherHistoryFactory extends Factory
{
    protected $model = WeatherHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'avg_temperature' => $this->faker->numberBetween(-10, 10),
            'date' => $this->faker->dateTimeBetween(startDate: '-10 days'),
        ];
    }
}
