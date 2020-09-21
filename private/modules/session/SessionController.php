<?php
/**
* Define Class for Session Controller
* 
* @author Kenny Hefner
* @version 1.0
* 
* @copyright AdFX, Inc., 19 Sep, 2020
* @package controller
* 
**/

/**
* Require file containing view superclass 
*/
require('ViewController.php');

/**
* Require file containing controller superclass 
*/
require('AdFXController.php');

/**
* Controller for Login
*
* @package controller
* 
* @author Kenny Hefner
*/
class SessionController extends AdFXController {

	/**
	 * Session View is stored here.
	 *
	 * @var viewController
	 */
	public $view;
	
	/**
	* Constructor Function
	* 
	* Initialize the Session View.
	*
	* @author Kenny Hefner
	*/
	public function __construct() {
		parent::__construct();
		$this->view = new ViewController();		
	}
	
	/**
	* Action: Session Expiration Notice.
	* 
	* @author Kenny Hefner
	*/
	public function indexAction() {
		// loads index view
		if($_SERVER['REQUEST_METHOD'] == "GET") {
		]echo '<div class="session"><span class="sessionhead">We\'re sorry</span>. Your session has expired. Please log in again (refresh your window or <a href="javascript:window.location.reload();">click here</a>).</div>';
		} else {
			// craft a proper json response 
			echo '{"success":false,"msg":"Your session has expired. Please login again to continue.","data":{"login":true}}';
		}
		exit();		
	}
	
	/**
	* Action: Session Expiration Warning.
	* 
	* @author Kenny Hefner
	*/
	public function ajaxSessionWarningAction() {
		logger(LOG_DEBUG,"Current Time: ".time().", Last Activity: ".$_SESSION['LAST_ACTIVITY'].", Time Remaining: ".$this->sessionSecondsRemaing().", User: ".$_SESSION['user_id'],__FUNCTION__);
		$this->view->seconds_remaining = $this->sessionSecondsRemaing();
	}
    
	/**
	* Action: Session Expiration Warning for market Place.
	* 
	* @author Kenny Hefner
	*/
	public function ajaxMarketSessionWarningAction() {
		logger(LOG_DEBUG,"Current Time: ".time().", Last Activity: ".$_SESSION['LAST_ACTIVITY'].", Time Remaining: ".$this->sessionSecondsRemaing().", User: ".$_SESSION['user_id'],__FUNCTION__);
		$this->view->seconds_remaining = $this->sessionSecondsRemaing();
	}
    
	/**
	* Action: Session Expiration Warning for Duda.
	* 
	* @author Kenny Hefner
	*/
	public function ajaxDudaSessionWarningAction() {
		logger(LOG_DEBUG,"Current Time: ".time().", Last Activity: ".$_SESSION['LAST_ACTIVITY'].", Time Remaining: ".$this->sessionSecondsRemaing().", User: ".$_SESSION['user_id'],__FUNCTION__);
		$this->view->seconds_remaining = $this->sessionSecondsRemaing();
	}
    
	/**
	* Action: Report Session Seconds Remaining.
	* 
	* @author Kenny Hefner
	*/
	public function ajaxCheckSessionAction() {
		logger(LOG_DEBUG,"Current Time: ".time().", Last Activity: ".$_SESSION['LAST_ACTIVITY'].", Time Remaining: ".$this->sessionSecondsRemaing().", User: ".$_SESSION['user_id'],__FUNCTION__);
		echo '{"success":true,"msg":"Session Checked","data":{"current_server_time":"'.time().'","last_activity":"'.$_SESSION['LAST_ACTIVITY'].'","seconds_remaining":"'.$this->sessionSecondsRemaing().'"}}';
		exit();
	}
    
    
	/**
	* AJAX Action: keepalive
	* 
	* @author Kenny Hefner
	*/
	public function ajaxKeepAliveAction() {
		logger(LOG_DEBUG,"Current Time: ".time().", Last Activity: ".$_SESSION['LAST_ACTIVITY'].", Time Remaining: ".$this->sessionSecondsRemaing().", User: ".$_SESSION['user_id'],__FUNCTION__);
		echo '{"success":true,"msg":"Session renewed","data":{"last_activity":"'.$_SESSION['LAST_ACTIVITY'].'"}}';
		exit();
	}
	
	/**
	* Private fuction: sessionSecondsRemaining
	* returns int
	* @author Kenny Hefner
	*/
	private function sessionSecondsRemaing() {
		$tmp = time() - $_SESSION['LAST_ACTIVITY'];
		$seconds_remaining = $GLOBALS['expire'] - $tmp;
		return $seconds_remaining;
	}

}

?>
