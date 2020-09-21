<?php
/**
* DispatchController.php
* 
* The public-facing "bootstrap" file (/public/index.php) calls the dispatch class,
* which in turn calls the proper controller, thus kicking off the MVC framework
* goodness.
*
* @author Kenny Hefner
* @version 1.0
* @copyright AdFX, Inc., 19 Sep, 2020
* @package dispatch
**/

/**
* The dispatch class contains static methods that are invoked on the bootstrap file.
*
* @package dispatch
*/
class Dispatch {

	/**
	* Initialize the controller and get the ball rolling on the page request.
	* 
	* The web server uses HTAccess to parse out the module and action from a request
	* URL.  The "module" is the base name of the controller to be called.  That, in
	* turn, calls the model for any data-processing functionality and the view to generate
	* the final HTML that the user sees.
	*
	* @return void
	*/
	public static function init() {
	
		if(!@include_once('modules/'.$GLOBALS['module'].'/'.ucwords($GLOBALS['module']).'Controller.php')) {
			logger(LOG_ERR,$_SERVER['REQUEST_URI']);
			logger(LOG_ERR,"Unable to load class: ".ucwords($GLOBALS['module'])."Controller");
			$_SESSION['error'] = "A File Error has occurred and been logged.";
			header("location: /error");
			exit();
		}
		
		if(class_exists(ucwords($GLOBALS['module']).'Controller')) {
		
			$class = ucwords($GLOBALS['module']).'Controller';
			$obj = new $class();
			$objaction = $GLOBALS['action'].'Action';
			
			if(method_exists($obj,$objaction)) {
				
				if(self::auth()) {
					$obj->$objaction();
				} else {
					logger(LOG_ERR,"Unable to authorize action: ".$class."->".$objaction." / User: ".$_SESSION['user_id']);
					$_SESSION['error'] = "An Authorization Error has occurred and been logged.";
					header("location: /error");
					exit();
				}
			
			} else {
			
				logger(LOG_ERR,"Unable to find method: ".$class."->".$objaction);
				$_SESSION['error'] = "A Requested Action Error has occurred and been logged.";
				header("location: /error");
				exit();
			
			}
		
		} else {
			
			logger(LOG_ERR,"Unable to load class: ".ucwords($GLOBALS['module'])."Controller");
			$_SESSION['error'] = "An Initialization Error has occurred and been logged.";
			header("location: /error");
			exit();
			
		}
	
	}
	
	/**
	* Stubbed out an auth function for future software licensing use.
	*
	* @return Boolean
	*/
	public static function auth() {
		return true;
	}

} //END class dispatch
?>
