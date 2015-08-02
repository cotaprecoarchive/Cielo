<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Cvv;
use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\Request\CreateTokenForHolder;
use CotaPreco\Cielo\RequestInterface;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CreateTokenForHolderSerializerTest extends SerializerTestCase
{
    /**
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function provideHolders()
    {
        $merchant = $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        return [
            [
                new CreateTokenForHolder($merchant, CardHolder::fromCard(
                    CreditCard::createWithSecurityCode(
                        '4012001038443335',
                        2015,
                        8,
                        Cvv::fromVerificationValue('973')
                    )
                )),
                <<<XML
<requisicao-token>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>1</indicador>
        <codigo-seguranca>973</codigo-seguranca>
    </dados-portador>
</requisicao-token>
XML
            ],
            [
                new CreateTokenForHolder($merchant, CardHolder::fromCard(
                    CreditCard::createWithInexistentSecurityCode(
                        '4012001038443335',
                        2015,
                        8
                    )
                )),
                <<<XML
<requisicao-token>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>9</indicador>
    </dados-portador>
</requisicao-token>
XML
            ],
            [
                new CreateTokenForHolder($merchant, CardHolder::fromCard(
                    CreditCard::createWithoutSecurityCode(
                        '4012001038443335',
                        2015,
                        8
                    )
                )),
                <<<XML
<requisicao-token>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>0</indicador>
    </dados-portador>
</requisicao-token>
XML
            ],
            [
                new CreateTokenForHolder($merchant, CardHolder::fromCard(
                    CreditCard::createWithUnreadableSecurityCode(
                        '4012001038443335',
                        2015,
                        8
                    )
                )),
                <<<XML
<requisicao-token>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>2</indicador>
    </dados-portador>
</requisicao-token>
XML
            ],
            [
                new CreateTokenForHolder($merchant, CardHolder::fromHolderNameAndCard(
                    'FULANO DA SILVA',
                    CreditCard::createWithSecurityCode(
                        '4012001038443335',
                        2015,
                        8,
                        Cvv::fromVerificationValue('973')
                    )
                )),
                <<<XML
<requisicao-token>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>1</indicador>
        <codigo-seguranca>973</codigo-seguranca>
        <nome-portador>FULANO DA SILVA</nome-portador>
    </dados-portador>
</requisicao-token>
XML
            ],
        ];
    }

    /**
     * @test
     * @param RequestInterface $request
     * @param string           $xml
     * @dataProvider provideHolders
     */
    public function invoke(RequestInterface $request, $xml)
    {
        $serializer = new CreateTokenForHolderSerializer();

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
