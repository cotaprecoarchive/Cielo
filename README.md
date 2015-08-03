## `CotaPreco\Cielo`

[![Build Status](https://travis-ci.org/CotaPreco/Cielo.svg)](https://travis-ci.org/CotaPreco/Cielo)

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

#### `Cielo#capture(): Transaction`
> Faz a captura de uma transação através do seu **TransactionId** (TID)

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação

Exemplo:
```PHP
$cielo->capture($transactionId);
```

#### `Cielo#authorize(): Transaction`
> Executa a autorização de uma transação através do seu **TransactionId** (TID)

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação

Exemplo:
```PHP
$cielo->authorize($transactionId);
```

#### `Cielo#createTokenForHolder`
> Solicita a criação de um token para o portador do cartão de crédito

#### `Cielo#createAndAuthorizeWithoutAuthentication(): Transaction`
> Cria uma transação e autoriza sem autenticação

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$holder` | `CardHolder` / `CardToken` | Identificação do portador do cartão
| `$order` | `Order` | Detalhes do pedido (número, valor, moeda, etc)
| `$paymentMethod` | `PaymentMethod` | Forma de pagamento (À Vista, Débito, etc)
| `$capture` | **bool** | A captura do valor já deverá ser realizada?
| `$generateToken` | **bool** *(padrão: false)* | Gerar token para o portador do cartão

Exemplo:
```PHP
$cielo->createAndAuthorizeWithoutAuthentication(
    CardHolder::fromCard(
        CreditCard::createWithSecurityCode(
            '4012001037141112',
            2018,
            5,
            Cvv::fromVerificationValue('123')
        )
    ),
    Order::fromOrderNumberAndValue(
        '1234',
        10000
    ),
    PaymentMethod::forIssuerAsOneTimePayment(
        CardIssuer::fromIssuerString(CardIssuer::VISA)
    ),
    true
);
```

Uma transação será criada, o número do pedido (dentro da loja) é `1234` e o valor é de **R$ 10,00**. A transação será capturada automáticamente caso a mesma seja autorizada, gerando o crédito para o lojista.

#### `Cielo#createAndAuthenticateOnly`
> Cria uma transação e autentica apenas

| Argumento | Tipo | Descrição
| :---: | :---: | ---
| `$holder` | `CardHolder` / `CardToken` | Identificação do portador do cartão
| `$order` | `Order` | Detalhes do pedido (número, valor, moeda, etc)
| `$paymentMethod` | `PaymentMethod` | Forma de pagamento (À Vista, Débito, etc)
| `$capture` | **bool** | A captura do valor já deverá ser realizada?
| `$returnUrl` | **string** | URL de retorno
| `$generateToken` | **bool** *(padrão: false)* | Gerar token para o portador do cartão

Cria uma transação requerendo apenas a autenticação do portador. O mesmo deverá ser redirecionado para o ambiente Cielo para realização do processo de autenticação. A transação poderá ser autorizada e capturada posteriormente, independente se a autenticação for ou não bem sucedida.

```PHP
$cielo->createAndAuthenticateOnly(
    $holder,
    $order,
    $paymentMethod,
    true,
    'http://localhost/cielo'
);
```

#### `Cielo#createAndAuthorizeOnly`
> Cria uma transação e autoriza apena

#### `Cielo#createAndAuthorizeOnlyIfAuthenticated`
> Cria uma transação e autoriza apenas se for autenticada


## Licença
[MIT](https://github.com/CotaPreco/Cielo/blob/master/LICENSE) &copy; Cota Preço, 2015.
