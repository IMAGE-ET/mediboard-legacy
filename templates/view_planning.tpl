<!-- $Id$ -->

{literal}
<script type="text/javascript">
//<![CDATA[

function printAdmission(id) {
  var url = './index.php?m=dPadmissions&a=print_admission&dialog=1';
  url = url + '&id=' + id;
  popup(700, 550, url, 'Patient');
}

//]]>
</script>
{/literal}

<table class="main">
  <tr>
    <th>
      <a href="javascript:window.print()">
        Planning du {$deb|date_format:"%d/%m/%Y"}
        {if $deb != $fin}
        au {$fin|date_format:"%d/%m/%Y"}
        {/if}
      </a>
    </th>
  </tr>
  {foreach from=$plagesop item=curr_plageop}
  <tr>
    <td>
	  <b>Dr. {$curr_plageop->_ref_chir->_view} :
	  {$curr_plageop->_ref_salle->nom} de
	  {$curr_plageop->debut|date_format:"%Hh%M"} - {$curr_plageop->fin|date_format:"%Hh%M"}
      le {$curr_plageop->date|date_format:"%d/%m/%Y"}</b>
	</td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th colspan="7"><b>Intervention</b></th>
		  <th colspan="4"><b>Patient</b></th>
		</tr>
		<tr>
		  <th>Heure</th>
		  <th>Intervention</th>
		  <th>Cot�</th>
          <th>Anesth�sie</th>
          <th>Hospi</th>
		  <th>Remarques</th>
		  <th>Mat�riel</th>
		  <th>Nom - Pr�nom</th>
		  <th>Age</th>
		  <th>Chambre</th>
		</tr>
		{foreach from=$curr_plageop->_ref_operations item=curr_op}
		<tr>
		  {if $curr_op->annulee}
		  <td>[ANNULE]</td>
		  {else}
		  <td>{$curr_op->time_operation|date_format:"%Hh%M"}</td>
		  {/if}
		  <td class="text">
        {foreach from=$curr_op->_ext_codes_ccam item=curr_code}
        {if !$curr_code->_code7}<strong>{/if}
        {$curr_code->libelleLong|truncate:80:"...":false}
        <em>({$curr_code->code})</em>
        {if !$curr_code->_code7}</strong>{/if}
        <br/>
        {/foreach}
      </td>
		  <td>{$curr_op->cote|truncate:1:""|capitalize}</td>
          <td>{if $curr_op->_lu_type_anesth != ''}{$curr_op->_lu_type_anesth}{else}Non D�finie{/if}</td>
          <td>{$curr_op->type_adm|truncate:1:""|capitalize}</td>
		  <td class="text">{$curr_op->rques|nl2br}</td>
		  <td class="text">
		    {if $curr_op->commande_mat == 'n' && $curr_op->materiel != ''}<em>Materiel manquant:</em>{/if}
		    {$curr_op->materiel|nl2br}
		  </td>
		  <td>
		    <a href="javascript:printAdmission({$curr_op->operation_id})">
		      {$curr_op->_ref_pat->_view}
		    </a>
		  </td>
		  <td>
		    <a href="javascript:printAdmission({$curr_op->operation_id})">
		      {$curr_op->_ref_pat->_age} ans
		    </a>
		  </td>
		  <td class="text">
		    {if $curr_op->_first_affectation}
		    {$curr_op->_first_affectation->_ref_lit->_ref_chambre->_ref_service->nom} -
		    {$curr_op->_first_affectation->_ref_lit->_ref_chambre->nom} -
		    {$curr_op->_first_affectation->_ref_lit->nom}
		    {/if}
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  {/foreach}
</table>