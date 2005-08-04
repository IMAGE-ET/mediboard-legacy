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

// XML Helper functions
ini_set("include_path", ".;./lib/PEAR");
require_once("XML/DTD/XmlValidator.php");

$dtd_path = "modules/$m/document.dtd";
$xml_path = "modules/$m/document.xml";
$dtd_parser = new XML_DTD_Parser;
$dtd_tree = $dtd_parser->parse($dtd_path);

$validator = new XML_DTD_XmlValidator;
if (!$validator->isValid($dtd_path, $xml_path)) {
  die($validator->getMessage());
} else {
  echo "Okay!";
}  


// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->display('export_hprim.tpl');

?>