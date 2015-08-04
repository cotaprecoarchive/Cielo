<?php

namespace CotaPreco\Cielo;

use CotaPreco\CieloTestUtil\CieloHttpClientThatAlwaysReturnsTransaction;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CieloTest extends TestCase
{
    /**
     * @return Cielo
     */
    private function getCieloWithClientThatAlwaysReturnTransaction()
    {
        return new Cielo(
            CieloEnvironment::DEVELOPMENT,
            Merchant::fromAffiliationIdAndKey(
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            ),
            new CieloHttpClientThatAlwaysReturnsTransaction()
        );
    }

    /**
     * @test
     */
    public function createFromAffiliationIdAndKey()
    {
        $this->assertInstanceOf(
            Cielo::class,
            Cielo::createFromAffiliationIdAndKey(
                CieloEnvironment::DEVELOPMENT,
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            )
        );
    }

    /**
     * @test
     */
    public function cancelTransactionPartially()
    {
        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->cancelTransactionPartially('1006993069372AD71001', 5000)
        );
    }

    /**
     * @test
     */
    public function cancelTransaction()
    {
        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->cancelTransaction('1006993069372AD71001')
        );
    }

    /**
     * @test
     */
    public function getTransactionById()
    {
        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->getTransactionById('1006993069372AD71001')
        );
    }

    /**
     * @test
     */
    public function capturePartially()
    {
        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->capturePartially(
                '1006993069372AD71001',
                1000,
                null
            )
        );
    }

    /**
     * @test
     */
    public function capture()
    {
        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->capture('1006993069372AD71001')
        );
    }

    /**
     * @test
     */
    public function authorize()
    {
        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->authorize('1006993069372AD71001')
        );
    }

    /**
     * @test
     */
    public function createTransaction()
    {
        $holder        = CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=');
        $order         = Order::fromOrderNumberAndValue('1000', 1000);
        $paymentMethod = PaymentMethod::forIssuerAsOneTimePayment(CardIssuer::fromIssuerString(CardIssuer::VISA));

        $cielo = $this->getCieloWithClientThatAlwaysReturnTransaction();

        $this->assertInstanceOf(Transaction::class, $cielo->createAndAuthorizeOnly(
            $holder,
            $order,
            $paymentMethod,
            'http://localhost/cielo.php'
        ));

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->createAndAuthorizeOnlyIfAuthenticated(
                $holder,
                $order,
                $paymentMethod,
                true
            )
        );

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->createAndAuthorizeWithoutAuthentication(
                $holder,
                $order,
                $paymentMethod,
                true
            )
        );

        $this->assertInstanceOf(
            Transaction::class,
            $cielo->createAndAuthenticateOnly(
                $holder,
                $order,
                $paymentMethod,
                true,
                'http://localhost/cielo/retorno.php'
            )
        );
    }
}
