<?php

declare(strict_types=1);

namespace App\Service\WeatherService;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\WeatherSearchHistory;
use App\Repository\WeatherSearchHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WeatherService
{
    public function __construct(
        private readonly OpenWeatherMapClient $openWeatherMapClient,
        private readonly AmbeeClient $ambeeClient,
        private readonly CacheInterface $cache,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function getWeather(City $city, Country $country): float
    {
        $ambeeClientData = $this->ambeeClient->getWeather($city->getLatitude(), $city->getLongitude());
        $openWeatherClientData = $this->openWeatherMapClient->getWeather($city->getLatitude(), $city->getLongitude());
        $currentAverageTemperature = ($ambeeClientData + $openWeatherClientData) / 2;
        $this->saveWeatherHistory($city, $currentAverageTemperature);

        return $currentAverageTemperature;
    }

    private function saveWeatherHistory(City $city, float $temperature)
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
}
