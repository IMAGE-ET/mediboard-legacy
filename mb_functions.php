<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage Style
 * @version $Revision$
 * @author Thomas Despoix
 */

/**
 * Returns the value of a variable retreived it from HTTP Get, then from the session
 * Stores it in _SESSION in all cases, with at least a default value
 * @access public
 * @return any 
 **/
function mbGetValueFromGetOrSession($valName, $valDefault = NULL) {
  global $m;

  if (isset($_GET[$valName])) {
    $_SESSION[$m][$valName] = $_GET[$valName];
  }
  
  return dPgetParam($_SESSION[$m], $valName, $valDefault);
}

/**
 * Sets a value to the session. Very useful to nullify object ids after deletion
 * @todo -c make it accessable from do_aed_class.php (can't be used upt to now)
 * @access public
 * @return void
 **/
function mbSetValueToSession($valName, $value = NULL) {
  global $m;

  $_SESSION[$m][$valName] = $value;
}

/**
 * Traces variable using preformated text et varibale export
 * @return void 
 **/
function mbTrace($label, &$var, $die = false) {
  $export = var_export($var, true); 
  $export = htmlspecialchars($export);
  
  echo "<pre>$label: $export</pre>";

  if ($die) {
    die();
  }
}

/**
 * Transforms absolute or relative time into a given format
 * @return string: the transformed time 
 **/
function mbTranformTime($relative, $ref = null, $format) {
  $timestamp = $ref ? strtotime($ref) : time();
  $transtime = strtotime($relative, $timestamp);
  return strftime($format, $transtime);
}

/**
 * Transforms absolute or relative time into DB friendly DATETIME format
 * @return string: the transformed time 
 **/
function mbDateTime($relative, $ref = null) {
  return mbTranformTime($relative, $ref, "%Y-%m-%d %H:%M:%S");
}

/**
 * Transforms absolute or relative time into DB friendly DATE format
 * @return string: the transformed time 
 **/
function mbDate($relative, $ref = null) {
  return mbTranformTime($relative, $ref, "%Y-%m-%d");
}

/**
 * Transforms absolute or relative time into DB friendly TIME format
 * @return string: the transformed time 
 **/
function mbTime($relative, $ref = null) {
  return mbTranformTime($relative, $ref, "%H:%M:%S");
}


?>