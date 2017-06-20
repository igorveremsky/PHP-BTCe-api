PHP BTC-e Api
====

Public and Trade BTC-e API Class

Allows for the use Trade and Public APIs from BTC-e.

An Object Oriented library to use public and trade apis from [BTC-e](https://btc-e.com/)

## Installation

The recommended way to install PHP-BTCe-Api is through [Composer](https://getcomposer.org).

```bash
$ composer require igorveremsky/php-btce-api
```

## Basic Usage

### Public API

Example for use public methods from public API. Can be use without api key.

```php
use BTCe\BTCeApi;
use BTCe\BTCePublicApi;

$BTCePublicAPI = new BTCePublicApi();

//Example for get info for one pair BTC-USD
$btc_usd_code = BTCeApi::BTC_USD_PAIR;

$btc_usd = array();
//Example for get fee
$btc_usd['fee'] = $BTCePublicAPI->getPairsFee($btc_usd_code);
//Example for get ticker info
$btc_usd['ticker'] = $BTCePublicAPI->getPairsTicker($btc_usd_code);
//Example for get trades info
$btc_usd['trades'] = $BTCePublicAPI->getPairsTrades($btc_usd_code);
//Example for get depth info
$btc_usd['depth'] = $BTCePublicAPI->getPairsDepth($btc_usd_code);

echo '<pre>';
print_r($btc_usd);

//Example for get info for one multiply pairs BTC-USD, BTC-RUR
$btc_usd_btc_rur_code = array(
	BTCeApi::BTC_USD_PAIR,
	BTCeApi::BTC_RUR_PAIR,
);

$btc_usd_btc_rur = array();
$btc_usd_btc_rur['fee'] = $BTCePublicAPI->getPairsFee($btc_usd_btc_rur_code);
$btc_usd_btc_rur['ticker'] = $BTCePublicAPI->getPairsTicker($btc_usd_btc_rur_code);
$btc_usd_btc_rur['trades'] = $BTCePublicAPI->getPairsTrades($btc_usd_btc_rur_code);
$btc_usd_btc_rur['depth'] = $BTCePublicAPI->getPairsDepth($btc_usd_btc_rur_code);

print_r($btc_usd_btc_rur);
```

### Trade API

Example for use trade methods from private API. Can be use only with api key.

```php
use BTCe\BTCeApi;
use BTCe\BTCeTradeApi;
use BTCe\Exception\BTCeApiErrorException;

$apiKey = API_KEY;
$apiSecret = API_SECRET;

$BTCeTradeAPI = new BTCeTradeApi($apiKey, $apiSecret);

echo '<pre>';
//Example for get user balance info
try {
	$res = $BTCeTradeAPI->getUserInfo();
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for get active orders info
try {
	$res = $BTCeTradeAPI->getActiveOrders();
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for get active orders for one pair
try {
	$res = $BTCeTradeAPI->getActiveOrders(BTCeApi::BTC_USD_PAIR);
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for get one order info
try {
	$res = $BTCeTradeAPI->getOrderInfo(ORDER_ID);
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for cancel order
try {
	$res = $BTCeTradeAPI->cancelOrder(ORDER_ID);
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for get trade history
try {
	$res = $BTCeTradeAPI->tradeHistory();
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for get transactions history
try {
	$res = $BTCeTradeAPI->transHistory();
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}

//Example for get coin deposit address
try {
	$res = $BTCeTradeAPI->getCoinDepositAddress(BTCeApi::COIN_NAME_BTC);
	print_r($res);
} catch(BTCeApiErrorException $e) {
	echo $e->getMessage();
}
```

## License

This project is licensed under the [MIT license](http://opensource.org/licenses/MIT).