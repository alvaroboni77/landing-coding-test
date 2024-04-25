<?php

namespace App\Exception;

use Exception;
use Throwable;

class EmptyProductListException extends Exception
{
    public function __construct(string $message = 'No products found.', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}