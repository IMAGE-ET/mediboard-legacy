<table class="main">

  <tr colspan="2">
    <td>
      <form action="index.php" target="_self" name="selection" method="get" encoding="">

      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="0">
      Choisir une salle :
      <select name="salle" onchange="this.form.submit()">
        <option value="0">Aucune salle</option>
        {foreach from=$listSalles item=curr_salle}
        <option value="{$curr_salle.id}" {if $curr_salle.id == $salle} selected="selected" {/if}>
          {$curr_salle.nom}
        </option>
        {/foreach}
      </select>
  
      </form>
    </td>
  </tr>

	<tr>

		<td width="50%">
			<table class="tbl">
        {foreach from=$plages item=curr_plage}
        <tr>
          <th colspan="5">
            <strong>Dr. {$curr_plage.lastname} {$curr_plage.firstname}</strong>
            {$curr_plage.debut} - {$curr_plage.fin}
          </th>
        </tr>
        <tr>
          <th>Heure</th>
          <th>Patient</th>
          <th>Intervention</th>
          <th>Coté</th>
          <th>Durée</th>
        </tr>
        {foreach from=$curr_plage.operations item=curr_operation}
        <tr>
          <td>{$curr_operation.heure}</td>
          <td>{$curr_operation.nom} {$curr_operation.prenom}</td>
          <td>{$curr_operation.CCAM_code}</td>
          <td>{$curr_operation.cote}</td>
          <td>{$curr_operation.duree}</td>
        </tr>
        {/foreach}
        {/foreach}
      </table>
		</td>

    <td width="50%">
      <table class="form>
      </table>
    </td>
	</tr>

</table>