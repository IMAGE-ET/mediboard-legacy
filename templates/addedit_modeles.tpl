{literal}
<script language="JavaScript" type="text/javascript">
function nouveau() {
  var url = "index.php?m=dPcompteRendu&tab=addedit_modeles&new=1";
  window.location.href = url;
}

function supprimer() {
  var form = document.editFrm;
  form.del.value = 1;
  form.submit();
}

{/literal}
</script>
  
<form name="editFrm" action="?m={$m}" method="POST">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_modele_aed" />
<input type="hidden" name="compte_rendu_id" value="{$compte_rendu->compte_rendu_id}" />

<table class="form">
  <tr>
    <th class="category">Informations sur le modèle</th>
    <th>Nom: </th>
    <td><input type="text" name="nom" value="{$compte_rendu->nom}"></td>
    <th>Praticien:</th>
    <td>
      <select name="chir_id">
        <option value="0">&mdash; Choisir un praticien &mdash;</options>
        {foreach from=$listPrat item=curr_prat}
          <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $prat_id} selected="selected" {/if}>
            {$curr_prat->user_last_name} {$curr_prat->user_first_name}
          </option>
        {/foreach}
      </select>
    </td>
    <th>Type de compte-rendu: </th>
    <td>
      <select name="type">
        {foreach from=$listType item=curr_type}
          <option value="{$curr_type}" {if $curr_type == $compte_rendu->type} selected="selected" {/if}>
            {$curr_type}
          </option>
        {/foreach}
      </select>
    </td>
    {if $compte_rendu->compte_rendu_id}
    <td class="button">
    <input type="submit" value="modifier" />
    <input type="button" value="supprimer" onclick="supprimer()" />
    <input type="button" value="nouveau" onclick="nouveau()" />
    </td>
    {else}
    <td class="button"><input type="submit" value="créer" /></td>
    {/if}
  </tr>
</table>

{if $compte_rendu->compte_rendu_id}
<textarea style="width: 99%" id="htmlarea" name="source" rows="40">
      {$compte_rendu->source}
</textarea>
{/if}

</form>