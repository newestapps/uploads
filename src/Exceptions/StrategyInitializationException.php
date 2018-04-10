<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Exceptions;

use Exception;
use Throwable;

class StrategyInitializationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Cannot initialize strategy: {$message}", $code, $previous);
    }

}