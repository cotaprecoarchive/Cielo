<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\Capture\FullCapture;
use CotaPreco\Cielo\Request\Capture\PartialCapture;
use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CaptureSerializerTest extends SerializerTestCase
{
    /**
     * @return array
     */
    public function provideCaptureRequests()
    {
        $transactionId = TransactionId::fromString('10017348980735271001');

        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        return [
            [
                new FullCapture($transactionId, $merchant),
                <<<XML
<requisicao-captura>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
</requisicao-captura>
XML
            ],
            [
                new PartialCapture($transactionId, $merchant, 10000),
                <<<XML
<requisicao-captura>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <valor>10000</valor>
    <taxa-embarque />
</requisicao-captura>
XML
            ],
            [
                new PartialCapture($transactionId, $merchant, 2000, 1000),
                <<<XML
<requisicao-captura>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <valor>2000</valor>
    <taxa-embarque>1000</taxa-embarque>
</requisicao-captura>
XML
            ]
        ];
    }

    /**
     * @test
     * @param RequestInterface $request
     * @param string           $xml
     * @dataProvider provideCaptureRequests
     */
    public function invoke(RequestInterface $request, $xml)
    {
        $serializer = new CaptureSerializer();

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
