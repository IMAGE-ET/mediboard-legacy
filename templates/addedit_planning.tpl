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

  if (field = form.pat_id)
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

function popChir() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=chir_selector';
  url += '&dialog=1';
  
  neo = window.open(url, 'Chirurgien', 'left=50, top=50, height=250, width=400, resizable=yes');
  if(neo.window.focus){neo.window.focus();}
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

  neo = window.open(url, 'Patient', 'left=50, top=50, width=500, height=500, resizable=yes');
  if(neo.window.focus){neo.window.focus();}
}

function setPat( key, val ) {
  var f = document.editFrm;

  if (val != '') {
    f.pat_id.value = key;
    f._pat_name.value = val;
    window.pat_id = key;
    window._pat_name = val;
  }
}

function popPlage() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=plage_selector';
  url += '&dialog=1';
  url += '&chir=' + document.editFrm.chir_id.value;
  url += '&curr_op_hour=' + document.editFrm._hour_op.value;
  url += '&curr_op_min=' + document.editFrm._min_op.value;
  if(checkChir())
    neo = window.open(url, 'Plage', 'left=50, top=50, width=400, height=250, resizable=yes');
    if(neo.window.focus){neo.window.focus();}
}

function setPlage( key, val, adm ) {
  var f = document.editFrm;

  if (key != '') {
    f.plageop_id.value = key;
    f.date.value = val;
    window.plageop_id = key;
    window.date = val;
    var sdate = val;
    if(sdate.slice(0,1) == "0")
      var tmpday = parseInt(sdate.slice(1,2));
    else
      var tmpday = parseInt(sdate.slice(0,2));
    if(sdate.slice(3,4) == "0")
      var tmpmonth = parseInt(sdate.slice(4,5)) - 1;
    else
      var tmpmonth = parseInt(sdate.slice(3,5)) - 1;
    var tmpyear = parseInt(sdate.slice(6,10));
    var date = new Date(tmpyear, tmpmonth, tmpday);
    if(adm) {
      date.setDate(parseInt(date.getDate()) - 1);
    }
    var day = "" + date.getDate();
    if(day.length == 1) {
      day = "0" + day;
    }
    var month = "" + (date.getMonth() + 1);
    if(month.length == 1) {
      month = "0" + month;
    }
    var year = "" + date.getFullYear();
    f._rdv_adm.value = day + "/" + month + "/" + year;
    f._date_rdv_adm.value = year + month + day;
  }
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
  
  neo = window.open(url, 'calwin', 'left=250, top=250, width=280, height=250, scrollbars=yes' );
  if(neo.window.focus){neo.window.focus();}
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
            <input type="hidden" name="chir_id" value="{$chir->user_id}" />
            <label for="editFrm_chir_id">Chirurgien:</label>
          </th>
          <td class="readonly"><input type="text" name="_chir_name" size="30" value="{if ($chir)}Dr. {$chir->user_last_name} {$chir->user_first_name}{/if}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()"></td>
        </tr>

        <tr>
          <th class="mandatory">
            <input type="hidden" name="pat_id" value="{$pat->patient_id}" />
            <label for="editFrm_chir_id">Patient:</label>
          </th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="{$pat->nom} {$pat->prenom}" readonly="readonly" /></td>
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
        <tr><th class="category" colspan="3">Date du rdv</th></tr>

        <tr>
          <th><label for="editFrm__rdv_anesth">Date:</label></th>
          <td class="readonly">
            <input type="hidden" name="_date_rdv_anesth" value="{$op->_date_rdv_anesth}" />
            <input type="text" name="_rdv_anesth" value="{$op->_rdv_anesth}" readonly="readonly" />
            <a href="#" onClick="popCalendar('_rdv_anesth', '_rdv_anesth');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>

        <tr>
          <th><label for="editFrm__hour_anesth">Heure:</label></th>
          <td>
            <select name="_hour_anesth">
            {foreach from=$hours item=hour}
              <option {if $op->_hour_anesth == $hour} selected="selected" {/if}>{$hour}</option>
            {/foreach}
            </select>
            :
            <select name="_min_anesth">
            {foreach from=$mins item=min}
              <option {if $op->_min_anesth == $min} selected="selected" {/if}>{$min}</option>
            {/foreach}
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
            <input type="button" value="Imprimer" onClick="printForm()" />
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table>

</form>