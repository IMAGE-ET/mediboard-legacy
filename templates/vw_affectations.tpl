{literal}
<script language="JavaScript" type="text/javascript">
function flipChambre(chambre_id) {
  flipElementClass("chambre" + chambre_id, "chambrecollapse", "chambreexpand");
}

</script>
{/literal}  

<table class="main">

<tr>
  <td class="greedyPane">

    <table class="tbl">

    <tr>
    {foreach from=$services item=curr_service}
      <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;service_id={$curr_service->service_id}">{$curr_service->nom}</a></td>
    {/foreach}
    </tr>

	<tr>
    {foreach from=$services item=curr_service}
      <td>
      {foreach from=$curr_service->_ref_chambres item=curr_chambre}
        <table class="chambrecollapse" id="chambre{$curr_chambre->chambre_id}">
		  <tr><th class="chambre" colspan="2" onclick="flipChambre({$curr_chambre->chambre_id})">{$curr_chambre->nom}</th></tr>
		  {foreach from=$curr_chambre->_ref_lits item=curr_lit}
		  <tr class="lit" ><td colspan="2">{$curr_lit->nom}</td></tr>
		  <tr class="patient"><td colspan="2">John Average Smith</td></tr>
		  <tr class="dates"><td>date entrée</td><td>date sortie</td></tr>
		  {/foreach}
        </table>
      {/foreach}
      </td>
    {/foreach}
    </tr>
    
    </table>
    
  </td>
  <td class="pane">
  
    <table class="tbl">

    <tr><th>Hospitalisation à affecter</td></tr>
 
    {foreach from=$opNonAffectees item=curr_operation}
    <tr><td>{$curr_operation->_ref_pat->nom}</td></tr>
    {/foreach}
    
    </table>

  </td>
</tr>

</table>
