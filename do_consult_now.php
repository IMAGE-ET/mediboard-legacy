<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI;

require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPpatients', 'patients') );
require_once( $AppUI->getModuleClass('dPcabinet', 'plageconsult') );
require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

// L'utilisateur est-il un chirurgien
$mediuser = new CMediusers;
$mediuser->load($AppUI->user_id);
if ($mediuser->isPraticien()) {
  $chir = $mediuser;
} else {
  $msg = "Vous n'avez pas les droits suffisants";
  $AppUI->redirect();
}

$day_now = strftime("%Y-%m-%d");
$hour_now = strftime("%H:%M:00");
$debut = strftime("%H:00:00");

//$day_now = "2005-06-21";
//$hour_now = "09:45:00";

$plage = new CPlageConsult();
$where = array();
$where["chir_id"] = "= '$chir->user_id'";
$where["date"] = "= '$day_now'";
$where["debut"] = "<= '$hour_now'";
$where["fin"] = "> '$hour_now'";
$plage->loadObject($where);
if(!$plage->plageconsult_id) {
  $plage->chir_id = $chir->user_id;
  $plage->date = $day_now;
  $plage->freq = "00:15:00";
  $plage->debut = $debut;
  $plage->fin = mbTime("+1 HOUR", $debut);
  $plage->libelle = "automatique";
  $plage->store();
}

$consult = new CConsultation;
$consult->plageconsult_id = $plage->plageconsult_id;
$consult->patient_id = $_POST["patient_id"];
$consult->heure = $hour_now;
$consult->duree = 1;
$consult->chrono = CC_PATIENT_ARRIVE;
$consult->motif = "Consultation immédiate";
$consult->store();

$AppUI->redirect("m=dPcabinet&tab=edit_consultation&selConsult=$consult->consultation_id");

?>