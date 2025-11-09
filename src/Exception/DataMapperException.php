<?php

namespace App\Exception;

class DataMapperException extends \Exception implements ExceptionInterface
{
    public function __construct(string $message = "Data mapper error", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
