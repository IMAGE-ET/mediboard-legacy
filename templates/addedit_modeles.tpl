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

function checkForm() {
  var form = document.editFrm;
  var field = null;
   
  if (field = form.elements['chir_id']) {
    if (field.value == 0) {
      alert("Utilisateur indéterminé");
      field.focus();
      return false;
    }
  }

  if (field = form.elements['nom']) {    
    if (field.value == 0) {
      alert("Intitulé indéterminé");
      field.focus();
      return false;
    }
  }
    
  return true;
}

{/literal}
</script>

<form name="editFrm" action="?m={$m}" method="POST" onsubmit="return checkForm()">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_modele_aed" />
<input type="hidden" name="compte_rendu_id" value="{$compte_rendu->compte_rendu_id}" />

<table class="main">

<tr>
  <td>
  
<table class="form">
  <tr>
    <th class="category" colspan="2">Informations sur le modèle</th>
  </tr>
  
  <tr>
    <th>Nom: </th>
    <td><input type="text" name="nom" value="{$compte_rendu->nom}"></td>
  </tr>
  
  <tr>
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
  </tr>
  
  <tr>
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
  </tr>
  
  <tr>
    <td class="button" colspan="2">
    {if $compte_rendu->compte_rendu_id}
      <input type="submit" value="modifier" />
      <input type="button" value="supprimer" onclick="supprimer()" />
      <input type="button" value="nouveau" onclick="nouveau()" />
    {else}
      <input type="submit" value="créer" />
    {/if}
    </td>
  </tr>
</table>

  </td>
  <td class="greedyPane" style="height: 400px">
  {if $compte_rendu->compte_rendu_id}
    <textarea style="width: 99%" id="htmlarea" name="source" rows="40">
    {$compte_rendu->source}
    </textarea>
  {/if}
  </td>
</tr>

</table>

</form>