<!-- $Id$ -->

{literal}
<script language="javascript">
function checkSalle() {
  var form = document.salle;
    
  if (form.nom.value.length == 0) {
    alert("Intitulé manquant");
    form.nom.focus();
    return false;
  }
    
  return true;
}
</script>
{/literal}

<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m={$m}&tab={$tab}&usersalle=0"><strong>Créer une salle</strong></a>

    <table class="color">
      
    <tr>
      <th>liste des salles</th>
    </tr>
    
    {foreach from=$salles item=curr_salle}
    <tr>
      <td><a href="index.php?m={$m}&tab={$tab}&usersalle={$curr_salle.id}">{$curr_salle.nom}</a></td>
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

    <form name="salle" action="./index.php?m={$m}" method="post" onsubmit="return checkSalle()">
    <input type="hidden" name="dosql" value="do_salle_aed" />
		<input type="hidden" name="id" value="{$sallesel.id}" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      {if $sallesel.exist}
        Modification de la salle &lsquo;{$sallesel.nom}&rsquo;
      {else}
        Création d'une salle
      {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory">Intitulé:</th>
      <td><input type="text" name="nom" value="{$sallesel.nom}" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        {if $sallesel.exist}
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
        {else}
        <input type="submit" name="btnFuseAction" value="Créer">
        {/if}
      </td>
    </tr>

    </table>

  </td>
</tr>

</table>
