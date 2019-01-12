### api-sdk
声明：本SDK由社群热心用户提供，供接入机构/个人调用Bitzon API参考之用，代码可自由使用，但未经全面测试，Bitzon不承担任何连带责任。

使用本工程中代码造成的任何后果完全自负。

### Usage
- 注册并登录Bitzon, 申请api key和secret


# APIS

[TOC]

## 接口列表

`symbol` 规则：基础币种 + 计价币种，`BTC/USDT` 为 `BTC_USDT`。  

| 接口数据类型 | 请求方法                                                     | 描述                          | 验签 |
| ------------ | ------------------------------------------------------------ | ----------------------------- | ---- |
| 市场行情     | `GET /v1/market/prices`                                      | 获取所有交易对当前市价        | N    |
| 市场行情     | `GET /v1/market/fex`                                         | 获取美元对其他国家法币汇率    | N    |
| 市场行情     | `GET /v1/market/bars/<symbol>/<type>`                        | 获取 K 线数据                 | N    |
| 交易品种信息 | `GET /v1/market/feeRates`                                    | 获取手续费率                  | N    |
| 交易品种信息 | `GET /v1/market/trades`                                      | 获取所有币种 / 交易对配置信息 | N    |
| 系统信息     | `GET /v1/market/timestamp`                                   | 获取服务器时间戳              | N    |
| 错误接口信息 | `GET /v1/market/errorCodes`                                  | 获取所有的错误码              | N    |
| 错误接口信息 | `GET /v1/market/error`                                       | 错误响应示例                  | N    |
| 交易         | `POST /v1/trade/orders`                                      | 创建订单                      | Y    |
| 交易         | `GET /v1/trade/orders/<the-order-id>`                        | 查询指定订单                  | Y    |
| 交易         | `GET /v1/trade/orders<?symbol=symbol><&offsetId=order-id><&limit=limit>` | 查询所有订单                  | Y    |
| 交易         | `GET /v1/trade/orders/active`                                | 查询未完成订单                | Y    |
| 交易         | `POST /v1/trade/orders/<order-id>/cancel`                    | 撤销订单                      | Y    |

---

### 市场行情

#### 1. 获取所有交易对当前市价

请求：

```
GET /v1/market/prices
```

响应：

数据字段：

| 交易对名称 | 子数据                      | 类型   | 必须 | 描述             |
| ---------- | --------------------------- | ------ | ---- | ---------------- |
| AE_BTC     | 交易对当前市场数据数组 data | String | YES  | 交易对标识符     |
|            | ——data[0]                   | Long   | NO   | 开盘价时间戳     |
|            | ——data[1]                   | Float  | NO   | 开盘价           |
|            | ——data[2]                   | Float  | NO   | 最高价           |
|            | ——data[3]                   | Float  | NO   | 最低价           |
|            | ——data[4]                   | Float  | NO   | 收盘价（当前价） |
|            | ——data[5]                   | Float  | NO   | 成交量           |

数据示例：

```
{
	// 交易对标识符:[时间戳 timestamp in millis, 开盘价 open price, 最高价 high price, 最低价 low price, 收盘价(当前价) close price, 交易量 volume]
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

#### 2. 获取K线数据

请求：

```
GET /v1/market/bars/<symbol>/<type>
```

参数：

| 位置     | 类型   | 必须 | 描述                                                         |
| -------- | ------ | ---- | ------------------------------------------------------------ |
| <symbol> | String | YES  | 交易对标识符                                                 |
| <type>   | String | YES  | K 线单位。如 "`K_1_SEC`", "`K_1_MIN`", "`K_1_HOUR`", "`K_1_DAY`". |

响应：

数据字段：

| 字段名称 | 子数据                       | 类型   | 必须 | 描述             |
| -------- | ---------------------------- | ------ | ---- | ---------------- |
| bars     | 交易对当前 K 线数据数组 data | String | YES  | 交易对标识符     |
|          | ——data[0]                    | Long   | NO   | 开盘价时间戳     |
|          | ——data[1]                    | Float  | NO   | 开盘价           |
|          | ——data[2]                    | Float  | NO   | 最高价           |
|          | ——data[3]                    | Float  | NO   | 最低价           |
|          | ——data[4]                    | Float  | NO   | 收盘价（当前价） |
|          | ——data[5]                    | Float  | NO   | 成交量           |

响应示例：

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

##### K 线数据格式

包含六个元素的数组：

```
[时间戳 timestamp in millis, 开盘价 open price, 最高价 high price, 最低价 low price, 收盘价 close price, 交易量 volume]

如 [1544602783000, 3302.1, 3419.9, 3029.6, 3298, 9.34]
```

#### 3.  获取美元对其他国家法币汇率

请求：

```
GET /v1/market/fex
```

响应：

数据字段：

| 字段名称  | 子数据       | 类型   | 必须 | 描述         |
| --------- | ------------ | ------ | ---- | ------------ |
| from      |              | String | YES  | 要转换的币种 |
| tos       | 转换目标数组 | String | YES  | 转换目标数组 |
| exchanges | 汇率数组     | String | YES  | 汇率数组     |

响应示例：

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

#### 4. 获取深度数据

请求：

```
GET URL /v1/market/depth/<symbol>
```

参数：

| 位置   | 类型   | 必须 | 描述         |
| ------ | ------ | ---- | ------------ |
| symbol | String | YES  | 交易对标识符 |

响应：

数据字段：

| 字段信息   | 字段说明     |
| ---------- | ------------ |
| symbol     | 交易对标识符 |
| timestamp  | 时间戳       |
| price      | 价格         |
| buyOrders  | 买盘数据     |
| sellOrders | 卖盘数据     |

 响应示例：

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

### 交易品种信息

#### 1. 获取所有币种/交易对信息

请求：

```
GET /v1/market/trades
```

响应：

数据字段：

| 字段名称   | 子数据            | 类型   | 必须 | 描述               |
| ---------- | ----------------- | ------ | ---- | ------------------ |
| currencies | 币种状态          | String | YES  | 币种状态列表       |
|            | ——name            | String | YES  | 币种名称           |
|            | ——depositEnabled  | Bool   | YES  | 充币状态           |
|            | ——withdrawEnabled | Bool   | YES  | 提币状态           |
|            | ——meta            | String | NO   |                    |
| symbols    | 交易对设置        | String | NO   | 所有的交易对的设置 |
|            | ——name            | String | YES  | 交易对标识符       |
|            | ——baseName        | String | YES  | 交易币种           |
|            | ——baseScale       | Int    | YES  | 交易币种展示精度   |
|            | ——baseMinimum     | Float  | YES  | 交易币种最小成交量 |
|            | ——quoteName       | String | YES  | 计价币种           |
|            | ——quoteScale      | Int    | YES  | 计价币种展示精度   |
|            | ——quoteMinimum    | Float  | YES  | 计价币种最小成交量 |
|            | ——startTime       | Long   | NO   |                    |
|            | ——endTime         | Long   | NO   |                    |
|            | ——meta            | String | NO   |                    |

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

#### 2. 获取手续费率

请求：

```
GET /v1/market/feeRates
```

响应：

数据字段：

| 字段名称          | 子数据                 | 类型   | 必须 | 描述                               |
| ----------------- | ---------------------- | ------ | ---- | ---------------------------------- |
| timestamp         |                        | String | YES  | 时间戳                             |
| alwaysChargeQuote |                        | Bool   | YES  | 买单收取手续费是否以计价币种为单位 |
| feeRates          | 交易对挂单吃单手续费率 | String | YES  | 交易对挂单吃单手续费率             |
|                   | —交易对名称            | String | NO   | 交易对名称                         |
|                   | —— takerFeeRate        | Float  | NO   | 吃单手续费率                       |
|                   | —— makerFeeRate        | Float  | NO   | 挂单手续费率                       |

响应示例：

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

### 用户信息 - 需签名

#### 1. 获取用户资产

请求：

```
GET URL /v1/user/accounts
```

响应：

字段数据：

| 字段信息  | 字段说明 |
| --------- | -------- |
| currency  | 币种     |
| available | 可用     |
| frozen    | 冻结     |
| locked    | 锁定     |

响应：

数据字段：

| 字段名称 | 子数据       | 类型   | 必须 | 描述         |
| -------- | ------------ | ------ | ---- | ------------ |
| accounts | 账户资产数组 | String | YES  | 账户资产数组 |
|          | —— currency  | String | YES  | 币种名称     |
|          | —— available | Long   | YES  | 可用         |
|          | —— frozen    | Long   | YES  | 冻结         |
|          | —— locked    | Long   | YES  | 锁定         |

响应示例：

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

### 系统信息

#### 1. 获取服务器时间戳

请求：

```
GET /v1/market/timestamp
```

响应：

数据字段：

| 字段名称  | 类型 | 必须 | 描述   |
| --------- | ---- | ---- | ------ |
| timestamp | Long | YES  | 时间戳 |

响应示例：

```
{
    "timestamp": 1546418387188
}
```

---

### 错误信息

#### 1. 获取所有的错误码

请求：

```
GET /v1/market/errorCodes
```

响应：

数据字段：

| 字段名称                   | 类型   | 必须 | 描述                      |
| -------------------------- | ------ | ---- | ------------------------- |
| ACCOUNT_FREEZE_FAILED      | String | YES  | 账户冻结失败              |
| ACCOUNT_UNFREEZE_FAILED    | String | YES  | 账户解除冻结失败          |
| ADDRESS_CHECK_FAILED       | String | YES  | 地址检测失败              |
| ADDRESS_INVALID            | String | YES  | 地址错误                  |
| ADDRESS_MAXIMUM            | String | YES  | 地址超过最大数量          |
| ADDRESS_NOT_ALLOWED        | String | YES  | 地址不被允许              |
| AUTH_APIKEY_DISABLED       | String | YES  | 认证的 API KEY 被禁止     |
| AUTH_APIKEY_INVALID        | String | YES  | 认证的 API KEY 非法       |
| AUTH_AUTHORIZATION_EXPIRED | String | YES  | Authorization header 过期 |
| AUTH_AUTHORIZATION_INVALID | String | YES  | Authorization header 错误 |
| AUTH_GA_INVALID            | String | YES  | 认证谷歌验证码错误        |
| AUTH_IP_FORBIDDEN          | String | YES  | IP 被禁止                 |
| AUTH_SIGNATURE_INVALID     | String | YES  | 签名错误                  |
| AUTH_SIGNIN_FAILED         | String | YES  | 登陆失败                  |
| AUTH_SIGNIN_REQUIRED       | String | YES  | 需要登陆                  |
| AUTH_USER_FORBIDDEN        | String | YES  | 用户被禁                  |
| AUTH_USER_NOT_ACTIVE       | String | YES  | 用户未激活                |
| DECRYPT_FAILED             | String | YES  | 解码失败                  |
| DEPOSIT_CANCEL             | String | YES  | 充币取消                  |
| DEPOSIT_FAILED             | String | YES  | 充币失败                  |
| ENCRYPT_FAILED             | String | YES  | 编码失败                  |
| HEADER_INVALID             | String | YES  | 请求头错误                |
| INTERNAL_SERVER_ERROR      | String | YES  | 内部服务错误              |
| OPERATION_FAILED           | String | YES  | 操作失败                  |
| ORDER_CANNOT_CANCEL        | String | YES  | 订单无法取消              |
| ORDER_NOT_FOUND            | String | YES  | 订单找不到                |
| PARAMETER_INVALID          | String | YES  | 参数错误                  |
| REQUEST_BODY_TOO_LARGE     | String | YES  | 请求体过大                |
| RETRY_LATER                | String | YES  | 请稍候重试                |
| SYSTEM_MAINTAIN            | String | YES  | 系统维护                  |
| USER_CANNOT_SIGNIN         | String | YES  | 用户无法登陆              |
| USER_CANNOT_TRADE          | String | YES  | 用户无法交易              |
| USER_CANNOT_WITHDRAW       | String | YES  | 用户无法提币              |
| USER_EMAIL_EXIST           | String | YES  | 用户邮箱已存在            |
| USER_NOT_FOUND             | String | YES  | 未找到用户                |
| WITHDRAW_DISABLED          | String | YES  | 提币禁止                  |
| WITHDRAW_INVALID_STATUS    | String | YES  | 提币状态错误              |

响应示例：

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

#### 2. 错误响应示例

请求：

```
GET /v1/market/error
```

响应：

字段数据：

| 字段名称 | 类型   | 必须 | 描述     |
| -------- | ------ | ---- | -------- |
| error    | String | YES  | 错误提示 |
| data     | String | YES  | 数据     |
| message  | String | YES  | 错误信息 |

响应数据：

```
{
    "error": "INTERNAL_SERVER_ERROR",
    "data": "testField",
    "message": "Test error message"
}
```

---

### 订单 - 需签名

订单接口用于创建 / 查询 / 撤销订单。除非返回了一个错误，任何订单接口都需要进行接口签名。

#### 1. 创建订单

请求：

```
POST /v1/trade/orders
```

参数：

| 字段名称          | 类型   | 必须 | 描述                                                         |
| ----------------- | ------ | ---- | ------------------------------------------------------------ |
| type              | String | YES  | 订单类型：`BUY_LIMIT, SELL_LIMIT, BUY_MARKET, SELL_MARKET`   |
| source            | String | 可选 | 下单来源："WEB", "API", "APP"                                |
| symbol            | String | YES  | 交易对标识符                                                 |
| price             | Float  | YES  | 订单类型为 `BUY_LIMIT / SELL_LIMIT / BUY_MARKET` 时必须，限价单时是价格，市价单时是要花费的总金额 |
| amount            | Float  | YES  | 订单类型为 `BUY_LIMIT / SELL_LIMIT / SELL_MARKET` 时必须。为交易数量。 |
| triggerOn         | Float  | 可选 | 当市价达到此价格时执行此订单                                 |
| fillOrKill        | Bool   | 可选 | 「Fill or Kill」（FOK）订单是一笔必须全部完成或者取消的限价订单。它的目的是确保立即以特定价格输入头寸。 默认为false |
| immediateOrCancel | Bool   | 可选 | 「immediate or cancel」订单是一笔立即执行的限价订单，如果这笔订单有部分未成交，那这部分会被取消。默认为 false |
| postOnly          | Bool   | 可选 | 「Post Only」限价订单使得用户确保订单是挂单。它只能被加入订单表或者取消（仅限限价订单）。默认是 false |
| hidden            | Bool   | 可选 | 这个字段允许发布隐藏显示的订单，它不会被其他交易者看到。价格和时间的优先级与显示的订单相同（仅限限价单）。默认是 false |
| trailingStop      | Bool   | 可选 | 止损订单通过价格差，在市场达到用户预先设置的价格时停止订单。保证金交易时，可以使用止损卖单来保证利润。如果设置了止损，`triggerOn` 字段被视为止损价格差而不是触发价格。默认为 false |

请求数据：

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

响应：

字段数据：

| 字段名称      | 类型   | 必须 | 描述                                                         |
| ------------- | ------ | ---- | ------------------------------------------------------------ |
| createdAt     | Long   | Y    | 创建时间                                                     |
| updatedAt     | Long   | Y    | 更新时间                                                     |
| seqId         | Int    | Y    |                                                              |
| previousSeqId | Int    | Y    |                                                              |
| refOrderId    | Int    | Y    |                                                              |
| refSeqId      | Int    | Y    |                                                              |
| userId        | Int    | Y    | 用户 ID                                                      |
| source        | String | Y    | 下单来源                                                     |
| symbol        | String | Y    | 交易对                                                       |
| sequenceIndex | Int    | Y    |                                                              |
| type          | String | Y    | 订单类型                                                     |
| price         | Float  | Y    | 价格                                                         |
| amount        | Float  | Y    | 数量                                                         |
| filledAmount  | Float  | Y    | 成交数量                                                     |
| fee           | Float  | Y    | 手续费                                                       |
| triggerOn     | Float  | Y    | 0 代表未停止的订单                                           |
| makerFeeRate  | Long   | Y    | 挂单手续费率                                                 |
| takerFeeRate  | Long   | Y    | 吃单手续费率                                                 |
| chargeQuote   | Bool   | Y    | 买时是否预先扣手续费，即手续费以计价币种为单位               |
| features      | Int    | Y    | 订单特性，组成：`FILL_OR_KILL = 0x0001；POST_ONLY = 0x0010；HIDDEN = 0x0100；IMMEDIATE_OR_CANCEL = 0x1000；TRAILING_STOP = 0x10000000000；` |
| status        | String | Y    | 订单状态                                                     |
| id            | Int    | Y    | 订单 ID                                                      |
| feeCurrency   | String | Y    | 手续费收取币种                                               |

响应数据：

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

#### 2. 查询指定订单

请求：

```
GET /v1/trade/orders/<the-order-id>
```

参数：

| 位置           | 类型   | 必须 | 描述    |
| -------------- | ------ | ---- | ------- |
| <the-order-id> | String | YES  | 订单 ID |

响应：

| 字段名称     | 类型   | 必须 | 描述                                                         |
| ------------ | ------ | ---- | ------------------------------------------------------------ |
| id           | String | YES  | 订单 ID                                                      |
| userId       | Int    | YES  | 用户 ID                                                      |
| symbol       | String | YES  | 交易对                                                       |
| status       | String | YES  | `SUBMITTED`: 刚提交；`SEQUENCED`: 等待处理或者正在处理；`FULLY_FILLED`: 完全成交；`FULLY_CANCELLED`: 完全撤销；`PARTIAL_CANCELLED`:部分撤销 |
| type         | String | YES  | 订单类型                                                     |
| price        | Float  | YES  | 价格                                                         |
| amount       | Float  | YES  | 数量                                                         |
| filledAmount | Long   | YES  | 成交数量                                                     |
| features     | Int    | YES  | 订单特性，组成：`FILL_OR_KILL = 0x0001；POST_ONLY = 0x0010；HIDDEN = 0x0100；IMMEDIATE_OR_CANCEL = 0x1000；TRAILING_STOP = 0x10000000000` |
| feeCurrency  | String | YES  | 交易收取的手续费币种                                         |
| fee          | Long   | YES  | 手续费                                                       |
| triggerOn    | Long   | YES  | 0 代表未停止的订单                                           |
| makerFeeRate | Long   | YES  | 挂单手续费率                                                 |
| takerFeeRate | Long   | YES  | 吃单手续费率                                                 |
| chargeQuote  | Bool   | YES  | 买时是否预先扣手续费，即手续费以计价币种为单位               |
| source       | String | YES  | 订单来源                                                     |
| createdAt    | Long   | YES  | 订单请求事件                                                 |
| updatedAt    | Long   | YES  | 订单最后更新时间                                             |

响应数据示例：

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

#### 3. 查询所有订单

请求：

```
GET /v1/trade/orders<?><symbol=symbol><&offsetId=order-id><&limit=limit>
```

参数：

| 参数名称 | 类型   | 必须 | 描述                                                         |
| -------- | ------ | ---- | ------------------------------------------------------------ |
| symbol   | String | NO   | 交易对标识符。如果指定了该参数，则返回对应交易对的数据。默认为空。 |
| offsetId | String | NO   | 开始偏移的订单 ID。如果指定了该参数，返回从该 ID 开始的订单。默认为 0。 |
| limit    | String | NO   | 返回订单的最大量。如果指定了该参数，返回最多为该数值的订单量。默认为 100。 |

示例：

```
获取最新的 5 条 BTC_USDT 交易对的订单
GET /v1/trade/orders?symbol=BTC_USDT&limit=5
```

响应：

| 字段名称     | 子数据   | 类型   | 必须 | 描述                                                         |
| ------------ | -------- | ------ | ---- | ------------------------------------------------------------ |
| hasMore      |          | Bool   | YES  | 是否有更多数据                                               |
| nextOffsetId |          | Int    | YES  | 下一页订单数据的 ID 偏移量，如 `GET /v1/trade/orders?symbol=BTC_USDT&offsetId=909971&limit=5` |
| orders       | 订单数组 | String | YES  | 订单数组，同接口 `GET /v1/trade/orders/<the-order-id>`       |

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

#### 4. 查询未完成订单

请求接口为：`/v1/trade/orders/active`，其余同上。

#### 5. 撤销订单

请求：

```
POST /v1/trade/orders/<order-id>/cancel
```

参数：

| 位置       | 类型 | 必须 | 描述            |
| ---------- | ---- | ---- | --------------- |
| <order-id> | Int  | YES  | 要撤销的订单 ID |

响应：

返回数据，同接口 `GET /v1/trade/orders/<the-order-id>`：

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

注意：

- 撤单结果决定于订单类型和当前订单状态。
- 无法撤销市价单，或者已经完成的订单。
- 无法撤销「已经撤销」或者「部分撤销」的订单。

错误消息：

```
{
  "error": "ORDER_CANNOT_CANCEL",
  "message": "Cannot cancel order with status: FULLY_FILLED"
}
```

---

## API 签名算法

### 处理步骤

1. 整理请求数据格式
2. 签名

#### 1.1 数据格式

数据包含：

- 方法名称：`GET` 或 `POST`
- 请求域名：例如`api.bitzon.com`
- 请求路径：以 `/` 开头的 `URI`，例如：`/v1/trade/orders`
- 请求参数：以`key1=value2&key2=value2`形式的参数，例如：`id=123456&sort=DESC&from=2017-09-10`
- 请求Header：例如，`Accept: */*`
- 请求 `Body`：二进制表示的 `JSON` 字符串，仅针对 `POST` 有效

数据格式：

| 除了请求体，其余后面都有换行符「\n」  | 释义                                                         |
| ------------------------------------- | ------------------------------------------------------------ |
| GET\n                                 | 请求方法，全部大写                                           |
| api.bitzon.com\n                      | 请求域名，全部小写                                           |
| /v1/trade/orders\n                    | 请求路径，严格大小写                                         |
| from=2017-09-10&id=123456&sort=DESC\n | 请求参数，字典序拼接，严格大小写，如果没有参数，直接「 \n」  |
| API-KEY: xyz123456\n                  | 「API-」开头的请求头，与签名相关。HEADER 全大写，冒号后面空格，VALUE 严格大小写。key 值 |
| API-SIGNATURE-METHOD: HmacSHA256\n    | 「API-」开头的请求头，与签名相关。HEADER 全大写，冒号后面空格，VALUE 严格大小写。加密算法 |
| API-SIGNATURE-VERSION: 1\n            | 「API-」开头的请求头，与签名相关。HEADER 全大写，冒号后面空格，VALUE 严格大小写。版本号 |
| API-TIMESTAMP: 12300000000\n          | 「API-」开头的请求头，与签名相关。HEADER 全大写，冒号后面空格，VALUE 严格大小写。时间戳 |
| API-UNIQUE-ID: uni-123-abc-xyz\n      | 「API-」开头的请求头，与签名相关。HEADER 全大写，冒号后面空格，VALUE 严格大小写。ID |
| <json body data>                      | 如果是 POST 请求，且含有 BODY，BODY 数据格式为 json 字符串   |

请求示例：

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

#### 1.2 签名

 将 1.1 步的请求字符串按 `UTF-8` 编码，得到一个二进制  `byte` 数组，然后使用 `API Secret` 计算 `Signature`:

```
signature = HmacSHA256(payload.encode("UTF-8"), "my-api-secret")
```

将所得签名以十六进制小写字符串形式添加到 `Header`：

```
API-Signature: a1b2c3ff001234500900dd01ff
```

### 说明

1. 参数使用原始字符串计算签名，例如`a=1/5`，不要使用`a=1%2F5`
2. `Header` 在计算签名时使用全大写，发送时大小写均可
3. 只有以`API-`开头的 `Header` 才被列入并计算签名（`API-Signature`除外，因为最后才能计算出 `API-Signature` 并附加到请求）
4. `API-Signature-Method` 必须为`HmacSHA256`
5. `API-Signature-Version` 必须为`1`
6. `API-Timestamp` 为当前时间戳，单位为毫秒整数，不支持小数，误差不得超过1分钟
7. `API-Unique-ID` 为可选，如果提供，则客户端需要提供一个唯一字符串标识，推荐 `UUID` 或自增序列 
8. 带 `Body` 的请求，需要先序列化为 `JSON` 字符串，然后把 `Body` 列入计算签名。不要对一个对象使用两次序列化，因为某些语言的实现可能导致两次序列化的 `JSON` 不一样，例如，`{"a":true, "b":1}`和`{"b":1, "a":true}`，两者 `JSON` 内容一致但字符串不同，将导致验证签名失败。


---

## WebSocket

用于获取市场事件。

### 如何连接 WebSocket

`URL: wss://wss.bitzon.com/v1/market/notification`

```
var ws = new WebSocket('wss://wss.bitzon.com/v1/market/notification');
```

连接成功之后，所有的数据的收发都是 `JSON` 字符串的形式。

在连接之后需要立刻发送一条订阅消息：

```
var msg = JSON.stringify({"action": "subscribe", "symbol": "BTC_USDT"});
ws.send(msg);
```

订阅之后，关于 `BTC_USDT` 交易对的所有市场事件都会被 `WebSocket` 立刻推送。

- 注意，该条信息必须是 **`JSON` 字符串** 而不是一个对象。

如果想要绘制市场图表，首先要通过 REST API 来获取历史数据，之后的最新数据则会被 WebSocket 推送。

### 如何处理 WebSocket 信息

从 WebSocket 获取的消息都是 `JSON` 字符串形式。下面是 `JavaScript` 代码示例：

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

### Topics - 主题

1. `topic_snapshot`: 交易对深度图

1. `topic_tick`: 全部市场行情数据。

1. `topic_bar`: K 线数据

1. `topic_order`: 订单实时数据

### K 线数据格式

包含六个元素的数组：

```
[时间戳 timestamp in millis, 开盘价 open price, 最高价 high price, 最低价 low price, 收盘价 close price, 交易量 volume]

如 [1544602783000, 3302.1, 3419.9, 3029.6, 3298, 9.34]
```



