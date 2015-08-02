<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\SearchTransaction;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class SearchTransactionSerializerTest extends SerializerTestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $serializer = new SearchTransactionSerializer();

        $request = new SearchTransaction(
            TransactionId::fromString('10017348980735271001'),
            Merchant::fromAffiliationIdAndKey(
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            )
        );

        $this->assertTrue($serializer->canSerialize($request));

        $expected = <<<REQUISICAO_CONSULTA
<requisicao-consulta>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
</requisicao-consulta>
REQUISICAO_CONSULTA;

        $this->assertXmlEqualsIgnoringRootAttributes(
            sprintf(
                $expected,
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            ),
            $serializer($request)
        );
    }
}
