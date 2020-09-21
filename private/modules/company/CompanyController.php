<?php

/**
 * Define Class for Company Module Controller
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
class CompanyController extends AdFXController {

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
	 * Initialize the Company Index View.
	 *
	 * @author Kenny Hefner
	 */
	public function __construct() {
		parent::__construct();
		$this->view = new ViewController();
	}
	
	/**
	 * Action function for Company Index Page.
	 * 
	 * 
	 * @author Kenny Hefner
	 */
	public function indexAction() {
		
		// Index view vars go here
		
	}	
	
	/**
	 * Action function for Company About Page.
	 * 
	 * 
	 * @author Kenny Hefner
	 */
	public function aboutAction() {
		
		// About view vars go here
		
	}
	
	/**
	 * Action function for Company Products Page.
	 * 
	 * 
	 * @author Kenny Hefner
	 */
	public function productsAction() {
		
		// Product view vars go here
		
	}
	
	/**
	 * Action function for Company Store Page.
	 * 
	 * 
	 * @author Kenny Hefner
	 */
	public function storeAction() {
		
		// Store view vars go here
		
	}
	
} // end class
?>