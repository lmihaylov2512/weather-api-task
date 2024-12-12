<?php

namespace Feature\Services;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\WeatherClientService;
use App\Exceptions\UnableWeatherServiceException;
use Closure, ReflectionClass, ReflectionException;

class WeatherClientServiceTest extends TestCase
{
    private WeatherClientService $weatherClientService;

    public function setUp(): void
    {
        parent::setUp();

        $this->weatherClientService = new WeatherClientService();
    }

    public function test_get_cached_temperature_stores_and_returns_cached_value(): void
    {
        $city = 'London';
        $expectedTemperature = 25;

        Cache::shouldReceive('remember')
            ->once()
            ->with("weather_city_$city", WeatherClientService::CACHE_TTL, Closure::class)
            ->andReturn($expectedTemperature);

        $temperature = $this->weatherClientService->getCachedTemperature($city);

        $this->assertEquals($expectedTemperature, $temperature);
    }

    public function test_get_cached_temperature_calls_fetch_when_not_cached(): void
    {
        $city = 'Sofia';
        $expectedTemperature = 30;

        Cache::shouldReceive('remember')
            ->once()
            ->with("weather_city_$city", WeatherClientService::CACHE_TTL, Closure::class)
            ->andReturnUsing(function ($key, $ttl, $closure) {
                return $closure();
            });


        Http::fake([
            'https://api.weatherapi.com/v1/current.json*' => Http::response([
                'current' => ['temp_c' => $expectedTemperature],
            ]),
        ]);

        $temperature = $this->weatherClientService->getCachedTemperature($city);

        $this->assertEquals($expectedTemperature, $temperature);
    }

    /**
     * @throws ReflectionException
     */
    public function test_fetch_current_temperature_reflection_returns_temperature_on_successful_api_call(): void
    {
        $city = 'Varna';
        $expectedTemperature = -10;

        // Mock the HTTP response
        Http::fake([
            'https://api.weatherapi.com/v1/current.json*' => Http::response([
                'current' => ['temp_c' => $expectedTemperature],
            ]),
        ]);

        $reflection = new ReflectionClass($this->weatherClientService);
        $method = $reflection->getMethod('fetchCurrentTemperature');

        $temperature = $method->invoke($this->weatherClientService, $city);

        $this->assertEquals($expectedTemperature, $temperature);
    }

    /**
     * @throws ReflectionException
     */
    public function test_fetch_current_temperature_via_reflection_handles_invalid_json_response(): void
    {
        $city = 'Burgas';

        Http::fake([
            'https://api.weatherapi.com/v1/current.json*' => Http::response(['unexpected' => 'data'], 500),
        ]);

        $reflection = new ReflectionClass($this->weatherClientService);
        $method = $reflection->getMethod('fetchCurrentTemperature');

        $this->expectException(UnableWeatherServiceException::class);
        $method->invoke($this->weatherClientService, $city);
    }
}
