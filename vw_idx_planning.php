<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

include_once("modules/dPbloc/checkDate.php");
require_once( $AppUI->getModuleClass('dPbloc', 'planning') );
require_once( $AppUI->getModuleClass('dPbloc', 'calendar') );

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
$sql = "select functions_mediboard.text as texte, functions_mediboard.color as color
		from functions_mediboard, groups_mediboard
		where groups_mediboard.group_id = functions_mediboard.group_id
		and (groups_mediboard.text = 'Chirurgie' or groups_mediboard.text = 'Anesthésie')
		order by groups_mediboard.text, functions_mediboard.text";
$rows = db_loadlist($sql);
echo "<table width=\"100%\">";
echo "<tr><td valign=\"top\" align=\"center\"><b><i>Légende</i></b>";
foreach($rows as $key => $value)
{
	echo "<tr><td valign=\"top\" bgcolor=\"#".$value['color']."\">".$value['texte']."</td></tr>";
}
echo "</table>";
?>
		</td>
	</tr>
</table>