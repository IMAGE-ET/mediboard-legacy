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
    alert("L'heure de d�but doit �tre sup�rieure � la l'heure de fin");
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

      <label for="selection_chirSel">Choisir un chirurgien:</label>
      <select name="chirSel" onchange="this.form.submit()">
        <option value="-1" {if $chirSel == -1} selected="selected" {/if}>Aucun chirurgien</option>
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
                <th class="category" colspan="4">Cr�er une plage</th>
                {else}
                <th class="category" colspan="4">Modifier cette plage</th>
                {/if}
              </tr>
              <tr>
                <th><label for="selection_chir_id">Choisir un chirurgien:</label></th>
                <td><select name="chir_id">
                    <option value="-1" {if $chirSel == -1} selected="selected" {/if}>Aucun chirurgien</option>
                    {foreach from=$listChirs item=curr_chir}
                      <option value="{$curr_chir->user_id}" {if $chirSel == $curr_chir->user_id} selected="selected" {/if}>
                      {$curr_chir->user_last_name} {$curr_chir->user_first_name}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th><label for="selection_jour">Choisir le jour de la semaine:</label></th>
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
                <th><label for="selection_debut">Heure de d�but:</label></th>
                <td><select name="_hour_deb">
                    {foreach from=$listHours item=curr_hour}
                      <option value="{$curr_hour}" {if $curr_hour == $plageSel->_hour_deb} selected="selected" {/if}>
                        {$curr_hour}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th>R�p�tition:</th>
                <td><input type="text" size="2" name="_repeat" value="1" /></td>
              </tr>
              <tr>
                <th><label for="selection_fin">Heure de fin:</label></th>
                <td><select name="_hour_fin">
                    {foreach from=$listHours item=curr_hour}
                      <option value="{$curr_hour}" {if $curr_hour == $plageSel->_hour_fin} selected="selected" {/if}>
                        {$curr_hour}
                      </option>
                    {/foreach}
                    </select>
                </td>
                <th>Une semaine sur deux</th>
                <td><input type="checkbox" name="_double"></td>
              </tr>
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
      </table>
    </td>
    <td>
      <table>
        <tr>
          {if $plageconsult_id != -1}
          <td colspan="4"><a href="index.php?m={$m}&amp;tab={$tab}&amp;plageconsult_id=-1">
          cliquez ici pour cr�er une nouvelle plage</a></td>
        </tr><tr>
            <th>Consultations du {$plageSel->date}</th>
          {else}
            <th>Pas de plage selectionn�e</th>
          {/if}
        </tr>
        <tr>
          <table class="tbl">
            <tr>
              <th>Heure</th>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Motif</th>
              <th>RDV</th>
            </tr>
            {foreach from=$plageSel->_ref_consultations item=curr_consult}
            <tr>
              <td>{$curr_consult->_hour}h{if $curr_consult->_min}{$curr_consult->_min}{/if}</td>
              <td>{$curr_consult->_ref_patient->nom}</td>
              <td>{$curr_consult->_ref_patient->prenom}</td>
              <td>{$curr_consult->motif}</td>
              <td></td>
            </tr>
            {/foreach}
          </table>
        </tr>
      </table>
  </tr>
</table>