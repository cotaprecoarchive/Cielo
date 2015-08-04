<?php

/*
 * Copyright (c) 2015 Cota Preço
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CotaPreco\Cielo;

use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitorTrait;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class Order implements AcceptsSerializationVisitor
{
    use AcceptsSerializationVisitorTrait;

    /**
     * @var string
     */
    private $number;

    /**
     * @var int
     */
    private $value;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var int
     */
    private $currency;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string
     */
    private $language;

    /**
     * @var int|null
     */
    private $shipping;

    /**
     * @var string|null
     */
    private $descriptor;

    /**
     * @param string      $number
     * @param int         $value
     * @param int         $currency
     * @param null|string $description
     * @param string      $language
     * @param null|int    $shipping
     * @param null|string $descriptor
     */
    public function __construct(
        $number,
        $value,
        $currency = Currency::REAL,
        $description = null,
        $language = CieloLanguage::PORTUGUESE,
        $shipping = null,
        $descriptor = null
    ) {
        $this->number      = (string) $number;
        $this->value       = (int) $value;
        $this->createdAt   = new \DateTimeImmutable('now');
        $this->currency    = (int) $currency;
        $this->language    = (string) $language;
        $this->description = $description ? (string) $description : null;
        $this->shipping    = $shipping ? (int) $shipping : null;
        $this->descriptor  = $descriptor;
    }

    /**
     * @param  string             $number
     * @param  int                $value
     * @param  \DateTimeImmutable $createdAt
     * @param  int                $currency
     * @param  null|string        $description
     * @param  string             $language
     * @param  null|string        $shipping
     * @param  null|string        $descriptor
     * @return self
     */
    public static function fromPreviouslyIssuedOrder(
        $number,
        $value,
        \DateTimeImmutable $createdAt,
        $currency = Currency::REAL,
        $description = null,
        $language = CieloLanguage::PORTUGUESE,
        $shipping = null,
        $descriptor = null
    ) {
        $order = new self(
            $number,
            $value, $currency, $description, $language, $shipping, $descriptor);

        $order->createdAt = $createdAt;

        return $order;
    }

    /**
     * @param  string $number
     * @param  int    $value
     * @return Order
     */
    public static function fromOrderNumberAndValue($number, $value)
    {
        return new self($number, $value);
    }

    /**
     * @param  int $shipping
     * @return self
     */
    public function withShipping($shipping)
    {
        return new self(
            $this->number,
            $this->value,
            $this->currency,
            $this->language,
            $this->description,
            $shipping,
            $this->descriptor
        );
    }

    /**
     * @param  string $descriptor
     * @return Order
     */
    public function withSoftDescriptor($descriptor)
    {
        return new self(
            $this->number,
            $this->value,
            $this->currency,
            $this->language,
            $this->description,
            $this->shipping,
            $descriptor
        );
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int|null
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @return null|string
     */
    public function getDescriptor()
    {
        return $this->descriptor;
    }
}
