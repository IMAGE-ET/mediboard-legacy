<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

include_once("modules/dPbloc/checkDate.php");
require_once($AppUI->getModuleClass('dPbloc', 'planning'));
require_once($AppUI->getModuleClass('dPbloc', 'calendar'));
require_once($AppUI->getModuleClass('mediusers', 'functions'));

$planning = new Cplanning($_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
$calendar = new Ccalendar("index.php?m=dPbloc", $_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
?>

<table width="100%">
	<tr>
		<td rowspan=2 width="100%" align="center">
<?php
$planning->displaySem();
?>
		</td>
		<td valign="top" align="right" height="100%">
<?php
echo $calendar->display();
?>
		</td>
	</tr>
	<tr>
		<td valign="top">
<?php
$listSpec = new CFunctions();
$listSpec = $listSpec->loadSpecialites();

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;

$smarty->assign('listSpec', $listSpec);

$smarty->display('vw_idx_planning.tpl');

?>

		</td>
	</tr>
</table>