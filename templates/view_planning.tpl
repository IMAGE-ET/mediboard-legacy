<table class="main">
  <tr><th><a href="javascript:window.print()">Planning du {$date}</a></th></tr>
  {foreach from=$plagesop item=curr_plageop}
  <tr>
    <td>
	  Dr. {$curr_plageop.firstname} {$curr_plageop.lastname}
	  <br />
	  {$curr_plageop.salle}
	  <br />
	  {$curr_plageop.debut} - {$curr_plageop.fin}
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
		  <th>Nom</th>
		  <th>Prénom</th>
		  <th>Sexe</th>
		  <th>Date de naissance</th>
		</tr>
		{foreach from=$curr_plageop.operations item=curr_op}
		<tr>
		  <td>{$curr_op.heure}</td>
		  <td>{$curr_op.CCAM} <i>({$curr_op.CCAM_code})</i></td>
		  <td>{$curr_op.cote}</td>
		  <td>{$curr_op.lastname}</td>
		  <td>{$curr_op.firstname}</td>
		  <td>{$curr_op.sexe}</td>
		  <td>{$curr_op.naissance}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  {/foreach}
</table>