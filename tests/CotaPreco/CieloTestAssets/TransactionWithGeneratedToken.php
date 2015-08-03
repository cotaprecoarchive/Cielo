<?php

namespace CotaPreco\CieloTestAssets;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class TransactionWithGeneratedToken
{
    /**
     * @return string
     */
    public function __toString()
    {
        return <<<WITH_GENERATED_TOKEN
<?xml version="1.0" encoding="ISO-8859-1"?>
<transacao xmlns="http://ecommerce.cbmp.com.br" versao="1.2.1" id="1234">
    <tid>100699306903613E1001</tid>
    <pan>uv9yI5tkhX9jpuCt+dfrtoSVM4U3gIjvrcwMBfZcadE=</pan>
    <dados-pedido>
        <numero>1234</numero>
        <valor>1000</valor>
        <moeda>986</moeda>
        <data-hora>2015-01-01T00:00:00.000-02:00</data-hora>
        <idioma>PT</idioma>
    </dados-pedido>
    <forma-pagamento>
        <bandeira>visa</bandeira>
        <produto>2</produto>
        <parcelas>12</parcelas>
    </forma-pagamento>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>0</indicador>
    </dados-portador>
    <token>
        <dados-token>
            <codigo-token>TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=</codigo-token>
            <status>1</status>
            <numero-cartao-truncado>455187******0183</numero-cartao-truncado>
        </dados-token>
    </token>
</transacao>
WITH_GENERATED_TOKEN;
    }
}
