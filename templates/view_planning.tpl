<!-- $Id$ -->

{literal}
<script type="text/javascript">
//<![CDATA[

function printAdmission(id) {
  var url = './index.php?m=dPadmissions&a=print_admission&dialog=1';
  url = url + '&id=' + id;
  popup(700, 550, url, 'Patient');
}

//]]>
</script>
{/literal}

<table class="main">
  <tr><th><a href="javascript:window.print()">Planning du {$date}</a></th></tr>
  {foreach from=$plagesop item=curr_plageop}
  <tr>
    <td>
	  <b>Dr. {$curr_plageop.firstname} {$curr_plageop.lastname} :
	  {$curr_plageop.salle} de
	  {$curr_plageop.debut} - {$curr_plageop.fin}
    le {$curr_plageop.date}</b>
	</td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th colspan="7"><b>Intervention</b></th>
		  <th colspan="4"><b>Patient</b></th>
		</tr>
		<tr>
		  <th>Heure</th>
		  <th>Intervention</th>
		  <th>Coté</th>
          <th>Anesthésie</th>
          <th>Hospi</th>
		  <th>Remarques</th>
		  <th>Materiel</th>
		  <th>Nom</th>
		  <th>Prénom</th>
		  <th>Age</th>
		  <th>Chambre</th>
		</tr>
		{foreach from=$curr_plageop.operations item=curr_op}
		<tr>
		  {if $curr_op.annulee}<td>ANNULE</td>
		  {else}<td>{$curr_op.heure}</td>{/if}
		  
		  <td class="text">{$curr_op.CCAM|truncate:80:"...":false} <i>({$curr_op.CCAM_code})</i>
		  {if $curr_op.CCAM_code2}<br />{$curr_op.CCAM2|truncate:80:"...":false} <i>({$curr_op.CCAM_code2}{/if}</td>
		  <td>{$curr_op.cote|truncate:1:""|capitalize}</td>
          <td>{$curr_op.lu_type_anesth}</td>
          <td>{$curr_op.adm|truncate:1:""|capitalize}</td>
		  <td class="text">{$curr_op.rques|nl2br}</td>
		  <td class="text">{$curr_op.mat|nl2br}</td>
		  <td><a href="#" onclick="printAdmission({$curr_op.id})">{$curr_op.lastname}</a></td>
		  <td><a href="#" onclick="printAdmission({$curr_op.id})">{$curr_op.firstname}</a></td>
		  <td><a href="#" onclick="printAdmission({$curr_op.id})">{$curr_op.age} ans</a></td>
		  <td></td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  {/foreach}
</table>