<?php

declare(strict_types=1);

namespace App\Service\WeatherService\Exception;

class WeatherServiceTransportException extends AbstractWeatherException
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $errorMessage = sprintf('Transport error occurred: %s. Code: %d', $message, $code);
        parent::__construct($errorMessage, $code, $previous);
    }
}
