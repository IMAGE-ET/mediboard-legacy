<!-- $Id$ -->

<table class="main">
  <tr><th>Materiel à commander</th></tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Opération</th>
		  <th>Materiel à commander</th>
		</tr>
		{foreach from=$op1 item=curr_op}
		<tr>
		  <td>{$curr_op->_ref_plageop->date|date_format:"%d / %m / %Y"}</td>
		  <td class="text">Dr. {$curr_op->_ref_chir->_view}</td>
		  <td class="text">{$curr_op->_ref_pat->_view}</td>
		  <td class="text">{$curr_op->_ext_code_ccam->code} : <i>{$curr_op->_ext_code_ccam->libelleLong}</i> (Côté : {$curr_op->cote})</td>
		  <td class="text">{$curr_op->materiel|nl2br}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  <tr><th>Materiel commandé</th></tr>
  <tr>
    <td>
      <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Opération</th>
		  <th>Materiel commandé</th>
		</tr>
		{foreach from=$op2 item=curr_op}
		<tr>
		  <td>{$curr_op->_ref_plageop->date|date_format:"%d / %m / %Y"}</td>
		  <td class="text">Dr. {$curr_op->_ref_chir->_view}</td>
		  <td class="text">{$curr_op->_ref_pat->_view}</td>
		  <td class="text">{$curr_op->_ext_code_ccam->code} : <i>{$curr_op->_ext_code_ccam->libelleLong}</i> (Côté : {$curr_op->cote})</td>
		  <td class="text">{$curr_op->materiel}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
</table>