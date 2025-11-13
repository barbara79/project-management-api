<?php

namespace App\Exception;

class NotFoundProjectException extends \Exception implements ExceptionInterface
{
    public function __construct(string $message = "Project Not Found", int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
