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

$filepath = "modules/dPccam/base/ccamV2.tar.gz";
$filedir = "tmp/ccam";

$tarball = new Archive_Tar($filepath);
if ($tarball->extract($filedir)) {
  $nbFiles = @count($tarball->listContent());
  echo "<div class='message'>Extraction de $nbFiles fichier(s)</div>";
} else {
  echo "<div class='error'>Erreur, impossible d'extraire l'archive</div>";
  exit(0);
}

$base = $AppUI->cfg["baseCCAM"];

do_connect($base);

$path = "tmp/ccam/ccamV2.sql";
$sqlLines = file($path);
$query = "";
foreach($sqlLines as $lineNumber => $sqlLine) {
  $sqlLine = trim($sqlLine);
  if (($sqlLine != "") && (substr($sqlLine, 0, 2) != "--") && (substr($sqlLine, 0, 1) != "#")) {
    $query .= $sqlLine;
    if (preg_match("/;\s*$/", $sqlLine)) {
      db_exec($query, $base);
      if($msg = db_error($base)) {
        echo "<div class='error'>Une erreur s'est produite : $msg</div>";
        exit(0);
      }
      $query = "";
    }
  }
}

echo "<div class='message'>import effectu� avec succ�s</div>";

?>