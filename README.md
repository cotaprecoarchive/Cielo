## `CotaPreco\Cielo`

Cliente escrito em PHP para integração com o WebService da Cielo (solução de pagamentos com cartão de crédito).

#### Dependências
- PHP >= 5.5;
- libxml.

Versões antigas do PHP não serão suportadas, tais como 5.3, 5.4.

#### `Cielo#cancelTransactionPartially(): Transaction`
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

#### `Cielo#cancelTransaction(): Transaction`
> Cancela a transação por completo

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação

Exemplo:
```PHP
$cielo->cancelTransaction($transactionId);
```

A transação será cancelada por completo.

#### `Cielo#getTransactionById(): Transaction`
> Recupera uma transação pelo seu **TransactionId** (TID)

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação

Exemplo:
```PHP
$cielo->getTransactionById($transactionId);
```

Recupera a representação completa da transação identificada por `$transactionId`.

#### `Cielo#capture`
> Faz a captura de uma transação através do seu **TransactionId** (TID)

#### `Cielo#authorize`
> Executa a autorização de uma transação através do seu **TransactionId** (TID)

#### `Cielo#createTokenForHolder`
> Solicita a criação de um token para o portador do cartão de crédito

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
