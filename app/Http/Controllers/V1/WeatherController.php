<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\InvalidCityException;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use App\Http\Requests\WeatherRequest;
use App\DTO\WeatherDTO;

class WeatherController extends Controller
{
    public function __construct(
        protected readonly WeatherService $weatherService
    )
    {
    }

    /**
     * @throws InvalidCityException
     */
    public function show(WeatherRequest $request, string $city): JsonResponse
    {
        $requestData = $request->validated();
        $weatherDTO = WeatherDTO::fromRequest($city, $requestData);

        $data = $this->weatherService->getCurrentTemperature($weatherDTO);

        return response()->json($data);
    }
}
