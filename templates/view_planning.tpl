<table class="main">
  <tr><th><a href="javascript:window.print()">Planning du {$date}</a></th></tr>
  {foreach from=$plagesop item=curr_plageop}
  <tr>
    <td>
	  <b>Dr. {$curr_plageop.firstname} {$curr_plageop.lastname} :
	  {$curr_plageop.salle} de
	  {$curr_plageop.debut} - {$curr_plageop.fin}</b>
	</td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th colspan=3><b>Intervention</b></th>
		  <th colspan=4><b>Patient</b></th>
		</tr>
		<tr>
		  <th>Heure</th>
		  <th>Intervention</th>
		  <th>Coté</th>
		  <th>Remarques</th>
		  <th>Nom</th>
		  <th>Prénom</th>
		  <th>Age</th>
		</tr>
		{foreach from=$curr_plageop.operations item=curr_op}
		<tr>
		  <td>{$curr_op.heure}</td>
		  <td>{$curr_op.CCAM} <i>({$curr_op.CCAM_code})</i></td>
		  <td>{$curr_op.cote}</td>
		  <td>{$curr_op.rques} ({$curr_op.mat})</td>
		  <td>{$curr_op.lastname}</td>
		  <td>{$curr_op.firstname}</td>
		  <td>{$curr_op.age} ans</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  {/foreach}
</table>