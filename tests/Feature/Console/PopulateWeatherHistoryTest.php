<?php

namespace Feature\Console;

use Tests\TestCase;
use App\Models\City;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PopulateWeatherHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_executes_successfully(): void
    {
        $weatherServiceMock = $this->mock(WeatherService::class);

        City::factory()->create();
        $weatherServiceMock->shouldReceive('generateFakeTemperature')->andReturn(25);

        $this->artisan('app:populate-weather-history --days=3')
            ->assertExitCode(0);
    }
}
