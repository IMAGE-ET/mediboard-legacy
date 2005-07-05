<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

// !! Attention, r�gression importante si ajout de type de paiement

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

// R�cup�ration des param�tres
$today = date("Y-m-d");
$debut = mbGetValueFromGetOrSession("debut_rapport", date("Ymd"));
$dayd = substr($debut, 6, 2);
$monthd = substr($debut, 4, 2);
$yeard = substr($debut, 0, 4);
$debutsql = $yeard."-".$monthd."-".$dayd;
$fin = mbGetValueFromGetOrSession("fin_rapport", date("Ymd"));
$dayf = substr($fin, 6, 2);
$monthf = substr($fin, 4, 2);
$yearf = substr($fin, 0, 4);
$finsql = $yearf."-".$monthf."-".$dayf;
$titre = "Rapport du ".strftime("%d/%m/%Y", mktime(0, 0, 0, $monthd, $dayd, $yeard));
if ($debut != $fin)
  $titre .= " au ".strftime("%d/%m/%Y", mktime(0, 0, 0, $monthf, $dayf, $yearf));
$chir = mbGetValueFromGetOrSession("chir", 0);
$chirSel = new CMediusers;
$chirSel->load($chir);
$etat = mbGetValueFromGetOrSession("etat", 0);
$type = mbGetValueFromGetOrSession("type", 0);
$aff = mbGetValueFromGetOrSession("aff", 1);

// Requ�te sur les plages de consultation consid�r�es
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

// On charge les r�f�rences des consultations qui nous interessent
$total["cheque"]["valeur"] = 0;
$total["CB"]["valeur"] = 0;
$total["especes"]["valeur"] = 0;
$total["tiers"]["valeur"] = 0;
$total["autre"]["valeur"] = 0;
$total["cheque"]["nombre"] = 0;
$total["CB"]["nombre"] = 0;
$total["especes"]["nombre"] = 0;
$total["tiers"]["nombre"] = 0;
$total["autre"]["nombre"] = 0;
$total["secteur1"] = 0;
$total["secteur2"] = 0;
$total["tarif"] = 0;
$total["nombre"] = 0;
foreach($listPlage as $key => $value) {
  $listPlage[$key]->loadRefsFwd();
  //unset($listPlage[$key]->_ref_consultations);
  $where = array();
  $where["plageconsult_id"] = "= '".$value->plageconsult_id."'";
  $where["chrono"] = ">= '".CC_TERMINE."'";
  $where["annule"] = "= 0";
  $where[] = "tarif IS NOT NULL AND tarif <> ''";
  if($etat != -1)
    $where["paye"] = "= '$etat'";
  if($etat == 0)
    $where[] = "(secteur1 + secteur2) != 0";
  $where["secteur1"] = "IS NOT NULL";
  if($type)
    $where["type_tarif"] = "= '$type'";
  $listConsult = new CConsultation;
  $listConsult = $listConsult->loadList($where, "heure");
  $listPlage[$key]->_ref_consultations = $listConsult;
  $listPlage[$key]->total1 = 0;
  $listPlage[$key]->total2 = 0;
  foreach($listPlage[$key]->_ref_consultations as $key2 => $value2) {
    $listPlage[$key]->_ref_consultations[$key2]->loadRefs();
    if($etat == -1 && $listPlage[$key]->_ref_consultations[$key2]->paye){
      $listPlage[$key]->total1 += $value2->secteur1;
      $listPlage[$key]->total2 += $value2->secteur2;
      $total[$value2->type_tarif]["valeur"] += $value2->secteur1 + $value2->secteur2;
      $total[$value2->type_tarif]["nombre"]++;
    }
    elseif($etat != -1){
      $listPlage[$key]->total1 += $value2->secteur1;
      $listPlage[$key]->total2 += $value2->secteur2;
      if($value2->type_tarif) {
        $total[$value2->type_tarif]["valeur"] += $value2->secteur1 + $value2->secteur2;
        $total[$value2->type_tarif]["nombre"]++;
      }
    }
  }
  $total["secteur1"] += $listPlage[$key]->total1;
  $total["secteur2"] += $listPlage[$key]->total2;
  $total["tarif"] += $listPlage[$key]->total1 + $listPlage[$key]->total2;
  $total["nombre"] += count($listPlage[$key]->_ref_consultations);
  if(!count($listPlage[$key]->_ref_consultations))
    unset($listPlage[$key]);
}

// Cr�ation du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('today', $today);
$smarty->assign('titre', $titre);
$smarty->assign('aff', $aff);
$smarty->assign('etat', $etat);
$smarty->assign('type', $type);
$smarty->assign('chirSel', $chirSel);
$smarty->assign('listPlage', $listPlage);
$smarty->assign('total', $total);

$smarty->display('print_rapport.tpl');

?>