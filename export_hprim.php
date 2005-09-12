<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

echo "toto";
if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

require_once($AppUI->getModuleClass($m, "hprimxmldocument"));
require_once($AppUI->getModuleClass($m, "hprimxmlschema"));
require_once($AppUI->getModuleClass("dPplanningOp", "planning"));

function DOMDocumentMerge(DOMDocument &$dom1, DOMDocument $dom2) {
   // pull all child elements of second XML
   $xpath = new domXPath($dom2);
   $xpathQuery = $xpath->query('/*/*');
   
   for ($i = 0; $i < $xpathQuery->length; $i++) {
       // and pump them into first one
       $dom1->documentElement->appendChild(
       $dom1->importNode($xpathQuery->item($i), true));
   }
}

$pmsipath = "modules/$m/hprim/serveurActe";
$schemapath = "$pmsipath/schema.xml";

if (!is_file($schemapath)) {
  $schema = new CHPrimXMLSchema();
  $schema->importSchemaPackage($pmsipath);
  $schema->purgeIncludes();
  $schema->purgeImportedNamespaces();
  $schema->save($schemapath);
}

// Une opération: ID=5174
$mbOp = new COperation();
$mbOp->load(5174);
$mbOp->loadRefs();

// DOM extension
$documentpath = "$pmsipath/document.xml";
$doc = new CHPrimXMLDocument();

$evenementsServeurActes = $doc->addElement($doc, "evenementsServeurActes", null, "http://www.hprim.org/hprimXML");
$doc->addAttribute($evenementsServeurActes, "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
$doc->addAttribute($evenementsServeurActes, "xsi:schemaLocation", "http://www.hprim.org/hprimXML schema.xml");
$doc->addAttribute($evenementsServeurActes, "version", "1.00");

$enteteMessage = $doc->addElement($evenementsServeurActes, "enteteMessage");
$doc->addAttribute($enteteMessage, "modeTraitement", "test"); // A supprimer pour un utilisation réelle
$doc->addElement($enteteMessage, "identifiantMessage", "OP$mbOp->operation_id");
$doc->addDateTimeElement($enteteMessage, "dateHeureProduction");

$emetteur = $doc->addElement($enteteMessage, "emetteur");
$agents = $doc->addElement($emetteur, "agents");
$doc->addAgent($agents, "application", "MediBoard", "Gestion des Etablissements de Santé");
$doc->addAgent($agents, "système", "CMCA", "Centre Médico-Chir. de l'Atlantique");
$doc->addAgent($agents, "acteur", "$AppUI->user_id", "$AppUI->user_first_name $AppUI->user_last_name");

$destinataire = $doc->addElement($enteteMessage, "destinataire");
$agents = $doc->addElement($destinataire, "agents");
$doc->addAgent($agents, "application", "SANTEcom", "Siemens Health Services: S@NTE.com");
$doc->addAgent($agents, "système", "CMCA", "Centre Médico-Chir. de l'Atlantique");
$doc->addAgent($agents, "acteur", "$AppUI->user_id", "$AppUI->user_first_name $AppUI->user_last_name");

$evenementServeurActe = $doc->addElement($evenementsServeurActes, "evenementServeurActe");

// Ajout du patient
$mbPatient = $mbOp->_ref_pat;

$patient = $doc->addElement($evenementServeurActe, "patient");
$identifiant = $doc->addElement($patient, "identifiant");
$emetteur = $doc->addElement($identifiant, "emetteur");
$doc->addElement($emetteur, "valeur", "patient$mbPatient->patient_id");

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
$emetteur = $doc->addElement($identifiant, "emetteur");
$doc->addElement($emetteur, "valeur", "op$mbOp->operation_id");

//$entree = $doc->addElement();

// Traitement final
$doc->purgeEmptyElements();
$doc->save($documentpath);

$doc_valid = $doc->schemaValidate($schemapath);

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("schemapath", $schemapath);
$smarty->assign("documentpath", $documentpath);
$smarty->assign("doc_valid", $doc_valid);

$smarty->display('export_hprim.tpl');

?>