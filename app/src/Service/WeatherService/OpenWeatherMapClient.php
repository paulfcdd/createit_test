<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

class OpenWeatherMapClient extends AbstractWeatherClient
{
    public function getWeather(float $latitude, float $longitude): float
    {
        $urlFormat = 'https://api.openweathermap.org/data/3.0/onecall?lat=%f&lon=%f&appid=%s&units=metric';
        $url = sprintf(
            $urlFormat,
            $latitude,
            $longitude,
            $this->parameterBag->get('open_weather_api_key')
        );

        $result = json_decode($this->makeRequest($url));

        return round($result->current->temp, 2);
    }
}
