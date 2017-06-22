<?php

namespace BTCe;

/**
 * BTC-e Public Api class
 * @package BTCe
 *
 * @author igorveremsky
 * @license MIT License - https://github.com/igorveremsky/PHP-BTCe-api
 */
class BTCePublicApi extends BTCeApi {
	/**
	 * Public API: Retrieve info about active pairs
	 *
	 * @return mixed
	 */
	public function getPairsInfo() {
		return $this->makePublicMethod( self::PUBLIC_METHOD_INFO);
	}

	/**
	 * Public API: Retrieve the Fee for one or more pairs
	 *
	 * @param $pairs
	 *
	 * @return mixed
	 */
	public function getPairsFee($pairs) {
		return $this->makePublicMethod( self::PUBLIC_METHOD_FEE, $pairs);
	}

	/**
	 * Public API: Retrieve the Ticker for one or more pairs
	 *
	 * @param $pairs
	 *
	 * @return mixed
	 */
	public function getPairsTicker($pairs) {
		return $this->makePublicMethod( self::PUBLIC_METHOD_TICKER, $pairs);
	}

	/**
	 * Public API: Retrieve the Trades for one or more pairs
	 *
	 * @param $pairs
	 *
	 * @return mixed
	 */
	public function getPairsTrades($pairs, $limit = false) {
		$extraGetString = false;
		if ($limit && is_int($limit)) {
			$extraGetString = 'limit=' . $limit;
		}

		return $this->makePublicMethod( self::PUBLIC_METHOD_TRADES, $pairs, $extraGetString);
	}

	/**
	 * Public API: Retrieve the Depth for one or more pairs
	 *
	 * @param $pairs
	 *
	 * @return mixed
	 */
	public function getPairsDepth($pairs) {
		return $this->makePublicMethod( self::PUBLIC_METHOD_DEPTH, $pairs);
	}

	/**
	 * Make public method
	 *
	 * @param $methodType
	 * @param bool $pairs
	 * @param bool $extraGetString
	 *
	 * @return mixed
	 */
	protected function makePublicMethod($methodType, $pairs = false, $extraGetString = false) {
		$this->checkPublicMethod($methodType);

		$alias = $methodType;

		if ($pairs) {
			$this->checkPairs($pairs);
			$alias .= '/' . $this->generatePairsString($pairs);
		}

		if ($extraGetString) {
			$alias .= '?' . $extraGetString;
		}

		$url = $this->_publicApiUrl . $alias;

		return $this->doCurl($url, null, self::CURL_METHOD_GET);
	}
}