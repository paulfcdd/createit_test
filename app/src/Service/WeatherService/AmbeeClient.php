<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AmbeeClient extends AbstractWeatherClient
{
    public function getWeather(float $latitude, float $longitude): float
    {
        try {
            $urlFormat = 'https://api.ambeedata.com/weather/latest/by-lat-lng?lat=%f&lng=%f';
            $url = sprintf(
                $urlFormat,
                $latitude,
                $longitude,
            );
            $options = [
                'headers' => [
                    'x-api-key' => $this->parameterBag->get('ambee_api_key')
                ]
            ];

            $result = json_decode($this->makeRequest($url, 'GET', $options));

            return $this->convertFahrenheitToCelsius($result->data->temperature);
        } catch (\Throwable $e) {
            // Handle the exception here or rethrow it if needed
            // For example, log the error, return a default value, etc.
            // If you don't want to handle it here, you can rethrow it to let the caller handle it.
            throw $e;
        }
    }

    private function convertFahrenheitToCelsius($temperature): float
    {
        $celsius = ($temperature - 32) * 5/9;
        return round($celsius, 2);
    }
}