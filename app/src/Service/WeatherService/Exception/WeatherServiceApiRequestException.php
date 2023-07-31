<?php

declare(strict_types=1);

namespace App\Service\WeatherService\Exception;

class WeatherServiceApiRequestException extends AbstractWeatherException
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('API request failed: %d', $code);
        parent::__construct($message, $code, $previous);
    }
}
