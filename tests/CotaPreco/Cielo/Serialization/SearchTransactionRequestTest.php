<?php

namespace CotaPreco\Cielo\Serialization;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\SearchTransactionRequest;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class SearchTransactionRequestTest extends XmlSerializerTestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $transactionId = TransactionId::fromString('10017348980735271001');

        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        $serializer = new SearchTransactionRequestXmlSerializer();

        $expected = <<<REQUISICAO_CONSULTA
<requisicao-consulta>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>1006993069</numero>
        <chave>25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3</chave>
    </dados-ec>
</requisicao-consulta>
REQUISICAO_CONSULTA;

        $request = new SearchTransactionRequest($transactionId, $merchant);

        $this->assertRequestXmlEquals(
            $expected,
            $serializer($request)
        );
    }
}
