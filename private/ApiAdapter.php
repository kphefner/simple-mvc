<?php

/**
* ApiAdapter.php
* 
* The ApiAdapter handles the communiction layer with the backend system
*
* @author Kenny Hefner
* @version 1.0
* @copyright AdFX, Inc., 20 Sep, 2020
* @package ApiAdapter
**/

class ApiAdapter {

	/**
	* URL pointing to the Object Server.
	*
	* @var string
	* @see doCurl()
	*/
	protected $url;
	
	public function __construct() {
	
	if(isset($GLOBALS['CONFIG']['obj']) && $GLOBALS['CONFIG']['obj']) {
		$this->url = $GLOBALS['CONFIG']['obj'];
	} else {
		$this->url = "http://local.obj"; // local audio eye object
	}
	
	}
	
	public function doCurl($json=null) {
		logger(LOG_DEBUG,"doCurl:: Starting");
		// Add addition params to request JSON
		$json = json_decode($json, true);
		$function = $json['function'];
		
		if(isset($_SESSION['user_id'])) {
			$json['user_id'] = $_SESSION['user_id'];
		}					
		
		if (!empty($_SESSION['unit_test'])) {
			$json['unit_test'] = true;
		}
		
		$json = json_encode($json);
		$post = array('json'=>$json);
		
		// NOTE: variables outside of the POST 'json' variable are not being used on object -- we may want to remove these
		$post['user_ip_address'] = $_SERVER['REMOTE_ADDR'];
		$post['server_timestamp'] = microtime(true);
		
		if(isset($_SESSION['user_id'])) {
			$post['user_id'] = $_SESSION['user_id'];
		}
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $this->url);
		logger(LOG_DEBUG,"doCurl:: url ==> ".$this->url);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		logger(LOG_DEBUG,"doCurl:: POSTFIELDS ==> ". (json_encode($post, true)));
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		
		if($result) {
			
			// Check for valid JSON response    
			$response = json_decode($result,true);
			
			
			if (empty($response)) { // Object did not return valid JSON
				$response = array('success' => false, 'msg' => 'Invalid JSON response.', 'csrf_token' => $_SESSION['csrf_token']);
			} else { // Add token to object response
				if (isset($_SESSION['csrf_token'])) {
				$response['csrf_token'] = $_SESSION['csrf_token'];
				}
			}
			
			$result = json_encode($response);
			return $result;
		
		} else {
			logger(LOG_ERR,"API Curl Failed:".__CLASS__.":".__METHOD__.":".curl_error($ch));
			return false;
		}
		
		curl_close($ch);
	
	} // end do curl
	
	public function response($success, $msg, $data=null) {
		
		if (!is_bool($success) || !is_string($msg)) {
			trigger_error('Response data was not properly formatted', E_USER_ERROR);
		}
		
		$resp = array(
		"success"=>$success,
		"msg"=>$msg,
		"csrf_token"=>$_SESSION['csrf_token']
		);
		
		if (isset($data)) {
			if (!is_array($data)) {
				trigger_error('Response data was not properly formatted', E_USER_ERROR);
			}    	
			
			$resp['data'] = $data;
		}
		
		return json_encode($resp);
	}
	
	public function jsonpResponse($success, $msg=null, $data=null) {
		if (empty($_REQUEST['callback'])) {
			trigger_error('Callback function is not defined', E_USER_ERROR);
		}
		
		$clean_callback = preg_replace("/\W/", "", $_REQUEST['callback']);
		
		// case when a response array is passed in
		if (is_array($success) && empty($msg)) {
			return $clean_callback.'('.json_encode($success).');';
		}
		
		return $clean_callback.'('.$this->response($success, $msg, $data).');';
	}
	
	public function __destruct() {
	
	}

}
?>
