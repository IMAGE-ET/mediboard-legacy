<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m=mediusers&tab=2&usergroup=0"><strong>Créer un groupe</strong></a>

    <table class="color">
      
    <tr>
      <th>liste des groupes</th>
    </tr>
    
		{foreach from=$groups item=curr_group}
    <tr>
      <td><a href="index.php?m=mediusers&tab=2&usergroup={$curr_group.group_id}">{$curr_group.text}</a></td>
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

    <form name="group" action="./index.php?m=mediusers" method="post">
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
      <th class="mandatory">Intitulé:</th>
      <td><input type="text" name="text" value="{$groupsel.text}" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        {if $groupsel.exist}
        <input type="reset" value="Annuler">
        <input type="submit" name="btnFuseAction" value="Modifier">
        {else}
        <input type="submit" name="btnFuseAction" value="Créer">
        {/if}
      </td>
    </tr>

    </table>

    </form>

    {if $groupsel.exist}
    <form name="group" action="./index.php?m=mediusers" method="post">
    <input type="hidden" name="dosql" value="do_groups_aed" />
		<input type="hidden" name="group_id" value="{$groupsel.group_id}" />
    <input type="hidden" name="del" value="1" />

    <table class="form">

    <tr>
      <th class="category">Supression du groupe &lsquo;{$groupsel.text}&rsquo;</th>
    </tr>
    
    <tr>
      <td class="button"><input class="button" type="submit" name="btnFuseAction" value="Supprimer" /></td>
    </tr>

    </table>

    </form>
    {/if}

  </td>
</tr>

</table>
