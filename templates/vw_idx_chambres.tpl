{literal}
<script language="javascript">

function pageMain() {
  initGroups("service");
}

function checkChambre() {
  var form = document.editChambre;
  var field = null;
    
  if (field = form.nom) {
    if (field.value.length == 0) {
      alert("Intitulé manquant");
      field.focus();
      return false;
    }
  }
    
  if (field = form.service_id) {
    if (field.value == 0) {
      alert("Service manquant");
      field.focus();
      return false;
    }
  }
    
  return true;
}

function checkLit() {
  var form = document.editLit;
  var field = null;
    
  if (field = form.nom) {
    if (field.value.length == 0) {
      alert("Intitulé manquant");
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
  <td class="halfPane">

    <table class="tbl">
      
    <tr>
      <th colspan="4">Liste des chambres</th>
    </tr>
    
    <tr>
      <th>Intitulé</th>
      <th>Caracteristiques</th>
      <th>Lits disponibles</th>
    </tr>
    
	{foreach from=$services item=curr_service}
	<tr class="groupcollapse" id="service{$curr_service->service_id}" onclick="flipGroup({$curr_service->service_id}, 'service')">
	  <td colspan="4">{$curr_service->nom}</td>
	</tr>
	{foreach from=$curr_service->_ref_chambres item=curr_chambre}
    <tr class="service{$curr_service->service_id}">
      <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;chambre_id={$curr_chambre->chambre_id}&amp;lit_id=0">{$curr_chambre->nom}</a></td>
      <td class="text">{$curr_chambre->caracteristiques|nl2br}</td>
      <td>
      {foreach from=$curr_chambre->_ref_lits item=curr_lit}
        <a href="?m={$m}&amp;tab={$tab}&amp;chambre_id={$curr_lit->chambre_id}&amp;lit_id={$curr_lit->lit_id}">{$curr_lit->nom}</a>
      {/foreach}
      </td>
    </tr>
    {/foreach}
    {/foreach}
      
    </table>

  </td>
  
  <td class="halfPane">

    <a href="index.php?m={$m}&amp;tab={$tab}&amp;chambre_id=0"><strong>Créer un chambre</strong></a>

    <form name="editChambre" action="?m={$m}" method="post" onsubmit="return checkChambre()">

    <input type="hidden" name="dosql" value="do_chambre_aed" />
    <input type="hidden" name="chambre_id" value="{$chambreSel->chambre_id}" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      {if $chambreSel->chambre_id}
        Modification du chambre &lsquo;{$chambreSel->nom}&rsquo;
      {else}
        Création d'un chambre
      {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="editChambre_nom" title="intitulé du chambre, obligatoire.">Intitulé:</label></th>
      <td><input type="text" name="nom" value="{$chambreSel->nom}" /></td>
    </tr>

	<tr>
      <th class="mandatory"><label for="editChambre_service_id" title="Service auquel la chambre est rattaché, obligatoire.">Service:</label></th>
	  <td>
        <select name="service_id">
          <option value="">&mdash; Choisir un service &mdash;</option>
        {foreach from=$services item=curr_service}
          <option value="{$curr_service->service_id}" {if $curr_service->service_id == $chambreSel->service_id}selected="selected"{/if}>{$curr_service->nom}</option>
        {/foreach}
        </select>
	  </td>
	</tr>
	    
    <tr>
      <th><label for="editChambre_caracteristiques" title="Caracteristiques du chambre.">Caractéristiques:</label></th>
      <td>
        <textarea name="caracteristiques" rows="4">{$chambreSel->caracteristiques}</textarea></td>
    </tr>

    <tr>
      <td class="button" colspan="2">
        {if $chambreSel->chambre_id}
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'la chambre', '{$chambreSel->nom|escape:javascript}')" />
        {else}
        <input type="submit" name="btnFuseAction" value="Créer" />
        {/if}
      </td>
    </tr>

    </table>

	</form>
    
    <table class="form">

    {if $chambreSel->chambre_id}
    <tr>
      <th class="category" colspan="2">Lits</th>
    {foreach from=$chambreSel->_ref_lits item=curr_lit}
    <tr>
      <th>Lit:</th>
      <td><a href="?m={$m}&amp;tab={$tab}&amp;chambre_id={$curr_lit->chambre_id}&amp;lit_id={$curr_lit->lit_id}">{$curr_lit->nom}</a></td>
    </tr>
	{/foreach}
    <tr>
      <td><strong><a href="?m={$m}&amp;tab={$tab}&amp;chambre_id={$curr_lit->chambre_id}&amp;lit_id=0">Ajouter un lit:<a/></strong>
      <td>
        <form name="editLit" action="?m={$m}" method="post" onsubmit="return checkLit()">

        <input type="hidden" name="dosql" value="do_lit_aed" />
        <input type="hidden" name="lit_id" value="{$litSel->lit_id}" />
        <input type="hidden" name="chambre_id" value="{$chambreSel->chambre_id}" />
        <input type="hidden" name="del" value="0" />
        <input type="text" name="nom" value="{$litSel->nom}" />
        {if $litSel->lit_id}
        <input type="submit" value="Modifier" />
        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le lit', '{$litSel->nom|escape:javascript}')" />
        {else}
        <input type="submit" value="Créer" />
        {/if}

        </form>
      </td>
    </tr>
    {/if}    

	
    </table>

  </td>
</tr>

</table>
