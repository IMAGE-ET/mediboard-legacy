<table class="main">
  {if !$isMyPlanning}
  <tr>
    <td>
	<form action="index.php" target="_self" name="selection" method="get" encoding="">
    <input type="hidden" name="m" value="dPplanningOp">
    <input type="hidden" name="tab" value="0">
	  <select name="selChir" onchange="this.form.submit()">
	    <option value="0" selected="selected">Choix du chirurgien :</option>
		{foreach from=$listChir item=curr_chir}
	    <option value="{$curr_chir.id}">{$curr_chir.lastname} {$curr_chir.firstname}</option>
		{/foreach}
	  </select>
    </form>
	</td>
	<th>
	  {$userName}
	</th>
  </tr>
  {/if}
  <tr>
    <th>
	  <a href="index.php?m=dPplanningOp&tab=0&day={$pmonthd}&month={$pmonth}&year={$pmonthy}"><<</a>
	  {$title1}
	  <a href="index.php?m=dPplanningOp&tab=0&day={$nmonthd}&month={$nmonth}&year={$nmonthy}">>></a>
	</th>
	<th>
	  <a href="index.php?m=dPplanningOp&tab=0&day={$pday}&month={$pdaym}&year={$pdayy}"><<</a>
	  {$title2}
	  <a href="index.php?m=dPplanningOp&tab=0&day={$nday}&month={$ndaym}&year={$ndayy}">>></a>
  </tr>
  <tr>
    <td>
	  <table class="color">
	    <tr>
		  <th>
		    Date
		  </th>
		  <th>
		    Plage
		  </th>
		  <th>
		    Opérations
		  </th>
		  <th>
		    Temps pris
		  </th>
		</tr>
	    {foreach from=$list item=curr_plage}
		{if $curr_plage.spe}
		<tr style="background: #ddd">
		  <td align="right">
			{$curr_plage.date}
		  </td>
		  <td align="center">
		    {$curr_plage.horaires}
		  </td>
		  <td align="center">
		    {$curr_plage.operations}
		  </td>
		  <td align="center">
		      Plage de spécialité
		  </td>
		</tr>
		{else}
		<tr style="background: #fff">
		  <td align="right">
		    <a href="index.php?m=dPplanningOp&tab=0&day={$curr_plage.day}&month={$month}&year={$year}">
			{$curr_plage.date}
			</a>
		  </td>
		  <td align="center">
		    {$curr_plage.horaires}
		  </td>
		  <td align="center">
		    {$curr_plage.operations}
		  </td>
		  <td align="center">
		      {$curr_plage.occupe}
		  </td>
		</tr>
		{/if}
		{/foreach}
	  </table>
	</td>
	<td>
	  <table class="tbl">
	    <tr>
		  <th>
		    Nom
		  </th>
		  <th>
		    Prénom
		  </th>
		  <th>
		    code CCAM
		  </th>
		  <th width="300">
		    Description
		  </th>
		  <th>
		    Durée
		  </th>
		</tr>
	    {foreach from=$today item=curr_op}
		<tr>
		  <td>
	        <a href="index.php?m=dPplanningOp&tab=1&id={$curr_op.id}">
		    {$curr_op.nom}
			</a>
		  </td>
		  <td>
	        <a href="index.php?m=dPplanningOp&tab=1&id={$curr_op.id}">
		    {$curr_op.prenom}
		  </a>
		  </td>
		  <td>
	        <a href="index.php?m=dPplanningOp&tab=1&id={$curr_op.id}">
		    {$curr_op.CCAM_code}
		  </a>
		  </td>
		  <td>
	        <a href="index.php?m=dPplanningOp&tab=1&id={$curr_op.id}">
		    {$curr_op.CCAM}
		  </a>
		  </td>
		  <td>
	        <a href="index.php?m=dPplanningOp&tab=1&id={$curr_op.id}">
		    {$curr_op.temps}
		  </a>
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>