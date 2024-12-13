<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use App\Models\{
    City,
    WeatherHistory
};

class WeatherHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_correct_table_name(): void
    {
        $weatherHistory = new WeatherHistory();

        $this->assertEquals('weather_history', $weatherHistory->getTable());
    }

    public function test_has_correct_primary_key(): void
    {
        $weatherHistory = new WeatherHistory();

        $this->assertEquals('id', $weatherHistory->getKeyName());
    }

    public function test_belongs_to_city(): void
    {
        /** @var WeatherHistory $weatherHistory */
        $weatherHistory = WeatherHistory::factory()->create();

        $this->assertInstanceOf(City::class, $weatherHistory->city);
        $this->assertGreaterThan(0, $weatherHistory->city->id);
    }

    public function test_scope_last_days_returns_correct_records(): void
    {
        WeatherHistory::factory()
            ->count(5)
            ->create([
                'date' => Carbon::now()->subDays(5),
            ]);
        WeatherHistory::factory()
            ->count(5)
            ->create([
                'date' => Carbon::now()->subDays(15),
            ]);

        $records = WeatherHistory::lastDays(10)->get();

        $this->assertCount(5, $records);
    }
}
