<!-- $Id$ -->

{literal}
<script language="javascript">
function editPerm( id, gon, it, vl, nm ) {
	var f = document.frmPerms;

	f.sqlaction2.value = "{/literal}{tr}edit{/tr}{literal}";
	
	f.permission_id.value = id;
	f.permission_item.value = it;
	f.permission_item_name.value = nm;
	for (var i=0, n=f.permission_grant_on.options.length; i < n; i++) {
		if (f.permission_grant_on.options[i].value == gon) {
			f.permission_grant_on.selectedIndex = i;
			break;
		}
	}
	f.permission_value.selectedIndex = vl+1;
	f.permission_item_name.value = nm;
}

function clearIt() {
	var f = document.frmPerms;
	f.sqlaction2.value =  "{/literal}{tr}edit{/tr}{literal}";
	f.permission_id.value = 0;
	f.permission_grant_on.selectedIndex = 0;
}

function delIt(id) {
	if (confirm( 'Are you sure you want to delete this permission?' )) {
		var f = document.frmPerms;
		f.del.value = 1;
		f.permission_id.value = id;
		f.submit();
	}
}

var tables = new Array;
{/literal}
{foreach from=$pgos item=pgo key=module}
	tables['{$module}'] = '{$pgo.table}';
{/foreach}
{literal}

function popPermItem() {
	var f = document.frmPerms;
	var pgo = f.permission_grant_on.options[f.permission_grant_on.selectedIndex].value;
	if (!(pgo in tables)) {
		alert( 'No list associated with the module ' + pgo + '.' );
		return;
	}
	
	url = './index.php?m=admin';
	url+= '&a=selector';
	url+= '&dialog=1';
	url+= '&callback=setPermItem';
	url+= '&table=' + tables[pgo];
	window.open(url, 'selector', 'left=50, top=50, height=250, width=400, resizable=yes')
}

// Callback function for the generic selector
function setPermItem( key, val ) {
	var f = document.frmPerms;
	if (val != '') {
		f.permission_item.value = key;
		f.permission_item_name.value = val;
	} else {
		f.permission_item.value = '-1';
		f.permission_item_name.value = 'all';
	}
}
</script>
{/literal}

<table class="main">
  <tr>
    <td class="halfPane">
    
    <table class="tbl">
      <tr>
      	<th />
      	<th>{tr}Module{/tr}</th>
      	<th>{tr}Item{/tr}</th>
      	<th>{tr}Type{/tr}</th>
      	<th />
      </tr>

      {foreach from=$perms item=perm} 
      <tr>
        <td>
          {if $canEdit}
          <a href="#" onClick="editPerm({$perm.perm_id}, '{$perm.perm_module}', {$perm.perm_item}, {$perm.perm_value}, '{$perm.perm_item_name}');" title="{tr}edit{/tr}">
          	{html_image file="./images/icons/stock_edit-16.png"}
          </a>
          {/if}
        </td>

		{if $perm.perm_module == "all" && $perm.perm_item == -1 && $perm.perm_value == -1}
			{assign var="bg_color" value="ffc235"}
		{elseif $perm.perm_item == -1 && $perm.perm_value == -1}
			{assign var="bg_color" value="ffff99"}
		{else}
			{assign var="bg_color" value="transparent"}
		{/if}

		<td style="background: #{$bg_color};">{tr}{$perm.perm_module}{/tr}</td>
	
		<td style="width: 100%;">{$perm.perm_item_name}</td>
	
		<td>{tr}{$perm.perm_value_name}{/tr}</td>
	
		<td>
		{if $canEdit}
		  <a href="javascript:delIt({$perm.perm_id});" title="{tr}delete{/tr}">
        	{html_image file="./images/icons/stock_delete-16.png"}
		  </a>
		{/if}
		</td>
	  </tr>	
	  {/foreach}
	</table>
	
    <table>
      <tr>
        <td>{tr}Key{/tr}</td>
		<td style="width: 20px; background: #ffc235;"></td>
		<td> = {tr}Super User{/tr}</td>
		<td style="width: 20px; background: #ffff99;"></td>
		<td> = {tr}full access to module{/tr}</td>
      </tr>
    </table>

  	</td>
  	<td class="halfPane">
	
	{if $canEdit}
	<form name="frmPerms" method="post" action="?m={$m}">

	<input type="hidden" name="del" value="0" />
	<input type="hidden" name="dosql" value="do_perms_aed" />
	<input type="hidden" name="user_id" value="{$user_id}" />
	<input type="hidden" name="permission_user" value="{$user_id}" />
	<input type="hidden" name="permission_id" value="0" />
	<input type="hidden" name="permission_item" value="-1" />

	<table class="form">
	  <tr>
		<th class="category" colspan="3">{tr}Add or Edit Permissions{/tr}</th>
	  </tr>
	  
	  <tr>
	    <th>{tr}Module{/tr}:</th>
	    <td colspan="2">
	      <select name="permission_grant_on">
	      	{html_options options=$modules selected="all"}
	      </select>
		</td>
	  </tr>

	  <tr>
		<th>{tr}Item{/tr}:</th>
		<td class="readonly">
		  <input type="text" name="permission_item_name" class="text" size="30" value="all" readonly="readonly" />
		</td>
	    <td class="button">	
		  <input type="button" name="choose_item" class="text" value="..." onclick="popPermItem();" />
		</td>
	  </tr>

      <tr>
	    <th>{tr}Level{/tr}:</th>
	    <td colspan="2">
	      <select name="permission_value">
	        {html_options options=$pvs selected="0"}
	      </select>
        </td>
      </tr>

      <tr>
        <td class="button" colspan="3">
		  <input type="reset" value="{tr}clear{/tr}" class="button" name="sqlaction" onclick="clearIt();">
		  <input type="submit" value="{tr}add{/tr}" class="button" name="sqlaction2">
	    </td>
	  </tr>
    </table>
    
    </form>

	<form name="cpPerms" method="post" action="?m={$m}">

	<input type="hidden" name="dosql" value="do_perms_cp" />
	<input type="hidden" name="user_id" value="{$user_id}" />
	<input type="hidden" name="permission_user" value="{$user_id}" />

	<table class="form">
      <tr>
	    <th class="category" colspan="2">{tr}Copy Permissions from Template{/tr}</th>
	  </tr>
	  
	  <tr>
	    <th>{tr}Copy Permissions from User{/tr}:</th>
	    <td>
	      <select name="temp_user_name">
			{html_options options=$otherUsers}
		  </select>
		</td>
	
	  </tr>

	  <tr>
        <td colspan="2">
          <input type="checkbox" name="delPerms" class="text" value="true" checked="checked" />
	      {tr}adminDeleteTemplate{/tr}
	    </td>
      </tr>

      <tr>
	    <td class="button" colspan="2">
	      <input type="submit" value="{tr}Copy from Template{/tr}" class="button" name="cptempperms" />
	    </td>
      </tr>
  
	</table>

	</form>
	{/if}
	
	</td>
  </tr>
</table>
