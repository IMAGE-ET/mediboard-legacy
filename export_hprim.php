<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

if (!class_exists("DOMDocument")) {
  trigger_error("sorry, DOMDocument is needed");
  return;
}

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

require_once($AppUI->getModuleClass($m, "hprimxmlserveuractes"));

$mb_operation_id = dPgetParam($_POST, "mb_operation_id", 10559);

$mbOp = new COperation();
$doc = new CHPrimXMLServeurActes;

// Chargement de l'opération et génération du document
if ($mbOp->load($mb_operation_id)) {
  $mbOp->loadRefs();
  
  $sc_patient_id   = dPgetParam($_POST, "sc_patient_id", $mbOp->_ref_pat->SHS);
  $sc_venue_id     = dPgetParam($_POST, "sc_venue_id", $mbOp->venue_SHS);
  $cmca_uf_code    = dPgetParam($_POST, "cmca_uf_code", "CHI");
  $cmca_uf_libelle = dPgetParam($_POST, "cmca_uf_libelle", "CHIRURGIE");

  if (!$doc->checkSchema()) {
    return;
  }
  
  $doc->generateFromOperation($mbOp, $sc_patient_id, $sc_venue_id, $cmca_uf_code, $cmca_uf_libelle);
  $doc_valid = $doc->schemaValidate();
}

$doc->addNameSpaces();
$doc->save();

require_once($AppUI->getSystemClass("ftp"));

$ftp = new CFTP;
$ftp->hostname = dPgetParam($_POST, "hostname", "ftpperso.free.fr");
$ftp->username = dPgetParam($_POST, "username", "tdespoix");
$ftp->userpass = dPgetParam($_POST, "userpass", "g5b3deay");

// Connexion FTP
if (isset($_POST["hostname"])) {
  $ftp->sendFile($doc->documentfilename, "document.xml");
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("doc", $doc);
$smarty->assign("ftp", $ftp);
$smarty->assign("doc_valid", $doc_valid);
$smarty->assign("mbOp", $mbOp);
$smarty->assign("mb_operation_id", $mb_operation_id);
$smarty->assign("sc_patient_id", $sc_patient_id);
$smarty->assign("sc_venue_id", $sc_venue_id);
$smarty->assign("cmca_uf_code", $cmca_uf_code);
$smarty->assign("cmca_uf_libelle", $cmca_uf_libelle);

$smarty->display('export_hprim.tpl');

?>