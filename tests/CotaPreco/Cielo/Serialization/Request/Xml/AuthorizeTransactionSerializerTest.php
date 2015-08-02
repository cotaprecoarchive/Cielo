<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\AuthorizeTransaction;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AuthorizeTransactionSerializerTest extends SerializerTestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $affiliationId  = getenv('CIELO_AFFILIATION_ID');
        $affiliationKey = getenv('CIELO_AFFILIATION_KEY');

        $request = new AuthorizeTransaction(
            TransactionId::fromString('10017348980735271001'),
            Merchant::fromAffiliationIdAndKey($affiliationId, $affiliationKey)
        );

        $serializer = new AuthorizeTransactionSerializer();

        $this->assertTrue($serializer->canSerialize($request));

        $expected = <<<REQUISICAO_AUTORIZACAO_TID
<requisicao-autorizacao-tid>
    <tid>10017348980735271001</tid>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
</requisicao-autorizacao-tid>
REQUISICAO_AUTORIZACAO_TID;

        $this->assertXmlEqualsIgnoringRootAttributes(
            sprintf(
                $expected,
                $affiliationId,
                $affiliationKey
            ),
            $serializer($request)
        );
    }
}
