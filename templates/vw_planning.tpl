<!-- $Id$ -->

{literal}
<script language="javascript">
function checkPlage() {
  var form = document.editFrm;
    
  if (form.id_chir.value == -1) {
    alert("Merci de choisir un chirurgien");
    form.cjir_id.focus();
    return false;
  }
  
  if (form._hour_fin.value < form._hour_deb.value) {
    alert("L'heure de début doit être supérieure à la l'heure de fin");
    form._hour_fin.focus();
    return false;
  }
  
  return true;
}
</script>
{/literal}

<table class="main">
  <tr>
    <th>
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;debut={$prec}"><<</a>
      Semaine du Lundi {$debut} au dimanche {$fin}
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;debut={$suiv}">>></a>
    </th>
    <td>
      <form action="index.php" name="selection" method="get">

      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="{$tab}">

      <label for="selection_chirSel">Praticien:</label>
      <select name="chirSel" onchange="this.form.submit()">
        <option value="-1" {if $chirSel == -1} selected="selected" {/if}>-- Choisir un praticien</option>
        {foreach from=$listChirs item=curr_chir}
        <option value="{$curr_chir->user_id}" {if $chirSel == $curr_chir->user_id} selected="selected" {/if}>
          {$curr_chir->user_last_name} {$curr_chir->user_first_name}
        </option>
        {/foreach}
      </select>
  
      </form>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <th></th>
          <th>Lundi</th>
          <th>Mardi</th>
          <th>Mercredi</th>
          <th>Jeudi</th>
          <th>Vendredi</th>
          <th>Samedi</th>
          <th>Dimanche</th>
        </tr>
        {foreach from=$listHours item=curr_hour}
        <tr>
          <th>{$curr_hour}h</th>
          {foreach from=$plages item=curr_day}
            {assign var="isNotIn" value=1}
            {foreach from=$curr_day.plages item=curr_plage}
              {if $curr_plage->_hour_deb == $curr_hour}
                <td align="center" bgcolor="#aaaaaa" rowspan="{$curr_plage->_hour_fin-$curr_plage->_hour_deb}">
                  <a href="index.php?m={$m}&amp;tab={$tab}&amp;plageconsult_id={$curr_plage->plageconsult_id}">
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
            <form name='editFrm' action='./index.php?m=dPcabinet' method='post' onsubmit='return checkPlage()'>

            <input type='hidden' name='dosql' value='do_plageconsult_aed' />
            <input type='hidden' name='del' value='0' />
            <input type='hidden' name='plageconsult_id' value='{if $plageconsult_id != -1}{$plageconsult_id}{/if}' />
            <input type='hidden' name='_year' value='{$year}' />
            <input type='hidden' name='_month' value='{$month}' />
            <input type='hidden' name='_day' value='{$day}' />
            
            <table class="form">
              <tr>
                {if $plageconsult_id == -1}
                <th class="category" colspan="4">Créer une plage</th>
                {else}
                <th class="category" colspan="4">Modifier cette plage</th>
                {/if}
              </tr>

              <tr>
                <th><label for="editFrm_chir_id">Praticien:</label></th>
                <td><select name="chir_id">
                    <option value="-1" {if $chirSel == -1} selected="selected" {/if}>-- Choisir un praticien</option>
                    {foreach from=$listChirs item=curr_chir}
                      <option value="{$curr_chir->user_id}" {if $chirSel == $curr_chir->user_id} selected="selected" {/if}>
                      {$curr_chir->user_last_name} {$curr_chir->user_first_name}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label for="editFrm__jour">Jour de la semaine:</label></th>
                <td><select name="_jour">
                    {foreach from=$daysOfWeek item=curr_day}
                    <option value="{$curr_day.index}" {if $curr_day.index == $plageSel->_jour} selected="selected" {/if}>
                      {$curr_day.name}
                    </option>
                    {/foreach}
                    </select>
                </td>
              </tr>

              <tr>
                <th><label for="editFrm__hour_deb">Heure de début:</label></th>
                <td><select name="_hour_deb">
                    {foreach from=$listHours item=curr_hour}
                      <option value="{$curr_hour}" {if $curr_hour == $plageSel->_hour_deb} selected="selected" {/if}>
                        {$curr_hour}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label for="editFrm__repeat">Nombre de répétitions:</label></th>
                <td><input type="text" size="2" name="_repeat" value="1" /></td>
              </tr>

              <tr>
                <th><label for="editFrm__hour_fin">Heure de fin:</label></th>
                <td><select name="_hour_fin">
                    {foreach from=$listHours item=curr_hour}
                      <option value="{$curr_hour}" {if $curr_hour == $plageSel->_hour_fin} selected="selected" {/if}>
                        {$curr_hour}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label>Type de répétition:</label></th>
                <td>
                  <input type="checkbox" name="_double">
                  <label for="editFrm__double">Une semaine sur deux</label>
                </td>
              </tr>
              
              <tr>
                <th><label>Fréquence:</label></th>
                <td><select name="_freq">
                  <option value="05" {if ($plageSel->_freq == "05")} selected="selected" {/if}>5</option>
                  <option value="10" {if ($plageSel->_freq == "10")} selected="selected" {/if}>10</option>
                  <option value="15" {if ($plageSel->_freq == "15") || (!$plageSel->plageconsult_id)} selected="selected" {/if}>15</option>
                  <option value="20" {if ($plageSel->_freq == "20")} selected="selected" {/if}>20</option>
                  <option value="30" {if ($plageSel->_freq == "30")} selected="selected" {/if}>30</option>
                </select> minutes</td>

              <tr>
                {if $plageconsult_id == -1}
                <td class="button" colspan="4"><input type="submit" value="Creer" /></td>
                {else}
                <td class="button" colspan="4"><input type="submit" value="Modifier" /></td>
                {/if}
              </tr>
            </table>
            </form>
          </td>
        </tr>
        {if $plageconsult_id != -1}
        <tr>
          <td colspan="8">
          <form name='removeFrm' action='./index.php?m=dPcabinet' method='post'>
          <input type='hidden' name='dosql' value='do_plageconsult_aed' />
          <input type='hidden' name='del' value='1' />
          <input type='hidden' name='plageconsult_id' value='{$plageconsult_id}' />
            <table class="form">
              <tr>
                <th class="category" colspan="2">Supprimer cette plage</th>
              </tr>
              <tr>
                <th>Supprimer cette plage pendant</th>
                <td><input type='text' name='_repeat' size="1" value='1' /> semaine(s)</td>
              </tr>
              <tr>
                <td class="button" colspan="2"><input type='submit' value='Supprimer' /></td>
              </tr>
            </table>
          </form>
          </td>
        </tr>
        {/if}
      </table>
    </td>
    <td>
      <table>
        <tr>
          {if $plageconsult_id != -1}
          <td colspan="4"><a href="index.php?m={$m}&amp;tab={$tab}&amp;plageconsult_id=-1">
          cliquez ici pour créer une nouvelle plage</a></td>
        </tr><tr>
            <th>Consultations du {$plageSel->date}</th>
          {else}
            <th>Pas de plage selectionnée</th>
          {/if}
        </tr>
        <tr>
          <table class="tbl">
            <tr>
              <th>Heure</th>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Motif</th>
              <th>rques</th>
            </tr>
            {foreach from=$plageSel->_ref_consultations item=curr_consult}
            <tr>
              <td><a href="index.php?m={$m}&tab=edit_planning&consultation_id={$curr_consult->consultation_id}">
              {$curr_consult->_hour}h{if $curr_consult->_min}{$curr_consult->_min}{/if}</a></td>
              <td><a href="index.php?m={$m}&tab=edit_planning&consultation_id={$curr_consult->consultation_id}">
              {$curr_consult->_ref_patient->nom}</a></td>
              <td><a href="index.php?m={$m}&tab=edit_planning&consultation_id={$curr_consult->consultation_id}">
              {$curr_consult->_ref_patient->prenom}</a></td>
              <td><a href="index.php?m={$m}&tab=edit_planning&consultation_id={$curr_consult->consultation_id}">
              {$curr_consult->motif|nl2br}</a></td>
              <td><a href="index.php?m={$m}&tab=edit_planning&consultation_id={$curr_consult->consultation_id}">
              {$curr_consult->rques|nl2br}</a></td>
            </tr>
            {/foreach}
          </table>
        </tr>
      </table>
  </tr>
</table>