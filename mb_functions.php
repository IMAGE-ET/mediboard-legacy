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
function mbTrace(&$var, $label = null, $die = false) {
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
function mbDate($relative = "+ 0 days", $ref = null) {
  return mbTranformTime($relative, $ref, "%Y-%m-%d");
}

/**
 * Transforms absolute or relative time into DB friendly TIME format
 * @return string: the transformed time 
 **/
function mbTime($relative, $ref = null) {
  return mbTranformTime($relative, $ref, "%H:%M:%S");
}

/**
 * Return the difference between two dates in days
 * @return int: number of days
 **/
function mbDaysRelative($from, $to) {
  $from = intval(strtotime($from) / 86400);
  $to   = intval(strtotime($to  ) / 86400);
  $days = $to - $from;
  return $days-1;
}

/**
 * Insert a CSV file into a mysql table 
 **/

function mbInsertCSV( $fileName, $tableName, $oldid = false )
{
    $file = fopen( $fileName, 'rw' );
    if(! $file) {
      echo "Fichier non trouvé<br>";
      return;
    }
    $k = 0;
    $reussite = 0;
    $echec = 0;
    $null = 0;
    
    //$contents = fread ($file, filesize ($fileName));
    //$content = str_replace(chr(10), " ", $content);
  
    while ( ! feof( $file ) )
    {
        $k++;
        $line = str_replace("NULL", "\"NULL\"", fgets( $file, 1024));
        $size = strlen($line)-3;
        $test1 = $line[$size] != "\"";
        $test2 = $line[$size-1] == "\\";
        $test3 = (! feof( $file ));
        $test = ($test1 || (!$test1 && $test2)) && $test3;
        while($test) {
          $line .= str_replace("NULL", "\"NULL\"", fgets( $file, 1024));
          $size = strlen($line)-3;
          $test1 = $line[$size] != "\"";
          $test2 = $line[$size-1] == "\\";
          $test3 = (! feof( $file ));
          $test = ($test1 || (!$test1 && $test2)) && $test3;
        }

        if ( strlen( $line ) > 2 )
        {
            $line = addslashes( $line );
            $line = str_replace ( "\\\";\\\"", "', '", $line );
            $line = str_replace ( "\\\"", "", $line );
            if($oldid)
              $requete = 'INSERT INTO '.$tableName.' VALUES ( \''.$line.'\', \'\' ) ';
            else
              $requete = 'INSERT INTO '.$tableName.' VALUES ( \''.$line.'\' ) ';
            if ( ! db_exec ( $requete ) ) {
                echo 'Erreur Ligne '.$k.' : '.mysql_error().'<br>'.$requete.'<br>';
                $echec++;
            }  else {
                //echo 'Ligne '.$k.' valide.<br>'.$requete.'<br>';
                $reussite++;
            }
        } else {
            //echo 'Ligne '.$k.' ignorée.<br>';
            $null++;
        }
    }

    echo '<p>Insertion du fichier '.$fileName.' terminé.</p>';
    echo '<p>'.$k.' lignes trouvées, '.$reussite.' enregistrées, ';
    echo $echec.' non conformes, '.$null.' ignorées.</p><hr>';

    fclose( $file );
}


?>