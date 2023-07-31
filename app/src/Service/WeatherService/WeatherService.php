<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\WeatherSearchHistory;
use App\Service\WeatherService\Exception\AbstractWeatherException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

readonly class WeatherService
{
    public function __construct(
        private OpenWeatherMapClient   $openWeatherMapClient,
        private AmbeeClient            $ambeeClient,
        private CacheInterface         $cache,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface  $parameterBag,
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getWeather(City $city, Country $country): float|string
    {
        $cacheKey = $this->getCacheKey($city, $country);
        $cacheTtl = $this->parameterBag->get('weather_app_cache_ttl');

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($city, $cacheTtl) {
                $item->expiresAfter((int)$cacheTtl);
                $currentAverageTemperature = $this->getAverageTemperature(
                    $city->getLatitude(),
                    $city->getLongitude(),
                    $this->ambeeClient,
                    $this->openWeatherMapClient
                );
                $this->saveWeatherHistory($city, $currentAverageTemperature);

                return $currentAverageTemperature;
            });
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @throws \Exception
     */
    private function saveWeatherHistory(City $city, float $temperature): void
    {
        $weatherHistory = new WeatherSearchHistory();
        $weatherHistory
            ->setCity($city)
            ->setTemperature($temperature);

        $this->entityManager->persist($weatherHistory);
        $this->entityManager->flush();
    }

    private function getCacheKey(City $city, Country $country): string
    {
        return sprintf('weather_%s_%s', $city->getName(), $country->getName());
    }

    /**
     * @throws AbstractWeatherException
     */
    private function getAverageTemperature(float $latitude, float $longitude, ...$clients): float
    {
        $numClients = count($clients);
        $sumTemperatures = array_reduce($clients, fn ($carry, $client) => $carry + $client->getWeather($latitude, $longitude), 0);

        return $sumTemperatures / $numClients;
    }
}
