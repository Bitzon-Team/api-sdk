### api-sdk
Disclaimer: This SDK is provided by the community's enthusiastic users for access to the Bitzon API reference by the access organization/individual. The code is free to use, but Bitzon does not assume any joint liability without full testing.

Any consequences arising from the use of the code in this project are entirely at your own risk.

### Usage
- Sign up and log in to Bitzon, apply for api key and secret


# APIS

[TOC]

## Api list

`symbol` rule：quote currency + base currency，`BTC/USDT` => `BTC_USDT`。    

| Api type   | Request method and url                                       | Description                                         | Verification signature |
| ---------- | ------------------------------------------------------------ | --------------------------------------------------- | ---------------------- |
| Market     | `GET /v1/market/prices`                                      | Get current market prices of all symbols            | N                      |
| Market     | `GET /v1/market/fex`                                         | Get US Dollar against other countries               | N                      |
| Market     | `GET /v1/market/bars/<symbol>/<type>`                        | Get Klines                                          | N                      |
| Market     | `GET URL /v1/market/depth/<symbol>`                          | Get market depth                                    | N                      |
| BaseInfo   | `GET /v1/market/feeRates`                                    | Get a commission rate                               | N                      |
| BaseInfo   | `GET /v1/market/trades`                                      | Get all currency / symbol configuration information | N                      |
| Account    | `GET URL /v1/user/accounts`                                  | Get user balance                                    | Y                      |
| SystemInfo | `GET /v1/market/timestamp`                                   | Get server timestamp                                | N                      |
| ErrorInfo  | `GET /v1/market/errorCodes`                                  | Get all the error codes                             | N                      |
| ErrorInfo  | `GET /v1/market/error`                                       | Error response example                              | N                      |
| Trading    | `POST /v1/trade/orders`                                      | Create an order                                     | Y                      |
| Trading    | `GET /v1/trade/orders/<the-order-id>`                        | Get the order detail of an order                    | Y                      |
| Trading    | `GET /v1/trade/orders<?symbol=symbol><&offsetId=order-id><&limit=limit>` | Get all orders                                      | Y                      |
| Trading    | `GET /v1/trade/orders/active`                                | Get outstanding orders                              | Y                      |
| Trading    | `POST /v1/trade/orders/<order-id>/cancel`                    | Cancel order                                        | Y                      |

------

### Market

#### 1. Get current market prices of all symbols

Request：

```
GET /v1/market/prices
```

Response：

Data fields：

| Symbol | Subdata               | Type   | Must | Description                   |
| ------ | --------------------- | ------ | ---- | ----------------------------- |
| AE_BTC | Market data of symbol | String | YES  | Symbol                        |
|        | ——data[0]             | Long   | NO   | Opening price time stamp      |
|        | ——data[1]             | Float  | NO   | Opening price                 |
|        | ——data[2]             | Float  | NO   | Highest price                 |
|        | ——data[3]             | Float  | NO   | Lowest price                  |
|        | ——data[4]             | Float  | NO   | Closing price (current price) |
|        | ——data[5]             | Float  | NO   | Volume                        |

Data example：

```
{
    // symbol:[timestamp in millis, opening price, highest price, lowest price, closing price, volume]
    "AE_BTC": [
        1546239118833,
        0.0001,
        0.0001,
        0.0001,
        0.0001,
        0.4
    ],
    "AE_ETH": [
        1546238938502,
        0.001,
        0.001,
        0.001,
        0.001,
        1
    ],
    // ...
}
```

#### 2. Get Klines

Request：

```
GET /v1/market/bars/<symbol>/<type>
```

Parameters：

| Parameter | Type   | Must | Description                                                  |
| --------- | ------ | ---- | ------------------------------------------------------------ |
| <symbol>  | String | YES  | e.g. 'BTC_USDT'                                              |
| <type>    | String | YES  | K Time unit of K line。e.g. "`K_1_SEC`", "`K_1_MIN`", "`K_1_HOUR`", "`K_1_DAY`". |

Response：

Data fields：

| Field name | Subdata                 | Type   | Must | Description                   |
| ---------- | ----------------------- | ------ | ---- | ----------------------------- |
| bars       | K line data of symbols. | String | YES  | Symbol                        |
|            | ——data[0]               | Long   | NO   | Opening price time stamp      |
|            | ——data[1]               | Float  | NO   | Opening price                 |
|            | ——data[2]               | Float  | NO   | Highest price                 |
|            | ——data[3]               | Float  | NO   | Lowest price                  |
|            | ——data[4]               | Float  | NO   | Closing price (current price) |
|            | ——data[5]               | Float  | NO   | Volume                        |

Response example：

```
{
    "bars": [
        [
            1545836400000,
            3706.31,
            3706.31,
            3706.31,
            3706.31,
            0.04
        ],
        [
            1545922800000,
            3721.1,
            3721.1,
            10,
            3581.53,
            4.6293
        ]
    ]
}
```

##### Data format of K line

All bar data is represented as array that contains 6 elements which are:

[timestamp in millis, open price, high price, low price, close price, volume]

Example:
```
[1544602783000, 3302.1, 3419.9, 3029.6, 3298, 9.34]
```

#### 3.  Get US Dollar against other countries

Request：

```
GET /v1/market/fex
```

Response：

Data fields：

| Field name | Subdata                 | Type   | Must | Description             |
| ---------- | ----------------------- | ------ | ---- | ----------------------- |
| from       |                         | String | YES  | The currency to convert |
| tos        | Conversion target array | String | YES  | Conversion target array |
| exchanges  | Exchange rate array     | String | YES  | Exchange rate array     |

Response example：

```
{
    "from": "USD",
    "tos": [
        "AUD",
        "CAD",
        "CNY",
        "EUR",
        "GBP",
        "HKD",
        "JPY",
        "KRW",
        "RUB",
        "SGD",
        "TWD"
    ],
    "exchanges": {
        "USD_GBP": 0.78571,
        "USD_CAD": 1.3606,
        "USD_JPY": 109.081,
        "USD_HKD": 7.83671,
        "USD_TWD": 30.782,
        "USD_EUR": 0.8728,
        "USD_RUB": 69.6733,
        "USD_AUD": 1.4256,
        "USD_KRW": 1119.04,
        "USD_SGD": 1.3643,
        "USD_CNY": 6.8507
    }
}
```

#### 4. Get market depth

Request：

```
GET URL /v1/market/depth/<symbol>
```

Parameter：

| Parameter | Type   | Must | Description |
| --------- | ------ | ---- | ----------- |
| symbol    | String | YES  | Symbol      |

Response：

Data fields：

| Field name | Description |
| ---------- | ----------- |
| symbol     | Symbol      |
| timestamp  | Timestamp   |
| price      | Price       |
| buyOrders  | Buy data    |
| sellOrders | Sell data   |

Response example：

```
{
        "symbol": "BTC_USDT",
        "sequenceId": 1929416,
        "timestamp": 1546659807581,
        "price": 3750.000000000000000000,
        "buyOrders": [{
            "price": 3746.700000000000000000,
            "amount": 0.000200000000000000
        }, {
            "price": 3741.000000000000000000,
            "amount": 0.000800000000000000
        }],
        "sellOrders": [{
            "price": 3750.770000000000000000,
            "amount": 0.000700000000000000
        }, {
            "price": 3750.870000000000000000,
            "amount": 0.000400000000000000
        }]
}
```

### Base information

#### 1. Get all currency / symbols information

Request：

```
GET /v1/market/trades
```

Response：

Data fields：

| Fields name | Subdata                        | Type   | Must | Description                                  |
| ----------- | ------------------------------ | ------ | ---- | -------------------------------------------- |
| currencies  | Configuration data of currency | String | YES  | Data of currency                             |
|             | ——name                         | String | YES  | Currency name                                |
|             | ——depositEnabled               | Bool   | YES  | Deposit status                               |
|             | ——withdrawEnabled              | Bool   | YES  | Withdraw status                              |
|             | ——meta                         | String | NO   |                                              |
| symbols     | Configuration data of symbol   | String | NO   | onfiguration data of symbol                  |
|             | ——name                         | String | YES  | Symbol                                       |
|             | ——baseName                     | String | YES  | Base currency                                |
|             | ——baseScale                    | Int    | YES  | Base currency display accuracy               |
|             | ——baseMinimum                  | Float  | YES  | Minimum transaction volume of base currency  |
|             | ——quoteName                    | String | YES  | Quote currency                               |
|             | ——quoteScale                   | Int    | YES  | Quote currency display accuracy              |
|             | ——quoteMinimum                 | Float  | YES  | Minimum transaction volume of quote currency |
|             | ——startTime                    | Long   | NO   |                                              |
|             | ——endTime                      | Long   | NO   |                                              |
|             | ——meta                         | String | NO   |                                              |

```
{
    "currencies": [
        {
            "name": "AE",
            "depositEnabled": false,
            "withdrawEnabled": false,
            "meta": {}
        },
        {
            "name": "IOST",
            "depositEnabled": false,
            "withdrawEnabled": false,
            "meta": {}
        },
    ],
    "symbols": [
        {
            "name": "BTC_USDT",
            "baseName": "BTC",
            "baseScale": 4,
            "baseMinimum": 0.001,
            "quoteName": "USDT",
            "quoteScale": 2,
            "quoteMinimum": 10,
            "startTime": 0,
            "endTime": 0,
            "meta": {}
        },
        {
            "name": "ETH_BTC",
            "baseName": "ETH",
            "baseScale": 4,
            "baseMinimum": 0.01,
            "quoteName": "BTC",
            "quoteScale": 5,
            "quoteMinimum": 0.001,
            "startTime": 0,
            "endTime": 0,
            "meta": {}
        }
    ]
}
```

#### 2. Get a commission rate

Request：

```
GET /v1/market/feeRates
```

Response：

Data fields：

| Field name        | Subdata                            | Type   | Must | Description                                                  |
| ----------------- | ---------------------------------- | ------ | ---- | ------------------------------------------------------------ |
| timestamp         |                                    | String | YES  |                                                              |
| alwaysChargeQuote |                                    | Bool   | YES  | Whether the handling fee for the order is based on the quote currency |
| feeRates          | Commission rate of maker and taker | String | YES  | Commission rate of maker and taker                           |
|                   | —symbol                            | String | NO   | Symbol                                                       |
|                   | —— takerFeeRate                    | Float  | NO   | Taker commission rate                                        |
|                   | —— makerFeeRate                    | Float  | NO   | Maker commission rate                                        |

Response example：

```
{
    "timestamp": 1546417882051,
    "alwaysChargeQuote": true,
    "feeRates": {
        "SNT_BTC": {
            "takerFeeRate": 0.002,
            "makerFeeRate": 0.002
        },
        "OMG_USDT": {
            "takerFeeRate": 0.002,
            "makerFeeRate": 0.002
        },
    }
}
```

---

### Account information - need signature

#### 1. Get user balance

Request：

```
GET URL /v1/user/accounts
```

Response：

Data fields：

| Field name | Description       |
| ---------- | ----------------- |
| currency   | Currency name     |
| available  | Available balance |
| frozen     | Frozen balance    |
| locked     | Locked balance    |

Response：

Data fields：

| Field name | Subdata            | Type   | Must | Description        |
| ---------- | ------------------ | ------ | ---- | ------------------ |
| accounts   | Balance of account | String | YES  | Balance of account |
|            | —— currency        | String | YES  | Currency name      |
|            | —— available       | Long   | YES  | Available balance  |
|            | —— frozen          | Long   | YES  | Frozen balance     |
|            | —— locked          | Long   | YES  | Locked balance     |

Response example：

```
{
    "accounts": [{
        "currency": "BTC",
        "available": 0.000025438348500000,
        "frozen": 0.18,
        "locked": 0
    }, {
        "currency": "ETH",
        "available": 0.321800000000000000,
        "frozen": 0.18,
        "locked": 0
    }]
  }
```

---

### System information 

#### 1. Get server timestamp

Request：

```
GET /v1/market/timestamp
```

Response：

Data fields：

| Field name | Type | Must | Description |
| ---------- | ---- | ---- | ----------- |
| timestamp  | Long | YES  | 时间戳      |

Response example：

```
{
    "timestamp": 1546418387188
}

```

---

### Error information

#### 1. Get all the error codes

Request：

```
GET /v1/market/errorCodes

```

Response：

Data fields：

| Field name                 | Type   | Must | Description                    |
| -------------------------- | ------ | ---- | ------------------------------ |
| ACCOUNT_FREEZE_FAILED      | String | YES  | Account freeze failed          |
| ACCOUNT_UNFREEZE_FAILED    | String | YES  | Account unfreeze failed        |
| ADDRESS_CHECK_FAILED       | String | YES  | Address checked failed         |
| ADDRESS_INVALID            | String | YES  | Invalid address                |
| ADDRESS_MAXIMUM            | String | YES  | Address exceeds maximum number |
| ADDRESS_NOT_ALLOWED        | String | YES  | Address is not allowed         |
| AUTH_APIKEY_DISABLED       | String | YES  | Certified API KEY is disabled  |
| AUTH_APIKEY_INVALID        | String | YES  | Certified API KEY is invalid   |
| AUTH_AUTHORIZATION_EXPIRED | String | YES  | Authorization header expired   |
| AUTH_AUTHORIZATION_INVALID | String | YES  | Authorization header invalid   |
| AUTH_GA_INVALID            | String | YES  | Ga is invalid                  |
| AUTH_IP_FORBIDDEN          | String | YES  | IP is forbidden                |
| AUTH_SIGNATURE_INVALID     | String | YES  | Signature invalid              |
| AUTH_SIGNIN_FAILED         | String | YES  | Sign in failed                 |
| AUTH_SIGNIN_REQUIRED       | String | YES  | Request sign in first          |
| AUTH_USER_FORBIDDEN        | String | YES  | User is forbidden              |
| AUTH_USER_NOT_ACTIVE       | String | YES  | User not activated             |
| DECRYPT_FAILED             | String | YES  | Decrypt failed                 |
| DEPOSIT_CANCEL             | String | YES  | Deposit canceled               |
| DEPOSIT_FAILED             | String | YES  | Deposit failed                 |
| ENCRYPT_FAILED             | String | YES  | Encrypt failed                 |
| HEADER_INVALID             | String | YES  | Header invalid                 |
| INTERNAL_SERVER_ERROR      | String | YES  | Internal server error          |
| OPERATION_FAILED           | String | YES  | Operation failed               |
| ORDER_CANNOT_CANCEL        | String | YES  | Order cannot cancel            |
| ORDER_NOT_FOUND            | String | YES  | Order not found                |
| PARAMETER_INVALID          | String | YES  | Parameter invalid              |
| REQUEST_BODY_TOO_LARGE     | String | YES  | Request body too large         |
| RETRY_LATER                | String | YES  | Retry later                    |
| SYSTEM_MAINTAIN            | String | YES  | System maintain                |
| USER_CANNOT_SIGNIN         | String | YES  | User cannot sign in            |
| USER_CANNOT_TRADE          | String | YES  | User cannot trade              |
| USER_CANNOT_WITHDRAW       | String | YES  | User cannot withdraw           |
| USER_EMAIL_EXIST           | String | YES  | User's email has been exist    |
| USER_NOT_FOUND             | String | YES  | User not found                 |
| WITHDRAW_DISABLED          | String | YES  | Withdraw disabled              |
| WITHDRAW_INVALID_STATUS    | String | YES  | Withdraw status invalid        |

Response example：

```
{
    "ACCOUNT_FREEZE_FAILED": "Account freeze failed.",
    "ACCOUNT_UNFREEZE_FAILED": "Account unfreeze failed.",
    "ADDRESS_CHECK_FAILED": "Address failed to check.",
    "ADDRESS_INVALID": "Invalid address.",
    "ADDRESS_MAXIMUM": "Cannot add more address.",
    "ADDRESS_NOT_ALLOWED": "Address is not allowed.",
    "AUTH_APIKEY_DISABLED": "API key is disabled.",
    "AUTH_APIKEY_INVALID": "Authenticate error: API key is invalid.",
    "AUTH_AUTHORIZATION_EXPIRED": "Authorization header is expired.",
    "AUTH_AUTHORIZATION_INVALID": "Authorization header is invalid.",
    "AUTH_GA_INVALID": "GA code is invalid.",
    "AUTH_IP_FORBIDDEN": "IP is forbidden.",
    "AUTH_SIGNATURE_INVALID": "API signature is invalid.",
    "AUTH_SIGNIN_FAILED": "Signin failed.",
    "AUTH_SIGNIN_REQUIRED": "Need signin first.",
    "AUTH_USER_FORBIDDEN": "User is forbidden to access the resource.",
    "AUTH_USER_NOT_ACTIVE": "User not active.",
    "DECRYPT_FAILED": "The decryption was failed.",
    "DEPOSIT_CANCEL": "The deposit was cancelled because blockchain forks.",
    "DEPOSIT_FAILED": "The deposit cannot be done because errors.",
    "ENCRYPT_FAILED": "The encryption was failed.",
    "HEADER_INVALID": "The request header is invalid.",
    "INTERNAL_SERVER_ERROR": "Internal server error.",
    "OPERATION_FAILED": "The requested operation cannot be done.",
    "ORDER_CANNOT_CANCEL": "The specific order cannot be cancelled.",
    "ORDER_NOT_FOUND": "The specific order not found.",
    "PARAMETER_INVALID": "The request parameter is invalid.",
    "REQUEST_BODY_TOO_LARGE": "The request body is too large.",
    "RETRY_LATER": "This operation cannot be done but can retry later.",
    "SYSTEM_MAINTAIN": "System maintain.",
    "USER_CANNOT_SIGNIN": "User cannot signin.",
    "USER_CANNOT_TRADE": "User cannot trade.",
    "USER_CANNOT_WITHDRAW": "User cannot withdraw.",
    "USER_EMAIL_EXIST": "User email already exist.",
    "USER_NOT_FOUND": "User not found.",
    "WITHDRAW_DISABLED": "Withdraw is disabled.",
    "WITHDRAW_INVALID_STATUS": "Invalid withdraw status."
}
```


#### 2. Error example

Request：

```
GET /v1/market/error
```

Response：

Data fields：

| Field name | Type   | Must | Description |
| ---------- | ------ | ---- | ----------- |
| error      | String | YES  |             |
| data       | String | YES  |             |
| message    | String | YES  |             |

Response example：

```
{
    "error": "INTERNAL_SERVER_ERROR",
    "data": "testField",
    "message": "Test error message"
}

```

---

### Trading - need signature

The trading APIs are used to create new orders, fetch exist orders, cancel orders.

A valid API signature is required when invoke order APIs, otherwise an error returned.

#### 1. Create a new order

Request：

```
POST /v1/trade/orders
```

Parameters：

| Parameter         | Type   | Must     | Description                                                  |
| ----------------- | ------ | -------- | ------------------------------------------------------------ |
| type              | String | YES      | Order type：`BUY_LIMIT, SELL_LIMIT, BUY_MARKET, SELL_MARKET` |
| source            | String | OPTIONAL | Order source："WEB", "API", "APP"                            |
| symbol            | String | YES      | Symbol                                                       |
| price             | Float  | YES      | This field refer to price of the asset for BUY_LIMIT or SELL_LIMIT. but refer to total spend money for BUY_MARKET. |
| amount            | Float  | YES      | REQUIRED for BUY_LIMIT, SELL_LIMIT and SELL_MARKET. This field refer to amount of the asset to buy or sell. |
| triggerOn         | Float  | OPTIONAL | This order will be executed when market price reaches        |
| fillOrKill        | Bool   | OPTIONAL | A "Fill or Kill" (FOK) order is a limit order that must be filled immediately  in its entirety or it is cancelled (killed). The purpose of a fill-or-kill order is to ensure that a position is entered instantly and at a specific price. Default to false. |
| immediateOrCancel | Bool   | OPTIONAL | An immediate or cancel order (IOC) is an order that must be executed immediately, and any portion of the order that cannot be immediately filled is cancelled (only for limit orders). Default to false. |
| postOnly          | Bool   | OPTIONAL | "Post Only" limit orders are orders that allow you to be sure to always be maker. When placed, a "Post Only" limit order is either inserted into the order book or cancelled (only for limit orders). Default to false. |
| hidden            | Bool   | OPTIONAL | This field allows you to place an order into the book but not have it displayed to other traders. Price/time priority is the same as a displayed order (only for limit orders). Default to false. |
| trailingStop      | Bool   | OPTIONAL | A trailing stop order provides flexibility over a stop order by executing once the market goes against you by a defined price, called the price distance. When margin trading, a trailing stop sell order can be used to protect profit. If trailing is true, the property of "triggerOn" is trailing distance NOT the trigger price. Default to false. |

Request data：

```
{
  "type": "BUY_LIMIT",
  "source": "API",
  "symbol": "BTC_USDT",
  "price": 3359.1,
  "amount": 1.52,
  "triggerOn": 3700,
  "fillOrKill": false,
  "immediateOrCancel": false,
  "postOnly": false,
  "hidden": false,
  "trailingStop": false
}
```

Response：

Data fields：

| Field name    | Type   | Must | Description                                                  |
| ------------- | ------ | ---- | ------------------------------------------------------------ |
| createdAt     | Long   | Y    | Create time                                                  |
| updatedAt     | Long   | Y    | Update time                                                  |
| seqId         | Int    | Y    |                                                              |
| previousSeqId | Int    | Y    |                                                              |
| refOrderId    | Int    | Y    |                                                              |
| refSeqId      | Int    | Y    |                                                              |
| userId        | Int    | Y    | User ID                                                      |
| source        | String | Y    | Order source                                                 |
| symbol        | String | Y    |                                                              |
| sequenceIndex | Int    | Y    |                                                              |
| type          | String | Y    | Order type: valid constants: BUY_LIMIT, SELL_LIMIT, BUY_MARKET, SELL_MARKET |
| price         | Float  | Y    | Price                                                        |
| amount        | Float  | Y    | Amount                                                       |
| filledAmount  | Float  | Y    | Filled amount (already done)                                 |
| fee           | Float  | Y    | Order fee                                                    |
| triggerOn     | Float  | Y    | 0 = not a stop order                                         |
| makerFeeRate  | Long   | Y    | Fee rate for maker                                           |
| takerFeeRate  | Long   | Y    | Fee rate for taker                                           |
| chargeQuote   | Bool   | Y    | Charge fee as quote currency when buy                        |
| features      | Int    | Y    | Order features combined with:：`FILL_OR_KILL = 0x0001；POST_ONLY = 0x0010；HIDDEN = 0x0100；IMMEDIATE_OR_CANCEL = 0x1000；TRAILING_STOP = 0x10000000000；` |
| status        | String | Y    | order status: `SUBMITTED`: just submitted, waiting for sequencing;`SEQUENCED`: waiting for processing or processing now;`FULLY_FILLED`: completely filled;`FULLY_CANCELLED`: completely cancelled (nothing bought or sold);`PARTIAL_CANCELLED`: partial cancelled (not fully bought or sold) |
| id            | Int    | Y    | Order ID                                                     |
| feeCurrency   | String | Y    | Order fee currency                                           |

Response example：

```
{
    "createdAt": 1546511983069,
    "updatedAt": 1546511983069,
    "seqId": 0,
    "previousSeqId": 0,
    "refOrderId": 0,
    "refSeqId": 0,
    "userId": 10063,
    "source": "",
    "symbol": "BTC_USDT",
    "sequenceIndex": 1,
    "type": "BUY_LIMIT",
    "price": 1,
    "amount": 1,
    "filledAmount": 0,
    "fee": 0,
    "triggerOn": 0,
    "makerFeeRate": -0.000500000000000000,
    "takerFeeRate": 0.001000000000000000,
    "chargeQuote": true,
    "features": 0,
    "status": "SUBMITTED",
    "id": 1875418,
    "feeCurrency": "USDT"
}
```

#### 2. Get exist orders

Request：

```
GET /v1/trade/orders/<the-order-id>
```

Parameters：

| Parameter      | Type   | Must | Description |
| -------------- | ------ | ---- | ----------- |
| <the-order-id> | String | YES  | 订单 ID     |

Response：

| Field name   | Type   | Must | Description                                                  |
| ------------ | ------ | ---- | ------------------------------------------------------------ |
| id           | String | YES  | Order ID                                                     |
| userId       | Int    | YES  | User ID                                                      |
| symbol       | String | YES  | Symbol                                                       |
| status       | String | YES  | `order status: `SUBMITTED`: just submitted, waiting for sequencing;`SEQUENCED`: waiting for processing or processing now;`FULLY_FILLED`: completely filled;`FULLY_CANCELLED`: completely cancelled (nothing bought or sold);`PARTIAL_CANCELLED`: partial cancelled (not fully bought or sold) |
| type         | String | YES  | Order type: valid constants: BUY_LIMIT, SELL_LIMIT, BUY_MARKET, SELL_MARKET |
| price        | Float  | YES  | 价格                                                         |
| amount       | Float  | YES  | Amount                                                       |
| filledAmount | Long   | YES  | Filled amount (already done)                                 |
| features     | Int    | YES  | Order features combined with:：`FILL_OR_KILL = 0x0001；POST_ONLY = 0x0010；HIDDEN = 0x0100；IMMEDIATE_OR_CANCEL = 0x1000；TRAILING_STOP = 0x10000000000；` |
| feeCurrency  | String | YES  | Order fee currency                                           |
| fee          | Long   | YES  | Order fee                                                    |
| triggerOn    | Long   | YES  | 0 = not a stop order                                         |
| makerFeeRate | Long   | YES  | Fee rate for maker                                           |
| takerFeeRate | Long   | YES  | Fee rate for taker                                           |
| chargeQuote  | Bool   | YES  | Charge fee as quote currency when buy                        |
| source       | String | YES  | Order source                                                 |
| createdAt    | Long   | YES  | Create time                                                  |
| updatedAt    | Long   | YES  | Update time                                                  |

Response example：

```
{
    "createdAt": 1546511983069,
    "updatedAt": 1546511983076,
    "seqId": 1775419,
    "previousSeqId": 1775418,
    "refOrderId": 0,
    "refSeqId": 0,
    "userId": 10063,
    "source": "",
    "symbol": "BTC_USDT",
    "sequenceIndex": 1,
    "type": "BUY_LIMIT",
    "price": 1.000000000000000000,
    "amount": 1.000000000000000000,
    "filledAmount": 0E-18,
    "fee": 0E-18,
    "triggerOn": 0E-18,
    "makerFeeRate": -0.000500000000000000,
    "takerFeeRate": 0.001000000000000000,
    "chargeQuote": true,
    "features": 0,
    "status": "SEQUENCED",
    "id": 1875418,
    "feeCurrency": "USDT"
}
```

#### 3. Get exist orders

Request：

```
GET /v1/trade/orders<?><symbol=symbol><&offsetId=order-id><&limit=limit>
```

Parameters：

| Parameter | Type   | Must | Description                                                  |
| --------- | ------ | ---- | ------------------------------------------------------------ |
| symbol    | String | NO   | Optional, return orders only with specified symbol. Default to empty string. |
| offsetId  | String | NO   | Optional, return orders starts with specified order id. Default to 0. |
| limit     | String | NO   | Optional, return maximum number of orders. Default to 100.   |

Example:

```
Get latest 5 orders for BTC_USDT:
GET /v1/trade/orders?symbol=BTC_USDT&limit=5
```

Response：

| Field name   | Subdata | Type   | Must | Description                                                  |
| ------------ | ------- | ------ | ---- | ------------------------------------------------------------ |
| hasMore      |         | Bool   | YES  | Is there more data                                           |
| nextOffsetId |         | Int    | YES  | Offset id of next page，如 `GET /v1/trade/orders?symbol=BTC_USDT&offsetId=909971&limit=5` |
| orders       | Orders  | String | YES  | Orders array                                                 |

```
{
  "hasMore": true, 
  "nextOffsetId": 909971, 
  "orders": [
    {
        "createdAt": 1546511983069,
        "updatedAt": 1546511983076,
        "seqId": 1775419,
        "previousSeqId": 1775418,
        "refOrderId": 0,
        "refSeqId": 0,
        "userId": 10063,
        "source": "",
        "symbol": "BTC_USDT",
        "sequenceIndex": 1,
        "type": "BUY_LIMIT",
        "price": 1.000000000000000000,
        "amount": 1.000000000000000000,
        "filledAmount": 0E-18,
        "fee": 0E-18,
        "triggerOn": 0E-18,
        "makerFeeRate": -0.000500000000000000,
        "takerFeeRate": 0.001000000000000000,
        "chargeQuote": true,
        "features": 0,
        "status": "SEQUENCED",
        "id": 1875418,
        "feeCurrency": "USDT"
    }, {
        "createdAt": 1546422391673,
        "updatedAt": 1546422391688,
        "seqId": 1473289,
        "previousSeqId": 1473288,
        "refOrderId": 0,
        "refSeqId": 0,
        "userId": 10063,
        "source": "",
        "symbol": "BTC_USDT",
        "sequenceIndex": 1,
        "type": "SELL_LIMIT",
        "price": 3758.000000000000000000,
        "amount": 0.100000000000000000,
        "filledAmount": 0.100000000000000000,
        "fee": 0.375800000000000000,
        "triggerOn": 0E-18,
        "makerFeeRate": -0.000500000000000000,
        "takerFeeRate": 0.001000000000000000,
        "chargeQuote": true,
        "features": 0,
        "status": "FULLY_FILLED",
        "id": 1573288,
        "feeCurrency": "USDT"
    }
  ]
}
```

#### 4. Get exist active orders

To get exist active orders (orders that are not been fully-filled or cancelled, in other words, orders are still in order book) is the same as get exist orders, but the url is `/v1/trade/orders/active`.

#### 5. Cancel order

Request：

```
POST /v1/trade/orders/<order-id>/cancel
```

Parameters：

| Parameter  | Type | Must | Description                                    |
| ---------- | ---- | ---- | ---------------------------------------------- |
| <order-id> | Int  | YES  | the specified order id that will be cancelled. |

Response：

Response example，as `GET /v1/trade/orders/<the-order-id>`：

```
{
    "createdAt": 1546513666060,
    "updatedAt": 1546513666060,
    "seqId": 0,
    "previousSeqId": 0,
    "refOrderId": 1875418,
    "refSeqId": 1775419,
    "userId": 10063,
    "source": "CANCEL",
    "symbol": "BTC_USDT",
    "sequenceIndex": 1,
    "type": "CANCEL_BUY",
    "price": 1.000000000000000000,
    "amount": 0,
    "filledAmount": 0,
    "fee": 0,
    "triggerOn": 0E-18,
    "makerFeeRate": 0,
    "takerFeeRate": 0,
    "chargeQuote": true,
    "features": 0,
    "status": "SUBMITTED",
    "id": 1875420,
    "feeCurrency": "USDT"
}
```

Note：

- the cancel result depends on order types and exist order status.
- You cannot cancel a market order, or an order with status `FULLY_FILLED`.
- You cannot re-cancel an order with status `FULLY_CANCELLED` or `PARTIAL_CANCELLED`.

You will get error response if cancel failed:：

```
{
  "error": "ORDER_CANNOT_CANCEL",
  "message": "Cannot cancel order with status: FULLY_FILLED"
}
```

---

## API Signature

### Processing steps

1. Organize request data format
2. Signature

#### 1.1 Data Format

Data contains：

- Request method：`GET` or `POST`
- Request amain: e.g. `api.bitzon.com`
- Request path：a URI starting with `/`, for example: `/v1/trade/orders`
- Request parameters：The parameter in the form of `key1=value2&key2=value2`, for example: `id=123456&sort=DESC&from=2017-09-10`
- Request Header：e.g. `Accept: */*`
- Request  `Body`：JSON string in binary representation, valid only for POST

Data Format：

| n addition to the request body, there are newline characters "\n" after the rest | Description                                                  |
| ------------------------------------------------------------ | ------------------------------------------------------------ |
| GET\n                                                        | Request method. All uppercase                                |
| api.bitzon.com\n                                             | Request domain. All lowercase                                |
| /v1/trade/orders\n                                           | Request path，Strict case                                    |
| from=2017-09-10&id=123456&sort=DESC\n                        | Request parameters，sorted alphabetically，Strict case，if no parameters，just「 \n」 |
| API-KEY: xyz123456\n                                         | The request header at the beginning of "API-" is related to the signature. HEADER is all uppercase, spaces after the colon, and VALUE is strictly case sensitive. Key value. |
| API-SIGNATURE-METHOD: HmacSHA256\n                           | The request header at the beginning of "API-" is related to the signature. HEADER is all uppercase, spaces after the colon, and VALUE is strictly case sensitive. Encryption Algorithm |
| API-SIGNATURE-VERSION: 1\n                                   | The request header at the beginning of "API-" is related to the signature. HEADER is all uppercase, spaces after the colon, and VALUE is strictly case sensitive. version number |
| API-TIMESTAMP: 12300000000\n                                 | The request header at the beginning of "API-" is related to the signature. HEADER is all uppercase, spaces after the colon, and VALUE is strictly case sensitive. Timestamp |
| API-UNIQUE-ID: uni-123-abc-xyz\n                             | The request header at the beginning of "API-" is related to the signature. HEADER is all uppercase, spaces after the colon, and VALUE is strictly case sensitive. ID |
| <json body data>                                             | If it is a `POST` request and contains `BODY`, the `BODY` data format is a json string. |

Request example：

```
GET\n
api.bitzon.com\n
/v1/trade/orders\n
from=2017-09-10&id=123456&sort=DESC\n
API-KEY: xyz123456\n
API-SIGNATURE-METHOD: HmacSHA256\n
API-SIGNATURE-VERSION: 1\n
API-TIMESTAMP: 12300000000\n
API-UNIQUE-ID: uni-123-abc-xyz\n
<json body data>
```

#### 1.2 signature

 Encode the 1.1-step request string in UTF-8 to get a binary byte array, then use the API Secret to calculate the Signature:

```
signature = HmacSHA256(payload.encode("UTF-8"), "my-api-secret")
```

Add the resulting signature to the Header as a hexadecimal lowercase string:

```
API-Signature: a1b2c3ff001234500900dd01ff
```

### Description

1. The parameter uses the original string to calculate the signature, for example `a=1/5`, don't use `a=1%2F5`
2. `Header` uses all uppercase when calculating signatures, and does not require it when sending
3. Only Headers starting with 'API-' are included and calculated for signatures (except `API-Signature`, because `API-Signature` can be calculated and appended to the request)
4. `API-Signature-Method` must be `HmacSHA256`
5. `API-Signature-Version` must be `1`
6. `API-Timestamp`  is the current timestamp, in milliseconds. It does not support decimals, and the error cannot exceed 1 minute.
7. `API-Unique-ID` Optional, if provided, the client needs to provide a unique string identifier, recommend `UUID` or auto-sequence
8. A request with a `Body` needs to be serialized as a `JSON` string and then the `Body` is included in the computed signature. Don't use serialization twice for an object, because implementations in some languages may cause two serialized `JSON`s to be different, for example, `{"a":true, "b":1}`, and `{"b":1, "a": true}`, the two `JSON` contents are the same but the strings are different, which will cause the verification signature to fail.


---

## WebSocket

WebSocket API is used for receiving market events.

## How to connect to WebSocket

`URL: wss://wss.bitzon.com/v1/market/notification`

```
var ws = new WebSocket('wss://wss.bitzon.com/v1/market/notification');
```

After connected successfully, all messages are sent / received as JSON string.

A subscription message need to be sent immediately after connected:

```
var msg = JSON.stringify({"action": "subscribe", "symbol": "BTC_USDT"});
ws.send(msg);

```

All market events with `BTC_USDT` will be sent by this `WebSocket` later.

- Note，message MUST be JSON-string (not object).

To draw market chart, the first step is request history bar data by REST API.

And later changed bars are pushed by WebSocket.

## How to handle WebSocket message

All messages received from `WebSocket` are JSON-string. Here is example  `JavaScript` code:

```
ws.onmessage = function (event) {
    // step 1: parse as json:
    var data = JSON.parse(event.data);
    // step 2: all events are arrays contains 2 elements:
    // ['topic', message]
    if (Array.isArray(data) && data.length==2) {
        var
            topic = data[0],
            message = data[1];
        if (topic === 'topic_snapshot') {
            // process snapshot:
        } else if (topic === 'topic_prices') {
            // process prices:
        } else if (topic === 'topic_tick') {
            // process tick:
        } else if (topic === 'topic_bar') {
            // process bar:
        } else if (topic === 'topic_order') {
            // process order:
        }
    }
};

```

### Topics

1. `topic_snapshot`: the order book snapshot data;
2. `topic_tick`: the tick data.
3. `topic_bar`:  the bar data.
4. `topic_order`: the order execution data.

## Bar data format

All bar data is represented as array that contains 6 elements which are:

[timestamp in millis, open price, high price, low price, close price, volume]

Example:

```
[1544602783000, 3302.1, 3419.9, 3029.6, 3298, 9.34]
```