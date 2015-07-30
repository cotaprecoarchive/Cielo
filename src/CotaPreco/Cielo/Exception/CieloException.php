<?php

namespace CotaPreco\Cielo\Exception;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CieloException extends \Exception implements ExceptionInterface
{
    /**
     * @param  int    $code
     * @param  string $message
     * @return CieloException
     */
    public static function forCodeAndMessage($code, $message)
    {
        return new self($message, (int) $code);
    }
}
