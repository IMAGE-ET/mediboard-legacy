<table>
  <tr>
    <td><< Semaine du Lundi {$debut} au dimanche {$fin} >></td>
    <td>
      <form action="index.php" name="selection" method="get">

      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="{$tab}">

      <label for="selection_chirSel">Choisir un chirurgien:</label>
      <select name="chirSel" onchange="this.form.submit()">
        <option value="-1" {if $chirSel == -1} selected="selected" {/if}>Aucun chirurgien</option>
        {foreach from=$listChirs item=curr_chir}
        <option value="{$curr_chir->user_id}" {if $chirSel == $curr_chir->user_id} selected="selected" {/if}>
          {$curr_chir->user_last_name} {$curr_chir->user_first_name}
        </option>
        {/foreach}
      </select>
  
      </form>
    </td>
  </tr>
  <tr>
    <td valign="top">
      <table>
        <tr>
          <th></th>
          <th>Lundi</th>
          <th>Mardi</th>
          <th>Mercredi</th>
          <th>Jeudi</th>
          <th>Vendredi</th>
          <th>Samedi</th>
          <th>Dimanche</th>
        </tr>
        {foreach from=$listHours item=curr_hour}
        <tr>
          <th>{$curr_hour}h</th>
          {foreach from=$plages item=curr_day}
          {assign var="isNotIn" value=1}
          {if $curr_day.plages}
            {foreach from=$curr_day.plages item=curr_plage}
              {if $curr_plage->_hour_deb == $curr_hour}
              <td bgcolor="#aaaaaa" rowspan="{$curr_plage->_hour_fin-$curr_plage->_hour_deb}"></td>
              {/if}
              {if ($curr_plage->_hour_deb <= $curr_hour) && ($curr_plage->_hour_fin > $curr_hour)}
                {assign var="isNotIn" value=0}
              {/if}
            {/foreach}
          {/if}
          {if $isNotIn}
          <td bgcolor="#ffffff"></td>
          {/if}
          {/foreach}
        </tr>
        {/foreach}
      </table>
    </td>
    <th valign="top">Formulaire de gestion des plages de consultation</th>
  </tr>
</table>