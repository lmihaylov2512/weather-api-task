<?php

namespace App\DTO;

use App\Enums\WeatherSource;

readonly class WeatherDTO
{
    public function __construct(
        public string $city,
        public ?WeatherSource $source = WeatherSource::Internal,
    )
    {
    }

    public static function fromRequest(string $city, array $data): static
    {
        $source = WeatherSource::from($data['source'] ?? WeatherSource::Internal->value);

        return new static($city, $source);
    }
}
