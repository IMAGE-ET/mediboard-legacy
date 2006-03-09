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

$mb_operation_id = dPgetParam($_GET, "operation_id");

$mbOp = new COperation();
$doc = new CHPrimXMLServeurActes;

// Chargement de l'opération et génération du document
if ($mbOp->load($mb_operation_id)) {
  $mbOp->loadRefs();
  
  $sc_patient_id   = dPgetParam($_POST, "sc_patient_id", $mbOp->_ref_pat->SHS);
  $sc_venue_id     = dPgetParam($_POST, "sc_venue_id", $mbOp->venue_SHS);
  $cmca_uf_code    = dPgetParam($_POST, "cmca_uf_code", $mbOp->code_uf);
  $cmca_uf_libelle = dPgetParam($_POST, "cmca_uf_libelle", $mbOp->libelle_uf);

  if (!$doc->checkSchema()) {
    return;
  }
  
  $doc->generateFromOperation($mbOp, $sc_patient_id, $sc_venue_id, $cmca_uf_code, $cmca_uf_libelle);
  $doc_valid = $doc->schemaValidate();
}

//$doc->addNameSpaces();
$doc->saveTempFile();

require_once($AppUI->getSystemClass("ftp"));

$ftp = new CFTP;
$ftp->hostname = dPgetParam($_POST, "hostname", "10.9.44.1");
$ftp->username = dPgetParam($_POST, "username", "mediboard");
$ftp->userpass = dPgetParam($_POST, "userpass", "oxcmca");

// Connexion FTP
if (isset($_POST["hostname"])) {

  // Compte le nombre de fichiers déjà générés
  $count = 0;
  $dir = dir($doc->finalpath);
  while (false !== ($entry = $dir->read())) {
    $count++;
  }
  $dir->close();
  $count -= 2; // Exclure . et ..
  $counter = $count % 100;
  
  // Transfert réel
  $destination_basename = sprintf("admls1%02d", $counter);
  if ($ftp->sendFile($doc->documentfilename, "$destination_basename.xml")) {
    $ftp->sendFile($doc->documentfilename, "$destination_basename.ok");

    $doc->saveFinalFile();
    $ftp->logStep("Archiving sent file in Mediboard server under name $doc->documentfinalfilename");
  }
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