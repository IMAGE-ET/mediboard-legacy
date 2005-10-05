<!-- $Id$ -->

{literal}
<script language="javascript">
function checkPlage() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id) {
    if (!field.value) {
      alert("Merci de choisir un praticien");
      field.focus();
      return false;
    }
  }
  
  if (field = form.date) {
    if (!field.value) {
      alert("Merci de choisir un jour de la semaine");
      field.focus();
      return false;
    }
  }

  var fieldDeb = form._hour_deb;
  var fieldFin = form._hour_fin;
  if (fieldDeb && fieldFin) {
    if (fieldDeb.value >= fieldFin.value) {
      alert("L'heure de d�but doit �tre inf�rieure � la l'heure de fin");
      fieldFin.focus();
      return false;
    }
  }
  
  return true;
}

function pageMain() {
  {/literal}
  regRedirectPopupCal("{$debut}", "index.php?m={$m}&tab={$tab}&debut=");
  {literal}
  
}

</script>
{/literal}

<table class="main">
  <tr>
    <th>
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;debut={$prec}">&lt;&lt;&lt;</a>
      Semaine du {$debut|date_format:"%A %d %b %Y"} au {$fin|date_format:"%A %d %b %Y"}
      <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;debut={$suiv}">&gt;&gt;&gt;</a>
      <br />
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;debut={$today}">Aujourd'hui</a>
    </th>
    <td>
      <form action="index.php" name="selection" method="get">

      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="{$tab}" />

      <label for="selection_chirSel">Praticien:</label>
      <select name="chirSel" onchange="this.form.submit()">
        <option value="-1" {if $chirSel == -1} selected="selected" {/if}>&mdash; Choisir un praticien &mdash;</option>
        {foreach from=$listChirs item=curr_chir}
        <option value="{$curr_chir->user_id}" {if $chirSel == $curr_chir->user_id} selected="selected" {/if}>
          {$curr_chir->_view}
        </option>
        {/foreach}
      </select>
  
      <label for="selection_vue1">Type de vue:</label>
      <select name="vue1" onchange="this.form.submit()">
        <option value="0"{if !$vue}selected="selected"{/if}>Tout afficher</option>
        <option value="1"{if $vue}selected="selected"{/if}>Cacher les pay�s</option>
      </select>

      </form>

    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <th></th>
          {foreach from=$plages key=curr_day item=plagesPerDay}
          <th>{$curr_day|date_format:"%A %d"}</th>
          {/foreach}
        </tr>
        {foreach from=$listHours item=curr_hour}
        <tr>
          <th>{$curr_hour}h</th>
          {foreach from=$plages key=curr_day item=plagesPerDay}
            {assign var="isNotIn" value=1}
            {foreach from=$plagesPerDay item=curr_plage}
              {if $curr_plage->_hour_deb == $curr_hour}
                <td align="center" bgcolor="#aaaaaa" rowspan="{$curr_plage->_hour_fin-$curr_plage->_hour_deb}">
                  <a href="index.php?m={$m}&amp;tab={$tab}&amp;plageconsult_id={$curr_plage->plageconsult_id}">
                    {if $curr_plage->libelle}{$curr_plage->libelle}<br />{/if}
                    {$curr_plage->_ref_consultations|@count} consult(s)
                  </a>
                </td>
              {/if}
              {if ($curr_plage->_hour_deb <= $curr_hour) && ($curr_plage->_hour_fin > $curr_hour)}
                {assign var="isNotIn" value=0}
              {/if}
            {/foreach}
            {if $isNotIn}
              <td bgcolor="#ffffff"></td>
            {/if}
          {/foreach}
        </tr>
        {/foreach}
        <tr>
          <td colspan="8">
            <form name='editFrm' action='?m=dPcabinet' method='post' onsubmit='return checkPlage()'>

            <input type='hidden' name='dosql' value='do_plageconsult_aed' />
            <input type='hidden' name='del' value='0' />
            <input type='hidden' name='plageconsult_id' value='{$plageSel->plageconsult_id}' />
            
            <table class="form">
              <tr>
                {if !$plageSel->plageconsult_id}
                <th class="category" colspan="4">Cr�er une plage</th>
                {else}
                <th class="category" colspan="4">Modifier cette plage</th>
                {/if}
              </tr>

              <tr>
                <th><label for="editFrm_chir_id">Praticien:</label></th>
                <td><select name="chir_id">
                    <option value="">&mdash; Choisir un praticien</option>
                    {foreach from=$listChirs item=curr_chir}
                      <option value="{$curr_chir->user_id}" {if $chirSel == $curr_chir->user_id} selected="selected" {/if}>
                      {$curr_chir->_view}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label for="editFrm_libelle">Libell�:</label></th>
                <td><input type="text" name="libelle" value="{$plageSel->libelle}" />
              </tr>

              <tr>
                <th><label for="editFrm__hour_deb">D�but:</label></th>
                <td><select name="_hour_deb">
                    {foreach from=$listHours item=curr_hour}
                      <option value="{$curr_hour|string_format:"%02d"}" {if $curr_hour == $plageSel->_hour_deb} selected="selected" {/if}>
                        {$curr_hour|string_format:"%02d"}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label for="editFrm_date">Jour de la semaine:</label></th>
                <td>
                  <select name="date">
                    <option value="">&mdash; Jour de la semaine</option>
                    {foreach from=$plages key=curr_day item=plagesPerDay}
                    <option value="{$curr_day}" {if $curr_day == $plageSel->date} selected="selected" {/if}>
                      {$curr_day|date_format:"%A"}
                    </option>
                    {/foreach}
                  </select>
                </td>
              </tr>

              <tr>
                <th><label for="editFrm__hour_fin">Fin:</label></th>
                <td><select name="_hour_fin">
                    {foreach from=$listHours item=curr_hour}
                      <option value="{$curr_hour|string_format:"%02d"}" {if $curr_hour == $plageSel->_hour_fin} selected="selected" {/if}>
                        {$curr_hour|string_format:"%02d"}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label for="editFrm__repeat">Nombre de r�p�titions:</label></th>
                <td><input type="text" size="2" name="_repeat" value="1" /></td>
              </tr>
              
              <tr>
                <th><label>Fr�quence:</label></th>
                <td><select name="_freq">
                  <option value="05" {if ($plageSel->_freq == "05")} selected="selected" {/if}>05</option>
                  <option value="10" {if ($plageSel->_freq == "10")} selected="selected" {/if}>10</option>
                  <option value="15" {if ($plageSel->_freq == "15") || (!$plageSel->plageconsult_id)} selected="selected" {/if}>15</option>
                  <option value="30" {if ($plageSel->_freq == "30")} selected="selected" {/if}>30</option>
                </select> minutes</td>
                <th><label>Type de r�p�tition:</label></th>
                <td>
                  <input type="checkbox" name="_double" />
                  <label for="editFrm__double">Une semaine sur deux</label>
                </td>
              <tr>
                {if !$plageSel->plageconsult_id}
                <td class="button" colspan="4"><input type="submit" value="Cr�er" /></td>
                {else}
                <td class="button" colspan="4"><input type="submit" value="Modifier" /></td>
                {/if}
              </tr>
            </table>
            </form>
          </td>
        </tr>
        {if $plageSel->plageconsult_id}
        <tr>
          <td colspan="8">
          <form name='removeFrm' action='./index.php?m=dPcabinet' method='post'>
          <input type='hidden' name='dosql' value='do_plageconsult_aed' />
          <input type='hidden' name='del' value='1' />
          <input type='hidden' name='plageconsult_id' value='{$plageSel->plageconsult_id}' />
            <table class="form">
              <tr>
                <th class="category" colspan="2">Supprimer cette plage</th>
              </tr>
              <tr>
                <th>Supprimer cette plage pendant</th>
                <td><input type='text' name='_repeat' size="1" value='1' /> semaine(s)</td>
              </tr>
              <tr>
                <td class="button" colspan="2">
                  <input type='button' value='Supprimer' onclick="confirmDeletion(this.form, 'la plage de consultations du', '{$plageSel->date|date_format:"%A %d %B %Y"}')" />
                </td>
              </tr>
            </table>
          </form>
          </td>
        </tr>
        {/if}
      </table>
    </td>
    <td>
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;plageconsult_id=0">cliquez ici pour cr�er une nouvelle plage</a>
      
      <table class="tbl">
        <tr>
          <th colspan="10">
            <strong>
            {if $plageSel->plageconsult_id}
            Consultations du {$plageSel->date|date_format:"%A %d %B %Y"}
            {else}
            Pas de plage selectionn�e
            {/if}
            </strong>
          </th>
        </tr>

        <tr>
          <th>Heure</th>
          <th>Nom</th>
          <th>Motif</th>
          <th>Remarques</th>
          <th>RDV</th>
          <th>Etat</th>
        </tr>
        {foreach from=$plageSel->_ref_consultations item=curr_consult}
        <tr>
          {eval var=$curr_consult->consultation_id assign="consult_id"}
          {assign var="href_consult" value="index.php?m=$m&amp;tab=edit_consultation&amp;selConsult=$consult_id"}
          {assign var="href_planning" value="index.php?m=$m&amp;tab=edit_planning&amp;consultation_id=$consult_id"}
          {if $curr_consult->premiere} 
            {assign var="style" value="style='background: #faa;'"}
          {else} 
            {assign var="style" value=""}
          {/if}
          
          <td {$style}><a href="{$href_consult}">{$curr_consult->_hour}h{if $curr_consult->_min}{$curr_consult->_min}{/if}</a></td>
          <td {$style}><a href="{$href_consult}">{$curr_consult->_ref_patient->_view}</a></td>
          <td class="text" {$style}><a href="{$href_consult}">{$curr_consult->motif|nl2br|truncate:35:"...":false}</a></td>
          <td class="text" {$style}><a href="{$href_consult}">{$curr_consult->rques|nl2br|truncate:35:"...":false}</a></td>
          <td {$style}>
            <form name="etatFrm{$curr_consult->consultation_id}" action="?m={$m}" method="POST">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="dosql" value="do_consultation_aed" />
            <input type="hidden" name="consultation_id" value="{$curr_consult->consultation_id}" />
            <input type="hidden" name="_check_premiere" value="{$curr_consult->_check_premiere}" />
            <input type="hidden" name="chrono" value="{$smarty.const.CC_PATIENT_ARRIVE}" />
            </form>
            
            <form name="cancelFrm{$curr_consult->consultation_id}" action="?m={$m}" method="POST">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="dosql" value="do_consultation_aed" />
            <input type="hidden" name="consultation_id" value="{$curr_consult->consultation_id}" />
            <input type="hidden" name="_check_premiere" value="{$curr_consult->_check_premiere}" />
            <input type="hidden" name="chrono" value="{$smarty.const.CC_TERMINE}" />
            <input type="hidden" name="annule" value="1" />
            </form>
            
            <a class="action" href="{$href_planning}">
              <img src="modules/{$m}/images/planning.png" title="Modifier le rendez-vous">
            </a>

			{if $curr_consult->chrono == $smarty.const.CC_PLANIFIE}
            <a class="action" href="javascript:document.etatFrm{$curr_consult->consultation_id}.submit()">
              <img src="modules/{$m}/images/check.png" title="Notifier l'arriv�e du patient">
            </a>
            <a class="action" href="javascript:document.cancelFrm{$curr_consult->consultation_id}.submit()">
              <img src="modules/{$m}/images/cancel.png" title="Annuler ce rendez-vous">
            </a>
            {/if}
          </td>
          <td {$style}>{$curr_consult->_etat}</td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>