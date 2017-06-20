<?php

namespace BTCe;

use BTCe\Exception\BTCeApiErrorException;
use BTCe\Exception\BTCeApiInvalidParameterException;

/**
 * BTC-e Trade Api class
 * @package BTCe
 *
 * @author igorveremsky
 * @license MIT License - https://github.com/igorveremsky/PHP-BTCe-api
 */
class BTCeTradeApi extends BTCeApi {
	const SORT_DESC = 'DESC';
	const SORT_ASC = 'ASC';

	protected $_apiKey;
	protected $_apiSecret;
	protected $_noonce;

	/**
	 * BTCeTradeApi constructor.
	 *
	 * @param $apiKey
	 * @param $apiSecret
	 * @param bool $customNoonce
	 */
	public function __construct($apiKey, $apiSecret, $customNoonce = false) {
		$this->_apiKey    = $apiKey;
		$this->_apiSecret = $apiSecret;

		$this->noonce = $customNoonce ? $customNoonce : time();
	}

	/**
	 * Get the noonce
	 *
	 * @return int
	 */
	public function getNoonce() {
		$this->noonce++;
		return $this->noonce;
	}

	/**
	 * Trade API: Get user info
	 *
	 * @return mixed
	 */
	public function getUserInfo() {
		return $this->makeTradeMethod(self::TRADE_METHOD_GET_INFO);
	}

	/**
	 * Trade API: Get active orders list
	 *
	 * @param bool $pair
	 *
	 * @param bool $pair
	 *
	 * @return array|mixed
	 */
	public function getActiveOrders($pair = false) {
		$addData = null;
		if ($pair) {
			$this->checkPair($pair);
			$addData = array(
				'pair' => $pair
			);
		}

		return $this->makeTradeMethod(self::TRADE_METHOD_ACTIVE_ORDERS, $addData);
	}

	/**
	 * Trade API: Get one order info
	 *
	 * @return mixed
	 */
	public function getOrderInfo($order_id) {
		$addData = array(
			'order_id' => $order_id
		);

		return $this->makeTradeMethod(self::TRADE_METHOD_ORDER_INFO, $addData);
	}

	/**
	 * Trade API: Cancel order
	 *
	 * @return mixed
	 */
	public function cancelOrder($order_id) {
		$addData = array(
			'order_id' => $order_id
		);

		return $this->makeTradeMethod(self::TRADE_METHOD_CANCEL_ORDER, $addData);
	}

	/**
	 * Trade API: Get trade history
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function tradeHistory($params = array()) {
		$addData = array();

		if (isset($params['from'])) $addData['from'] = $params['from'];
		if (isset($params['count'])) $addData['count'] = $params['count'];
		if (isset($params['from_id'])) $addData['from_id'] = $params['from_id'];
		if (isset($params['end_id'])) $addData['end_id'] = $params['end_id'];
		if (isset($params['order'])) $addData['order'] = $params['order'];
		if (isset($params['since'])) $addData['since'] = $params['since'];
		if (isset($params['end'])) $addData['end'] = $params['end'];
		if (isset($params['pair'])) {
			$pair = $params['pair'];
			$this->checkPair($pair);
			$addData['pair'] = $pair;
		}

		return $this->makeTradeMethod(self::TRADE_METHOD_TRADE_HISTORY, $addData);
	}

	/**
	 * Trade API: Get transaction history
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function transHistory($params = array()) {
		$addData = array();

		if (isset($params['from'])) $addData['from'] = $params['from'];
		if (isset($params['count'])) $addData['count'] = $params['count'];
		if (isset($params['from_id'])) $addData['from_id'] = $params['from_id'];
		if (isset($params['end_id'])) $addData['end_id'] = $params['end_id'];
		if (isset($params['order'])) $addData['order'] = $params['order'];
		if (isset($params['since'])) $addData['since'] = $params['since'];
		if (isset($params['end'])) $addData['end'] = $params['end'];

		return $this->makeTradeMethod(self::TRADE_METHOD_TRANS_HISTORY, $addData);
	}

	/**
	 * Get coin deposit address
	 *
	 * @param $coinName
	 *
	 * @return mixed
	 */
	public function getCoinDepositAddress($coinName) {
		$this->checkCoinName($coinName);

		$addData = array(
			'coinName' => $coinName,
		);

		return $this->makeTradeMethod(self::TRADE_METHOD_COIN_DEPOSIT_ADDRESS, $addData);
	}

	/**
	 * Place an order
	 *
	 * @param $amount
	 * @param $pair
	 * @param $direction
	 * @param $price
	 *
	 * @return mixed
	 * @throws BTCeApiInvalidParameterException
	 */
	public function makeOrder($amount, $pair, $direction, $price) {
		if($direction == self::ACTION_BUY || $direction == self::ACTION_SELL) {
			$data = $this->apiQuery("Trade"
				,array(
					'pair' => $pair,
					'type' => $direction,
					'rate' => $price,
					'amount' => $amount
				)
			);
			return $data;
		} else {
			throw new BTCeApiInvalidParameterException( 'Expected constant from ' . __CLASS__ . '::DIRECTION_BUY or ' . __CLASS__ . '::DIRECTION_SELL. Found: ' . $direction);
		}
	}

	/**
	 * Make trade method
	 *
	 * @param $methodType
	 * @param bool $addData
	 *
	 * @return mixed
	 * @throws BTCeApiErrorException
	 * @throws BTCeApiInvalidParameterException
	 */
	protected function makeTradeMethod($methodType, $addData = false) {
		$this->checkTradeMethod($methodType);

		$postData = $addData;
		$postData['method'] = $methodType;
		$postData['nonce'] = $this->getNoonce();

		$postDataString = http_build_query($postData, '', '&');

		// Generate the keyed hash value to post
		$sign = hash_hmac("sha512", $postDataString, $this->_apiSecret);

		// Add to the headers
		$headers = array(
			'Sign: '.$sign,
			'Key: '.$this->_apiKey,
		);

		$res = $this->doCurl($this->_tradeApiUrl, $headers, self::CURL_METHOD_POST, $postDataString);

		// Check is error
		if(isset($res['error'])) {
			if(strpos($res['error'], 'nonce') > -1) {
				throw new BTCeApiInvalidParameterException('Invalid noonce parameter. Noonce '.$this->getNoonce().'. Error message: '.$res['error'].'. Response: '.print_r($res, true));
			} else {
				throw new BTCeApiErrorException( 'API Error Message: ' . $res['error'] . ". Response: " . print_r($res, true));
			}
		}

		return $res['return'];
	}
}