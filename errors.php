<?php /* $Id$ */

/**
 * @package Mediboard
 * @subpackage Style
 * @version $Revision$
 * @author Thomas Despoix
 */

error_reporting( E_ALL );
ini_set("error_log", "./mediboard.error");
ini_set("log_errors_max_len", "4M");
ini_set("log_errors", true);
ini_set("display_errors", $dPconfig["debug"]);

$divClasses = array (
  E_ERROR => "big-error",
  E_WARNING => "big-warning",
  E_NOTICE => "big-info",
  E_PARSE => "big-info",
  E_CORE_ERROR => "big-error",
  E_CORE_WARNING => "big-warning",
  E_COMPILE_ERROR => "big-error",
  E_COMPILE_WARNING => "big-warning",
  E_USER_ERROR => "big-error",
  E_USER_WARNING => "big-warning",
  E_USER_NOTICE => "big-info",
);

$errorTypes = array (
  E_ERROR => "Error",
  E_WARNING => "Warning",
  E_NOTICE => "Notice",
  E_PARSE => "Parse",
  E_CORE_ERROR => "Core error",
  E_CORE_WARNING => "Core warning",
  E_COMPILE_ERROR => "Compile error",
  E_COMPILE_WARNING => "Compile warning",
  E_USER_ERROR => "User error",
  E_USER_WARNING => "User warning",
  E_USER_NOTICE => "User notice",
);

// To be put in mbFonctions
function mbRelativePath($absPath) {
  global $dPconfig;
  $mbPath = $dPconfig["root_dir"];
  
  // Hack for MS Windows server
  $absPath = strtr($absPath, "\\", "/");
  
  assert(strpos($absPath, $mbPath) === 0);
  $relPath = substr($absPath, strlen($mbPath) + 1);
  return $relPath;
}

function errorHandler($errno, $errstr, $errfile, $errline) {
  global $divClasses;
  global $errorTypes;
  
  // Handles the @ case
  if (!error_reporting()) {
    return;
  }
   
  if (!array_key_exists($errno, $divClasses)) {
    return;
  }
  
  $date = date("Y-m-d H:i:s (T)");
  $divClass = @$divClasses[$errno];
  $errorType = @$errorTypes[$errno];
  
  echo "<div class='$divClass'>";
  echo "\n<strong>Type: </strong>$errorType";
  echo "\n<strong>Text: </strong>$errstr";
  echo "\n<strong>File: </strong>" . mbRelativePath($errfile);
  echo "\n<strong>Line: </strong>$errline";
  echo "<hr />";
  $contexts = debug_backtrace();
  array_shift($contexts);
  foreach($contexts as $context) {
    echo "\t<strong>Function: </strong>" . $context["function"];
    if (isset($context["class"])) {
      echo "\t<strong>Class: </strong>" . $context["class"];
    }
    echo "\t<strong>File: </strong>" . mbRelativePath($context["file"]);
    echo "\t<strong>Line: </strong>" . $context["line"];
    echo "<br />";
  }
  
  echo "</div>";
  
} 

set_error_handler("errorHandler");
?>
