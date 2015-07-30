<?php

namespace CotaPreco\Cielo;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class AffiliationKey
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    private function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param  string $keyString
     * @return AffiliationKey
     * @throws \InvalidArgumentException if `$keyString` doesn't contains at least 1 character or exceeds 100 characters
     */
    public static function fromString($keyString)
    {
        $len = strlen($keyString);

        if ($len === 0 || $len > 100) {
            throw new \InvalidArgumentException(sprintf('Is not a valid affiliation key: `%s`', $keyString));
        }

        return new self($keyString);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->key;
    }
}
