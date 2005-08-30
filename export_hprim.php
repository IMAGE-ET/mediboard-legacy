<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

$doc = new DOMDocument('1.0');
// we want a nice output
$doc->formatOutput = true;

// XML Paths
$dtd_path = "modules/$m/document.dtd";
$xml_path = "modules/$m/document.xml";

// DOM extension
$dom = new DOMDocument('1.0', 'iso-8859-1');
$dom->format_output = true;

$evenementsPMSI = $dom->appendChild($dom->createElement("evenementsPMSI"));

$enteteMessage = $evenementsPMSI->appendChild(new DOMElement("enteteMessage"));
$enteteMessage->setAttribute("modeTraitement", "test"); // A supprimer pour un utilisation réelle

$identifiantMessage = $enteteMessage->appendChild(new DOMElement("identifiantMessage", "PMSI{op.id}"));
$identifiantMessage = $enteteMessage->appendChild(new DOMElement("datHeureProduction", mbTranformTime(null, null, "%Y-%m-%dT%H:%M:%S")));

$agents = $enteteMessage->appendChild(new DOMElement("agents"));

$agent = $agents->appendChild(new DOMElement("agent"));
$agent->setAttribute("categorie", "application");
$agent->appendChild(new DOMElement("code", "MediBoard"));
$agent->appendChild(new DOMElement("libelle", "Gestion des Etablissements de Sante"));

$agent = $agents->appendChild(new DOMElement("agent"));
$agent->setAttribute("categorie", "système");
$agent->appendChild(new DOMElement("code", "CMCA"));
$agent->appendChild(new DOMElement("libelle", "Centre Médico-Chir. de l'Atlantique"));

$agent = $agents->appendChild(new DOMElement("agent"));
$agent->setAttribute("categorie", "acteur");
$agent->appendChild(new DOMElement("code", "AppUI.userId"));
$agent->appendChild(new DOMElement("libelle", "user.firstName user.lastName"));


$identifiantMessage = $enteteMessage->appendChild(new DOMElement("datHeureProduction", mbTranformTime(null, null, "%Y-%m-%dT%H:%M:%S")));
$identifiantMessage = $enteteMessage->appendChild(new DOMElement("datHeureProduction", mbTranformTime(null, null, "%Y-%m-%dT%H:%M:%S")));

$dom_valid = $dom->schemaValidate("modules/$m/document.xsd");

$dom_export = $dom->saveXML();

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("dom_export", $dom_export);
$smarty->assign("dom_valid", $dom_valid);

$smarty->display('export_hprim.tpl');

?>