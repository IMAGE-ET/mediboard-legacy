<script language="javascript">
function setClose(hour, min) {ldelim}
  window.opener.setRDV(hour, min, "{$plage->plageconsult_id}", "{$plage->_dateFormated}", "{$plage->freq}");
  window.close();
{rdelim}
</script>

<table class="main">

<tr>
  <th class="category" colspan="2">
    <a href="index.php?m=dPcabinet&a=plage_selector&dialog=1&chir={$chir}&month={$pmonth}&year={$pyear}"><<</a>
    {$nameMonth} {$year}
    <a href="index.php?m=dPcabinet&a=plage_selector&dialog=1&chir={$chir}&month={$nmonth}&year={$nyear}">>></a>
  </th>
</tr>

<tr>
  <td>
    <table class="tbl">
      <tr>
        <th>Date</th>
      </tr>
      {foreach from=$listPlage item=curr_plage}
      <tr>
        <td>
          <a href="index.php?m=dPcabinet&a=plage_selector&dialog=1&plagesel={$curr_plage.plageconsult_id}&chir={$chir}&month={$month}&year={$year}">
          {if $curr_plage.plageconsult_id == $plageSel}<b>{/if}
          {$curr_plage.affichage} ({$curr_plage.nb} / {$curr_plage.total})
          {if $curr_plage.plageconsult_id == $plageSel}</b>{/if}
          </a>
        </td>
      </tr>
      {/foreach}
    </table>
  </td>
  <td>
    <table class="tbl">
      <tr>
        <th>Heure</th>
        <th>Patient</th>
        <th>Durée</th>
      </tr>
      {foreach from=$listPlace item=curr_place}
      <tr>
        <td><input type="button" value="+" onclick="setClose({$curr_place.hour}, {$curr_place.min})" />{$curr_place.hour}h{$curr_place.min}</td>
        <td>{foreach from=$curr_place.patient item=curr_patient}
          {if $curr_patient.patient}{$curr_patient.patient}<br />{else}-{/if}
        {/foreach}</td>
        <td>{foreach from=$curr_place.patient item=curr_patient}
          {$curr_patient.duree}{if $curr_patient.duree}mn<br />{else}-{/if}
        {/foreach}</td>
      </tr>
      {/foreach}
    </table>
  </td>
</tr>

</table>

</form>
