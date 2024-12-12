<?php

namespace App\Enums;

enum WeatherSource: string
{
    case Internal = 'internal';
    case External = 'external';
}
