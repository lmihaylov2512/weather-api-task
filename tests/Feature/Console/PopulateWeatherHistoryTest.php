<?php

namespace Feature\Console;

use Tests\TestCase;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PopulateWeatherHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_executes_successfully(): void
    {
        $days = 3;
        City::factory()
            ->count(5)
            ->create();

        $this->artisan('app:populate-weather-history', ['--days' => $days])
            ->assertSuccessful();
    }

    public function test_command_executes_output_successfully(): void
    {
        $days = 3;
        $city = City::factory()->create();

        $this->artisan('app:populate-weather-history', ['--days' => $days])
            ->assertSuccessful()
            ->expectsOutput("Populating weather history for {$city->name} city, {$days} days period...")
            ->expectsOutput("Populated {$days} records for {$city->name} city.");
    }

    public function test_command_executes_negative_days_error(): void
    {
        $this->artisan('app:populate-weather-history --days=-2')
            ->assertFailed()
            ->expectsOutput('The --days argument must be a positive number.');
    }

    public function test_command_executes_no_cities_warning(): void
    {
        $this->artisan('app:populate-weather-history')
            ->assertSuccessful()
            ->expectsOutput('There is no city records into the database.');
    }
}
