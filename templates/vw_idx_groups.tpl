{literal}
<script language="javascript">
function checkGroup() {
  var form = document.group;
    
  if (form.text.value.length == 0) {
    alert("Intitulé manquant");
    form.text.focus();
    return false;
  }
    
  return true;
}
</script>
{/literal}

<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m={$m}&tab={$tab}&usergroup=0"><strong>Créer un groupe</strong></a>

    <table class="color">
      
    <tr>
      <th>liste des groupes</th>
    </tr>
    
		{foreach from=$groups item=curr_group}
    <tr>
      <td><a href="index.php?m={$m}&tab={$tab}&usergroup={$curr_group.group_id}">{$curr_group.text}</a></td>
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

    <form name="group" action="./index.php?m={$m}" method="post" onsubmit="return checkGroup()">
    <input type="hidden" name="dosql" value="do_groups_aed" />
		<input type="hidden" name="group_id" value="{$groupsel.group_id}" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      {if $groupsel.exist}
        Modification du groupe &lsquo;{$groupsel.text}&rsquo;
      {else}
        Création d'un groupe
      {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="group_text" title="intitulé du groupe, obligatoire.">Intitulé:</label></th>
      <td><input type="text" name="text" id="group_text" value="{$groupsel.text}" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        {if $groupsel.exist}
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}" />
        {else}
        <input type="submit" name="btnFuseAction" value="Créer" />
        {/if}
      </td>
    </tr>

    </table>

  </td>
</tr>

</table>
