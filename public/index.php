<?php

// Issue Security Related Headers
header("X-XSS-Protection: 1; mode=block");

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
  $protocol = "https://";
} else {
  $protocol = "http://";
}

session_start();

// Set our global include paths
$incpath = get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/../private/";
set_include_path($incpath);

// Converting URI to module(main menu) and action(page)
$tmp = explode("?",$_SERVER['REQUEST_URI']);
$uri = explode("/",$tmp[0]);
global $module;
global $action;

if(empty($uri[1])) {
  //this is root
  $module = "index";
} else {
  $module = $uri[1];
}
if(empty($uri[2])){
  $action = "index";
} else {
  $action = $uri[2];
}

// CUSTOM
require_once('globals.php');
require_once('config.php');

// Convert all remaining URI path items to key/value pairs
if(!empty($uri[3])) {
	$tmpr = array();
	for($x=3;$x<count($uri);$x++) {
		if($x%2) {
			// If next param does not exist, set value to null
			if (!array_key_exists($x + 1, $uri)) {
				$_REQUEST[$uri[$x]] = null;
				break;
			}
			
			$t = explode("&",$uri[$x + 1]);
			$_REQUEST[$uri[$x]] = urldecode($t[0]);
			if(!empty($t[1])) {
				$tmpr[] = $t[1];
			}
		}
	}	
}
if(!empty($tmpr)) {
	foreach($tmpr as $i) {
		$t = explode("=",$i);
		if(!empty($t[0]) && !empty($t[1])) {
			$_REQUEST[$t[0]] = urldecode($t[1]);
		}
	}
}

// AUTHENTICATION
// Public Modules
$public_modules = array("login", "error", "company", "index");

if(empty($_SESSION['user_id']) && !in_array($module, $public_modules)) {
	
	// Drop a cookie to store the intended page request ONLY if that request has a corresponding view file
	unset($_COOKIE['portalPageRequest']);
	$view = $action.".php";
	logger($view);
	if(file_exists("../private/modules/".$module."/view/".$view)) {
		logger("file exists");
		setcookie("portalPageRequest", remove_line_break($_SERVER['REQUEST_URI']), [
			"expires" => 0,
			"path" => "/",
			"domain" => "",
			"secure" => true, 
			"httponly" => false,
			"samesite" => "None"
		]);
	}
	
	// HANDLE Non Public Module Request
	header("Location: /login/index");
	exit();
	
} 

// DISPATCHER
include_once('DispatchController.php');
Dispatch::init();

// MESSAGE BUSS
unset($_SESSION['messages']);
unset($_REQUEST['msg_rtn']);

exit();

?>