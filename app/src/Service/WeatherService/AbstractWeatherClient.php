<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractWeatherClient
{
    public function __construct(
        protected readonly ParameterBagInterface $parameterBag,
        private readonly HttpClientInterface $client,
    )
    {
    }

    abstract public function getWeather(float $latitude, float $longitude): float;

    public function makeRequest(string $apiUrl, string $method = 'GET', array $options = [])
    {
        $response = $this->client->request(
            $method,
            $apiUrl,
            $options
        );

        return $response->getContent();
    }
}
