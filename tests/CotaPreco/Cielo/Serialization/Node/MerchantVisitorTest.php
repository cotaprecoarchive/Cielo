<?php

namespace CotaPreco\Cielo\Serialization\Node;

use CotaPreco\Cielo\Merchant;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class MerchantVisitorTest extends TestCase
{
    /**
     * @test
     */
    public function visit()
    {
        $document = new \DOMDocument();

        $root = $document->createElement('root');

        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        $merchant->accept(new MerchantVisitor($root));

        $expected = <<<EXPECTED
<root>
    <dados-ec>
        <numero>%s</numero>
        <chave>%s</chave>
    </dados-ec>
</root>
EXPECTED;

        $this->assertXmlStringEqualsXmlString(
            sprintf(
                $expected,
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            ),
            $document->saveXML($root)
        );
    }
}
