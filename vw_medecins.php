<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPpatients', 'medecin') );

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$medecin_id = mbGetValueFromGetOrSession("medecin_id");

// Récuperation du medecin sélectionné
$medecin = new CMedecin();
if(dPgetParam($_GET, "new", 0)) {
  $medecin->load(NULL);
  mbSetValueToSession("medecin_id", null);
}
else
  $medecin->load($medecin_id);
$medecin->loadRefs();

// Récuperation des medecins recherchés
$medecin_nom    = mbGetValueFromGetOrSession("medecin_nom"   );
$medecin_prenom = mbGetValueFromGetOrSession("medecin_prenom");

if ($medecin_nom || $medecin_prenom) {
  $sql = "SELECT * 
    FROM medecin
    WHERE nom LIKE '$medecin_nom%'
    AND prenom LIKE '$medecin_prenom%'
    ORDER BY nom, prenom";
  $medecins = db_loadlist($sql);
}

// Création du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;

$smarty->assign('nom', $medecin_nom);
$smarty->assign('prenom', $medecin_prenom);
$smarty->assign('medecins', $medecins);
$smarty->assign('medecin', $medecin);

$smarty->display('vw_medecins.tpl');
?>