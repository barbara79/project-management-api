<?php

namespace App\Exception;

use App\Exception\ExceptionInterface;

class PersistException extends \Exception implements ExceptionInterface
{
    public function __construct(string $message = "Failing in persisting in the database", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
