<?php

namespace CotaPreco\Cielo\Serialization;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\CancellationRequestInterface;
use CotaPreco\Cielo\Request\FullCancellationRequest;
use CotaPreco\Cielo\Request\PartialCancellationRequest;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CancellationRequestXmlSerializerTest extends XmlSerializerTestCase
{
    /**
     * @return array
     */
    public function provideCancellationRequests()
    {
        $transactionId = TransactionId::fromString('10017348980735271001');

        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        return [
            [
                new FullCancellationRequest($transactionId, $merchant),
                <<<XML
<requisicao-cancelamento>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>1006993069</numero>
        <chave>25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3</chave>
    </dados-ec>
</requisicao-cancelamento>
XML
            ],
            [
                new PartialCancellationRequest(
                    $transactionId,
                    $merchant,
                    50000
                ),
                <<<XML
<requisicao-cancelamento>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>1006993069</numero>
        <chave>25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3</chave>
    </dados-ec>
    <valor>50000</valor>
</requisicao-cancelamento>
XML
            ],
            [
                new PartialCancellationRequest(
                    $transactionId,
                    $merchant,
                    1000
                ),
                <<<XML
<requisicao-cancelamento>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>1006993069</numero>
        <chave>25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3</chave>
    </dados-ec>
    <valor>1000</valor>
</requisicao-cancelamento>
XML
            ]
        ];
    }

    /**
     * @test
     * @param CancellationRequestInterface $request
     * @param string                       $xml
     * @dataProvider provideCancellationRequests
     */
    public function invoke(CancellationRequestInterface $request, $xml)
    {
        $serializer = new CancellationRequestXmlSerializer();

        $this->assertRequestXmlEquals(
            $xml,
            $serializer($request)
        );
    }
}
