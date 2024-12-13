<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Services\WeatherService;
use App\Models\City;

class PopulateWeatherHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-weather-history
                            {--days=1 : How many days ago populating history}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate weather history for a specific days. Defaults to 1 days ago';

    /**
     * Execute the console command.
     */
    public function handle(
        WeatherService $weatherService
    ): int
    {
        $days = $this->option('days');

        if ($days <= 0) {
            $this->error('The --days argument must be a positive number.');

            return 1;
        }

        // generate an array of dates using a range, between today and days option period
        $dates = array_map(fn($daysAgo) => Carbon::now()->subDays($daysAgo)->toDateString(), range(1, $days));

        $cities = City::query()
            ->with('weatherHistory', fn(HasMany $query) => $query->lastDays($days))
            ->get();

        if ($cities->count() === 0) {
            $this->warn('There is no city records into the database.');

            return 0;
        }

        /** @var City $city */
        foreach ($cities as $city) {
            $this->info("Populating weather history for {$city->name} city, {$days} days period...");

            $historyDates = $city->weatherHistory->pluck('date')->map(fn(Carbon $date) => $date->toDateString())->toArray();
            $datesDiff = array_diff($dates, $historyDates);
            $datesDiffCount = count($datesDiff);

            $records = array_map(fn(string $date) => [
                'avg_temperature' => $weatherService->generateFakeTemperature(),
                'date' => $date,
            ], $datesDiff);

            $city->weatherHistory()->createMany($records);

            $this->info("Populated {$datesDiffCount} records for {$city->name} city.");
        }

        return 0;
    }
}
