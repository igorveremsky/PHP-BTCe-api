<?php
require_once 'vendor/autoload.php';

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