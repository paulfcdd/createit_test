<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\City;
use App\Entity\Country;

class LocationDto
{
    public ?int $country;
    public ?int $city;
}
