<!-- $Id$ -->

<table class="main">
  <tr><th>Materiel � commander</th></tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Op�ration</th>
		  <th>Materiel � commander</th>
		</tr>
		{foreach from=$op1 item=curr_op}
		<tr>
		  <td>{$curr_op.dateFormed}</td>
		  <td>{$curr_op.chir_name}</td>
		  <td>{$curr_op.pat_name}</td>
		  <td>{$curr_op.CCAM} <i>({$curr_op.CCAM_code})</i></td>
		  <td>{$curr_op.materiel}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  <tr><th>Materiel command�</th></tr>
  <tr>
    <td>
      <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Op�ration</th>
		  <th>Materiel command�</th>
		</tr>
		{foreach from=$op2 item=curr_op}
		<tr>
		  <td>{$curr_op.dateFormed}</td>
		  <td>{$curr_op.chir_name}</td>
		  <td>{$curr_op.pat_name}</td>
		  <td>{$curr_op.CCAM} <i>({$curr_op.CCAM_code})</i></td>
		  <td>{$curr_op.materiel}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>