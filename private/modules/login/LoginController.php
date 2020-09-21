<?php

	/**
	* Define Class for Login Controller
	*
	* @author Kenny Hefner
	* @version 1.0
	*
	* @copyright ADFX, Inc., 21 Sep, 2020
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
	* @package controller
	* @author Kenny Hefner
	*/
	class LoginController extends AdFXController {
	
		/**
		* Login View is stored here.
		*
		* @var viewController
		*/
		public $view;
		
		/**
		* Login Model is stored here.
		*
		* @var reports
		*/
		private $model;
		
		/**
		* Constructor Function
		*
		* Initialize the Login View.
		*
		* @author Kenny Hefner
		*/
		public function __construct() {
			parent::__construct();
			$this->view = new ViewController();
			require_once('models/Login.php');
			$this->model = new Login();
		}
		
		/**
		* Action function for Login Page.
		*
		*
		* @author Kenny Hefner
		*/
		public function indexAction() {
		
		}
		
		public function ajaxDoLoginAction() {
			try {
				$resp = $this->model->doLogin($_POST);
				if(!empty($_POST['login']) && $_POST['login'] === "subscription") {
					$tmp = json_decode($resp,true);
					$tmp['data']['reload'] = true;
					$resp = json_encode($tmp);
				}
				echo $resp;
			} catch(Exception $e) {
				echo json_encode(array('success'=>false,'msg'=>$e->getMessage()));
			}
			exit();
		}
			
		public function ajaxDoLogoutAction() {
			// grab the userid before destroying the session
			$userid = $_SESSION['user_id'];
			unset($_SESSION);
			session_destroy();
			
			$path = "/login";
			
			header("location:".$path."?logout=1");
			exit();
		}
	
	} // end class
?>
