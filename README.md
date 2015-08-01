## `CotaPreco\Cielo`

Cliente escrito em PHP para integração com o WebService da Cielo (solução de pagamentos com cartão de crédito).

#### `Cielo#cancelTransactionPartially`
> Cancela uma transação parcialmente (um determinado valor)

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação
| `$value` | **int** | Valor à ser cancelado

Exemplo:
```PHP
$cielo->cancelTransactionPartially($transactionId, 1000);
```

No exemplo acima, será cancelado de `$transactionId` o valor de 10 reais *(ou da moeda usada no pedido)*.

#### `Cielo#cancelTransaction`
> Cancela a transação por completo

#### `Cielo#getTransactionById`
> Recupera uma transação pelo seu **TransactionId** (TID)

#### `Cielo#createAndAuthorizeWithoutAuthentication`
> Cria uma transação e autoriza sem autenticação

#### `Cielo#createAndAuthenticateOnly`
> Cria uma transação e autentica apenas

#### `Cielo#createAndAuthorizeOnly`
> Cria uma transação e autoriza apena

#### `Cielo#createAndAuthorizeOnlyIfAuthenticated`
> Cria uma transação e autoriza apenas se for autenticada


## Licença
[MIT](https://github.com/CotaPreco/Cielo/blob/master/LICENSE) &copy; Cota Preço, 2015.
