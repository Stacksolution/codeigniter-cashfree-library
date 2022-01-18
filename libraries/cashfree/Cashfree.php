<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cashfree Payment Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Cashfree
 * @author		K.K ADIL KHAN AJAD
 */
 
class Cashfree {
	/**
	 * Constructor
	 *
	 * @param	array	$config
	 * @return	void
	 */
	public $cashfree_mode       = 'testing';
	
	public $cashfree_app_id     = '';
	
	public $cashfree_app_secret = '';
	
	public $cashfree_live_url   = 'https://api.cashfree.com/api/';
	
	public $cashfree_test_url   = 'https://test.cashfree.com/api/';
	
	public $cashfree_payout_url = 'https://payout-api.cashfree.com/payout/';
	
	public function __construct($config = array()){
		empty($config) OR $this->initialize($config, FALSE);

		$this->_CI =& get_instance();

		log_message('info', 'Casfree Class Initialized');
	}
	/**
	 * Initialize preferences
	 *
	 * @param	array	$config
	 * @param	bool	$reset
	 * @return	CI_Cashfree
	 */
	public function initialize(array $config = array(), $reset = TRUE){
		$reflection = new ReflectionClass($this);
        
		$defaults = $reflection->getDefaultProperties();
		foreach (array_keys($defaults) as $key){
			if ($key[0] === '_'){
				continue;
			}

			if (isset($config[$key])){
				$this->$key = $config[$key];
			}else{
				$this->$key = $defaults[$key];
			}
		}
		return $this;
	}
	/*
    *@ganrate token
    *@Auth Token
    */
    public function _token(){
        //check token url is empty
        if(empty($this->cashfree_payout_url)){
            $this->api_return(array('status' =>false,'message' => 'Cashfree token url empty !'));exit;
        }
        
        $url = $this->cashfree_payout_url.'v1/authorize';
        $headers = array(
            'X-Client-Id: '.$this->config['appid'],
            'X-Client-Secret: '.$this->config['secret'], 
            'Content-Type: application/json',
        );
        
        $data = array();
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
        $ex = curl_exec($ch);
        curl_close($ch);
        $robj = json_decode($ex, true);
        
        if(@$robj['subCode'] == 200 && @$robj['status'] == 'SUCCESS'){
            return @$robj['data']['token'];
        }
    }
    
    public function _create_order($order_id,$amount,$curncy = "INR"){
    
        $token  = $this->_token();
        if($this->cashfree_mode != 'testing'){
            $urls   = $this->cashfree_live_url."v2/cftoken/order";
        }else{
            $urls   = $this->cashfree_test_url."v2/cftoken/order";
        }
        
        $header = array(
            'X-Client-Id: '.$this->cashfree_app_id,
            'X-Client-Secret: '.$this->cashfree_app_secret, 
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        );
        
        $orders['orderId']         = $order_id;
        $orders['orderAmount']     = $amount;
        $orders['orderCurrency']   = $curncy;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $urls);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orders)); 
        $ex = curl_exec($ch);
        curl_close($ch);
        $robj = json_decode($ex, true);
        
        return $robj;
    }
    
    /*
     * Public Response Function
    */
    public function api_return($data = NULL) {
        ob_start();
        header('content-type:application/json; charset=UTF-8');
        print_r(json_encode($data));
        ob_end_flush();
    }
}
