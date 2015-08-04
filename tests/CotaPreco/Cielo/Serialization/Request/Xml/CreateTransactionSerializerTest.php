<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Order;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\Request\CreateTransaction;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CreateTransactionSerializerTest extends SerializerTestCase
{
    /**
     * @return array
     */
    public function provideCreateRequests()
    {
        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        $holder = CardHolder::fromHolderNameAndCard(
            'FULANO DA SILVA',
            CreditCard::createWithoutSecurityCode(
                '4012001038443335',
                2015,
                8
            )
        );

        $order = Order::fromPreviouslyIssuedOrder(
            '1000',
            1000,
            new \DateTimeImmutable('2015-01-01')
        );

        $paymentMethod = PaymentMethod::forIssuerAsOneTimePayment(CardIssuer::fromIssuerString(CardIssuer::VISA));

        return [
            [
                CreateTransaction::authenticateOnly(
                    $merchant,
                    $holder,
                    $order,
                    $paymentMethod,
                    true,
                    'http://localhost/cielo.php'
                ),
                <<<XML
<requisicao-transacao>
    <url-retorno>http://localhost/cielo.php</url-retorno>
    <capturar>true</capturar>
    <bin>401200</bin>
    <gerar-token>false</gerar-token>
</requisicao-transacao>
XML
            ]
        ];
    }

    /**
     * @test
     * @param CreateTransaction $request
     * @param string            $xml
     * @dataProvider provideCreateRequests
     */
    public function invoke($request, $xml)
    {
        $serializer = new CreateTransactionSerializer($request);

        $this->assertTrue($serializer->canSerialize($request));

        $this->assertXmlIgnoringSpecifiedNodes(
            [
                'dados-ec',
                'dados-portador',
                'dados-pedido',
                'forma-pagamento'
            ],
            $xml,
            $serializer($request)
        );
    }
}
