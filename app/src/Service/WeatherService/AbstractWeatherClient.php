<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

use App\Service\WeatherService\Exception\WeatherServiceApiRequestException;
use App\Service\WeatherService\Exception\WeatherServiceException;
use App\Service\WeatherService\Exception\WeatherServiceTransportException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractWeatherClient
{
    public function __construct(
        protected readonly ParameterBagInterface $parameterBag,
        private readonly HttpClientInterface $client,
    )
    {
    }

    abstract public function getWeather(float $latitude, float $longitude): float;

    public function makeRequest(string $apiUrl, string $method = 'GET', array $options = []): string
    {
        try {
            $response = $this->client->request($method, $apiUrl, $options
            );
            if (!$this->isSuccessful($response)) {
                throw new WeatherServiceApiRequestException($response->getStatusCode());
            }

            return $response->getContent();
        } catch (TransportExceptionInterface $e) {
            throw new WeatherServiceTransportException($e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new WeatherServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function isSuccessful(ResponseInterface $response): bool
    {
        $statusCode = $response->getStatusCode();
        return $statusCode >= 200 && $statusCode < 300;
    }
}
