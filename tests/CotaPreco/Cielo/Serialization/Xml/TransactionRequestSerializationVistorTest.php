<?php

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\CreditCardType;
use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Order;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\Request\TransactionRequest;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class TransactionRequestSerializationVistorTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function assertPreConditions()
    {
        if (! class_exists(\XMLWriter::class)) {
            $this->markTestSkipped();
        }
    }

    /**
     * @return array
     */
    public function provideTransactionsAndExpectedStructures()
    {
        /* @var Merchant $merchant */
        $merchant      = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        /* @var Order $order */
        $order         = Order::fromOrderNumberAndValue('1234', 10000);

        /* @var CardToken $token */
        $token         = CardToken::fromPreviouslyIssuedTokenString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=');

        /* @var PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethod::forIssuerAsOneTimePayment(
            CardIssuer::fromCreditCardType(CreditCardType::VISA)
        );

        return [
            [
                TransactionRequest::createAndAuthorizeWithoutAuthentication(
                    $merchant,
                    $token,
                    $order,
                    $paymentMethod,
                    true,
                    null,
                    true
                ),
                <<<XML
<autorizar>3</autorizar>
<capturar>true</capturar>
<gerar-token>true</gerar-token>
XML
            ],
            [
                TransactionRequest::createAndAuthorizeWithoutAuthentication(
                    $merchant,
                    $token,
                    $order,
                    $paymentMethod,
                    false,
                    'http://localhost/cielo.php',
                    false
                ),
                <<<XML
<url-retorno>http://localhost/cielo.php</url-retorno>
<autorizar>3</autorizar>
<capturar>false</capturar>
<gerar-token>false</gerar-token>
XML
            ],
            [
                TransactionRequest::createAndAuthorizeOnlyIfAuthenticated(
                    $merchant,
                    CardHolder::fromCard(
                        CreditCard::createWithoutSecurityCode(
                            '4012001038443335',
                            2015,
                            8
                        )
                    ),
                    $order,
                    $paymentMethod,
                    true,
                    null,
                    true
                ),
                <<<XML
<autorizar>1</autorizar>
<capturar>true</capturar>
<bin>401200</bin>
<gerar-token>true</gerar-token>
XML
            ]
        ];
    }

    /**
     * @test
     * @param TransactionRequest $request
     * @param string             $xml
     * @dataProvider provideTransactionsAndExpectedStructures
     */
    public function withRealXmlWriter(TransactionRequest $request, $xml)
    {
        $writer = new \XMLWriter();

        $writer->openMemory();

        $visitor = new TransactionRequestSerializationVistor($writer);

        $visitor->visit($request);

        $wrapRoot = function ($xml) {
            return sprintf('<root>%s</root>', $xml);
        };

        $this->assertXmlStringEqualsXmlString(
            $wrapRoot($xml),
            $wrapRoot($writer->outputMemory(true))
        );
    }
}
