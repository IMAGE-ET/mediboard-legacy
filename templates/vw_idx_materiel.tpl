<!-- $Id$ -->

<table class="main">
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Opération</th>
		  <th>Materiel à commander</th>
		  <th>Valider</th>
		</tr>
		{foreach from=$op item=curr_op}
		<tr>
		  <td>{$curr_op.dateFormed}</td>
		  <td>{$curr_op.chir_name}</td>
		  <td>{$curr_op.pat_name}</td>
		  <td>{$curr_op.CCAM} <i>({$curr_op.CCAM_code})</i></td>
		  <td>{$curr_op.materiel}</td>
		  <td>
			<form name="editFrm{$curr_op.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="dPbloc" />
            <input type="hidden" name="a" value="do_edit_mat" />
            <input type="hidden" name="id" value="{$curr_op.id}" />
		    <input type="submit" value="commandé" />
			</form>
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>