{literal}
<script language="javascript">
function popOp(id) {
  window.open('./index.php?m=dPbloc&a=view_operation&dialog=1&id='+id, 'Intervention', 'left=50,top=50,height=250,width=700,resizable');
}
</script>
{/literal}

<table class="main">
  <tr>
    <th colspan=2>
	  Dr. {$title.firstname} {$title.lastname}
	  <br />
	  {$title.salle}
	  <br />
	  {$title.dateFormed}
	</th>
  </tr>
  <tr>
    <td width="50%">
	  <table class="tbl">
	    <tr>
		  <th colspan=3>
		    Patients à placer
		  </th>
		</tr>
		{foreach from=$list1 item=curr_op}
		<tr>
		  <td width="50%">
		    <b><a href="#" onclick="popOp( '{$curr_op.id}');">{$curr_op.firstname} {$curr_op.lastname}</a></b>
			<br />
			Code CCAM : {$curr_op.CCAM_code}
			<br />
			Durée : {$curr_op.duree}
		  </td>
		  <td>
			<i>{$curr_op.CCAM}</i>
		  </td>
		  <td>
		    <a href="index.php?m={$module}&a=do_order_op&cmd=insert&id={$curr_op.id}">
		    <img src="./modules/{$module}/images/tick.png" width="" height="" alt="ajouter" border="0" />
			</a>
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
	<td width="50%">
	  <table class="tbl">
	    <tr>
		  <th colspan=3>
		    Ordre des interventions
		  </th>
		</tr>
		{foreach from=$list2 item=curr_op}
		<tr>
		  <td width="50%">
		    <b><a href="#" onclick="popOp( '{$curr_op.id}');">{$curr_op.firstname} {$curr_op.lastname}</a></b>
			<br />
			Code CCAM : {$curr_op.CCAM_code}
			<br />
			Durée : {$curr_op.duree}
			<br />
		  </td>
		  <td>
			<i>{$curr_op.CCAM}</i>
		  </td>
		  <td>
		    {if $curr_op.rank != 1}
		    <a href="index.php?m={$module}&a=do_order_op&cmd=up&id={$curr_op.id}">
		    <img src="./modules/{$module}/images/uparrow.png" width="" height="" alt="monter" border="0" />
			</a>
			{/if}
			{if $curr_op.rank != 1 and $curr_op.rank != $max}
			<br />
			{/if}
			{if $curr_op.rank != $max}
		    <a href="index.php?m={$module}&a=do_order_op&cmd=down&id={$curr_op.id}">
		    <img src="./modules/{$module}/images/downarrow.png" width="" height="" alt="descendre" border="0" />
			</a>
			{/if}
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>