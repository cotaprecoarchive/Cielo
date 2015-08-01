<?php

/*
 * Copyright (c) 2015 Cota Preço
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\IdentifiesHolder;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CardHolderSerializationVisitor extends AbstractXmlWriterSerializationVisitor
{
    /**
     * {@inheritdoc}
     * @param IdentifiesHolder|CardToken|CardHolder $holder
     */
    public function visit(AcceptsSerializationVisitor $holder)
    {
        $this->writer->startElement('dados-portador');

        if ($holder instanceof CardHolder) {
            $this->writeCardHolder($holder);
        }

        if ($holder instanceof CardToken) {
            $this->writer->writeElement('token', (string) $holder);
        }

        $this->writer->endElement();
    }

    /**
     * @param CardHolder $holder
     */
    public function writeCardHolder(CardHolder $holder)
    {
        $card = $holder->getCard();

        $this->writeAllValues(array_filter(
            [
                'numero'           => $card->getNumber(),
                'validade'         => $card->getExpiration()->getFullYearAndMonth(),
                'indicador'        => $card->getSecurityCodeIndicator(),
                'codigo-seguranca' => $card->getSecurityCode(),
                'nome-portador'    => $holder->getName()
            ]
        ));
    }
}
