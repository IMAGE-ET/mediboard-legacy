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
  window.open('./index.php?m=dPbloc&a=view_planning&dialog=1&day='+day+'&month='+month+'&year='+year, 'Planning', 'left=10,top=10,height=550,width=700,resizable=1,scrollbars=1');
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
	echo "<tr><td style='background: #{$value['color']};'>{$value['texte']}</td></tr>";
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