<script type="text/javascript" src="lib/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="lib/scriptaculous/src/scriptaculous.js"></script>
  
<script type="text/javascript">
{literal}

function changeList() {
  var oElement = document.getElementById("listConsult");
  var oEffect = new Effect.Squish(oElement);
}

{/literal}
</script>

<div id="listConsult" style="float: left; width: 300px; height: 400px;">
  <form name="changeView" action="index.php" method="get">
    <input type="hidden" name="m" value="{$m}" />
    <input type="hidden" name="tab" value="{$tab}" />
  
    <table class="form">
      <tr>
        <td colspan="6" style="text-align: center; width: 100%; font-weight: bold;">
          {$date|date_format:"%A %d %B %Y"}
          <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
        </td>
      </tr>
      <tr>
        <th><label for="vue2" title="Type de vue du planning">Type de vue:</label></th>
        <td colspan="5">
            <select name="vue2" onchange="this.form.submit()">
              <option value="0"{if $vue == "0"}selected="selected"{/if}>Tout afficher</option>
              <option value="1"{if $vue == "1"}selected="selected"{/if}>Cacher les Terminées</option>
            </select>
        </td>
      </tr>
    </table>

  </form>
  <table class="tbl">
  {if $listPlage}
  {foreach from=$listPlage item=curr_plage}
    <tr>
      <th colspan="4" style="font-weight: bold;">Consultations de {$curr_plage->_hour_deb}h à {$curr_plage->_hour_fin}h</th>
    </tr>
    <tr>
      <th>Heure</th>
      <th>Patient</th>
      <th>RDV</th>
      <th>Etat</th>
    </tr>
    {foreach from=$curr_plage->_ref_consultations item=curr_consult}
      {if $curr_consult->premiere} 
        {assign var="style" value="style='background: #faa;'"}
      {else} 
        {assign var="style" value=""}
      {/if}
    <tr {if $curr_consult->consultation_id == $consult->consultation_id} style="font-weight: bold;" {/if}>
      <td {$style}>
        <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">{$curr_consult->heure|truncate:5:"":true}</a>
      </td>
      <td {$style}>
        <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">{$curr_consult->_ref_patient->_view}</a>
      </td>
      <td {$style}>
        <a href="index.php?m={$m}&amp;tab=edit_planning&amp;consultation_id={$curr_consult->consultation_id}" title="Modifier le RDV">
          <img src="modules/dPcabinet/images/planning.png" />
        </a>
      </td>
      <td {$style}>{$curr_consult->_etat}</td>
    </tr>
    {/foreach}
  {/foreach}
  {else}
    <tr>
      <th colspan="2" style="font-weight: bold;">Pas de consultations</th>
    </tr>
  {/if}
  </table>
</div>