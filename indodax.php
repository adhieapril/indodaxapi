<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class indodax{
	private $keys;
    private $secret;
	
	public function __construct()
    {
		date_default_timezone_set('Asia/Jakarta');
		// API settings
		$this->apiKey = ""; // your API-key
		$this->secretKey = ""; // your Secret-key
	}
	
	
	public static function seconds() {
		return time();
    }
	
	public static function milliseconds() {
        list($msec, $sec) = explode(' ', microtime());
        return $sec . substr($msec, 2, 3);
    }

    public static function microseconds() {
        list($msec, $sec) = explode(' ', microtime());
        return $sec . str_pad(substr($msec, 2, 6), 6, '0');
    }
	
	public static function iso8601($timestamp = null) {
        if (!isset($timestamp)) {
            return null;
        }
        if (!is_numeric($timestamp) || intval($timestamp) != $timestamp) {
            return null;
        }
        $timestamp = (int) $timestamp;
        if ($timestamp < 0) {
            return null;
        }
        $result = gmdate('c', (int) floor($timestamp / 1000));
        $msec = (int) $timestamp % 1000;
        $result = str_replace('+00:00', sprintf('.%03dZ', $msec), $result);
        return $result;
    }

    public static function parse_date($timestamp) {
        return static::parse8601($timestamp);
    }

    public static function parse8601($timestamp = null) {
        if (!isset($timestamp)) {
            return null;
        }
        if (!$timestamp || !is_string($timestamp)) {
            return null;
        }
        $timedata = date_parse($timestamp);
        if (!$timedata || $timedata['error_count'] > 0 || $timedata['warning_count'] > 0 || (isset($timedata['relative']) && count($timedata['relative']) > 0)) {
            return null;
        }
        if (($timedata['hour'] === false) ||
            ($timedata['minute'] === false) ||
            ($timedata['second'] === false) ||
            ($timedata['year'] === false) ||
            ($timedata['month'] === false) ||
            ($timedata['day'] === false)) {
            return null;
        }
        $time = strtotime($timestamp);
        if ($time === false) {
            return null;
        }
        $time *= 1000;
        if (preg_match('/\.(?<milliseconds>[0-9]{1,3})/', $timestamp, $match)) {
            $time += (int) str_pad($match['milliseconds'], 3, '0', STR_PAD_RIGHT);
        }
        return $time;
    }

    public static function rfc2616($timestamp) {
        if (!$timestamp) {
            $timestamp = static::milliseconds();
        }
        return gmdate('D, d M Y H:i:s T', (int) round($timestamp / 1000));
    }
	
	public function nonce() {
        return $this->milliseconds();
    }

	public function postIDX($method, array $req = array()) {
		// generate the POST data string
		$req['method'] = $method;
		$req['nonce'] = $this->getServerTime();
		$post_data = http_build_query($req, '', '&');
		$sign = hash_hmac('sha512', $post_data, $this->secretKey);
		$headers = array('Sign: '.$sign,'Key: '.$this->apiKey);
		
		// our curl handle (initialize if required)
		static $ch = null;
		if (is_null($ch)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		}

		curl_setopt($ch, CURLOPT_URL, 'https://indodax.com/tapi/');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		$res = curl_exec($ch);
		if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
	 		$dec = json_decode($res);
		if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists: '.$res);
	 
		curl_close($ch);
		$ch = null;
	 
		return $dec;
	}
	
	public function getIDX($method, $pairs = null) {
		if($pairs==""){
			$url="https://indodax.com/api/".$method."/".$pairs;
		}else{
			$url="https://indodax.com/api/".$method;
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$res = curl_exec($ch);
		if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
	 		$dec = json_decode($res);
		if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists: '.$res);
	 
		curl_close($ch);
		$ch = null;
	 
		return $dec;
	}
	
	public function getServerTime(){
		$getr=$this->getIDX('server_time');
		return $getr->server_time;
	}
	
	public function getPairs(){
		return $this->getIDX('pairs');
	}
	
	public function getPriceInt(){
		return $this->getIDX('price_increments');
	}
	
	public function getSummaries(){
		return $this->getIDX('summaries');
	}
	
	public function getTickerall(){
		return $this->getIDX('ticker_all');
	}
	
	public function getTicker($pair_id){
		return $this->getIDX('ticker',$pair_id);
	}
	
	public function getTrades($pair_id){
		return $this->getIDX('trades',$pair_id);
	}
	
	public function getDepth($pair_id){
		return $this->getIDX('depth',$pair_id);
	}
	
	public function InfoAccount(){
		return $this->postIDX('getInfo');
	}
	
	public function TransHistory(){
		return $this->postIDX('transHistory');
	}
	
	public function Trade($data = array()){
		return $this->postIDX('trade',$data);
	}
	
	public function TradeHistory($data = array()){
		return $this->postIDX('tradeHistory',$data);
	}
	
	public function OpenOrder($data = array()){
		return $this->postIDX('openOrders',$data);
	}
	
	public function OrderHistory($data = array()){
		return $this->postIDX('orderHistory',$data);
	}
	
	public function GetOrder($data = array()){
		return $this->postIDX('getOrder',$data);
	}
	
	public function CancelOrder($data = array()){
		return $this->postIDX('cancelOrder',$data);
	}
	
}