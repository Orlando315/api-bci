<?php

namespace App\Integrations\Bci\Exceptions;

use Exception;

class BciError extends Exception
{
    /**
     * Create a new BciError instance.
     *
     * @return static
     */
    public static function error($message)
    {
      return new static($message);
    }
}
