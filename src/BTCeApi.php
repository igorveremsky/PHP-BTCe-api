<?php

namespace BTCe;

use BTCe\Exception\BTCeApiErrorException;
use BTCe\Exception\BTCeApiInvalidCoinNameException;
use BTCe\Exception\BTCeApiInvalidMethodException;
use BTCe\Exception\BTCeApiInvalidPairException;
use BTCe\Exception\BTCeCurlFailureException;

/**
 * BTC-e Api class
 * @package BTCe
 *
 * @author igorveremsky
 * @license MIT License - https://github.com/igorveremsky/PHP-BTCe-api
 */
class BTCeApi {
	// Trade methods types
	const TRADE_METHOD_GET_INFO = 'getInfo';
	const TRADE_METHOD_TRADE = 'Trade';
	const TRADE_METHOD_ACTIVE_ORDERS = 'ActiveOrders';
	const TRADE_METHOD_ORDER_INFO = 'OrderInfo';
	const TRADE_METHOD_CANCEL_ORDER = 'CancelOrder';
	const TRADE_METHOD_TRADE_HISTORY = 'TradeHistory';
	const TRADE_METHOD_TRANS_HISTORY = 'TransHistory';
	const TRADE_METHOD_COIN_DEPOSIT_ADDRESS = 'CoinDepositAddress';
	const TRADE_METHOD_TRANS_WITHDRAW_COIN = 'WithDrawCoin';
	const TRADE_METHOD_TRANS_CREATE_COUPON = 'CreateCoupon';
	const TRADE_METHOD_TRANS_REDEEM_COUPON = 'RedeemCoupon';

	protected $_supportedTradeMethods = array(
		self::TRADE_METHOD_GET_INFO,
		self::TRADE_METHOD_TRADE,
		self::TRADE_METHOD_ACTIVE_ORDERS,
		self::TRADE_METHOD_ORDER_INFO,
		self::TRADE_METHOD_CANCEL_ORDER,
		self::TRADE_METHOD_TRADE_HISTORY,
		self::TRADE_METHOD_TRANS_HISTORY,
		self::TRADE_METHOD_COIN_DEPOSIT_ADDRESS,
		self::TRADE_METHOD_TRANS_WITHDRAW_COIN,
		self::TRADE_METHOD_TRANS_CREATE_COUPON,
		self::TRADE_METHOD_TRANS_REDEEM_COUPON,
	);

	// Public methods types
	const PUBLIC_METHOD_INFO = 'info';
	const PUBLIC_METHOD_FEE = 'fee';
	const PUBLIC_METHOD_TICKER = 'ticker';
	const PUBLIC_METHOD_DEPTH = 'depth';
	const PUBLIC_METHOD_TRADES = 'trades';

	protected $_supportedPublicMethods = array(
		self::PUBLIC_METHOD_INFO,
		self::PUBLIC_METHOD_FEE,
		self::PUBLIC_METHOD_TICKER,
		self::PUBLIC_METHOD_DEPTH,
		self::PUBLIC_METHOD_TRADES,
	);

	// Curl request methods
	const CURL_METHOD_POST = 'POST';
	const CURL_METHOD_GET = 'GET';

	// Coin names
	const COIN_NAME_BTC = 'BTC';
	const COIN_NAME_LTC = 'LTC';
	const COIN_NAME_NMC = 'NMC';
	const COIN_NAME_NVC = 'NVC';
	const COIN_NAME_PPC = 'PPC';
	const COIN_NAME_DSH = 'DSH';
	const COIN_NAME_ETH = 'ETH';

	protected $_supportedCoinNames = array(
		self::COIN_NAME_BTC,
		self::COIN_NAME_LTC,
		self::COIN_NAME_NMC,
		self::COIN_NAME_NVC,
		self::COIN_NAME_PPC,
		self::COIN_NAME_DSH,
		self::COIN_NAME_ETH,
	);

	// Bitcoins pairs
	const BTC_USD_PAIR = 'btc_usd';
	const BTC_RUR_PAIR = 'btc_rur';
	const BTC_EUR_PAIR = 'btc_eur';

	// Litecoins pairs
	const LTC_BTC_PAIR = 'ltc_btc';
	const LTC_USD_PAIR = 'ltc_usd';
	const LTC_RUR_PAIR = 'ltc_rur';
	const LTC_EUR_PAIR = 'ltc_eur';

	// Namecoins pairs
	const NMC_BTC_PAIR = 'nmc_btc';
	const NMC_USD_PAIR = 'nmc_btc';

	// Novacoins pairs
	const NVC_BTC_PAIR = 'nvc_btc';
	const NVC_USD_PAIR = 'nvc_usd';

	// Peercoins pairs
	const PPC_BTC_PAIR = 'ppc_btc';
	const PPC_USD_PAIR = 'ppc_usd';

	// Dashcoins pairs
	const DSH_BTC_PAIR = 'dsh_btc';
	const DSH_USD_PAIR = 'dsh_usd';
	const DSH_RUR_PAIR = 'dsh_rur';
	const DSH_EUR_PAIR = 'dsh_eur';
	const DSH_LTC_PAIR = 'dsh_ltc';
	const DSH_ETH_PAIR = 'dsh_eth';

	// Ethereum pairs
	const ETH_BTC_PAIR = 'eth_btc';
	const ETH_LTC_PAIR = 'eth_ltc';
	const ETH_USD_PAIR = 'eth_usd';
	const ETH_EUR_PAIR = 'eth_eur';
	const ETH_RUR_PAIR = 'eth_rur';

	// Else pair
	const USD_RUR_PAIR = 'usd_rur';
	const EUR_USD_PAIR = 'eur_usd';
	const EUR_RUR_PAIR = 'eur_rur';

	protected $_supportedPairs = array(
		self::BTC_EUR_PAIR,
		self::BTC_RUR_PAIR,
		self::BTC_USD_PAIR,
		self::LTC_BTC_PAIR,
		self::LTC_EUR_PAIR,
		self::LTC_RUR_PAIR,
		self::LTC_USD_PAIR,
		self::NMC_BTC_PAIR,
		self::NMC_USD_PAIR,
		self::NVC_BTC_PAIR,
		self::NVC_USD_PAIR,
		self::ETH_BTC_PAIR,
		self::ETH_EUR_PAIR,
		self::ETH_LTC_PAIR,
		self::ETH_RUR_PAIR,
		self::ETH_USD_PAIR,
		self::DSH_BTC_PAIR,
		self::DSH_ETH_PAIR,
		self::DSH_EUR_PAIR,
		self::DSH_LTC_PAIR,
		self::DSH_RUR_PAIR,
		self::DSH_USD_PAIR,
		self::USD_RUR_PAIR,
		self::EUR_RUR_PAIR,
		self::EUR_USD_PAIR,
	);

	protected $_publicApiUrl = 'https://btc-e.com/api/3/';
	protected $_tradeApiUrl = 'https://btc-e.com/tapi/';

	/**
	 * Generate curl request to url
	 *
	 * @param $url
	 * @param $headers
	 * @param $method
	 * @param bool $postDataString
	 *
	 * @return mixed
	 * @throws BTCeApiErrorException
	 * @throws BTCeCurlFailureException
	 */
	protected function doCurl($url, $headers, $method, $postDataString = false) {
		// Create a CURL Handler for use
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; igorveremsky BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
		curl_setopt($ch, CURLOPT_URL, $url);

		if ($method == self::CURL_METHOD_POST && $postDataString) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataString);
		}

		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		// Send API Request
		$res = curl_exec($ch);

		// Check for failure & Clean-up curl handler
		if($res === false) {
			$e = curl_error($ch);
			curl_close($ch);
			throw new BTCeCurlFailureException( 'CURL error: ' . $e);
		} else {
			curl_close($ch);
		}

		// Decode the JSON
		$result = json_decode($res, true);
		// is it valid JSON?
		if(!$result) {
			throw new BTCeApiErrorException('Invalid data received, please make sure connection is working and requested API exists');
		}

		return $result;
	}

	/**
	 * Check is public method correct
	 *
	 * @param $methodType
	 *
	 * @throws BTCeApiInvalidMethodException
	 */
	protected function checkTradeMethod($methodType) {
		if (!in_array($methodType, $this->_supportedTradeMethods)) {
			throw new BTCeApiInvalidMethodException('Invalid trade method: ' . $methodType);
		}
	}

	/**
	 * Check is public method correct
	 *
	 * @param $methodType
	 *
	 * @throws BTCeApiInvalidMethodException
	 */
	protected function checkPublicMethod($methodType) {
		if (!in_array($methodType, $this->_supportedPublicMethods)) {
			throw new BTCeApiInvalidMethodException('Invalid public method: ' . $methodType);
		}
	}

	/**
	 * Generate pairs string from array
	 *
	 * @param $pairs
	 *
	 * @return string
	 */
	protected function generatePairsString($pairs) {
		if (!is_array($pairs)) {
			$pairsArr[] = $pairs;
			$pairs = $pairsArr;
		}

		$pairsString = '';
		$firstPair = true;
		foreach ($pairs as $pair) {
			$pairsString .= ($firstPair) ? $pair : '-' . $pair;

			$firstPair = false;
		}

		return $pairsString;
	}

	/**
	 * Check pairs
	 *
	 * @param $pairs
	 */
	protected function checkPairs($pairs) {
		if (!is_array($pairs)) {
			$pairsArr[] = $pairs;
			$pairs = $pairsArr;
		}

		foreach ($pairs as $pair) {
			$this->checkPair($pair);
		}
	}

	/**
	 * Check is pair correct
	 *
	 * @param $pair
	 *
	 * @throws BTCeApiInvalidPairException
	 */
	protected function checkPair($pair) {
		if (!in_array($pair, $this->_supportedPairs)) {
			throw new BTCeApiInvalidPairException('Invalid pair: ' . $pair);
		}
	}

	/**
	 * Check is coin name correct
	 *
	 * @param $coinName
	 *
	 * @throws BTCeApiInvalidCoinNameException
	 */
	protected function checkCoinName($coinName) {
		if (!in_array($coinName, $this->_supportedCoinNames)) {
			throw new BTCeApiInvalidCoinNameException('Invalid coin name: ' . $coinName);
		}
	}
}