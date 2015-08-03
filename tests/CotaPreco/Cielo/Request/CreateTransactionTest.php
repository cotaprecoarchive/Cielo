<?php

namespace CotaPreco\Cielo\Request;

use CotaPreco\Cielo\Bin;
use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Cvv;
use CotaPreco\Cielo\IdentifiesHolder;
use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Order;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\TransactionAuthorizationIndicator;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CreateTransactionTest extends TestCase
{
    /**
     * @var CreateTransaction
     */
    private $request;

    /**
     * @return array
     */
    public function provideRequests()
    {
        $merchant = Merchant::fromAffiliationIdAndKey(
            '1006993069',
            '25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3'
        );

        $card = CreditCard::createWithSecurityCode(
            '4551870000000183',
            2018,
            5,
            Cvv::fromVerificationValue('123')
        );

        $paymentMethod = PaymentMethod::forIssuerAsOneTimePayment(CardIssuer::fromIssuerString(CardIssuer::VISA));

        $order = Order::fromOrderNumberAndValue('123452', 1000);

        return [
            [
                TransactionAuthorizationIndicator::ONLY_AUTHENTICATE,
                CreateTransaction::authenticateOnly(
                    $merchant,
                    CardHolder::fromCard($card),
                    $order,
                    $paymentMethod,
                    true
                )
            ],
            [
                TransactionAuthorizationIndicator::AUTHORIZE,
                CreateTransaction::authorizeOnly(
                    $merchant,
                    CardHolder::fromCard($card),
                    $order,
                    $paymentMethod,
                    true,
                    'http://localhost/cielo.php'
                )
            ],
            [
                TransactionAuthorizationIndicator::AUTHORIZE_ONLY_IF_AUTHENTICATED,
                CreateTransaction::authorizeOnlyIfAuthenticated(
                    $merchant,
                    CardHolder::fromCard($card),
                    $order,
                    $paymentMethod,
                    true
                )
            ],
            [
                TransactionAuthorizationIndicator::AUTHORIZE_WITHOUT_AUTHENTICATION,
                CreateTransaction::authorizeWithoutAuthentication(
                    $merchant,
                    CardHolder::fromCard($card),
                    $order,
                    $paymentMethod,
                    true
                )
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        list(, $request) = $this->provideRequests()[0];

        $this->request = $request;
    }

    /**
     * @test
     */
    public function getMerchant()
    {
        $this->assertInstanceOf(Merchant::class, $this->request->getMerchant());
    }

    /**
     * @test
     */
    public function getHolder()
    {
        $this->assertInstanceOf(IdentifiesHolder::class, $this->request->getHolder());
    }

    /**
     * @test
     */
    public function getOrder()
    {
        $this->assertInstanceOf(Order::class, $this->request->getOrder());
    }

    /**
     * @test
     */
    public function getPaymentMethod()
    {
        $this->assertInstanceOf(PaymentMethod::class, $this->request->getPaymentMethod());
    }

    /**
     * @test
     */
    public function getReturnUrl()
    {
        $this->assertNull($this->request->getReturnUrl());
    }

    /**
     * @test
     */
    public function getBin()
    {
        $this->assertInstanceOf(Bin::class, $this->request->getBin());
    }

    /**
     * @test
     */
    public function shouldCapture()
    {
        $this->assertTrue($this->request->shouldCapture());
    }

    /**
     * @test
     */
    public function shouldGenerateToken()
    {
        $this->assertFalse($this->request->shouldGenerateToken());
    }

    /**
     * @test
     * @param int               $indicator
     * @param CreateTransaction $request
     * @dataProvider provideRequests
     */
    public function getAuthorizeIndicator($indicator, $request)
    {
        $this->assertEquals($indicator, $request->getAuthorizeIndicator());
    }
}
