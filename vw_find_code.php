<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPccam
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once("lib/smarty/Smarty.class.php");

//Keywords initialisation
if(!isset($_SESSION["findacte"]["clefs"]))
{
  $_SESSION["findacte"]["clefs"] = "";
}
if(dPgetParam($_GET, "clefs", "noclefs") != "noclefs")
{
  $_SESSION["findacte"]["clefs"] = dPgetParam($_GET, "clefs", "");
}
$clefs = $_SESSION["findacte"]["clefs"];

//access method initialisation
if(!isset($_SESSION["findacte"]["selacces"]))
{
  $_SESSION["findacte"]["selacces"] = "0";
}
if(dPgetParam($_GET, "selacces", "noacces") != "noacces")
{
  $_SESSION["findacte"]["selacces"] = dPgetParam($_GET, "selacces", "0");
}
$selacces = $_SESSION["findacte"]["selacces"];

//first topography initialisation
if(!isset($_SESSION["findacte"]["seltopo1"]))
{
  $_SESSION["findacte"]["seltopo1"] = "0";
}
if(dPgetParam($_GET, "seltopo1", "notopo1") != "notopo1")
{
  $_SESSION["findacte"]["seltopo1"] = dPgetParam($_GET, "seltopo1", "0");
}
$seltopo1 = $_SESSION["findacte"]["seltopo1"];

//second topography initialisation
if(!isset($_SESSION["findacte"]["seltopo2"]))
{
  $_SESSION["findacte"]["seltopo2"] = "0";
}
if(dPgetParam($_GET, "seltopo2", "notopo2") != "notopo2")
{
  $_SESSION["findacte"]["seltopo2"] = dPgetParam($_GET, "seltopo2", "0");
}
$seltopo2 = $_SESSION["findacte"]["seltopo2"];

//code initialisation
if(!isset($_SESSION["findacte"]["code"]))
{
  $_SESSION["findacte"]["code"] = "";
}
if(dPgetParam($_GET, "code", "nocode") != "nocode")
{
  $_SESSION["findacte"]["code"] = dPgetParam($_GET, "code", "");
}
$code = $_SESSION["findacte"]["code"];

//Connection a la base de donnees pour la recherche
$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
  or die("Could not connect");
mysql_select_db("ccam")
  or die("Could not select database");

//Création de la requête
//$query = "select CODE, LIBELLELONG, CODEACTE, TEXTE from ACTES, NOTES where 0";
$query = "select CODE, LIBELLELONG from ACTES where 0";

//Si un autre élément est remplis
if($code != "" || $clefs != "" || $selacces != "0" || $seltopo1 != "0")
{
  $query .= " or (1";
  //On fait la recherche sur le code
  if($code != "")
  {
	$query .= " and CODE like '" . addslashes($code) . "%'";
  }
  //On explode les mots clefs
  if($clefs != "")
  {
    $listeClefs = explode(" ", $clefs);
    foreach($listeClefs as $key => $value)
    {
      $query .= " and (LIBELLELONG like '%" .  addslashes($value) . "%')";
	  //$query .= " or (CODEACTE = CODE and TEXTE like '%" .  addslashes($value) . "%'))";
    }
  }
  //On tris selon les voies d'accès
  if($selacces != "0")
  {
    $query .= " and CODE like '___" . $selacces . "___'";
  }
  //On tris selon les topologies de niveau 1 ou 2
  if($seltopo1 != "0")
  {
    if($seltopo2 != "0")
    {
      $query .= " and CODE like '" . $seltopo2 . "_____'";
    }
    else
    {
      $query .= " and CODE like '" . $seltopo1 . "______'";
    }
  }
  $query .= ")";
}
$query .= " order by CODE limit 0 , 100";

//Codes correspondants à la requete
$result = mysql_query($query);
$i = 0;
while($row = mysql_fetch_array($result))
{
  $codes[$i]["code"] = $row['CODE'];
  $codes[$i]["texte"] = $row['LIBELLELONG'];
  $i++;
}
$numcodes = $i;

//On récupère les voies d'accès
$query = "select * from ACCES1";
$result = mysql_query($query);
$acces[0]["code"] = "0";
$acces[0]["texte"] = "Selection d'une voie d'accès";
$i = 1;
while($row = mysql_fetch_array($result))
{
  $acces[$i]["code"] = $row['CODE'];
  $acces[$i]["texte"] = $row['ACCES'];
  $i++;
}

//On récupère les appareils : topographie1
$query = "select * from TOPOGRAPHIE1";
$result = mysql_query($query);

$topo1[0]["code"] = "0";
$topo1[0]["texte"] = "Selection de l'appareil concerné";
$i = 1;
while($row = mysql_fetch_array($result))
{
  $topo1[$i]["code"] = $row['CODE'];
  $topo1[$i]["texte"] = $row['LIBELLE'];
  $i++;
}

//On récupère les systèmes correspondants à l'appareil : topographie2
$query = "select * from TOPOGRAPHIE2 where PERE = '" . $seltopo1 . "'";
$result = mysql_query($query);

$topo2[0]["code"] = "0";
$topo2[0]["texte"] = "Selection du système concerné";
$i = 1;
while($row = mysql_fetch_array($result))
{
  $topo2[$i]["code"] = $row['CODE'];
  $topo2[$i]["texte"] = $row['LIBELLE'];
  $i++;
}

mysql_close($mysql);

//Creation de l'objet smarty
$smarty = new Smarty();

//initialisation des repertoires
$smarty->template_dir = "modules/$m/templates/";
$smarty->compile_dir = "modules/$m/templates_c/";
$smarty->config_dir = "modules/$m/configs/";
$smarty->cache_dir = "modules/$m/cache/";

//Mapping des variables
$smarty->assign('canEdit', $canEdit);
$smarty->assign('clefs', $clefs);
$smarty->assign('selacces', $selacces);
$smarty->assign('seltopo1', $seltopo1);
$smarty->assign('seltopo2', $seltopo2);
$smarty->assign('code', $code);
$smarty->assign('acces', $acces);
$smarty->assign('topo1', $topo1);
$smarty->assign('topo2', $topo2);
$smarty->assign('codes', $codes);
$smarty->assign('numcodes', $numcodes);

//Affichage de la page
$smarty->display('vw_find_code.tpl');

?>