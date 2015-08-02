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

namespace CotaPreco\Cielo\Serialization\Node;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CardHolderOrTokenVisitor extends AbstractNodeSerializationVisitor
{
    /**
     * {@inheritdoc}
     * @param CardHolder|CardToken $holderOrToken
     */
    public function visit(AcceptsSerializationVisitor $holderOrToken)
    {
        $child = $this->document->createElement('dados-portador');

        if ($holderOrToken instanceof CardHolder) {
            $this->writeCardHolder($child, $holderOrToken);
        }

        if ($holderOrToken instanceof CardToken) {
            $child->appendChild(
                $this->document->createElement(
                    'token',
                    (string) $holderOrToken
                )
            );
        }

        $this->root->appendChild($child);
    }

    /**
     * @param \DOMElement $root
     * @param CardHolder  $holder
     */
    private function writeCardHolder(\DOMElement $root, $holder)
    {
        $this->writeCardInformation($root, $holder->getCard());

        if (! is_null($holder->getName())) {
            $root->appendChild(
                $this->document->createElement('nome-portador', $holder->getName())
            );
        }
    }

    /**
     * @param \DOMElement $root
     * @param CreditCard  $card
     */
    private function writeCardInformation(\DOMElement $root, CreditCard $card)
    {
        $root->appendChild(
            $this->document->createElement(
                'numero',
                $card->getNumber()
            )
        );

        $root->appendChild(
            $this->document->createElement(
                'validade',
                $card->getExpiration()->getFullYearAndMonth()
            )
        );

        $root->appendChild(
            $this->document->createElement(
                'indicador',
                $card->getSecurityCodeIndicator()
            )
        );

        if (! is_null($card->getSecurityCode())) {
            $root->appendChild(
                $this->document->createElement(
                    'codigo-seguranca',
                    $card->getSecurityCode()
                )
            );
        }
    }
}
