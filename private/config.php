<?php
/**
 * config.php
 *
 * Populate common application variables.
 *
 * @author Kenny Hefner
 * @version 1.0
 * @copyright AdFX, Inc., 19 Sep, 2020
 * @package globals
 */

// false For Productions Servers
ini_set("display_errors",true);
error_reporting(E_ALL);

// probably want to get this from account if logged in
date_default_timezone_set('America/New_York');

$CONFIG = array();

// Object Server
$CONFIG['obj'] = "https://localapi";

// Portal Server
$CONFIG['portal_url'] = 'localhost';

// javascript caching control
$CONFIG['version_number'] = '1.0';


// available template
$CONFIG['templates']['ajax'] = APPATH.'templates/ajax/template.html';
$CONFIG['templates']['default'] = APPATH.'templates/default/template.html';

// define which one to use as the main template
$CONFIG['main_template'] = "default";

// setup some usable global vars
$CONFIG['admin_name'] = "Admin";
$CONFIG['admin_email'] = "support@domain.com";
$CONFIG['admin_phone'] = "777 555-1212";
$CONFIG['webmaster_email'] = "webmaster@domain.com";