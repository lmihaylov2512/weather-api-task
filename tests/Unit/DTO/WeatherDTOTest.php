<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;
use App\DTO\WeatherDTO;
use App\Enums\WeatherSource;
use ValueError;

class WeatherDTOTest extends TestCase
{
    public function test_can_be_initialized_with_default_source(): void
    {
        $dto = new WeatherDTO('Sofia');

        $this->assertEquals('Sofia', $dto->city);
        $this->assertEquals(WeatherSource::Internal, $dto->source);
    }

    public function test_initialized_with_custom_source(): void
    {
        $dto = new WeatherDTO('Sofia', WeatherSource::External);

        $this->assertEquals('Sofia', $dto->city);
        $this->assertEquals(WeatherSource::External, $dto->source);
    }

    public function test_from_request_uses_default_source_not_provided(): void
    {
        $data = [];
        $dto = WeatherDTO::fromRequest('Sofia', $data);

        $this->assertEquals('Sofia', $dto->city);
        $this->assertEquals(WeatherSource::Internal, $dto->source);
    }

    public function test_from_request_parses_source_correctly(): void
    {
        $data = ['source' => WeatherSource::External->value];
        $dto = WeatherDTO::fromRequest('Stara Zagora', $data);

        $this->assertEquals('Stara Zagora', $dto->city);
        $this->assertEquals(WeatherSource::External, $dto->source);
    }

    public function test_from_request_throws_error_invalid_source(): void
    {
        $this->expectException(ValueError::class);

        $data = ['source' => 'Invalid'];
        WeatherDTO::fromRequest('Sofia', $data);
    }
}
