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