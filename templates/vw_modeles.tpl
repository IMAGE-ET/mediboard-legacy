<table class="main">
  <tr>
    <td>
      <form name="selectPrat" action="index.php" method="GET">
      <input type="hidden" name="m" value="{$m}" />
        <select name="selPrat" onchange="submit()">
          <option value="0">&mdash; Choisir un praticien &mdash;</options>
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
          <th>Nom</th><th>Type</th><th>Supprimer</th>
        </tr>
        {foreach from=$listModele item=curr_modele}
        <tr>
          <td><a href="index.php?m={$m}&tab=addedit_modeles&compte_rendu_id={$curr_modele->compte_rendu_id}">
          {$curr_modele->nom}</a></td>
          <td><a href="index.php?m={$m}&tab=addedit_modeles&compte_rendu_id={$curr_modele->compte_rendu_id}">
          {$curr_modele->type}</a></td>
          <td>
            <form name="editFrm" action="?m={$m}" method="POST">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="1" />
            <input type="hidden" name="dosql" value="do_modele_aed" />
            <input type="hidden" name="compte_rendu_id" value="{$curr_modele->compte_rendu_id}" />
            <input type="submit" value="supprimer" />
            </form>
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
  {/if}
</table>