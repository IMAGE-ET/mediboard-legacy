<script language="javascript">
function setClose(hour, min) {ldelim}
  window.opener.setRDV(hour, min, "{$plage->plageconsult_id}", "{$plage->date|date_format:"%d/%m/%Y"}", "{$plage->freq}");
  window.close();
{rdelim}
</script>

<table class="main">

<tr>
  <th class="category" colspan="2">
    <a href="index.php?m=dPcabinet&amp;a=plage_selector&amp;dialog=1&amp;chir={$chir}&amp;month={$pmonth}&amp;year={$pyear}">&lt;&lt;&lt;</a>
    {$nameMonth} {$year}
    <a href="index.php?m=dPcabinet&amp;a=plage_selector&amp;dialog=1&amp;chir={$chir}&amp;month={$nmonth}&amp;year={$nyear}">&gt;&gt;&gt;</a>
  </th>
</tr>

<tr>
  <td>
    <table class="tbl">
      <tr>
        <th>Date</th>
        <th>Libelle</th>
        <th>Etat</th>
      </tr>
      {foreach from=$listPlage item=curr_plage}
      <tr style="{if $curr_plage.plageconsult_id == $plageSel}font-weight: bold;{/if}">
        <td>
          <a href="index.php?m=dPcabinet&amp;a=plage_selector&amp;dialog=1&amp;plagesel={$curr_plage.plageconsult_id}&amp;chir={$chir}&amp;month={$month}&amp;year={$year}">
          {$curr_plage.affichage}
          </a>
        </td>
        <td>{$curr_plage.libelle}</td>
        <td>{$curr_plage.nb} / {$curr_plage.total}</td>
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
        <td>
          {foreach from=$curr_place.patient item=curr_patient}
            {if $curr_patient.patient}
              <div {if $curr_patient.premiere}style="background: #faa;" {/if}>
              {$curr_patient.patient}
              </div>
            {/if}
          {/foreach}
        </td>
        <td>
          {foreach from=$curr_place.patient item=curr_patient}
            {if $curr_patient.patient}
              <div {if $curr_patient.premiere}style="background: #faa;" {/if}>
              {$curr_patient.duree}
              </div>
            {/if}
          {/foreach}
        </td>
      </tr>
      {/foreach}
    </table>
  </td>
</tr>

</table>

</form>
