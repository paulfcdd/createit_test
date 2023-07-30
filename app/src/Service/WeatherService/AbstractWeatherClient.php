<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

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
            $response = $this->client->request(
                $method,
                $apiUrl,
                $options
            );

            // Check if the response is successful (HTTP status code 2xx)
            if (!$this->isSuccessful($response)) {
                throw new \RuntimeException('API request failed: ' . $response->getStatusCode());
            }

            return $response->getContent();
        } catch (TransportExceptionInterface $e) {
            // Handle any transport-related exceptions, like network errors
            throw new \RuntimeException('Transport error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $e) {
            // Handle any other unexpected exceptions
            throw new \RuntimeException('An unexpected error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function isSuccessful(ResponseInterface $response): bool
    {
        $statusCode = $response->getStatusCode();
        return $statusCode >= 200 && $statusCode < 300;
    }
}
