<?php

namespace Feature\Controllers;

use App\Models\City;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Mockery\MockInterface;

class WeatherControllerTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface $weatherServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->weatherServiceMock = $this->mock(WeatherService::class);
    }

    public function test_show_weather_success(): void
    {
        City::factory()->create(['name' => 'Sofia']);
        $mockedResponse = [
            'city' => 'Sofia',
            'temperature' => 8,
            'trend' => '🥵',
        ];

        $this->weatherServiceMock
            ->shouldReceive('getCurrentTemperature')
            ->once()
            ->andReturn($mockedResponse);

        $response = $this->get(route('api.v1.weather.show', ['city' => 'Sofia']));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($mockedResponse);
    }
}
