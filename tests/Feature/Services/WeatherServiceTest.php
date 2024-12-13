<?php

namespace Feature\Services;

use Tests\TestCase;
use App\Services\WeatherService;
use App\Services\WeatherClientService;
use App\Models\City;
use App\Models\WeatherHistory;
use App\DTO\WeatherDTO;
use App\Enums\WeatherSource;
use App\Exceptions\InvalidCityException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Mockery;

class WeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface $weatherServiceMock;
    private MockInterface $weatherClientServiceMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->weatherClientServiceMock = $this->mock(WeatherClientService::class);
        $this->weatherServiceMock = Mockery::mock(WeatherService::class, [$this->weatherClientServiceMock])->makePartial();
    }

    public function test_get_current_temperature_with_internal_source(): void
    {
        City::factory()
            ->create([
                'name' => 'Sofia'
            ]);
        $weatherDTO = new WeatherDTO('Sofia', WeatherSource::Internal);

        $fakeTemperature = 7;
        $this->weatherServiceMock->shouldReceive('generateFakeTemperature')
            ->once()
            ->andReturn($fakeTemperature);

        $result = $this->weatherServiceMock->getCurrentTemperature($weatherDTO);

        $this->assertEquals('Sofia', $result['city']);
        $this->assertEquals($fakeTemperature, $result['temperature']);
        $this->assertEquals('-', $result['trend']);
    }

    public function test_get_current_temperature_with_external_source(): void
    {
        City::factory()
            ->create(
                ['name' => 'Stara Zagora']
            );
        $weatherDTO = new WeatherDTO('Stara Zagora', WeatherSource::External);

        $externalTemperature = 9;
        $this->weatherClientServiceMock->shouldReceive('getCachedTemperature')
            ->once()
            ->with('Stara Zagora')
            ->andReturn($externalTemperature);

        $result = $this->weatherServiceMock->getCurrentTemperature($weatherDTO);

        $this->assertEquals('Stara Zagora', $result['city']);
        $this->assertEquals($externalTemperature, $result['temperature']);
        $this->assertEquals('-', $result['trend']);
    }

    public function test_get_current_temperature_throws_invalid_city_exception(): void
    {
        $weatherDTO = new WeatherDTO('Bla-bla', WeatherSource::Internal);

        $this->expectException(InvalidCityException::class);
        $this->expectExceptionMessage('The passed Bla-bla city does not exist');

        $this->weatherServiceMock->getCurrentTemperature($weatherDTO);
    }

    public function test_get_current_temperature_with_trend_formation(): void
    {
        City::factory()
            ->has(WeatherHistory::factory()->set('avg_temperature', 5))
            ->create([
                'name' => 'Sofia'
            ]);
        $weatherDTO = new WeatherDTO('Sofia', WeatherSource::Internal);

        $fakeTemperature = 10;
        $this->weatherServiceMock->shouldReceive('generateFakeTemperature')
            ->once()
            ->andReturn($fakeTemperature);

        $result = $this->weatherServiceMock->getCurrentTemperature($weatherDTO);

        $this->assertEquals('Sofia', $result['city']);
        $this->assertEquals($fakeTemperature, $result['temperature']);
        $this->assertEquals('🥵', $result['trend']);
    }

    public function test_get_current_temperature_with_no_history(): void
    {
        City::factory()->create(['name' => 'Paris']);
        $weatherDTO = new WeatherDTO('Paris', WeatherSource::Internal);

        $fakeTemperature = 4;
        $this->weatherServiceMock->shouldReceive('generateFakeTemperature')
            ->once()
            ->andReturn($fakeTemperature);

        $result = $this->weatherServiceMock->getCurrentTemperature($weatherDTO);

        $this->assertEquals('Paris', $result['city']);
        $this->assertEquals($fakeTemperature, $result['temperature']);
        $this->assertEquals('-', $result['trend']);
    }

    public function test_get_current_temperature_with_zero_avg_temperature(): void
    {
        City::factory()
            ->has(WeatherHistory::factory()->set('avg_temperature', 0))
            ->create([
                'name' => 'London'
            ]);
        $weatherDTO = new WeatherDTO('London', WeatherSource::Internal);

        $fakeTemperature = 3;
        $this->weatherServiceMock->shouldReceive('generateFakeTemperature')
            ->once()
            ->andReturn($fakeTemperature);

        $result = $this->weatherServiceMock->getCurrentTemperature($weatherDTO);

        $this->assertEquals('London', $result['city']);
        $this->assertEquals($fakeTemperature, $result['temperature']);
        $this->assertEquals('-', $result['trend']);
    }

    public function test_get_current_temperature_with_negative_avg_temperature(): void
    {
        City::factory()
            ->has(WeatherHistory::factory()->set('avg_temperature', 10))
            ->create([
                'name' => 'Pleven',
            ]);
        $weatherDTO = new WeatherDTO('Pleven', WeatherSource::Internal);

        $fakeTemperature = 5;
        $this->weatherServiceMock->shouldReceive('generateFakeTemperature')
            ->once()
            ->andReturn($fakeTemperature);

        $result = $this->weatherServiceMock->getCurrentTemperature($weatherDTO);

        $this->assertEquals('Pleven', $result['city']);
        $this->assertEquals($fakeTemperature, $result['temperature']);
        $this->assertEquals('🥶', $result['trend']);
    }

    public function test_generate_fake_temperature_within_range(): void
    {
        $min = -5;
        $max = 5;

        $generatedTemperature = $this->weatherServiceMock->generateFakeTemperature($min, $max);

        $this->assertGreaterThanOrEqual($min, $generatedTemperature);
        $this->assertLessThanOrEqual($max, $generatedTemperature);
    }
}
