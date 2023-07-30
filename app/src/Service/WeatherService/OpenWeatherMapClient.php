<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OpenWeatherMapClient extends AbstractWeatherClient
{
    public function getWeather(float $latitude, float $longitude): float
    {
        try {
            $urlFormat = 'https://api.openweathermap.org/data/3.0/onecall?lat=%f&lon=%f&appid=%s&units=metric';
            $url = sprintf(
                $urlFormat,
                $latitude,
                $longitude,
                $this->parameterBag->get('open_weather_api_key')
            );

            $result = json_decode($this->makeRequest($url));

            return round($result->current->temp, 2);
        } catch (TransportExceptionInterface $e) {
            // Handle transport-related exceptions, like network errors
            throw new \RuntimeException('Transport error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $e) {
            // Handle any other unexpected exceptions
            throw new \RuntimeException('An unexpected error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}