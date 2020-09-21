<?php

/**
 * Define Class for Index Module Controller
 * 
 * @author Kenny Hefner
 * @version 1.0
 * 
 * @copyright ADFX, Inc., 26 Feb, 2013
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
class IndexController extends AdFXController {

	/**
	 * View is stored here.
	 *
	 * @var viewController
	 */
	public $view;
	
	/**
	 *  Model is stored here.
	 *
	 * @var reports
	 */
	private $model;
	
	/**
	 * Constructor Function
	 * 
	 * Initialize the Index View.
	 *
	 * @author Kenny Hefner
	 */
	public function __construct() {
		parent::__construct();
		$this->view = new ViewController();		
	}
	
	/**
	 * Action function for Main Page.
	 * 
	 * 
	 * @author Kenny Hefner
	 */
	public function indexAction() {
		header("location: /company/index");
		return;
	}
	
	
} // end class
?>