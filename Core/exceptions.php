<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 18.10.16
 * Time: 00:42
 */

if (!defined('system')) {
    http_response_code(403);
    echo "Method not allowed";
}

class InvalidCodeSampleException extends Exception
{
    public function __construct($error, $code = 0, Exception $previous = null)
    {
        parent::__construct("Wrong CodeSample: {$error}", $code, $previous);
    }
}

class RenderException extends Exception
{
}