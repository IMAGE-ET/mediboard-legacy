<table class="main">
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
	  <table class="tbl">
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
		<tr>
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
		    {if $curr_plage.occupe == h0}
			  -
			{else}
		      {$curr_plage.occupe}
			{/if}
		  </td>
		</tr>
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
		    {$curr_op.nom}
		  </td>
		  <td>
		    {$curr_op.prenom}
		  </td>
		  <td>
		    {$curr_op.CCAM_code}
		  </td>
		  <td>
		    {$curr_op.CCAM}
		  </td>
		  <td>
		    {$curr_op.temps}
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>