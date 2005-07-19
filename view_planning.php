<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getModuleClass('dPbloc', 'plagesop'));
require_once( $AppUI->getModuleClass('dPhospi', 'affectation'));

$deb = mbGetValueFromGetOrSession("deb", mbDate());
$fin = mbGetValueFromGetOrSession("fin", mbDate());

$vide = dPgetParam( $_GET, 'vide', false );
$type = dPgetParam( $_GET, 'type', 0 );
$chir = dPgetParam( $_GET, 'chir', 0 );
$spe = dPgetParam( $_GET, 'spe', 0);
$salle = dPgetParam( $_GET, 'salle', 0 );
$CCAM = dPgetParam( $_GET, 'CCAM', '' );

//On sort les plages opératoires
//  Chir - Salle - Horaires

$plagesop = new CPlageOp;

$where = array();
$where["date"] =  "BETWEEN '$deb' AND '$fin'";

$ljoin = array();
$ljoin["users"] = "plagesop.id_chir = users.user_username";
$ljoin["sallesbloc"] = "plagesop.id_salle = sallesbloc.id";

$order = array();
$order[] = "date";
$order[] = "id_salle";
$order[] = "debut";

// En fonction du chirurgien
if ($chir) {
  $sql = "SELECT user_username
           FROM users
           WHERE user_id = '$chir'";
  $chir_id = db_loadlist($sql);
  $where["id_chir"] = "= '".$chir_id[0]["user_username"]."'";
}

// En fonction du cabinet
if ($spe) {
  $sql = "SELECT user_username " .
  		"FROM users, users_mediboard " .
  		"WHERE users.user_id = users_mediboard.user_id " .
  		"AND users_mediboard.function_id = '$spe'";
  $listChirs = db_loadlist($sql);
  $inSpe = array();
  foreach($listChirs as $key =>$value)
    $inSpe[] = "'".$value["user_username"]."'";
  $where["id_chir"] = "IN(".implode(", ", $inSpe).")";
}

// En fonction de la salle
if ($salle) {
  $where["id_salle"] = "= '$salle'";
}

$plagesop = $plagesop->loadList($where, $order, null, null, $ljoin);

// Operations de chaque plage
foreach($plagesop as $key=>$value) {
  $plagesop[$key]->loadRefsFwd();
  $listOp = new COperation;
  $where = array();
  $where["plageop_id"] = "= '".$value->id."'";
  switch ($type) {
    case "1" : $where["rank"] = "!= '0'"; break;
    case "2" : $where["rank"] = "= '0'"; break;
  }
  
  if ($CCAM) {
    $where["CCAM_code"] = "LIKE '$CCAM%'";
  }
  
  $order = "operations.rank";
  $listOp = $listOp->loadList($where, $order);
  if((sizeof($listOp) == 0) && ($vide == "false"))
    unset($plagesop[$key]);
  else {
    foreach($listOp as $key2 => $currOp) {
      $listOp[$key2]->loadRefsFwd();
      $listOp[$key2]->_first_affectation = $listOp[$key2]->getFirstAffectation();
      if($listOp[$key2]->_first_affectation) {
        $listOp[$key2]->_first_affectation->loadRefsFwd();
        $listOp[$key2]->_first_affectation->_ref_lit->loadRefsFwd();
        $listOp[$key2]->_first_affectation->_ref_lit->_ref_chambre->loadRefsFwd();
      }
    }
    $plagesop[$key]->_ref_operations = $listOp;
  }
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('deb', $deb);
$smarty->assign('fin', $fin);
$smarty->assign('plagesop', $plagesop);

$smarty->display('view_planning.tpl');

?>