<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPstats
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;
require_once( $AppUI->getModuleClass('mediusers') );
require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_pie'));
require_once( $AppUI->getLibraryClass('jpgraph/src/jpgraph_pie3D'));

/*
$sql = "SELECT COUNT(user_log.user_log_id) AS total," .
    "\nusers.user_last_name," .
    "\nusers.user_first_name" .
    "\nFROM user_log, users" .
    "\nWHERE users.user_id = user_log.user_id" .
    "\nGROUP BY users.user_id";
$result = db_loadlist($sql);
$data = array();
$leg = array();
foreach($result as $value) {
  $data[] = $value["total"];
  $leg[] = $value["user_last_name"]." ".$value["user_first_name"];
}
*/
$sql = "SELECT COUNT(operations.operation_id) AS total," .
    "\nsallesbloc.nom" .
    "\nFROM operations, plagesop, sallesbloc" .
    "\nWHERE operations.plageop_id = plagesop.id" .
    "\nAND plagesop.id_salle = sallesbloc.id" .
    "\nGROUP BY sallesbloc.id";
$result = db_loadlist($sql);
$data = array();
$leg = array();
foreach($result as $value) {
  $data[] = $value["total"];
  $leg[] = $value["nom"];
}

$graph = new PieGraph(400,300,"auto");
$graph->SetShadow();
$graph->title->Set("Graph des salles");

$p1 = new PiePlot3D($data);
$p1->SetSize(.3);
$p1->SetCenter(0.45);
$p1->SetStartAngle(20);
$p1->SetAngle(45);

$p1->SetLegends($leg);

$p1->value->SetColor("darkred");
$p1->SetLabelType(PIE_VALUE_PER);

$a = array_search(max($data),$data);
$p1->ExplodeSlice($a);

$graph->Add($p1);
$graph->Stroke();