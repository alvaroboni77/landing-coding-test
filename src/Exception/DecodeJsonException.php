<?php

namespace App\Exception;

use Exception;
use Throwable;

class DecodeJsonException extends Exception
{
    public function __construct(string $message = 'Error decoding JSON.', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}