<?php

/**
 * Define Class for Login Controller
 * 
 * @author Kenny Hefner
 * @version 1.0
 * 
 * @copyright AdFX, Inc., 20 Sep, 2020
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
class ErrorController extends AdFXController {

	/**
	 * Login View is stored here.
	 *
	 * @var viewController
	 */
	public $view;
	
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
	}
	
	/**
	 * Action function for Login Page.
	 * 
	 * 
	 * @author Kenny Hefner
	 */
	public function indexAction() {
		// loads index view
		// handle errors
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
		  $this->view->setTemplate('ajax');
		}
		if(!empty($_SESSION['error'])) {
		    $this->view->error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
	}
	
} // end class
?>