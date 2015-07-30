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
     */
    public static function createFromAffiliationIdAndKey($affiliationId, $affiliationKey)
    {
        if (! is_numeric($affiliationId) || strlen($affiliationId) > 20) {
            throw new \InvalidArgumentException(
                'Is not a valid affiliation id: `'. $affiliationId .'`'
            );
        }

        if (strlen($affiliationKey) === 0 || strlen($affiliationKey) > 100) {
            throw new \InvalidArgumentException('Is not a valid affiliation key: `'. $affiliationKey  .'`');
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
