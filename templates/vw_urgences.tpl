{literal}
<script language="javascript">
</script>
{/literal}
<table class="main">
  <tr>
    {foreach from=$list item=curr_day}
    <td>
      <table class="tbl">
        <tr>
          <th colspan="4">{$curr_day.date|date_format:"%d %b %Y"}</th>
        </tr>
        <tr>
          <th>Patient</th><th>Chirurgien</th><th>Admsission</th><th>Remarques</th>
        </tr>
        {foreach from=$curr_day.urgences item=curr_urgence}
        <tr>
          <td class="text">
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_urgence->operation_id}">
            {$curr_urgence->_ref_pat->_view}
            </a>
          </td>
          <td class="text">
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_urgence->operation_id}">
            Dr. {$curr_urgence->_ref_chir->_view}
            </a>
          </td>
          <td class="text">
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_urgence->operation_id}">
            {$curr_urgence->time_adm|date_format:"%H h %M"}
            </a>
          </td>
          <td class="text">
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_urgence->operation_id}">
            {$curr_urgence->rques|escape:javascript}
            </a>
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
    {/foreach}
  </tr>
</table>