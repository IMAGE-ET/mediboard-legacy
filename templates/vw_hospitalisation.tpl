<table class="main">
  <tr>
    <td>
      <form name="hospitalisation" action="index.php" method="GET">
      <input type="hidden" name="m" value="dPstats" />
      <table class="form">
        <tr>
          <th colspan="4" class="category">Occupation des lits</th>
        </tr>
        <tr>
          <th>Début:</th>
          <td><input type="text" name="debutact" value="{$debutact}" /></td>
          <th>Salle:</th>
          <td>
            <select name="salle_id">
              <option value="0">&mdash Tous les services</option>
              {foreach from=$listServices item=curr_service}
              <option value="{$curr_service->service_id}" {if $curr_service->service_id == $service_id}selected="selected"{/if}>
                {$curr_service->nom}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <th>Fin:</th>
          <td><input type="text" name="finact" value="{$finact}" /></td>
          <th>Praticien:</th>
          <td>
            <select name="prat_id">
              <option value="0">&mdash Tous les praticiens</option>
              {foreach from=$listPrats item=curr_prat}
              <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $prat_id}selected="selected"{/if}>
                {$curr_prat->_view}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="4" class="button"><button type="submit">Go</button></td>
        </tr>
        <tr>
          <td colspan="4" class="button">
            <img src='?m=dPstats&amp;a=graph_occupationlits&amp;suppressHeaders=1&amp;debut={$debutact}&amp;fin={$finact}&amp;service_id={$service_id}&amp;prat_id={$prat_id}' />
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>