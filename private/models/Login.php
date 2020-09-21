<?php
	/**
	* Define class for Login Model.
	*
	* @author Kenny Hefner
	* @version 1.0
	* 
	* @copyright AdFX, Inc., 21 Sep, 2020
	* @package model
	* 
	**/
	
	/**
	* Require the Api Adapter Class
	**/
	require_once("ApiAdapter.php");
	define("RECENT_FAILURES_WARNING", 3);
	
	class Login extends ApiAdapter {
	
		/**
		* API call to Login
		*
		* @param array $pvars User and Password
		* @return Boolean Success of Login Request
		* @author Kenny Hefner
		*/
		
		
		public function doLogin($pvars) {	
			
			// This system is designed to query a private API system that returns a JSON response
			// Typically, the response is decoded and parsed
			// {"success":true|false,"msg":"Text message form the backend system","data":jsonArray of key value pairs}
			// A hard coded example
			//$resp = '{"success":false, "msg":"Invalid Login"}';
			$resp = '{"success":true, "msg":"Testing Login"}';
			$r = json_decode($resp,true);
			
			if($r['success'] !== true) {
				return $resp;
				exit();
			}
			
			$this->createSessionUser($r);
			return $resp;		
			
		} // end do login

		
		public function createSessionUser($r) {
			// login successful. set user data up in memory
			session_regenerate_id(); // Create new session ID now that the user has logged in
			
			// A new ae_token is added upon the creation of the user session
			// A per session token is discussed here: https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html#synchronizer-token-pattern
			// Generate a CSRF token for session
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
			
			$r['data']['id'] = 1;
			$r['data']['first_name'] = "Kenny";
			$r['data']['last_name'] = "Hefner";
			
			// put user data in session
			foreach($r['data'] as $k=>$v) {
				if($k == "id") {
					$_SESSION['user_id'] = $v;
				} else {
					$_SESSION['user'][$k] = $v;
				}
			}
		} // end create session user
		
	}
?>
