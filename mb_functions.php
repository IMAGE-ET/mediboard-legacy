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

//  echo "<br />_GET[$valName] = " . $_GET[$valName];
//  echo "<br />_SESSION[$m][$valName] = " . $_SESSION[$m][$valName];

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

?>