<!--  $Id$ -->

{literal}
<script language="javascript">

function checkForm() {
  var form = document.editFrm;
  var field = null;
   
  if (field = form.elements['user_id']) {
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
</script>
{/literal}

<table class="main">

<tr>
  <td class="greedyPane">

    <form name="filterFrm" action="?" method="get">
    
    <input type="hidden" name="m" value="{$m}">
        
    <table class="form">

      <tr>
        <th><label for="filterFrm_filter_user_id" title="Filtrer les listes pour cet utilisateur">Utilisateur:</label></th>
        <td>
          <select name="filter_user_id" onchange="this.form.submit()">
            <option value="0">&mdash; Tous les utilisateurs</option>
            {foreach from=$users item=curr_user}
            <option value="{$curr_user->user_id}" {if $curr_user->user_id == $user_id} selected="selected" {/if}>
              {$curr_user->user_last_name} {$curr_user->user_first_name}
            </option>
            {/foreach}
          </select>
        </td>
      </tr>
    </table>

    </form>
    
    <table class="tbl">
    
    <tr>
      <th colspan="4"><strong>Listes de choix créées</strong></th>
    </tr>
    
    <tr>
      <th>Utilisateur</th>
      <th>Nom</th>
      <th>Valeurs</th>
      <th>Compte-rendu associé</th>
    </tr>

    {foreach from=$listes item=curr_liste}
    <tr>
      {eval var=$curr_liste->liste_choix_id assign="liste_id"}
      {assign var="href" value="?m=$m&amp;tab=$tab&amp;liste_id=$liste_id"}
      <td><a href="{$href}">{$curr_liste->_ref_chir->_view}</a></td>
      <td><a href="{$href}">{$curr_liste->nom}</a></td>
      <td><a href="{$href}">{$curr_liste->_valeurs|@count}</a></td>
      {if $curr_liste->_ref_modele->compte_rendu_id}
      <td><a href="{$href}">{$curr_liste->_ref_modele->nom} ({$curr_liste->_ref_modele->type})</a></td>
      {else}
      <td><a href="{$href}">&mdash; Tous &mdash;</a></td>
      {/if}
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

	<a href="index.php?m={$m}&amp;tab={$tab}&amp;liste_id=0"><strong>Créer une liste de choix</strong></a>

    <form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">

    <input type="hidden" name="dosql" value="do_liste_aed" />
    <input type="hidden" name="liste_choix_id" value="{$liste->liste_choix_id}" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      {if $liste->liste_choix_id}
        Modification d'une liste
      {else}
        Création d'une liste
      {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_user_id" title="Utilisateur concerné, obligatoire.">Utilisateur:</label></th>
      <td>
        <select name="chir_id">
          <option value="0">&mdash; Choisir un utilisateur</option>
          {foreach from=$users item=curr_user}
          <option value="{$curr_user->user_id}" {if $curr_user->user_id == $liste->chir_id} selected="selected" {/if}>
            {$curr_user->user_last_name} {$curr_user->user_first_name}
          </option>
          {/foreach}
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_name" title="intitulé de la liste, obligatoire.">Intitulé:</label></th>
      <td><input type="text" name="nom" value="{$liste->nom}" /></td>
    </tr>
    
    <tr>
      <th><label for="editFrm_compte_rendu_id" title="Compte-rendu associé.">Compte-rendu</label></th>
      <td>
        <select name="compte_rendu_id">
          <option value="0">&mdash; Tous &mdash;</option>
          {foreach from=$listCr item=curr_cr}
          <option value="{$curr_cr->compte_rendu_id}">{$curr_cr->nom}</option>
          {/foreach}
        </select>

    <tr>
      <td class="button" colspan="2">
        {if $liste->liste_choix_id}
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'la liste', '{$liste->nom|escape:javascript}')" />
        {else}
        <input type="submit" value="Créer" />
        {/if}
      </td>
    </tr>

    </table>
    
    </form>

  </td>
  
  {if $liste->liste_choix_id}
  <td class="pane">
  
    <table class="form">
      {if $liste->_valeurs|@count}
      <tr><th class="category" colspan="2">Choix diponibles</th></tr>
      {foreach from=$liste->_valeurs item=curr_valeur}
      <tr><td>{$curr_valeur}</td>
        <td>
          <form name="delFrm{$liste->liste_choix_id}" action="?m={$m}" method="post" onsubmit="return checkForm()">
          <input type="hidden" name="dosql" value="do_liste_aed" />
          <input type="hidden" name="liste_choix_id" value="{$liste->liste_choix_id}" />
          <input type="hidden" name="del" value="0" />
        <input type="hidden" name="valeurs" value="{$liste->valeurs|escape:javascript}" />
          <input type="hidden" name="_del" value="{$curr_valeur}" />
          <button type="submit"><img src="modules/dPcompteRendu/images/trash.png" /></button>
          </form>
        </td>
      </tr>
      {/foreach}
      {/if}
      <tr><th class="category" colspan="2">Ajouter un choix</th></tr>
      <tr><td colspan="2">
        <form name="addFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">
        <input type="hidden" name="dosql" value="do_liste_aed" />
        <input type="hidden" name="liste_choix_id" value="{$liste->liste_choix_id}" />
        <input type="hidden" name="del" value="0" />
        <input type="hidden" name="valeurs" value="{$liste->valeurs|escape:javascript}" />
        <input type="text" name="_new" value="" />
        <button type="submit"><img src="modules/dPcompteRendu/images/check.png" /></button>
        </form>
      </td></tr>
    </table>
  
  </td>
  {/if}
  
</tr>

</table>
