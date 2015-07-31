<?php

namespace CotaPreco\Cielo;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CreditCardExpiration
{
    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @param int $year
     * @param int $month
     */
    public function __construct($year, $month)
    {
        $this->year  = (int) $year;
        $this->month = (int) $month;
    }

    /**
     * @param  int $year
     * @param  int $month
     * @return self
     */
    public static function fromYearAndMonth($year, $month)
    {
        return new self($year, $month);
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getFullYearAndMonth()
    {
        return sprintf('%4d%02d', $this->year, $this->month);
    }
}
