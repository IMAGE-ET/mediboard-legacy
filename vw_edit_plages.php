<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

global $m;
require_once("modules/dPbloc/checkDate.php");
require_once($AppUI->getModuleClass($m, "planning"));
require_once($AppUI->getModuleClass($m, "calendar"));

$planning = new Cplanning($_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
$calendar = new Ccalendar("index.php?m=dPbloc", $_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
?>

<script language="javascript">
function popPlanning(debut) {
  var url = './index.php?m=dPbloc&a=view_planning&dialog=1&debut='+debut+'&fin='+debut;
  window.open(url, 'Planning', 'left=10,top=10,height=550,width=700,resizable=1,scrollbars=1');
}
</script>

<table width="100%">
	<tr>
		<td valign="top" rowspan=2>

      <table class="form">
        <tr><th class="category">Légende</th></tr>
<?php
$sql = "SELECT functions_mediboard.text AS texte, functions_mediboard.color AS color
  FROM functions_mediboard, groups_mediboard
  WHERE groups_mediboard.group_id = functions_mediboard.group_id
  AND (groups_mediboard.text = 'Chirurgie' 
  OR groups_mediboard.text = 'Anesthésie')
  ORDER BY groups_mediboard.text, functions_mediboard.text";
$rows = db_loadlist($sql);

foreach($rows as $key => $value) {
	echo "<tr><td class='text' style='background: #{$value['color']};'>{$value['texte']}</td></tr>";
}
?>
      </table>

		</td>
		<td width="100%" valign="top" align="center">
<?php
$planning->displayJour();
?>
		</td>
		<td valign="top" align="right" rowspan=2>
<?php
echo $calendar->display();
?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="center">
<?php
if (!getDenyEdit($m)) {
  ////////////////// NEW WAY /////////////////////
  global $m, $AppUI;
  
  require_once($AppUI->getModuleClass($m, "plagesop"));
  $plagesel = new CPlageOp;
  $plagesel->load(mbGetValueFromGetOrSession("id"));
  
  require_once($AppUI->getModuleClass($m, "salle"));
  $salle = new CSalle;
  $salles = $salle->loadlist();
  
  require_once($AppUI->getModuleClass("mediusers", "functions"));
  $function = new CFunctions;
  $specs = $function->loadSpecialites();

  require_once($AppUI->getModuleClass("mediusers"));
  $mediuser = new CMediusers;
  $chirs = $mediuser->loadChirurgiens();
  $anesths = $mediuser->loadAnesthesistes();

  // Heures & minutes
  $start = 8;
  $stop = 20;
  $step = 15;
  
  for ($i = $start; $i < $stop; $i++) {
      $hours[] = $i;
  }
  
  for ($i = 0; $i < 60; $i += $step) {
      $mins[] = $i;
  }
  
  // Création du template
  require_once($AppUI->getSystemClass("smartydp"));
  $smarty = new CSmartyDP;
  
  $smarty->assign('plagesel', $plagesel);
  $smarty->assign('chirs', $chirs);
  $smarty->assign('anesths', $anesths);
  $smarty->assign('salles', $salles);
  $smarty->assign('specs', $specs);
  
  $smarty->assign('heures', $hours);
  $smarty->assign('minutes', $mins);

  $smarty->assign('day'  , $_SESSION['day'  ]);
  $smarty->assign('month', $_SESSION['month']);
  $smarty->assign('year' , $_SESSION['year' ]);

  $smarty->display('vw_edit_plages.tpl');
}
?>
		</td>
	</tr>
</table>