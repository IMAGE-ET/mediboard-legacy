<table>
  <tr>
    <td><strong>{$patient->_view} &mdash {$patient->_age} ans</strong></td>
  </tr>
  {if $patient->_ref_operations|@count == 0}
  <tr>
    <td>Aucune intervention</td>
  </tr>
  {/if}
  {foreach from=$patient->_ref_operations item=curr_op}
  <tr>
    <td>
      Intervention le {$curr_op->_ref_plageop->date|date_format:"%d/%m/%Y"}
      avec le Dr. {$curr_op->_ref_chir->_view}
    </td>
  </tr>
  {/foreach}
  {if $patient->_ref_consultations|@count == 0}
  <tr>
    <td>Aucune consultation</td>
  </tr>
  {/if}
  {foreach from=$patient->_ref_consultations item=curr_consult}
  <tr>
    <td>
      Consultation le {$curr_consult->_ref_plageconsult->date|date_format:"%d/%m/%Y"}
      avec le Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view}
    </td>
  </tr>
  {/foreach}
</table>