<table class="main">
  <tr>
    <th colspan=2>
	  Dr. {$title.firstname} {$title.lastname}
	  <br />
	  {$title.date|date_format:"%A %d %B %Y"}
	  <br />
	  {$title.salle} : {$title.plage}
	</th>
  </tr>
  <tr>
    <td width="50%">
	  <table class="tbl">
	    <tr>
		  <th colspan=3>
		    Patients � placer
		  </th>
		</tr>
		{foreach from=$list1 item=curr_op}
		<tr>
		  <td width="50%">
		    <b><a href="index.php?m=dPplanningOp&tab=vw_edit_planning&operation_id={$curr_op.id}">{$curr_op.lastname} {$curr_op.firstname} ({$curr_op.age} ans)</a></b>
			<br />
			Admission le {$curr_op.date_adm|date_format:"%a %d %b"} � {$curr_op.time_adm} ({$curr_op.type_adm|truncate:1:""|capitalize})
            <br />
			Dur�e : {$curr_op.duree}
		  </td>
		  <td class="text">
			{$curr_op.CCAM_code} : <i>{$curr_op.CCAM}</i>
			<br />
            C�t� : {$curr_op.cote}
            <br />
            <form name="editFrm{$curr_op.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_order_op" />
            <input type="hidden" name="cmd" value="setanesth" />
            <input type="hidden" name="id" value="{$curr_op.id}" />
            <select name="type">
              <option value="NULL">-- Anesth�sie</option>
              {foreach from=$anesth item=curr_anesth}
              <option {if $curr_op.lu_type_anesth == $curr_anesth} selected="selected" {/if}>{$curr_anesth}</option>
              {/foreach}
            </select>
            <input type="submit" value="changer" />
            </form>
		  </td>
		  <td>
		    {if $curr_op.annulee}
		    <img src="./modules/{$m}/images/cross.png" width="12" height="12" alt="annul�e" border="0" />
		    {else}
		    <a href="index.php?m={$m}&a=do_order_op&cmd=insert&id={$curr_op.id}">
		    <img src="./modules/{$m}/images/tick.png" width="12" height="12" alt="ajouter" border="0" />
			</a>
			{/if}
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
	<td width="50%">
	  <table class="tbl"
	    <tr>
		  <th colspan=3>
		    Ordre des interventions
		  </th>
		</tr>
		{foreach from=$list2 item=curr_op}
		<tr>
		  <td width="50%">
		    <a name="{$curr_op.id}">
			<form name="editFrm{$curr_op.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_order_op" />
            <input type="hidden" name="cmd" value="sethour" />
            <input type="hidden" name="id" value="{$curr_op.id}" />
            </a>
		    <b><a href="index.php?m=dPplanningOp&tab=vw_edit_planning&operation_id={$curr_op.id}">{$curr_op.lastname} {$curr_op.firstname} ({$curr_op.age} ans)</a></b>
			<br />
			Admission le {$curr_op.date_adm|date_format:"%a %d %b"} � {$curr_op.time_adm} ({$curr_op.type_adm|truncate:1:""|capitalize})
            <br />
			Dur�e : {$curr_op.duree}
			<br />
			<select name="hour">
			  <option selected="selected">{$curr_op.hour}</option>
			  {foreach from=$curr_op.listhour item=curr_hour}
			  <option>{$curr_hour}</option>
			  {/foreach}
			</select>
			h
			<select name="min">
			  <option selected="selected">{$curr_op.min}</option>
			  <option>00</option>
			  <option>15</option>
			  <option>30</option>
			  <option>45</option>
			</select>
			<input type="submit" value="changer" />
			</form>
		  </td>
		  <td class="text">
			{$curr_op.CCAM_code} : <i>{$curr_op.CCAM}</i>
			<br />
            C�t� : {$curr_op.cote}
            <br />
            <form name="editFrm{$curr_op.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_order_op" />
            <input type="hidden" name="cmd" value="setanesth" />
            <input type="hidden" name="id" value="{$curr_op.id}" />
            <select name="type">
              <option value="NULL">-- Anesth�sie</option>
              {foreach from=$anesth item=curr_anesth}
              <option {if $curr_op.lu_type_anesth == $curr_anesth} selected="selected" {/if}>{$curr_anesth}</option>
              {/foreach}
            </select>
            <input type="submit" value="changer" />
            </form>
		  </td>
		  <td>
		    {if $curr_op.rank != 1}
		    <a href="index.php?m={$m}&a=do_order_op&cmd=up&id={$curr_op.id}">
		    <img src="./modules/{$m}/images/uparrow.png" width="12" height="12" alt="monter" border="0" />
			</a>
			<br />
			{/if}
			<a href="index.php?m={$m}&a=do_order_op&cmd=rm&id={$curr_op.id}">
		    <img src="./modules/{$m}/images/cross.png" width="12" height="12" alt="supprimer" border="0" />
			</a>
			{if $curr_op.rank != $max}
			<br />
		    <a href="index.php?m={$m}&a=do_order_op&cmd=down&id={$curr_op.id}">
		    <img src="./modules/{$m}/images/downarrow.png" width="12" height="12" alt="descendre" border="0" />
			</a>
			{/if}
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>