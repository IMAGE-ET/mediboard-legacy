<!-- $Id$ -->

{literal}
<script language="javascript">
function checkPlage() {
  var form = document.editFrm;
    
  if (form.id_chir.value == 0 && form.id_spec.value == 0) {
    alert("Merci de choisir un chirurgien ou une sp�cialit�");
    form.id_chir.focus();
    return false;
  }
  
  if (form._heurefin.value < form._heuredeb.value || (form._heurefin.value == form._heuredeb.value && form._minutefin.value <= form._minutedeb.value)) {
    alert("L'heure de d�but doit �tre sup�rieure � la l'heure de fin");
    form._heurefin.focus();
    return false;
  }
  
  return true;
}

function pageMain() {
  {/literal}
  regFlatCalendar("calendar-container", "{$date}", "index.php?m={$m}&tab={$tab}&date=");
  {literal}
}

</script>
{/literal}

{if $canEdit}

<form name='editFrm' action='./index.php?m={$m}' method='post' onsubmit='return checkPlage()'>

<input type='hidden' name='dosql' value='do_plagesop_aed' />
<input type='hidden' name='del' value='0' />
<input type='hidden' name='id' value='{$plagesel->id}' />

<table class="form">
  <tr>
    <th class="category" colspan="6">{if $plagesel->id}Modifier {else}Ajouter {/if} une plage op�ratoire</th>
  </tr>

  <tr>
    <th class="mandatory">Chirurgien:</th>
    <td>
      <select name='id_chir'>
        <option value="0">-- Choisir un praticien</option>

        <optgroup label="Chirurgiens">
        {foreach from=$chirs item=chir}
          <option value="{$chir->user_username}" {if $plagesel->id_chir == $chir->user_username} selected="selected" {/if} >
            Dr. {$chir->_view}
          </option>
        {/foreach}
        </optgroup>

        <optgroup label="Anesth�sistes">
        {foreach from=$anesths item=anesth}
          <option value="{$anesth->user_username}" {if $plagesel->id_anesth == $anesth->user_username} selected="selected" {/if} >
            Dr. {$anesth->_view}
          </option>
        {/foreach}
        </optgroup>
      </select>
    </td>
    
    <th>Salle:</th>
    <td>
      <select name='id_salle'>
      {foreach from=$salles item=salle}
        <option value="{$salle->id}" {if $plagesel->id_salle == $salle->id} selected="selected"{/if} >
          {$salle->nom}
        </option>
      {/foreach}
      </select>
    </td>

    <th class="mandatory">D�but:</th>
    <td>
      <select name='_heuredeb'>
      {foreach from=$heures item=heure}
        <option {if $plagesel->_heuredeb == $heure} selected="selected" {/if} >
          {$heure|string_format:"%02d"}
        </option>
      {/foreach}
      </select>
      :
      <select name='_minutedeb'>
      {foreach from=$minutes item=minute}
        <option {if $plagesel->_minutedeb == $minute} selected="selected" {/if} >
          {$minute|string_format:"%02d"}
        </option>
      {/foreach}
      </select>
    </td>
  </tr>

  <tr>
    <th>Anesth�siste:</th>
    <td>
      <select name='id_anesth'>
        <option value="0">-- Choisir un anesth�siste</option>
      {foreach from=$anesths item=anesth}
        <option value="{$anesth->user_username}" {if $plagesel->id_anesth == $anesth->user_username} selected="selected" {/if} >
          Dr. {$anesth->_view}
        </option>
      {/foreach}
	  </select>
    </td>

    <th>Date:</th>
    <td class="readonly">
      <input type="text" name="_day"   value="{$day  }" readonly="readonly" size='1' />-
      <input type="text" name="_month" value="{$month}" readonly="readonly" size='1' />-
      <input type="text" name="_year"  value="{$year }" readonly="readonly" size='2' />
    </td>

    <th class="mandatory">Fin:</th>
    <td>
      <select name='_heurefin'>
      {foreach from=$heures item=heure}
        <option {if $plagesel->_heurefin == $heure} selected="selected" {/if} >
          {$heure|string_format:"%02d"}
        </option>
      {/foreach}
      </select>
      :
      <select name='_minutefin'>
      {foreach from=$minutes item=minute}
        <option {if $plagesel->_minutefin == $minute} selected="selected" {/if} >
          {$minute|string_format:"%02d"}
        </option>
      {/foreach}
      </select>
    </td>
  </tr>
  
  <tr>
    <th class="mandatory">Sp�cialit�:</th>
    <td colspan="5">
      <select name='id_spec'>
        <option value="0">-- Choisir une sp�cialit�</option>
        {foreach from=$specs item=spec}
          <option value="{$spec->function_id}" {if $spec->function_id == $plagesel->id_spec} selected="selected" {/if} >
            {$spec->text}
          </option>
        {/foreach}
      </select>
    </td>
  </tr>
  
  <tr>
    <th>Dur�e de r�p�tition:</th>
    <td><input type="text" name="_repeat" size="1" value="1" /> semaine(s)</td>
    <td colspan="4"><input type="checkbox" name="_double" />Une semaine sur deux</td>
  </tr>
  
  <tr>
    <td class="button" colspan="6">
    {if $plagesel->id}
      <input type='reset' value='R�initialiser' />
      <input type='submit' value='Modifier' />
    {else}
      <input type='submit' value='Ajouter' >
    {/if}
    </td>
  </tr>

</table>

</form>

{if $plagesel->id}
  <form name='removeFrm' action='./index.php?m={$m}' method='post'>

  <input type='hidden' name='dosql' value='do_plagesop_aed' />
  <input type='hidden' name='del' value='1' />
  <input type='hidden' name='id' value='{$plagesel->id}' /> 
  <table class="form">
    <tr>
      <th class="category" colspan="2">Supprimer la plage op�ratoire</th>
    </tr>
  
    <tr>
      <th>Supprimer cette plage pendant</th> 
      <td><input type='text' name='_repeat' size="1" value='1' /> semaine(s)</td>
    </tr>
   
    <tr>
      <td class="button" colspan="2">
        <input type='submit' value='Supprimer' />
      </td>
    </tr>
  </table>

  </form>
{/if}

{/if}

</td>
<td>

<div id="calendar-container"></div>

<table class="tbl">
  <tr>
  	<th>Liste des sp�cialit�s</th>
  </tr>
  {foreach from=$specs item=curr_spec}
  <tr>
    <td class="text" style="background: #{$curr_spec->color};">{$curr_spec->text}</td>
  </tr>
  {/foreach}
</table>

