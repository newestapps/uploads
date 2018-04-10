<?php
/**
 * Created by rodrigobrun
 *   with PhpStorm
 */

namespace Newestapps\Uploads\Exceptions;

use Exception;
use Throwable;

class FileOwnerNotSpecifiedException extends Exception
{

    public function __construct(string $message = "File owner not specified, please make sure you are with a valid session before performing this upload!", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}