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

namespace CotaPreco\Cielo\Request;

use CotaPreco\Cielo\Bin;
use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\IdentifiesHolder;
use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Order;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitorTrait;
use CotaPreco\Cielo\TransactionAuthorizationIndicator;
use CotaPreco\Cielo\Url;
use Rhumsaa\Uuid\Uuid;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class CreateTransaction extends AbstractCieloRequest implements AcceptsSerializationVisitor
{
    use AcceptsSerializationVisitorTrait;

    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * @var IdentifiesHolder
     */
    private $holder;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * @var Url
     */
    private $returnUrl;

    /**
     * @var bool
     */
    private $capture;

    /**
     * @var Bin|null
     */
    private $bin;

    /**
     * @var bool
     */
    private $generateToken;

    /**
     * @see TransactionAuthorizationIndicator
     * @var int
     */
    private $authorize;

    /**
     * @param Merchant         $merchant
     * @param IdentifiesHolder $holder
     * @param Order            $order
     * @param PaymentMethod    $paymentMethod
     * @param int              $authorize
     * @param bool             $capture
     * @param Url|null         $returnUrl
     * @param bool|false       $generateToken
     */
    private function __construct(
        Merchant $merchant,
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $authorize,
        $capture,
        Url $returnUrl = null,
        $generateToken = false
    ) {
        $this->requestId     = Uuid::uuid4();
        $this->merchant      = $merchant;
        $this->holder        = $holder;
        $this->order         = $order;
        $this->paymentMethod = $paymentMethod;
        $this->authorize     = (int) $authorize;
        $this->capture       = (bool) $capture;
        $this->returnUrl     = $returnUrl;
        $this->generateToken = (bool) $generateToken;

        if ($holder instanceof CardHolder) {
            $this->bin = $holder->getCard()->getBin();
        }
    }

    /**
     * @param  Merchant         $merchant
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  string|null      $returnUrl
     * @param  bool|false       $generateToken
     * @return self
     */
    public static function authenticateOnly(
        Merchant $merchant,
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $returnUrl = null,
        $generateToken = false
    ) {
        return new self(
            $merchant,
            $holder,
            $order,
            $paymentMethod,
            TransactionAuthorizationIndicator::ONLY_AUTHENTICATE,
            $capture,
            $returnUrl ? Url::fromString($returnUrl) : null,
            $generateToken
        );
    }

    /**
     * @param  Merchant         $merchant
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  string|null      $returnUrl
     * @param  bool|false       $generateToken
     * @return self
     */
    public static function authorizeOnlyIfAuthenticated(
        Merchant $merchant,
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $returnUrl = null,
        $generateToken = false
    ) {
        return new self(
            $merchant,
            $holder,
            $order,
            $paymentMethod,
            TransactionAuthorizationIndicator::AUTHORIZE_ONLY_IF_AUTHENTICATED,
            $capture,
            $returnUrl ? Url::fromString($returnUrl) : null,
            $generateToken
        );
    }

    /**
     * @param  Merchant         $merchant
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  string           $returnUrl
     * @param  bool|false       $generateToken
     * @return self
     */
    public static function authorizeOnly(
        Merchant $merchant,
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $returnUrl,
        $generateToken = false
    ) {
        return new self(
            $merchant,
            $holder,
            $order,
            $paymentMethod,
            TransactionAuthorizationIndicator::AUTHORIZE,
            $capture,
            $returnUrl ? Url::fromString($returnUrl) : null,
            $generateToken
        );
    }

    /**
     * @param  Merchant         $merchant
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  string|null      $returnUrl
     * @param  bool|false       $generateToken
     * @return self
     */
    public static function authorizeWithoutAuthentication(
        Merchant $merchant,
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $returnUrl = null,
        $generateToken = false
    ) {
        return new self(
            $merchant,
            $holder,
            $order,
            $paymentMethod,
            TransactionAuthorizationIndicator::AUTHORIZE_WITHOUT_AUTHENTICATION,
            $capture,
            $returnUrl ? Url::fromString($returnUrl) : null,
            $generateToken
        );
    }

    /**
     * @return Merchant
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @return IdentifiesHolder
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return Url
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @return Bin|null
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @return int
     */
    public function getAuthorizeIndicator()
    {
        return $this->authorize;
    }

    /**
     * @return bool
     */
    public function shouldCapture()
    {
        return $this->capture;
    }

    /**
     * @return bool
     */
    public function shouldGenerateToken()
    {
        return $this->generateToken;
    }
}
