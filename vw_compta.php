<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('mediusers', 'functions') );
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPcabinet', 'tarif') );

// Réparation de la base de données due à des dates de paiement non sauvées
$sql = "SELECT consultation.consultation_id as consult, plageconsult.date
        FROM consultation
        LEFT JOIN plageconsult ON consultation.plageconsult_id = plageconsult.plageconsult_id
        WHERE date_paiement = '0000-00-00'
        AND paye =1
        AND date >= '2005-06-05'
        AND date <= '2005-07-22'";
$badRecords = db_loadlist($sql);
$n = count($badRecords);
$i = 0;
foreach($badRecords as $key => $value) {
  $sql = "UPDATE consultation
          SET date_paiement = '".$value["date"]."'
          WHERE consultation_id = '".$value["consult"]."'";
  if(db_exec($sql))
    $i++;
}
if($n)
  echo "$n mauvais enregistrements, $i enregistrements corrigés<br />";

$deb = mbDate();
$fin = mbDate("+ 0 day");

// Edite t'on un tarif ?
$tarif_id = mbGetValueFromGetOrSession("tarif_id", null);
$tarif = new CTarif;
$tarif->load($tarif_id);

// L'utilisateur est-il praticien ?
$mediuser = new CMediusers();
$mediuser->load($AppUI->user_id);
$user = $mediuser->createUser();

// Liste des tarifs du chirurgien
if ($mediuser->isPraticien()) {
  $where = array();
  $where["function_id"] = "= 0";
  $where["chir_id"] = "= '$user->user_id'";
  $listeTarifsChir = new CTarif();
  $listeTarifsChir = $listeTarifsChir->loadList($where);
}
else
  $listeTarifsChir = null;

// Liste des tarifs de la spécialité
$where = array();
$where["chir_id"] = "= 0";
$where["function_id"] = "= '$mediuser->function_id'";
$listeTarifsSpe = new CTarif();
$listeTarifsSpe = $listeTarifsSpe->loadList($where);

// Liste des praticiens du cabinet -> on ne doit pas voir les autres...
if($user->user_type == 'Administrator' || $user->user_type == 'Secrétaire') {
  $listPrat = new CMediusers();
  $listPrat = $listPrat->loadPraticiens(PERM_READ);
}
else
  $listPrat[0] = $user;

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('deb', $deb);
$smarty->assign('fin', $fin);
$smarty->assign('mediuser', $mediuser);
$smarty->assign('listeTarifsChir', $listeTarifsChir);
$smarty->assign('listeTarifsSpe', $listeTarifsSpe);
$smarty->assign('tarif', $tarif);
$smarty->assign('listPrat', $listPrat);

$smarty->display('vw_compta.tpl');

