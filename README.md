## `CotaPreco\Cielo` [![Build Status](https://travis-ci.org/CotaPreco/Cielo.svg)](https://travis-ci.org/CotaPreco/Cielo) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CotaPreco/Cielo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CotaPreco/Cielo/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/CotaPreco/Cielo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/CotaPreco/Cielo/?branch=master)

Cliente escrito em PHP para integração com o WebService da Cielo (solução de pagamentos com cartão de crédito).

![Cielo](https://raw.githubusercontent.com/CotaPreco/Cielo/master/Cielo.png)

#### Dependências
- PHP >= 5.6;
- libxml (DOM);
- `ramsey/uuid` (através do **composer**).

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

# Cancelamento

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

# Captura
#### `Cielo#capture(): Transaction`
> Faz a captura de uma transação através do seu **TransactionId** (TID)

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação

Exemplo:
```PHP
$cielo->capture($transactionId);
```

#### `Cielo#capturePartially(): Transaction`
> Faz a captura de um valor parcial da transação

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$transactionId` | `TransactionId` &#124; **string** | Identificador da transação
| `$value` | **int** | Valor à se capturado
| `$shipping` | **int** *(opcional)* | Valor da taxa de embarque

Através do `$transactionId`, faz uma captura parcial do valor total da transação. Lembrando que a captura uma vez feita é definitiva, independente de ser parcial ou completa.

Exemplo:
```PHP
$cielo->capturePartially($transactionId, 5000);
```

Será capturado apenas **R$ 50,00** da transação.

# Autorização & Tokenização
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

| Argumento | Tipo | Descrição
| --- | :---: | ---
| `$holder` | `CardHolder` | Dados do portador do cartão

Por várias questões de segurança, a informação do cartão de crédito do cliente não deve armazenada junto a loja (ser salva em seu banco de dados para reusar posteriormente em novas transações) &mdash; exceto se a infraestrutura da loja for compatível com o [PCI](https://www.pcisecuritystandards.org/security_standards/).

O processo de tokenização oferece uma alternativa para que ao invés de armazenar os dados do cartão, você armazene um **token** que identifica o cartão com a **Cielo**. Esse **token** poderá ser reusado para processar as transações subsequentes.

Exemplo:
```PHP
$token = $cielo->createTokenForHolder(
    CardHolder::fromCard(
        CreditCard::createWithSecurityCode(
            '4012001037141112',
            2018,
            5,
            '123'
        )
    )
);

var_dump(
    $token->getStatus(),
    $token->getTruncatedCardNumber(),
    (string) $token->getToken()
);
```

Na loja de testes, a saída será:
```
int(1)
string(16) "211141******2104"
string(44) "HYcQ0MQ39fl8kn9OR7lFsTtxa+wNuM4lqQLUeN5SYZY="
```

O número do cartão truncado poderá ser armazenado para fins de referência.

# Criando transações
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
            '123'
        )
    ),
    Order::fromOrderNumberAndValue(
        '1234',
        1000
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

#### `Cielo#createAndAuthorizeOnlyIfAuthenticated(): Transaction`
> Cria uma transação e autoriza apenas se for autenticada

## Licença
[MIT](https://github.com/CotaPreco/Cielo/blob/master/LICENSE) &copy; Cota Preço, 2015.
