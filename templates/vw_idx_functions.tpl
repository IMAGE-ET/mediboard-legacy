{literal}
<script language="javascript">
function setColor(color) {
	var f = document.editFrm;

	if (color) {
		f.color.value = color;
	}

	document.getElementById('test').style.background = '#' + f.color.value;
}
</script>
{/literal}

<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m=mediusers&tab=1&userfunction=0"><strong>Créer une fonction</strong></a>

    <table class="color">
      
    <tr>
      <th>listes des fonctions</th>
      <th>groupe</th>
      <th>couleur</th>
    </tr>
    
		{foreach from=$functions item=curr_function}
    <tr>
      <td><a href="index.php?m=mediusers&tab=1&userfunction={$curr_function.function_id}">{$curr_function.text}</a></td>
      <td><a href="index.php?m=mediusers&tab=1&userfunction={$curr_function.function_id}">{$curr_function.mygroup}</a></td>
      <td style="background: #{$curr_function.color}" />
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

  	<form name="editFrm" action="./index.php?m=mediusers" method="post">
  	<input type="hidden" name="dosql" value="do_functions_aed">
		<input type="hidden" name="function_id" value="{$functionsel.function_id}">
  	<input type="hidden" name="del" value="0">

    <table class="form">

    <tr>
      <th class="category" colspan="2">
     {if $functionsel.exist}
        Modification de la fonction &lsquo;{$functionsel.text}&rsquo;
        {else}
        Création d'une fonction
        {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory">Intitulé:</th>
      <td><input type="text" name="text" value="{$functionsel.text}" /></td>
    </tr>
    
    <tr>
      <th class="mandatory">Groupe:</th>
      <td>
      	<select name="group_id">
      	{foreach from=$groups item=curr_group}
      		{if $curr_group.group_id == $functionsel.group_id}
      			<option value="{$curr_group.group_id}" selected="selected">{$curr_group.text}</option>
      		{else}
      			<option value="{$curr_group.group_id}">{$curr_group.text}</option>
      		{/if}
      	{/foreach}
      	</select>
      </td>
    </tr>

    <tr>
      <th>Couleur:</th>
      <td>
        <span id="test" title="test" style="background: #{$defColor};"><a href="#" onClick="newwin=window.open('./index.php?m=public&a=color_selector&dialog=1&callback=setColor', 'calwin', 'width=320, height=300, scollbars=false');">cliquez ici</a></span>
        <input type="hidden" name="color" value="$functionsel.color" />
      </td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        {if $functionsel.exist}
        <input type="reset" value="Annuler">
        <input type="submit" name="btnFuseAction" value="Modifier">
        {else}
        <input type="submit" name="btnFuseAction" value="Créer">
        {/if}
      </td>
    </tr>

    </table>

    </form>

    {if $functionsel.exist}
    <form name="group" action="./index.php?m=mediusers" method="post">
    <input type="hidden" name="dosql" value="do_functions_aed">
    <input type="hidden" name="function_id" value="{$functionsel.function_id}">
    <input type="hidden" name="del" value="1">

    <table class="form">

    <tr>
      <th class="category">Supression de la fonction &lsquo;{$functionsel.text}&rsquo;</th>
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
