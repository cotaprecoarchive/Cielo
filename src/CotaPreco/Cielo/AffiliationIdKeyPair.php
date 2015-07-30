<?php

namespace CotaPreco\Cielo;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class AffiliationIdKeyPair
{
    /**
     * @var string
     */
    private $affiliationId;

    /**
     * @var string
     */
    private $affiliationKey;

    /**
     * @param string $affiliationId
     * @param string $affiliationKey
     */
    private function __construct($affiliationId, $affiliationKey)
    {
        $this->affiliationKey = $affiliationKey;
        $this->affiliationId  = $affiliationId;
    }

    /**
     * @param  string $affiliationId
     * @param  string $affiliationKey
     * @return AffiliationIdKeyPair
     * @throws \InvalidArgumentException if `$affiliationId` is not numeric or exceeds 20 characters
     * @throws \InvalidArgumentException if `$affiliationKey` doesn't contains at least 1 character or exceeds 100
     * characters
     */
    public static function createFromAffiliationIdAndKey($affiliationId, $affiliationKey)
    {
        if (! (is_numeric($affiliationId) || strlen($affiliationId) > 20)) {
            throw new \InvalidArgumentException(
                sprintf('Is not a valid affiliation id: `%s`', $affiliationId)
            );
        }

        $keyLength = strlen($affiliationKey);

        if ($keyLength === 0 || $keyLength > 100) {
            throw new \InvalidArgumentException(
                sprintf('Is not a valid affiliation key: `%s`', $affiliationKey)
            );
        }

        return new self($affiliationId, $affiliationKey);
    }

    /**
     * @return string
     */
    public function getAffiliationId()
    {
        return $this->affiliationId;
    }

    /**
     * @return string
     */
    public function getAffiliationKey()
    {
        return $this->affiliationKey;
    }
}
