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

require_once($AppUI->getModuleClass($m, "hprimxmldocument"));
require_once($AppUI->getModuleClass($m, "hprimxmlschema"));
require_once($AppUI->getModuleClass("dPplanningOp", "planning"));

$pmsipath = "modules/$m/hprim/serveurActe";
$schemapath = "$pmsipath/schema.xml";

if (!is_file($schemapath)) {
  $schema = new CHPrimXMLSchema();
  $schema->importSchemaPackage($pmsipath);
  $schema->purgeIncludes();
  $schema->purgeImportedNamespaces();
  $schema->save($schemapath);
}

$documentpath = null;
$doc = null;
$doc_valid = null;
$mbOp = null;

$mb_operation_id = dPgetParam($_POST, "mb_operation_id", 5174);
$sc_patient_id = dPgetParam($_POST, "sc_patient_id", "00000435");
$sc_venue_id = dPgetParam($_POST, "sc_venue_id", "05000393");
$cmca_uf_code = dPgetParam($_POST, "cmca_uf_code", "CHI");
$cmca_uf_libelle = dPgetParam($_POST, "cmca_uf_libelle", "CHIRURGIE");

// Une opération
$mbOp = new COperation();
$mbOp->load($mb_operation_id);
$mbOp->loadRefs();

// DOM extension
$doc = new CHPrimXMLDocument();

$evenementsServeurActes = $doc->addElement($doc, "evenementsServeurActes", null, "http://www.hprim.org/hprimXML");
$doc->addAttribute($evenementsServeurActes, "version", "1.01");

$enteteMessage = $doc->addElement($evenementsServeurActes, "enteteMessage");
$doc->addAttribute($enteteMessage, "modeTraitement", "test"); // A supprimer pour un utilisation réelle
$doc->addElement($enteteMessage, "identifiantMessage", "OP$mbOp->operation_id");
$doc->addDateTimeElement($enteteMessage, "dateHeureProduction");

$emetteur = $doc->addElement($enteteMessage, "emetteur");
$agents = $doc->addElement($emetteur, "agents");
$doc->addAgent($agents, "application", "MediBoard", "Gestion des Etablissements de Santé");
$doc->addAgent($agents, "système", "CMCA", "Centre Médico-Chir. de l'Atlantique");
$doc->addAgent($agents, "acteur", "user$AppUI->user_id", "$AppUI->user_first_name $AppUI->user_last_name");

$destinataire = $doc->addElement($enteteMessage, "destinataire");
$agents = $doc->addElement($destinataire, "agents");
$doc->addAgent($agents, "application", "SANTEcom", "Siemens Health Services: S@NTE.com");
$doc->addAgent($agents, "système", "CMCA", "Centre Médico-Chir. de l'Atlantique");

$evenementServeurActe = $doc->addElement($evenementsServeurActes, "evenementServeurActe");
$doc->addDateTimeElement($evenementServeurActe, "dateAction");

// Ajout du patient
$mbPatient =& $mbOp->_ref_pat;

$patient = $doc->addElement($evenementServeurActe, "patient");
$identifiant = $doc->addElement($patient, "identifiant");
$doc->addIdentifiantPart($identifiant, "emetteur", "pat$mbPatient->patient_id");
$doc->addIdentifiantPart($identifiant, "recepteur", $sc_patient_id);

$personnePhysique = $doc->addElement($patient, "personnePhysique");
$doc->addAttribute($personnePhysique, "sexe", 
  $mbPatient->sexe == "m" ? "M" :
  $mbPatient->sexe == "f" ? "F" : "J");
$doc->addElement($personnePhysique, "nomUsuel", substr($mbPatient->nom, 0, 35));
$doc->addElement($personnePhysique, "nomNaissance", substr($mbPatient->_nom_naissance, 0, 35));

$prenoms = $doc->addElement($personnePhysique, "prenoms");
foreach ($mbPatient->_prenoms as $mbKey => $mbPrenom) {
  if ($mbKey < 4) {
    $doc->addElement($prenoms, "prenom", substr($mbPrenom, 0, 35));
  }
}

$adresses = $doc->addElement($personnePhysique, "adresses");
$adresse = $doc->addElement($adresses, "adresse");
$doc->addElement($adresse, "ligne", $mbPatient->adresse);
$doc->addElement($adresse, "ville", $mbPatient->ville);
$doc->addElement($adresse, "codePostal", $mbPatient->cp);

$telephones = $doc->addElement($personnePhysique, "telephones");
$doc->addElement($telephones, "telephone", $mbPatient->tel);
$doc->addElement($telephones, "telephone", $mbPatient->tel2);

$dateNaissance = $doc->addElement($personnePhysique, "dateNaissance");
$doc->addElement($dateNaissance, "date", $mbPatient->naissance);

// Ajout de la venue: +/- l'hospitalisation
$venue = $doc->addElement($evenementServeurActe, "venue");

$identifiant = $doc->addElement($venue, "identifiant");
$doc->addIdentifiantPart($identifiant, "emetteur", "op$mbOp->operation_id");
$doc->addIdentifiantPart($identifiant, "recepteur", $sc_venue_id);

$entree = $doc->addElement($venue, "entree");
$dateHeureOptionnelle = $doc->addElement($entree, "dateHeureOptionnelle");
$doc->addElement($dateHeureOptionnelle, "date", $mbOp->date_adm);
$doc->addElement($dateHeureOptionnelle, "heure", $mbOp->time_adm);

// Ajout du médecin prescripteur
$mbChir =& $mbOp->_ref_chir;

$medecins = $doc->addElement($venue, "medecins");
$medecin = $doc->addElement($medecins, "medecin");
$doc->addElement($medecin, "numeroAdeli", $mbChir->adeli);
$doc->addAttribute($medecin, "lien", "exec");

$identification = $doc->addElement($medecin, "identification");
$doc->addElement($identification, "code", "chir$mbChir->user_id");
$doc->addElement($identification, "libelle", $mbChir->_user_username);

$sortie = $doc->addElement($venue, "sortie");
$dateHeureOptionnelle = $doc->addElement($sortie, "dateHeureOptionnelle");
$doc->addElement($dateHeureOptionnelle, "date", mbDate(null, $mbOp->_ref_last_affectation->sortie));
$doc->addElement($dateHeureOptionnelle, "heure", mbTime(null, $mbOp->_ref_last_affectation->sortie));

$placement = $doc->addElement($venue, "Placement");
$modePlacement = $doc->addElement($placement, "modePlacement");
$doc->addAttribute($modePlacement, "modaliteHospitalisation", $mbOp->_modalite_hospitaliation);
$datePlacement = $doc->addElement($placement, "datePlacement");
$doc->addElement($datePlacement, "date", $mbOp->date_adm);
$doc->addElement($datePlacement, "heure", $mbOp->time_adm);

// Ajout de l'intervention
$intervention = $doc->addElement($evenementServeurActe, "intervention");
$identifiant = $doc->addElement($intervention, "identifiant");
$emetteur = $doc->addElement($identifiant, "emetteur", "op$mbOp->operation_id");

$mbOpDebut = $mbOp->entree_bloc ? $mbOp->entree_bloc : $mbOp->time_operation;
$debut = $doc->addElement($intervention, "debut");
$doc->addElement($debut, "date", $mbOp->_ref_plageop->date);
$doc->addElement($debut, "heure", $mbOpDebut);

$mbOpFin   = $mbOp->sortie_bloc ? $mbOp->sortie_bloc : mbAddTime($mbOp->temp_operation, $mbOp->time_operation);
$fin = $doc->addElement($intervention, "fin");
$doc->addElement($fin, "date", $mbOp->_ref_plageop->date);
$doc->addElement($fin, "heure", $mbOpFin);

$mbChir->loadRefs();
$doc->addUniteFonctionnelle($intervention, $mbChir->_ref_function);

// Ajout des participants
$mbPlage = $mbOp->_ref_plageop;
$mbPlage->loadRefsFwd();
$mbAnest = $mbPlage->_ref_anesth;

$participants = $doc->addElement($intervention, "participants");
$participant = $doc->addElement($participants, "participant");
$doc->addProfessionnelSante($participant, $mbChir);
$participant = $doc->addElement($participants, "participant");
$doc->addProfessionnelSante($participant, $mbAnest);

// Libellé de l'opération
$doc->addElement($intervention, "libelle", substr($mbOp->libelle, 0, 80));

// Ajout des actes CCAM
$actesCCAM = $doc->addElement($evenementServeurActe, "actesCCAM");

foreach ($mbOp->_ext_codes_ccam as $mbCode) {
  $doc->addActeCCAM($actesCCAM, $mbCode, $mbOp);
}

// Traitement final
$doc->purgeEmptyElements();
$doc_valid = $doc->schemaValidate($schemapath);

// Ajout des namespace pour XML Spy
$doc->addAttribute($evenementsServeurActes, "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
$doc->addAttribute($evenementsServeurActes, "xsi:schemaLocation", "http://www.hprim.org/hprimXML schema.xml");

$documentpath = "$pmsipath/document.xml";
$doc->save($documentpath);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("schemapath", $schemapath);
$smarty->assign("documentpath", $documentpath);
$smarty->assign("doc_valid", $doc_valid);
$smarty->assign("mbOp", $mbOp);
$smarty->assign("mb_operation_id", $mb_operation_id);
$smarty->assign("sc_patient_id", $sc_patient_id);
$smarty->assign("sc_venue_id", $sc_venue_id);
$smarty->assign("cmca_uf_code", $cmca_uf_code);
$smarty->assign("cmca_uf_libelle", $cmca_uf_libelle);

$smarty->display('export_hprim.tpl');

?>