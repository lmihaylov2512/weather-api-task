<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use App\Models\{
    City,
    WeatherHistory
};

class CityTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_correct_table_name(): void
    {
        $city = new City();

        $this->assertEquals('cities', $city->getTable());
    }

    public function test_has_correct_primary_key(): void
    {
        $city = new City();

        $this->assertEquals('id', $city->getKeyName());
    }

    public function test_can_create_and_retrieve_data(): void
    {
        $city = City::create(
            ['name' => 'Sofia'],
        );

        $this->assertDatabaseHas('cities', ['name' => 'Sofia']);
        $this->assertEquals('Sofia', $city->name);
    }

    public function test_has_many_weather_history(): void
    {
        /** @var City $city */
        $city = City::factory()
            ->has(WeatherHistory::factory()->count(3))
            ->create();

        $this->assertInstanceOf(Collection::class, $city->weatherHistory);
        $this->assertCount(3, $city->weatherHistory);
        $this->assertInstanceOf(WeatherHistory::class, $city->weatherHistory->first());
    }

    public function test_weather_history_relationship_returns_empty_when_no_data(): void
    {
        /** @var City $city */
        $city = City::factory()->create();

        $this->assertInstanceOf(Collection::class, $city->weatherHistory);
        $this->assertCount(0, $city->weatherHistory);
    }
}
