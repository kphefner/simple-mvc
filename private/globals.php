<?php
/**
 * global.php
 * 
 * Create system-wide utility constants and functions.
 *
 * @author Kenny Hefner
 * @version 1.0
 * @copyright AdFX, Inc., 19 Sep, 2020
 * @package globals
 */

/**
 * Absolute link to the framework application root
 * 
 * @name APPATH
 */
define('APPATH',$_SERVER['DOCUMENT_ROOT'].'/../private/');
/**
 * Absolute link to the modules root, where the controllers and views are housed.
 * 
 * @name MODPATH
 */
define('MODSPATH',$_SERVER['DOCUMENT_ROOT'].'/../private/modules/');
/**
 * Absolute path to the library root, where utility classes and methods are housed.
 * 
 * @name LIBPATH
 */
define('LIBPATH',$_SERVER['DOCUMENT_ROOT'].'/../libraries/');


/**
 * In addition to PHP error and access logs, we use this function to log any noteworthy
 * occurrence in our code.
 *
 * NOTE php.ini MUST have error_log = syslog
 *
 * @param variable data - could be string,array or object
 * @param int level - The predefined constant for syslog levels or the
 * 
 */
function logger($data,$method=null) {
  
  $trace = debug_backtrace(/*DEBUG_BACKTRACE_IGNORE_ARGS*/);
    $breadcrumb = '';
    if(!empty($method)) {
        $breadcrumb .= $method."|";
    }
  
  if(is_array($data) || is_object($data)) {
    ob_start();
    var_dump($data);
    $data = ob_get_contents();
    ob_end_clean();
  }
  foreach ($trace as $frame) {
      if (empty($frame['file'])) {
          break;
      }
      $breadcrumb .= basename($frame['file']).':'.$frame['line'].'|';
      if (strlen($breadcrumb) > 1000) {
          break;
      }
  }

  //openlog('simplemvc', LOG_NDELAY, LOG_LOCAL2);
  //syslog($level,session_id().' | '.$breadcrumb.$data);
  //closelog();
  error_log($breadcrumb.$data."\n", 3, "/var/tmp/simplemvc.log");
}

function format_phone($sPhone){
    if(strlen(preg_replace('/[^0-9]/', '', $sPhone)) != 10) return $sPhone;
  $sPhone = preg_replace('/[^0-9]/', '', $sPhone);    
  return '('.substr($sPhone,0,3).') '.substr($sPhone,3,3).'-'.substr($sPhone, 6);
} // end format phone

//RETRIEVE ALL US TIMEZONES
function get_us_timezones(){
  return DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'US');
}

// Format time to user's timezone preference
function format_user_time($usertimezone,$timestring,$format="n/j/y g:i a T") {
  //todo: check if timestring is GMT, if not add +00 to end
  $unixtimestamp = strtotime($timestring);
  $do = new DateTime('@'.$unixtimestamp);
  $dtzo = new DateTimeZone($usertimezone);
  $do->setTimezone($dtzo);
  return $do->format($format);
}

// Format time to user's timezone preference - with seconds
function format_user_time_sec($usertimezone,$timestring,$format="n/j/y g:i:s a T") {
  //todo: check if timestring is GMT, if not add +00 to end
  $unixtimestamp = strtotime($timestring);
  $do = new DateTime('@'.$unixtimestamp);
  $dtzo = new DateTimeZone($usertimezone);
  $do->setTimezone($dtzo);
  return $do->format($format);
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
  $str = '';
  $max = mb_strlen($keyspace, '8bit') - 1;
  for ($i = 0; $i < $length; ++$i) {
      $str .= $keyspace[random_int(0, $max)];
  }
  return $str;
}

// remove line break characters -  \r \n %0a %0A %0d %0D 
function remove_line_break($string) {
    return preg_replace( "/\r|\n|\r\n|%0a|%0A|%0d|%0D/", "", $string);
}

$nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
                   "eight", "nine", "ten", "eleven", "twelve", "thirteen",
                   "fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
                   "nineteen", "twenty", 30 => "thirty", 40 => "forty",
                   50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
                   90 => "ninety" );

function int_to_words($x) {
	global $nwords;
	
	if(!is_numeric($x))
	  $w = '#';
	else if(fmod($x, 1) != 0)
	  $w = '#';
	else {
	  if($x < 0) {
	     $w = 'minus ';
	     $x = -$x;
	  } else
	     $w = '';
	  // ... now $x is a non-negative integer.
	
	  if($x < 21)   // 0 to 20
	     $w .= $nwords[$x];
	  else if($x < 100) {   // 21 to 99
	     $w .= $nwords[10 * floor($x/10)];
	     $r = fmod($x, 10);
	     if($r > 0)
	        $w .= '-'. $nwords[$r];
	  } else if($x < 1000) {   // 100 to 999
	     $w .= $nwords[floor($x/100)] .' hundred';
	     $r = fmod($x, 100);
	     if($r > 0)
	        $w .= ' and '. int_to_words($r);
	  } else if($x < 1000000) {   // 1000 to 999999
	     $w .= int_to_words(floor($x/1000)) .' thousand';
	     $r = fmod($x, 1000);
	     if($r > 0) {
	        $w .= ' ';
	        if($r < 100)
	           $w .= 'and ';
	        $w .= int_to_words($r);
	     }
	  } else {    //  millions
	     $w .= int_to_words(floor($x/1000000)) .' million';
	     $r = fmod($x, 1000000);
	     if($r > 0) {
	        $w .= ' ';
	        if($r < 100)
	           $word .= 'and ';
	        $w .= int_to_words($r);
	     }
	  }
	}
	return $w;
}

?>