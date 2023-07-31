<?php

declare(strict_types=1);

namespace App\Service\WeatherService\Exception;

class WeatherServiceException extends AbstractWeatherException
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('An unexpected error occurred: %s. Code: %d', $message, $code);
        parent::__construct($message, $code, $previous);
    }
}
