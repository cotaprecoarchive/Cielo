<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\Cancellation;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractAllCancellationsTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $cancellations = <<<CANCELAMENTOS
<cancelamentos>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Transacao cancelada com sucesso</mensagem>
        <data-hora>2015-01-01T00:00:00.00-02:00</data-hora>
        <valor>1000</valor>
    </cancelamento>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Transacao cancelada com sucesso</mensagem>
        <data-hora>2015-01-01T00:00:00.00-02:00</data-hora>
        <valor>1000</valor>
    </cancelamento>
</cancelamentos>
CANCELAMENTOS;

        $allCancellations = new ExtractAllCancellations();

        $cancellations = $allCancellations(dom_import_simplexml(
            simplexml_load_string($cancellations)
        ));

        $this->assertContainsOnlyInstancesOf(Cancellation::class, $cancellations);
        $this->assertCount(2, $cancellations);
    }
}
