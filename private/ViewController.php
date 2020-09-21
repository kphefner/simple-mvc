<?php
	/**
	* Helper class for all module views.
	* 
	* This class will hold variables from the model and make helper functions available
	* to a module's view file. The view determines what markup will appear on the page.
	* 
	* @author Kenny Hefner
	* @version 1.0
	* @copyright AdFX, Inc., 20 Sep, 2020
	* @package view
	**/
	
	/**
	* Base class for views.
	* 
	* Each controller has a view assigned to it, and the view is responsible for laying
	* out the content that gets displayed to the user.
	*
	* @package view
	* @author Kenny Hefner
	*/
	class ViewController {
	
	/**
	* Storage for the HTML to insert into the template.
	*
	* @var string
	* @see render()
	*/
	public $markup;
	
	/**
	* Storage for properties accessed by the get/set methods.
	*
	* @var array
	* @see __get()
	* @see __set()
	*/
	private $data = array();
	
	/**
	* Choose which template to use.
	*
	* @var string
	* @see setTemplate()
	**/
	public $template;
	
	/**
	* Storage for the names of the javascript files that will be included in the
	* HTML head
	*
	* @var array
	* @see requireScript();
	* @see scripts();
	*/
	private $scripts = array();
	
	/**
	* Storage for the names of the CSS files that will be included in the
	* HTML head
	*
	* @var array
	* @see requireStyle();
	* @see styles();
	*/
	private $styles = array();
	
	/**
	* Class Constructor.
	* 
	* When a viewController instance is constructed, we generate a breadcrumb based
	* on the HTTP Referrer.
	*
	* @author Kenny Hefner
	*/
	public function __construct() {
		// Set the default template
		$this->template = $GLOBALS['CONFIG']['main_template'];
		
		// Define variables for all views here
		$this->data['menu'] = ["Home"=>"/company/index","About"=>"/company/about","Products"=>"/company/products","Store"=>"/company/store","My Account" => "/myAccount/index"];
	}
	
	/**
	* Destructor Function.
	* 
	* Render the page.
	*
	* 
	* @author Kenny Hefner
	* @see viewController::render()
	*/
	public function __destruct() {
		$this->render();
	}
	
	/**
	* Setter Function.
	* 
	* Allows access to the private $data array
	*
	* @param string $name 
	* @param mixed $value 
	* 
	* @author Kenny Hefner
	*/
	public function __set($name,$value) {
		$this->data[$name] = $value;
	}
	
	/**
	* Getter Function.
	* 
	* Allows access to the private $data array
	*
	* @param string $name 
	* @return mixed
	* @author Kenny Hefner
	*/
	public function &__get($name) {
		return $this->data[$name];
	}
	
	/**
	* Empty or Isset function for object properties
	*/
	public function __isset($name) {
		return isset($this->data[$name]);
	}
	
	/**
	* Switch which template will be used in the render function
	*
	* @param string $template
	* 
	* @author Kenny Hefner
	* @see render()
	**/
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	/**
	* Render page.
	* 
	* The Renderer will link to the appropriate view file and capture the output
	* for use in the site template. 
	*
	* 
	* @author Kenny Hefner
	*/
	public function render() {
	
		// if the view file exists... we render the view AND the template using OutputBuffering,
		// ELSE, the action controller simply echoes whatever its told to echo
		// as in the case of Ajax Actions
		
		if(file_exists(APPATH.'modules/'.$GLOBALS['module'].'/view/'.$GLOBALS['action'].'.php')) {
			header("Content-Type: text/html; charset=utf-8");
			// required library scripts 
			//$this->requireScript('jquery.validate.min.js additional-methods.min.js jquery.form.js jqwidget.spinner.js jqwidget.systemMessage.js jqwidget.navigator.js jquery.tipsy.js grafton.js');
			
			$this->markup = "";
			
			ob_start();
			include('modules/'.$GLOBALS['module'].'/view/'.$GLOBALS['action'].'.php');
			$this->markup .= ob_get_contents();	
			ob_end_clean();
			
			ob_start();
			
			if($GLOBALS['module'] == "thickbox") {
				$this->setTemplate('thickbox');
			} else if (substr($GLOBALS['action'], 0, 4) == "ajax" && $this->template != "jsonp") {
				$this->setTemplate('ajax');
			}
			
			// die on false JSONP requests
			if (!empty($_REQUEST['callback']) && $this->template != "jsonp") {
				die('Error: invalid JSONP Request.');
			}
			
			if (empty($this->template) ) {
				logger(LOG_ERR,'Failed to access template:: '.$this->template);
				die('Error: no template defined');
			}
			
			if (empty($GLOBALS['CONFIG']['templates'][$this->template])) {
				// Config is not set, check for template
				$template_file = APPATH.'templates/'.$this->template.'/template.html';
				if (file_exists($template_file)) {
					$GLOBALS['CONFIG']['templates'][$this->template] = $template_file;
				} else {
					logger(LOG_ERR,'Template config not found:: '.$this->template);
					die('Error: no template file');
				}
			}
			
			if(file_exists($GLOBALS['CONFIG']['templates'][$this->template])) {
				require($GLOBALS['CONFIG']['templates'][$this->template]);
			} else {
				exit("Config Error: Template");
			}
			
			$this->markup = ob_get_contents();
			
			ob_end_clean();
			
			echo $this->markup;
		
		} else {
			if (!headers_sent()) {
				header("Content-Type: application/json; charset=utf-8");
			}
		}
	}
	
	/**
	* Push one or more script files onto the stack for inclusion.
	* 
	* This method accepts a space-delimited list of script file names.
	*
	* @param string $script list of script names to include.
	* 
	* @author Kenny Hefner
	* @see scripts()
	*/
	public function requireScript($script) {
		foreach(explode(" ", $script) as $value) {
			if (!in_array($value, $this->scripts)) {
				array_push($this->scripts, $value);
			}
		}
	}
	
	/**
	* Push one or more CSS files onto the stack for inclusion.
	* 
	* This method accepts a space-delimited list of CSS file names.
	*
	* @param string $style list of style names to include.
	* 
	* @author Kenny Hefner
	* @see styles()
	*/
	public function requireStyle($style) {
		foreach(explode(" ", $style) as $value) {
			if (!in_array($value, $this->styles)) {
				array_push($this->styles, $value);
			}
		}
	}
	
	/**
	* Output the script links.
	* 
	* Given the list of script files defined in the {@link requireScript() script require function },
	* generate the HTML tags to include them.
	*
	* 
	* @author Kenny Hefner
	*/
	public function scripts() {
		foreach($this->scripts as $value) {
			if (substr($value, 0, 1) == "/" || substr($value,0,4) == "http") {
				echo '<script type="text/javascript" src="'.$value.((stristr($value,"?"))?"&":"?").'av='.$GLOBALS['CONFIG']['version_number'].'"></script>
				';
			}
			else {
				echo '<script type="text/javascript" src="/scripts/'.$value.((stristr($value,"?"))?"&":"?").'av='.$GLOBALS['CONFIG']['version_number'].'"></script>
				';
			}
		}
	}
	
	/**
	* Output the style links.
	* 
	* Given the list of CSS files defined in the {@link requireStyle() style require function },
	* generate the HTML tags to include them.
	*
	* 
	* @author Kenny Hefner
	*/
	public function styles() {
		foreach($this->styles as $value) {
			if (substr($value, 0, 1) == "/" || substr($value,0,4) == "http") {
				echo '<link rel="stylesheet" href="'.$value.'?av='.$GLOBALS['CONFIG']['version_number'].'" type="text/css" media="screen" charset="utf-8" />
				';
			}
			else {
				echo '<link rel="stylesheet" href="/styles/'.$value.'?av='.$GLOBALS['CONFIG']['version_number'].'" type="text/css" media="screen" charset="utf-8" />
				';
			}
		}
	}
	
} // end class
