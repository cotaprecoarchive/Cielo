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

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
final class Transaction
{
    /**
     * @var TransactionId
     */
    private $transactionId;

    /**
     * @var Pan
     */
    private $pan;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * @var int
     */
    private $status;

    /**
     * @var Authentication|null
     */
    private $authentication;

    /**
     * @var Authorization|null
     */
    private $authorization;

    /**
     * @var Capture
     */
    private $capture;

    /**
     * @var Url|null
     */
    private $authenticationUrl;

    /**
     * @var GeneratedToken|null
     */
    private $generatedToken;

    /**
     * @var array|Cancellation[]
     */
    private $cancellations = [];

    /**
     * @param TransactionId       $transactionId
     * @param Pan                 $pan
     * @param Order               $order
     * @param PaymentMethod       $paymentMethod
     * @param int                 $status
     * @param GeneratedToken|null $generatedToken
     */
    public function __construct(
        TransactionId $transactionId,
        Pan           $pan,
        Order         $order,
        PaymentMethod $paymentMethod,
        $status,
        GeneratedToken $generatedToken = null
    ) {
        $this->transactionId  = $transactionId;
        $this->pan            = $pan;
        $this->order          = $order;
        $this->paymentMethod  = $paymentMethod;
        $this->status         = (int) $status;
        $this->generatedToken = $generatedToken;
    }

    /**
     * @param Authentication $authentication
     */
    public function authenticate(Authentication $authentication)
    {
        $this->status         = $authentication->getCode();
        $this->authentication = $authentication;
    }

    /**
     * @param Authorization $authorization
     */
    public function authorize(Authorization $authorization)
    {
        $this->status        = $authorization->getCode();
        $this->authorization = $authorization;
    }

    /**
     * @param Capture $capture
     */
    public function capture(Capture $capture)
    {
        $this->status  = $capture->getCode();
        $this->capture = $capture;
    }

    /**
     * @param Cancellation $cancellation
     */
    public function cancel(Cancellation $cancellation)
    {
        $this->status          = $cancellation->getCode();
        $this->cancellations[] = $cancellation;
    }

    /**
     * @param Url $authenticationUrl
     */
    public function withAuthenticationUrl(Url $authenticationUrl)
    {
        $this->authenticationUrl = $authenticationUrl;
    }

    /**
     * @return TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return Pan
     */
    public function getPan()
    {
        return $this->pan;
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function hasCapture()
    {
        return ! is_null($this->capture);
    }

    /**
     * @return Capture|null
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * @return bool
     */
    public function hasAuthentication()
    {
        return ! is_null($this->authentication);
    }

    /**
     * @return Authentication|null
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @return bool
     */
    public function hasAuthorization()
    {
        return ! is_null($this->authorization);
    }

    /**
     * @return Authorization|null
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @return bool
     */
    public function hasGeneratedToken()
    {
        return ! is_null($this->generatedToken);
    }

    /**
     * @return GeneratedToken|null
     */
    public function getGeneratedToken()
    {
        return $this->generatedToken;
    }

    /**
     * @return Url|null
     */
    public function getAuthenticationUrl()
    {
        return $this->authenticationUrl;
    }

    /**
     * @return array|Cancellation[]
     */
    public function getCancellations()
    {
        return $this->cancellations;
    }

    /**
     * @return bool
     */
    public function hasCancellations()
    {
        return count($this->cancellations) > 0;
    }
}
