<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

include_once("modules/dPbloc/checkDate.php");
require_once("modules/dPbloc/planning.class.php");
require_once("modules/dPbloc/calendar.class.php");
require_once("modules/dPbloc/formulaire.class.php");

$planning = new Cplanning($_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
$calendar = new Ccalendar("index.php?m=dPbloc", $_SESSION['day'], $_SESSION['month'], $_SESSION['year']);
$formulaire = new Cformulaire(dPgetParam($_GET, "tool", ""), dPgetParam($_GET, "id", 0));
?>

<script language="javascript">
function popPlanning(day, month, year) {
  window.open('./index.php?m=dPbloc&a=view_planning&dialog=1&day='+day+'&month='+month+'&year='+year, 'Planning du jour', 'left=10,top=10,height=550,width=700,resizable,scrollbars=1');
}
</script>

<table width="100%">
	<tr>
		<td valign="top" rowspan=2>
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
if(!getDenyEdit($m))
{
  $formulaire->display();
}
else
{
  echo "<i>Vous n'avez pas le droit de modifier le planning</i>";
}
?>
		</td>
	</tr>
</table>