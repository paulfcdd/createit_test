<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Form\LocationFormType;
use App\Service\WeatherService\AbstractWeatherClient;
use App\Service\WeatherService\OpenWeatherMapClient;
use App\Service\WeatherService\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class AppController extends AbstractController
{
    public function __construct(
        private readonly WeatherService $weatherService
    )
    {}

    #[Route(path: '/', name: 'app.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(LocationFormType::class, [], [
            'method' => 'GET'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $city = $formData['city'];
            $country = $formData['country'];

            return $this->redirectToRoute('app.show_weather_for_city', [
                'country' => $country->getName(),
                'city' => $city->getName()
            ]);
        }

        return $this->render('/app/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/weather/{country}/{city}', name: 'app.show_weather_for_city')]
    #[ParamConverter('country', class: Country::class, options: ['mapping' => ['country' => 'name']])]
    #[ParamConverter('city', class: City::class, options: ['mapping' => ['city' => 'name']])]
    public function showWeatherData(Country $country, City $city)
    {
        $weatherData = $this->weatherService->getWeather($city, $country);

        return $this->render('/app/weather_data.html.twig', [
            'weatherData' => $weatherData,
            'city' => $city->getName(),
            'country' => $country->getName(),
        ]);
    }
}
