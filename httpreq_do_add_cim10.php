<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPccam
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once("Archive/Tar.php");

set_time_limit(360);

$filepath = "modules/dPcim10/base/cim10.tar.gz";
$filedir = "tmp/cim10";

$tarball = new Archive_Tar($filepath);
if ($tarball->extract($filedir)) {
  $nbFiles = @count($tarball->listContent());
  echo "<strong>Done</strong> : extraction de $nbFiles fichier(s)<br />";
} else {
  echo "Erreur, impossible d'extraire l'archive<br />";
  exit(0);
}

$base = $AppUI->cfg["baseCIM10"];

do_connect($base);

$path = "tmp/cim10/cim10.sql";
$sqlLines = file($path);
$query = "";
foreach($sqlLines as $lineNumber => $sqlLine) {
  $sqlLine = trim($sqlLine);
  if (($sqlLine != "") && (substr($sqlLine, 0, 2) != "--") && (substr($sqlLine, 0, 1) != "#")) {
    $query .= $sqlLine;
    if (preg_match("/;\s*$/", $sqlLine)) {
      db_exec($query, $base);
      if($msg = db_error($base)) {
        echo "<strong>une erreur s'est produite</strong> : $msg<br />";
        exit(0);
      }
      $query = "";
    }
  }
}

echo "import effectu� avec succ�s<br />";

?>