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

$dialog = dPgetParam($_GET, "dialog", 0);
$type = mbGetValueFromGetOrSession("type", '_traitant');
$medecin_id = mbGetValueFromGetOrSession("medecin_id");

// Récuperation du medecin sélectionné
$medecin = new CMedecin();
if(dPgetParam($_GET, "new", 0)) {
  $medecin->load(NULL);
  mbSetValueToSession("medecin_id", null);
}
else {
  $medecin->load($medecin_id);
  //$medecin->loadRefs();
}

// Récuperation des medecins recherchés
$medecin_nom    = mbGetValueFromGetOrSession("medecin_nom"   );
$medecin_prenom = mbGetValueFromGetOrSession("medecin_prenom");

if ($medecin_nom || $medecin_prenom) {
  $sql = "SELECT * 
    FROM medecin
    WHERE 1 ";
  if ($medecin_nom)
    $sql .= "AND nom LIKE '$medecin_nom%'";
  if ($medecin_prenom)
    $sql .= "AND prenom LIKE '$medecin_prenom%'";
  $sql .= "ORDER BY nom, prenom LIMIt 0, 100";
  $medecins = db_loadlist($sql);
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('dialog', $dialog);
$smarty->assign('type', $type);
$smarty->assign('nom', $medecin_nom);
$smarty->assign('prenom', $medecin_prenom);
$smarty->assign('medecins', $medecins);
$smarty->assign('medecin', $medecin);

$smarty->display('vw_medecins.tpl');
?>