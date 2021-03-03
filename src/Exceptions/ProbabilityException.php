<?php

namespace App\Exceptions;

use Exception;

class ProbabilityException extends Exception
{
    public function getExceptionClass(): string
    {
        return self::class;
    }
}