<?php 
/**
 * Define AdFX Controller class
 *
 * @author Kenny Hefner
 * @version 1.0
 * @copyright AdFX, Inc., 19 Sep, 2020
 * @package controller
 * 
 **/

/**
 * Base controller class for the ADFX MVC Framework.
 *
 * @package controller
 * 
 **/
class AdFXController {

	/**
	 * mirror of $_POST, only trimmed and HTML Entity Encoded.
	 *
	 * @var string
	 */
	public $pvars = array();
	
	/**
	 * mirror of $_REQUEST, only trimmed and HTML Entity Encoded.
	 *
	 * @var string
	 */
	public $rvars = array();

	/**
	 * populate the rvars and pvars
	 * 
	 * As a security and convenience measure, we trim and entity encode all REQUEST and POST variables.
	 * If a REQUEST or POST variable is an array, then we perform the same treatment
	 * to its children. We do not expect, and will reject, any further levels of array.
	 *
	 * 
	 */
	public function __construct() {
		foreach($_POST as $key=>$val) {
			if ($key == "xml") {
				$this->pvars[$key] = trim($val);
			} else if (is_string($val)) {
				$this->pvars[$key] = htmlentities(trim($val));
			} else if (is_array($val)) {
				$this->pvars[$key] = array();
				foreach($val as $k=>$v) {
					if (is_string($v)) {
						$this->pvars[$key][$k] = htmlentities(trim($v));						
					}
				}
			}
			
			//$_SESSION['pvars'][$key] = $this->pvars[$key];
		}

		foreach($_REQUEST as $key=>$val) {
			if ($key == "xml") {
				$this->rvars[$key] = trim($val);
			} else if (is_string($val)) {
				$this->rvars[$key] = htmlentities(trim($val));
			} else if (is_array($val)) {
				$this->rvars[$key] = array();
				foreach($val as $k=>$v) {
					if (is_string($v)) {
						$this->rvars[$key][$k] = htmlentities(trim($v));						
					}
				}
			}
		}
	}
		
} // END class AdFXController
?>
