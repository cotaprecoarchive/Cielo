<?php

namespace CotaPreco\CieloTestAssets;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class TransactionWithCapture
{
    /**
     * @return string
     */
    public function __toString()
    {
        return <<<WITH_CAPTURE
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
        <produto>3</produto>
        <parcelas>07</parcelas>
    </forma-pagamento>
    <dados-portador>
        <numero>4012001038443335</numero>
        <validade>201508</validade>
        <indicador>0</indicador>
    </dados-portador>
    <autorizacao>
        <codigo>6</codigo>
        <mensagem>Transação autorizada</mensagem>
        <data-hora>2015-01-01T00:00:00.000-02:00</data-hora>
        <valor>1000</valor>
        <lr>00</lr>
        <nsu>123456</nsu>
    </autorizacao>
    <captura>
        <codigo>6</codigo>
        <mensagem>Transacao capturada com sucesso</mensagem>
        <data-hora>2015-01-01T00:00:00.000-02:00</data-hora>
        <valor>1000</valor>
        <taxa-embarque>1000</taxa-embarque>
    </captura>
</transacao>
WITH_CAPTURE;
    }
}
