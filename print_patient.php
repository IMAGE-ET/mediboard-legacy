<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPpatients
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPpatients', 'patients') );

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

// R�cup�ration des variables pass�es en GET
$patient_id = dPgetParam($_GET, "patient_id", 0);

//Cr�ation du patient
$patient = new CPatient();
$patient->load($patient_id);
$patient->loadRefs();

foreach($patient->_ref_operations as $key => $value) {
  $patient->_ref_operations[$key]->loadRefs();
}

// Cr�ation du template
require_once("classes/smartydp.class.php");
$smarty = new CSmartyDP;
$smarty->assign('patient', $patient);
$smarty->display('print_patient.tpl');

?>