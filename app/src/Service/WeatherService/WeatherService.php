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
        //TODO: Caching mechanism is implemented but tbh I don't get clue of adding cache here. Weather data is pretty dynamic data and may vary from request to request
        //TODO: moreover in task we want to keep results of searching in the database but because of caching we will not be able to save data a lot of data to database, only requests after cache expire will be saved
        //TODO: Here I add example of how can I cache data in Symfony but I decide to not use cache
//        $cacheKey = $this->getCacheKey($city, $country);
//
//        return $this->cache->get($cacheKey, function () use ($city) {
//            $ambeeClientData = $this->ambeeClient->getWeather($city->getLatitude(), $city->getLongitude());
//            $openWeatherClientData = $this->openWeatherMapClient->getWeather($city->getLatitude(), $city->getLongitude());
//
//            return ($ambeeClientData + $openWeatherClientData) / 2;
//        });

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
