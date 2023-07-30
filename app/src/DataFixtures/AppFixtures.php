<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $poland = (new Country())->setName('Poland');

        $polishCities = [
            (new City())
                ->setName('Warsaw')
                ->setLatitude(52.237049)
                ->setLongitude( 21.017532),
            (new City())
                ->setName('Krakow')
                ->setLatitude(50.049683)
                ->setLongitude( 19.944544),
            (new City())
                ->setName('Wroclaw')
                ->setLatitude(51.107883)
                ->setLongitude(17.038538)
        ];

        /** @var City $polishCity */
        foreach ($polishCities as $polishCity) {
            $poland->addCity($polishCity);
        }

        $manager->persist($poland);
        $manager->flush();
    }
}
