<!-- $Id$ -->

{literal}
<script language="javascript">
function checkForm() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id)
    if (field.value == 0) {
      alert("Chirurgien manquant");
      popChir();
      return false;
    }

  if (field = form.patient_id)
    if (field.value == 0) {
      alert("Patient manquant");
      popPat();
      return false;
    }

  if (field = form.plageconsult_id)
    if (field.value == 0) {
      alert("Jour de consultation non selectionné");
      popPlage();
      return false;
    }

  return true;
}

function checkChir() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id) {
    if (field.value == 0) {
      alert("Chirurgien manquant");
      popChir();
      return false;
    }
    else
      return true;
  }
  else
    return false;
}

function popChir() {
  var url = './index.php?m=mediusers';
  url += '&a=chir_selector';
  url += '&dialog=1';
  popup(400, 250, url, 'Chirurgien');
}

function setChir( key, val ){
  var f = document.editFrm;
   if (val != '') {
      f.chir_id.value = key;
      f._chir_name.value = val;
      window.chir_id = key;
      window._chir_name = val;
    }
}

function popPat() {
  var url = './index.php?m=dPpatients';
  url += '&a=pat_selector';
  url += '&dialog=1';
  popup(500, 500, url, 'Patient');
}

function setPat( key, val ) {
  var f = document.editFrm;

  if (val != '') {
    f.patient_id.value = key;
    f._pat_name.value = val;
    window.patient_id = key;
    window._pat_name = val;
  }
}

function popRDV() {
  var url = './index.php?m=dPcabinet';
  url += '&a=plage_selector';
  url += '&dialog=1';
  url += '&chir=' + document.editFrm.chir_id.value;
  if(checkChir())
    popup(600, 550, url, 'Plage');
}

function setRDV( hour, min, id, date, freq ) {
  var f = document.editFrm;
  f.plageconsult_id.value = id;
  window.plageconsult_id = id;
  f._date.value = date;
  window._date_id = date;
  f._hour.value = hour;
  window._hour = hour;
  f._min.value = min;
  window._min = min;
  f.duree.value = freq;
  window.duree = freq;
}

var calendarField = '';
var calWin = null;
 
function popCalendar( field ) {
  calendarField = field;
  idate = eval( 'document.editFrm._date' + field + '.value' );
  
  var url =  'index.php?m=public';
  url += '&a=calendar';
  url += '&dialog=1';
  url += '&callback=setCalendar';
  url += '&date=' + idate;
  popup(280, 250, url, 'calwin');
}

function setCalendar( idate, fdate ) {
  fld_date = eval( 'document.editFrm._date' + calendarField );
  fld_fdate = eval( 'document.editFrm.' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}
</script>
{/literal}

<form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">

<input type="hidden" name="dosql" value="do_consultation_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />

<table class="main">
  <tr>
    <td>
  
      <table class="form">
        <tr><th class="category" colspan="3">Informations sur la consultation</th></tr>
        
        <tr>
          <th class="mandatory">
            <input type="hidden" name="chir_id" value="{$consult->_ref_plageconsult->_ref_chir->user_id}" />
            <label for="editFrm_chir_id">Chirurgien:</label>
          </th>
          <td class="readonly"><input type="text" name="_chir_name" size="30" value="{$consult->_ref_plageconsult->_ref_chir->user_last_name} {$consult->_ref_plageconsult->_ref_chir->user_first_name}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()"></td>
        </tr>

        <tr>
          <th class="mandatory">
            <input type="hidden" name="patient_id" value="{$consult->_ref_patient->patient_id}" />
            <label for="editFrm_chir_id">Patient:</label>
          </th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="{$consult->_ref_patient->nom} {$consult->_ref_patient->prenom}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        
        <tr>
          <th><label for="editFrm_motif">Motif</label></th>
          <td colspan="2"><textarea name="motif" rows="3">{$consult->motif}</textarea></td>
        </tr>

        <tr>
          <th><label for="editFrm_rques">Remarques</label></th>
          <td colspan="2"><textarea name="rques" rows="3">{$consult->rques}</textarea></td>
        </tr>

      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">Rendez-vous</th></tr>

        <tr>
          <th><label for="editFrm__date">Date:</label></th>
          <td class="readonly">
            <input type="text" name="_date" value="{$consult->_ref_plageconsult->_dateFormated}" readonly="readonly" />
            <input type="hidden" name="plageconsult_id" value="{$consult->_ref_plageconsult->plageconsult_id}" />
          </td>
          <td rowspan="3" class="button"><input type="button" value="Selectionner" onclick="popRDV()" /></td>
        </tr>

        <tr>
          <th><label for="editFrm__hour">Heure:</label></th>
          <td class="readonly">
            <input type="text" name="_hour" value="{$consult->_hour}" size="3" readonly="readonly" /> h
            <input type="text" name="_min" value="{$consult->_min}" size="3" readonly="readonly" />
          </td>
        </tr>
        <tr>
          <th><label for="editFrm__duree">Durée:</label></th>
          <td>
            <input type="hidden" name="duree" value="" />
            <select name="_mult">
              <option value="1" {if $consult->_mult == 1} selected="selected" {/if}>simple</option>
              <option value="2" {if $consult->_mult == 2} selected="selected" {/if}>double</option>
            </select>
          </td>
        </tr>

      </table>
    
    </td>
  </tr>

  <tr>
    <td colspan="2">

      <table class="form">
        <tr>
          <td class="button">
          {if $op}
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Modifier" />
            <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
          {else}
            <input type="submit" value="Créer" />
          {/if}
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table>

</form>