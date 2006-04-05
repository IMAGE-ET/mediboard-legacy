<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once("Archive/Tar.php");

$filepath = "modules/dPpatients/INSEE/insee.tar.gz";
$filedir = "tmp/insee";

$tarball = new Archive_Tar($filepath);
if ($tarball->extract($filedir)) {
  $nbFiles = @count($tarball->listContent());
  echo "<strong>Done</strong> : extraction de $nbFiles fichiers<br />";
} else {
  echo "Erreur, impossible d'extraire l'archive<br />";
  exit(0);
}

$base = $AppUI->cfg["baseINSEE"];

do_connect($base);

$sql = "DROP TABLE IF EXISTS `communes_france`";
db_exec($sql, $base);
$sql = "CREATE TABLE `communes_france` (
          `commune` varchar(25) NOT NULL default '',
          `code_postal` varchar(5) NOT NULL default '',
          `departement` varchar(25) NOT NULL default '',
          `INSEE` varchar(5) NOT NULL default '',
          PRIMARY KEY  (`INSEE`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Table des informations sur les communes françaises';";
db_exec($sql, $base);
$sql = "LOAD DATA LOCAL INFILE '".$AppUI->cfg["root_dir"]."/$filedir/insee.csv'" .
    "\nINTO TABLE `communes_france`" .
    "\nFIELDS TERMINATED BY ';'" .
    "\nLINES TERMINATED BY '\r\n'";
db_exec($sql, $base);
if(!($msg = db_error($base)))
  echo "import effectué avec succès<br />";
else
  echo "<strong>une erreur s'est produite</strong> : $msg<br />";