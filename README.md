## `CotaPreco\Cielo`

Cliente escrito em PHP para integração com o WebService da Cielo (solução de pagamentos com cartão de crédito).

#### Dependências
- PHP >= 5.5;
- libxml (DOM);
- `ramsey/uuid` (através do **composer**).

Versões antigas do PHP não serão suportadas, tais como 5.3, 5.4.

### Começando
Antes de realizar as operações com a Cielo, é necessário configurar o cliente com as informações da loja *(Merchant)* e qual ambiente será utilizado: **desenvolvimento** ou **produção**.

Para especificação do ambiente, as constantes `CieloEnvironment::DEVELOPMENT` e `CieloEnvironment::PRODUCTION` devem ser utilizadas, por exemplo (loja de testes):

```PHP
use CotaPreco\Cielo\Cielo;
use CotaPreco\Cielo\CieloEnvironment;

$cielo = Cielo::createFromAffiliationIdAndKey(
    CieloEnvironment::DEVELOPMENT,
    '1006993069',
    '25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3'
);
```

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
