<?php
	require_once('indodax.php')
	
	$idx=new indodax();
	
	//Get indodax server time
	$idx->getServerTime(){
	
	//get available COIN pairs
	$idx->getPairs(){
	
	//get an increase in the price of each COIN pair
	$idx->getPriceInt(){
	
	//get COIN summary
	$idx->getSummaries()
	
	//get the whole price of COIN
	$idx->getTickerall()
	
	//get a certain COIN price
	$idx->getTicker('btc_idr')
	
	//get certain COIN order data
	$idx->getTrades('btc_idr')
	
	//get a list of certain COIN buy and sell volumes
	$idx->getDepth('btc_idr')
	
	//Get Transaction history withdrawl or deposit account indodax
	$idx->InfoAccount();
	
	//make a coin purchase on indodax
	$databuy = array(
		'pair' => 'bnb_idr',
		'type' => 'buy',
		'price' => '4000000',
		'idr' => '100000',
	);
	$idx->Trade($databuy);
	
	//sell the coins you have on indodax
	$datasell = array(
		'pair' => 'btt_idr',
		'type' => 'sell',
		'price' => '4000',
		'btt' => '250',
	);
	$idx->Trade($datasell);
	
	//This method gives information about transaction in buying and selling history
	$datatranshistory = array(
		'count' => '1000',
		'from_id' => '0',
		'end_id' => '0',
		'order' => 'asc', // : asc/desc
		'since' => strtotime('2021-04-01'),
		'end' => strtotime('2021-06-15'),
		'pair' => 'btt_idr',
	);
	$idx->TradeHistory($datatranshistory);
	
	//This method gives the list of current open orders (buy and sell).
	$dataopenorder = array(
		'pair' => 'btt_idr',
	);
	$idx->OpenOrder($dataopenorder);
	
	//This method gives the list of order history (buy and sell)
	$dataorderhistory = array(
		'pair' => 'btt_idr',
	);
	$idx->OrderHistory($dataorderhistory);
	
	//Use getOrder to get specific order details.
	$datagetorder = array(
		'pair' => 'btt_idr',
		'order_id' => '9633303',
	);
	$idx->GetOrder($datagetorder);
	
	//This method is for canceling an existing open order.
	$datacancelorder = array(
		'pair' => 'bnb_idr',
		'order_id' => '43683021',
		'type' => 'buy', // : buy/sell
	);
	$idx->CancelOrder($datacancelorder);