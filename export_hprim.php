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

// XML Paths
$dtd_path = "modules/$m/document.dtd";
$xml_path = "modules/$m/document.xml";

// SimpleXML extension
$xml = simplexml_load_file($xml_path);
$simpleXML_export = var_export($xml, true);

// DOM extension
$dom = new DOMDocument;
$dom->Load($xml_path);
$dom_valid = $dom->validate();

$dom_export = $dom->saveXML();
$dom_export = htmlspecialchars($dom_export);

$xml_header_open  = htmlspecialchars("<?xml");
$xml_header_close = htmlspecialchars("?>");
$dom_export = preg_replace("/$xml_header_open/");
 

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign("simpleXML_export", $simpleXML_export);
$smarty->assign("dom_export", $dom_export);
$smarty->assign("dom_valid", $dom_valid);

$smarty->display('export_hprim.tpl');

?>