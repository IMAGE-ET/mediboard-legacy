{literal}
<script language="javascript">
function checkFrm() {
  var form = document.editFrm;
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

    <a href="index.php?m={$m}&amp;tab={$tab}&amp;chambre_id=0"><strong>Créer un chambre</strong></a>

    <table class="tbl">
      
    <tr>
      <th colspan="3">Liste des chambres</th>
    </tr>
    
    <tr>
      <th>Intitulé</th>
      <th>Caracteristiques</th>
      <th>Service</th>
    </tr>
    
	{foreach from=$chambres item=curr_chambre}
    <tr>
      <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;chambre_id={$curr_chambre->chambre_id}">{$curr_chambre->nom}</a></td>
      <td class="text">{$curr_chambre->caracteristiques|nl2br}</td>
      <td><a href="index.php?m={$m}&amp;tab=vw_idx_services&amp;service_id={$curr_chambre->_ref_service->service_id}">{$curr_chambre->_ref_service->nom}</td>
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="halfPane">

    <form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkGroup()">

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
      <th class="mandatory"><label for="editFrm_nom" title="intitulé du chambre, obligatoire.">Intitulé:</label></th>
      <td><input type="text" name="nom" value="{$chambreSel->nom}" /></td>
    </tr>

	<tr>
      <th class="mandatory"><label for="editFrm_service_id" title="Service auquel la chambre est rattaché, obligatoire.">Service:</label></th>
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
      <th><label for="editFrm_caracteristiques" title="Caracteristiques du chambre.">Caracteristiques:</label></th>
      <td>
        <textarea name="caracteristiques" rows="4">{$chambreSel->caracteristiques}</textarea></td>
    </tr>

    {if $chambreSel->chambre_id}
    <tr>
      <th class="category" colspan="2">Chambres</th>
    </tr>
    {/if}    

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

  </td>
</tr>

</table>
