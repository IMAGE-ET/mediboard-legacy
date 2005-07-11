<table class="main">

  <tr>
    <td>
      <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="{$t}">
      Choisir une salle :
      <select name="salle" onchange="this.form.submit()">
        <option value="0">Aucune salle</option>
        {foreach from=$listSalles item=curr_salle}
        <option value="{$curr_salle->id}" {if $curr_salle->id == $salle} selected="selected" {/if}>
          {$curr_salle->nom}
        </option>
        {/foreach}
      </select>
      </form>
    </td>
  </tr>

  {foreach from=$plages item=curr_plage}
  <tr>
    <td>
      <strong>Dr. {$curr_plage->_ref_chir->_view}
      de {$curr_plage->debut|date_format:"%Hh%M"} à {$curr_plage->fin|date_format:"%Hh%M"}</strong>
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
          <th>Entrée en salle</th>
          <th>Sortie de salle</th>
        </tr>
        {foreach from=$curr_plage->_ref_operations item=curr_operation}
        <tr>
          <td>{$curr_operation->time_operation|date_format:"%Hh%M"}</td>
          <td class="text">
            {$curr_operation->_ext_code_ccam->libelleLong} (<i>{$curr_operation->_ext_code_ccam->code}</i>)
            {if $curr_operation->CCAM_code2}
            <br />{$curr_operation->_ext_code_ccam2->libelleLong} (<i>{$curr_operation->_ext_code_ccam2->code}</i>)
            {/if}
          </td>
          <td>{$curr_operation->cote}</td>
          <td>{$curr_operation->_lu_type_anesth}</td>
          <td class="text">{$curr_operation->rques|nl2br} {if $curr_operation->materiel}({$curr_operation->materiel}) {/if}</td>
          <td>{$curr_operation->_ref_pat->_view}</td>
          <td>{$curr_operation->temp_operation|date_format:"%Hh%M"}</td>
          <td align="center">
            {if $curr_operation->entree_bloc}
            {$curr_operation->entree_bloc}
            {else}
			<form name="editFrm{$curr_operation->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="entree" value="{$curr_operation->operation_id}" />
              <input type="submit" value="Entrée" />
            </form>
            {/if}
          </td>
          <td align="center">
            {if $curr_operation.sortie}
            {$curr_operation->sortie_bloc}
            {else}
            <form name="editFrm{$curr_operation->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="sortie" value="{$curr_operation->operation_id}" />
              <input type="submit" value="Sortie" />
            </form>
            {/if}
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
  {/foreach}

</table>