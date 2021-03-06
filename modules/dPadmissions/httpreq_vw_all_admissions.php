<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPadmissions
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {
  $AppUI->redirect( "m=system&a=access_denied" );
}

require_once( $AppUI->getModuleClass('dPplanningOp', 'planning') );

// Initialisation de variables
$date = mbGetValueFromGetOrSession("date", mbDate());
$lastmonth = mbDate("-1 month", $date);
$nextmonth = mbDate("+1 month", $date);

// Liste des admissions par jour
$sql = "SELECT plagesop.id AS pid, operations.operation_id, operations.date_adm AS date,
    operations.depassement AS depassement, count(operation_id) AS num
    FROM plagesop
    LEFT JOIN operations
    ON operations.plageop_id = plagesop.id
    WHERE operations.date_adm LIKE '".mbTranformTime("+ 0 day", $date, "%Y-%m")."-__'
    GROUP BY operations.date_adm
    ORDER BY operations.date_adm";
$list1 = db_loadlist($sql);

// Liste des admissions non effectu�es par jour
$sql = "SELECT operations.date_adm AS date,
    operations.depassement AS depassement, count(operation_id) AS num
    FROM plagesop
    LEFT JOIN operations
    ON operations.plageop_id = plagesop.id
    WHERE operations.date_adm LIKE '".mbTranformTime("+ 0 day", $date, "%Y-%m")."-__'
    AND operations.admis = 'n'
    AND operations.annulee = 0
    GROUP BY operations.date_adm
    ORDER BY operations.date_adm";
$list2 = db_loadlist($sql);

// Liste des admissions non pr�par�es
$sql = "SELECT operations.date_adm AS date,
    operations.depassement AS depassement, count(operation_id) AS num
    FROM plagesop
    LEFT JOIN operations
    ON operations.plageop_id = plagesop.id
    WHERE operations.date_adm LIKE '".mbTranformTime("+ 0 day", $date, "%Y-%m")."-__'
    AND operations.saisie = 'n'
    AND operations.annulee = 0
    GROUP BY operations.date_adm
    ORDER BY operations.date_adm";
$list3 = db_loadlist($sql);

// On met toutes les sommes d'intervention dans le m�me tableau
foreach($list1 as $key => $value) {
  $i2 = 0;
  $i2fin = sizeof($list2);
  while((@$list2[$i2]["date"] != $value["date"]) && ($i2 < $i2fin)) {
    $i2++;
  }
  if(@$list2[$i2]["date"] == $value["date"])
    $list1[$key]["num2"] = $list2[$i2]["num"];
  else
    $list1[$key]["num2"] = 0;
  $i3 = 0;
  $i3fin = sizeof($list3);
  while((@$list3[$i3]["date"] != $value["date"]) && ($i3 < $i3fin)) {
    $i3++;
  }
  if(@$list3[$i3]["date"] == $value["date"])
    $list1[$key]["num3"] = $list3[$i3]["num"];
  else
    $list1[$key]["num3"] = 0;
}

// Cr�ation du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;


$smarty->assign('date', $date);
$smarty->assign('lastmonth', $lastmonth);
$smarty->assign('nextmonth', $nextmonth);
$smarty->assign('list1', $list1);

$smarty->display('inc_vw_all_admissions.tpl');

?>