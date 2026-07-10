<?php

namespace App\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    public function __construct(
        string $message = 'Application exception.',
        protected int $statusCode = 500,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}
