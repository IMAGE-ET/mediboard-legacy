<table class="main">
  <tr>
    <td>
	<form action="index.php" target="_self" name="selection" method="get" encoding="">
    <input type="hidden" name="m" value="dPadmissions">
    <input type="hidden" name="tab" value="0">
	  <select name="selAff" onchange="this.form.submit()">
	    <option value="-1">Type d'affichage :</option>
	    <option value="0">--> Toutes les admissions</option>
		<option value="o">--> Admissions effectuées</option>
        <option value="n">--> Admissions non effectuées</option>
	  </select>
    </form>
	</td>
	<th>
	  {$typeAff}
	</th>
  </tr>
  <tr>
    <th>
	  <a href="index.php?m=dPadmissions&tab=0&day={$pmonthd}&month={$pmonth}&year={$pmonthy}"><<</a>
	  {$title1}
	  <a href="index.php?m=dPadmissions&tab=0&day={$nmonthd}&month={$nmonth}&year={$nmonthy}">>></a>
	</th>
	<th>
	  <a href="index.php?m=dPadmissions&tab=0&day={$pday}&month={$pdaym}&year={$pdayy}"><<</a>
	  {$title2}
	  <a href="index.php?m=dPadmissions&tab=0&day={$nday}&month={$ndaym}&year={$ndayy}">>></a>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>
		    Date
		  </th>
		  <th>
		    Nombre d'admissions
		  </th>
		</tr>
	    {foreach from=$list item=curr_list}
		<tr>
		  <td align="right">
		    <a href="index.php?m=dPadmissions&tab=0&day={$curr_list.day}&month={$month}&year={$year}">
			{$curr_list.dateFormed}
			</a>
		  </td>
		  <td align="center">
		    {$curr_list.num}
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
		    Chirurgien
		  </th>
		  <th>
		    Heure
		  </th>
		</tr>
	    {foreach from=$today item=curr_adm}
		<tr>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id={$curr_adm.operation_id}">
		    {$curr_adm.nom}
			</a>
		  </td>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id={$curr_adm.operation_id}">
		    {$curr_adm.prenom}
			</a>
		  </td>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id={$curr_adm.operation_id}">
		    Dr. {$curr_adm.chir_lastname} {$curr_adm.chir_firstname}
			</a>
		  </td>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id={$curr_adm.operation_id}">
		    {$curr_adm.hour}
			</a>
		  </td>
		  {if $curr_adm.admis == "n"}
		  <td>
			<form name="editFrm{$curr_adm.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="dPadmissions" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="{$curr_adm.operation_id}" />
			<input type="submit" value="Admis" />
			</form> 
		  </td>
		  {/if}
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>