<table class="main">
  <tr>
    <td>
      <form name="selectPrat" action="index.php" method="GET">
      <input type="hidden" name="m" value="{$m}" />
        <select name="selPrat" onchange="submit()">
          {foreach from=$listPrat item=curr_prat}
            <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $prat_id} selected="selected" {/if}>
              {$curr_prat->user_last_name} {$curr_prat->user_first_name}
            </option>
          {/foreach}
        </select>
      </form>
    </td>
  </tr>
  {if $listModele}
  <tr>
    <td>
      <table class="tbl">
        <tr>
          <th>Nom</th><th>Type</th><th>Aperçu</th><th>Supprimer</th>
        </tr>
        {foreach from=$listModele item=curr_modele}
        <tr>
          <td><a href="index.php?m={$m}&tab=addedit_modeles&compte_rendu_id={$curr_modele->compte_rendu_id}">
          {$curr_modele->nom}</a></td>
          <td><a href="index.php?m={$m}&tab=addedit_modeles&compte_rendu_id={$curr_modele->compte_rendu_id}">
          {$curr_modele->type}</a></td>
          <td class="text">{$curr_modele->source}</td>
          <td><input type="button" value="supprimer" /></td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
  {/if}
</table>