<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

// Récupération des paramètres
$debut = dPgetParam($_GET, "debut", date("Ymd"));
$dayd = substr($debut, 6, 2);
$monthd = substr($debut, 4, 2);
$yeard = substr($debut, 0, 4);
$debutsql = $yeard."-".$monthd."-".$dayd;
$fin = dPgetParam($_GET, "fin", date("Ymd"));
$dayf = substr($fin, 6, 2);
$monthf = substr($fin, 4, 2);
$yearf = substr($fin, 0, 4);
$finsql = $yearf."-".$monthf."-".$dayf;
$titre = "Rapport du ".strftime("%d/%m/%Y", mktime(0, 0, 0, $monthd, $dayd, $yeard));
if ($debut != $fin)
  $titre .= " au ".strftime("%d/%m/%Y", mktime(0, 0, 0, $monthf, $dayf, $yearf));
$chir = dPgetParam($_GET, "chir", 0);
$chirSel = new CMediusers;
$chirSel->load($chir);
$etat = dPgetParam($_GET, "etat", 1);
$type = dPgetParam($_GET, "type", 0);
$aff = dPgetParam($_GET, "aff", 1);

// Requète sur les plages de consultation considérées
$where = array();
$where[] = "date >= '$debutsql'";
$where[] = "date <= '$finsql'";
if($chir)
  $where["chir_id"] = "= '$chir'";
else {
  $listPrat = new CMediusers();
  $listPrat = $listPrat->loadPraticiens(PERM_READ);
  $in = array();
  foreach($listPrat as $key => $value) {
    $in[] = "'$key'";
  }
  $in = implode(", ", $in);
  $where["chir_id"] = "IN ($in)";
}
$listPlage = new CPlageconsult;
$listPlage = $listPlage->loadList($where, "date, chir_id");

// On charge les références des consultations qui nous interessent
$total["tarif"] = 0;
$total["nombre"] = 0;
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefs();
  unset($listPlage[$key]->_ref_consultations);
  $where = array();
  $where["plageconsult_id"] = "= '".$value->plageconsult_id."'";
  $where["paye"] = "= '$etat'";
  $where["tarif"] = "IS NOT NULL";
  if($type)
    $where["type_tarif"] = "= '$type'";
  $listConsult = new CConsultation;
  $listConsult = $listConsult->loadList($where, "heure");
  $listPlage[$key]->_ref_consultations = $listConsult;
  $listPlage[$key]->total = 0;
  foreach($listPlage[$key]->_ref_consultations as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations[$key2]->loadRefs();
    $listPlage[$key]->total += $value2->tarif;
  }
  $total["tarif"] += $listPlage[$key]->total;
  $total["nombre"] += count($listPlage[$key]->_ref_consultations);
  if(!$listPlage[$key]->total)
    unset($listPlage[$key]);
}

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('titre', $titre);
$smarty->assign('aff', $aff);
$smarty->assign('etat', $etat);
$smarty->assign('type', $type);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('listPlage', $listPlage);
$smarty->assign('total', $total);

$smarty->display('print_rapport.tpl');

?>