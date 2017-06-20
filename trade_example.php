<?php
require_once 'vendor/autoload.php';

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