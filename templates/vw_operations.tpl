<table class="main">

  <tr>
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

  {foreach from=$plages item=curr_plage}
	<tr>
		<td>
      <strong>Dr. {$curr_plage.lastname} {$curr_plage.firstname}
      de {$curr_plage.debut} à {$curr_plage.fin}</strong>
    </td>
  </tr>
  <tr>
    <td>
			<table class="tbl">
        <tr>
          <th>Heure</th>
          <th>Intervention</th>
          <th>Coté</th>
          <th>Anesthésie</th>
          <th>Remarques</th>
          <th>Patient</th>
          <th>Durée</th>
        </tr>
        {foreach from=$curr_plage.operations item=curr_operation}
        <tr>
          <td>{$curr_operation.heure}</td>
          <td>{$curr_operation.CCAM_libelle} (<i>{$curr_operation.CCAM_code}</i>)</td>
          <td>{$curr_operation.cote}</td>
          <td>{$curr_operation.type_anesth}</td>
          <td>{$curr_operation.remarques} {if $curr_operation.mat}({$curr_operation.mat}) {/if}</td>
          <td>{$curr_operation.nom} {$curr_operation.prenom}</td>
          <td>{$curr_operation.duree}</td>
        </tr>
        {/foreach}
      </table>
		</td>
	</tr>
  {/foreach}

</table>