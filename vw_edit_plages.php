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
require_once($AppUI->getModuleClass("mediusers", "functions"));

$planning = new Cplanning($_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
$calendar = new Ccalendar("index.php?m=dPbloc", $_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
?>

<script language="javascript">
function popPlanning(debut) {
  var url = './index.php?m=dPbloc&a=view_planning&dialog=1&debut='+debut+'&fin='+debut;
  window.open(url, 'Planning', 'left=10,top=10,height=550,width=700,resizable=1,scrollbars=1');
}
</script>

<table class="main">
	<tr>
		<td width="100%" valign="top" align="center">
<?php
$planning->displayJour();

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

		</td>
		<td valign="top" align="right">
<?php
echo $calendar->display();
?>
<?php
$listSpec = new CFunctions();
$listSpec = $listSpec->loadSpecialites();
?>

      <table class="tbl">
        <tr><th>Liste des spécialités</th></tr>
        <?php foreach($listSpec as $curr_spec) { ?>
        <tr>
          <td class="text" style="background: #<?php echo $curr_spec->color; ?>;"><?php echo $curr_spec->text; ?></td>
        </tr>
        <?php } ?>
      </table>

    </td>
  </tr>
</table>