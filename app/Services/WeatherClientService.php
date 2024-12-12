<?php

namespace App\Services;

use Illuminate\Support\Facades\{Http, Cache};
use App\Exceptions\UnableWeatherServiceException;

class WeatherClientService
{
    public const int CACHE_TTL = 3600; // in seconds, 1 hour

    public function getCachedTemperature(string $city): int
    {
        return Cache::remember("weather_city_$city", static::CACHE_TTL, fn() => $this->fetchCurrentTemperature($city));
    }

    /**
     * @throws UnableWeatherServiceException
     */
    protected function fetchCurrentTemperature(string $city): int
    {
        $apiKey = config('services.weatherApi.key');

        $response = Http::get("https://api.weatherapi.com/v1/current.json", [
            'key' => $apiKey,
            'q' => $city
        ]);

        $data = $response->json();

        if (!$response->ok()) {
            throw new UnableWeatherServiceException($data['error']['message'] ?? 'Unable to fetch weather data');
        }

        $data = $response->json();

        return $data['current']['temp_c'];
    }
}
