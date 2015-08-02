<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\Cancellation\FullCancellation;
use CotaPreco\Cielo\Request\Cancellation\PartialCancellation;
use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CancellationSerializerTest extends SerializerTestCase
{
    /**
     * @return array
     */
    public function provideCancellations()
    {
        $transactionId = TransactionId::fromString('10017348980735271001');

        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        return [
            [
                new FullCancellation($transactionId, $merchant),
                <<<XML
<requisicao-cancelamento>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
</requisicao-cancelamento>
XML
            ],
            [
                new PartialCancellation($transactionId, $merchant, 1000),
                <<<XML
<requisicao-cancelamento>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <valor>1000</valor>
</requisicao-cancelamento>
XML
            ]
        ];
    }

    /**
     * @test
     * @param RequestInterface $request
     * @param string           $xml
     * @dataProvider provideCancellations
     */
    public function invoke(RequestInterface $request, $xml)
    {
        $serializer = new CancellationSerializer();

        $this->assertTrue($serializer->canSerialize($request));

        $this->assertXmlEqualsIgnoringRootAttributes(
            sprintf(
                $xml,
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            ),
            $serializer($request)
        );
    }
}
