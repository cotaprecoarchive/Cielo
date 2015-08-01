<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\Cancellation;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AllCancellationsFromRootNodeTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $root = <<<CANCELLATIONS
<cancelamentos>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Transacao cancelada com sucesso</mensagem>
        <data-hora>2015-07-31T23:36:05.000-03:00</data-hora>
        <valor>10000</valor>
    </cancelamento>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Cancelamento parcial realizado com sucesso</mensagem>
        <data-hora>2015-07-31T23:35:15.000-03:00</data-hora>
        <valor>10000</valor>
    </cancelamento>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Cancelamento parcial realizado com sucesso</mensagem>
        <data-hora>2015-07-31T23:35:12.000-03:00</data-hora>
        <valor>10000</valor>
    </cancelamento>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Cancelamento parcial realizado com sucesso</mensagem>
        <data-hora>2015-07-31T23:35:09.000-03:00</data-hora>
        <valor>10000</valor>
    </cancelamento>
    <cancelamento>
        <codigo>9</codigo>
        <mensagem>Cancelamento parcial realizado com sucesso</mensagem>
        <data-hora>2015-07-31T23:35:01.000-03:00</data-hora>
        <valor>10000</valor>
    </cancelamento>
</cancelamentos>
CANCELLATIONS;

        $allCancellations = new AllCancellationsFromRootNode();

        $cancellations = $allCancellations(new \SimpleXMLElement($root));

        $this->assertCount(5, $cancellations);
        $this->assertContainsOnlyInstancesOf(Cancellation::class, $cancellations);
    }
}
