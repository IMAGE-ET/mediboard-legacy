{literal}
<script language="javascript">
function checkForm() {
  var form = document.editFrm;
    
  if (form.text.value.length == 0) {
    alert("Intitul� manquant");
    form.text.focus();
    return false;
  }
    
  return true;
}

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

		<a href="index.php?m=mediusers&tab=1&userfunction=0"><strong>Cr�er une fonction</strong></a>

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

  	<form name="editFrm" action="./index.php?m=mediusers" method="post" onSubmit="return checkForm()">
  	<input type="hidden" name="dosql" value="do_functions_aed">
		<input type="hidden" name="function_id" value="{$functionsel.function_id}">
  	<input type="hidden" name="del" value="0">

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      {if $functionsel.exist}
        Modification de la fonction &lsquo;{$functionsel.text}&rsquo;
      {else}
        Cr�ation d'une fonction
      {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_text" title="Intitul� de la fonction. Obligatoire">Intitul�:</label></th>
      <td><input type="text" name="text" value="{$functionsel.text}" /></td>
    </tr>
    
    <tr>
      <th class="mandatory"><label for="editFrm_group_id" title="Groupe auquel se rattache la fonction">Groupe:</label></th>
      <td>
      	<select name="group_id">
      	{foreach from=$groups item=curr_group}
     			<option value="{$curr_group.group_id}" {if $curr_group.group_id == $functionsel.group_id} selected="selected" {/if}>
            {$curr_group.text}
          </option>
      	{/foreach}
      	</select>
      </td>
    </tr>

    <tr>
      <th>Couleur:</th>
      <td>
        <span id="test" title="test" style="background: #{$functionsel.color};">
          <a href="#" onClick="window.open('./index.php?m=public&a=color_selector&dialog=1&callback=setColor', 'calwin', 'width=320, height=300, scollbars=false');">cliquez ici</a>
        </span>
        <input type="hidden" name="color" value="{$functionsel.color}" />
      </td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
      {if $functionsel.exist}
        <input type="reset" value="R�initialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
      {else}
        <input type="submit" name="btnFuseAction" value="Cr�er">
      {/if}
      </td>
    </tr>

    </table>

    </form>
  </td>
</tr>

</table>
