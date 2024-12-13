<?php

namespace App\Services;

use App\DTO\WeatherDTO;
use App\Models\City;
use App\Enums\WeatherSource;
use App\Exceptions\InvalidCityException;

class WeatherService
{
    public function __construct(
        protected WeatherClientService $weatherClientService
    )
    {}

    /**
     * @throws InvalidCityException
     */
    public function getCurrentTemperature(WeatherDTO $weatherDTO): array
    {
        $city = City::query()
            ->where('name', $weatherDTO->city)
            ->first();

        if ($city === null) {
            throw new InvalidCityException("The passed $weatherDTO->city city does not exist");
        }

        $temperature = match ($weatherDTO->source) {
            WeatherSource::Internal => $this->generateFakeTemperature(),
            WeatherSource::External => $this->weatherClientService->getCachedTemperature($city->name),
        };

        $avgTemperature = $city->weatherHistory()->avg('avg_temperature');

        $trend = match (true) {
            $avgTemperature === null, $avgTemperature == 0 => '-',
            $temperature > $avgTemperature => '🥵',
            $temperature < $avgTemperature => '🥶',
        };

        return [
            'city' => $city->name,
            'temperature' => $temperature,
            'trend' => $trend,
        ];
    }

    /**
     * @todo connect with any weather service provider
     * @param int $min
     * @param int $max
     * @return int
     */
    public function generateFakeTemperature(int $min = -10, int $max = 10): int
    {
        // mocking temperature at the moment
        return fake()->numberBetween($min, $max);
    }
}
