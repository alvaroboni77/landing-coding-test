<?php

namespace App\Exception;

use Exception;
use Throwable;

class FetchJsonException extends Exception
{
    public function __construct(string $message = 'Failed to fetch JSON from file', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}