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

  if (field = form.CIM10_code)
    if (field.value.length == 0) {
      alert("Code CIM10 Manquant");
      popCode('cim10');
      return false;
    }

  if (field = form.CCAM_code)
    if (field.value.length == 0) {
      alert("Code CCAM Manquant");
      popCode('ccam');
      return false;
    }

/* Bug in IE
  if (form._hour_op.value == 0 && form._min_op.value == 0) {
    alert("Temps opératoire invalide");
    form.hour_op.focus();
    return false;
  }
*/

  if (field = form.plageop_id)
    if (field.value == 0) {
      alert("Intervention non planifiée");
      popPlage();
      return false;
    }

  if (field = form._date_rdv_adm)
    if (field.value.length == 0) {
      alert("Admission: date manquante");
      popCalendar('_rdv_adm', '_rdv_adm');
      return false;
    }

  if (field = form._hour_adm)
    if (field.value.length == 0) {
      alert("Admission: heure manquante");
      form.hour_anesth.focus();
      return false;
    }

  return true;
}

function popChir() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=chir_selector';
  url += '&dialog=1';
  
  window.open(url, 'Chirurgien', 'left=50,top=50,height=250,width=400,resizable');
}

function popPat() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=pat_selector';
  url += '&dialog=1';

  window.open(url, 'Patient', 'left=50,top=50,height=250,width=400,resizable');
}

function popCode(type) {
  var url = './index.php?m=dPplanningOp';
  url += '&a=code_selector';
  url += '&dialog=1';
  url += '&chir='+ document.editFrm.chir_id.value;
  url += '&type='+ type;

  window.open(url, 'CIM10', 'left=50,top=50,height=500,width=600,resizable');
}

function popPlage() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=plage_selector';
  url += '&dialog=1';
  url += '&chir=' + document.editFrm.chir_id.value;
  url += '&hour=' + document.editFrm._hour_op.value;
  url += '&min=' + document.editFrm._min_op.value;

  window.open(url, 'Plage', 'left=50,top=50,height=250,width=400,resizable');
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

function setPat( key, val ){
  var f = document.editFrm;

  if (val != '') {
    f.pat_id.value = key;
    f._pat_name.value = val;
    window.pat_id = key;
    window._pat_name = val;
  }
}

function setCode( key, type ){
  var f = document.editFrm;

  if (key != '') {
    if(type == 'ccam') {
      f.CCAM_code.value = key;
      window.CCAM_code = key;
    }
    else {
      f.CIM10_code.value = key;
      window.CIM10_code = key;
    }
  }
}

function setPlage( key, val ){
  var f = document.editFrm;

  if (key != '') {
    f.plageop_id.value = key
    f.date.value = val;
    window.plageop_id = key;
    window.date = val;
  }
}

var calendarField = '';
var calWin = null;
 
function popCalendar( field ){
  calendarField = field;
  idate = eval( 'document.editFrm._date' + field + '.value' );
  
  var url =  'index.php?m=public';
  url += '&a=calendar';
  url += '&dialog=1';
  url += '&callback=setCalendar';
  url += '&date=' + idate;
  
  window.open(url, 'calwin', 'top=250,left=250,width=280, height=250, scollbars=false' );
}

function setCalendar( idate, fdate ) {
  fld_date = eval( 'document.editFrm._date' + calendarField );
  fld_fdate = eval( 'document.editFrm.' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}
	
function printForm() {
  if (checkForm()) {
    url = './index.php?m=dPplanningOp';
    url += '&a=view_planning';
    url += '&dialog=1';
    url += '&chir_id='     + eval('document.editFrm.chir_id.value'    );
    url += '&pat_id='      + eval('document.editFrm.pat_id.value'     );
    url += '&CCAM_code='   + eval('document.editFrm.CCAM_code.value'  );
    url += '&cote='        + eval('document.editFrm.cote.value'       );
    url += '&hour_op='     + eval('document.editFrm._hour_op.value'    );
    url += '&min_op='      + eval('document.editFrm._min_op.value'     );
    url += '&date='        + eval('document.editFrm.date.value'       );
    url += '&info='        + eval('document.editFrm.info.value'       );
    url += '&rdv_anesth='  + eval('document.editFrm._rdv_anesth.value' );
    url += '&hour_anesth=' + eval('document.editFrm._hour_anesth.value');
    url += '&min_anesth='  + eval('document.editFrm._min_anesth.value' );
    url += '&rdv_adm='     + eval('document.editFrm._rdv_adm.value'    );
    url += '&hour_adm='    + eval('document.editFrm._hour_adm.value'   );
    url += '&min_adm='     + eval('document.editFrm._min_adm.value'    );
    url += '&duree_hospi=' + eval('document.editFrm.duree_hospi.value');
    url += '&type_adm='    + eval('document.editFrm.type_adm.value'   );
    url += '&chambre='     + eval('document.editFrm.chambre.value'    ); 
 
    window.open( url, 'printAdm', 'top=10,left=10, width=800, height=600, scollbars=true' );
  }
}
</script>
{/literal}

<form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">

<input type="hidden" name="dosql" value="do_planning_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="operation_id" value="{$op->operation_id}" />
<input type="hidden" name="rank" value="{$op->rank}" />

<table class="main">
  <tr>
    <td>
	
      <table class="form">
        <tr><th class="category" colspan="3">Informations concernant l'opération</th></tr>

        <tr>
		      <th class="mandatory"><input type="hidden" name="chir_id" value="{$chir->user_id}" />Chirurgien:</th>
          <td class="readonly"><input type="text" name="_chir_name" size="30" value="{if ($chir)}Dr. {$chir->user_last_name} {$chir->user_first_name}{/if}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()"></td>
        </tr>

        {if !$protocole}
        <tr>
          <th class="mandatory"><input type="hidden" name="pat_id" value="{$pat->patient_id}" />Patient:</th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="{$pat->nom} {$pat->prenom}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        
        <tr>
          <th class="mandatory">Diagnostic (CIM10):</th>
          <td><input type="text" name="CIM10_code" size="10" value="{$op->CIM10_code}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('cim10')" /></td>
        </tr>
        {/if}

        <tr>
          <th class="mandatory">Acte médical (CCAM):</th>
          <td><input type="text" name="CCAM_code" size="10" value="{$op->CCAM_code}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam')"/></td>
        </tr>

        <tr>
          <th>Coté:</th>
          <td colspan="2">
            <select name="cote">
          	  <option {if !$op && $op->cote == "total"} selected="selected" {/if} >total</option>
          	  <option {if $op->cote == "droit"    } selected="selected" {/if} >droit     </option>
          	  <option {if $op->cote == "gauche"   } selected="selected" {/if} >gauche    </option>
          	  <option {if $op->cote == "bilatéral"} selected="selected" {/if} >bilatereal</option>
          	</select>
          </td>
        </tr>

        <tr>
          <th class="mandatory">Temps opératoire:</th>
          <td colspan="2">
            <select name="_hour_op">
            {foreach from=$hours key=key item=hour}
            	<option {if (!$op && $key == 1) || $op->_hour_op == $key} selected="selected" {/if}>{$key}</option>
            {/foreach}
            </select>
            :
            <select name="_min_op">
            {foreach from=$mins item=min}
            	<option {if (!$op && $min == 0) || $op->_min_op == $min} selected="selected" {/if}>{$min}</option>
            {/foreach}
            </select>
          </td>
        </tr>

        {if !$protocole}
        <tr>
          <th class="mandatory"><input type="hidden" name="plageop_id" value="{$plage->id}" />Date de l'intervention:</th>
          <td class="readonly"><input type="text" name="date" readonly="readonly" size="10" value="{$plage->_date}" /></td>
          <td class="button"><input type="button" value="choisir une date" onclick="popPlage()" /></td>
        </tr>
        {/if}
        
        <tr>
          <th>Examens complémentaires:</th>
          <td colspan="2"><textarea name="examen" rows="3">{$op->examen}</textarea></td>
        </tr>

        {if !$protocole}
        <tr>
          <th>Matériel à prévoir:</th>
          <td colspan="2"><textarea name="materiel" rows="3">{$op->materiel}</textarea></td>
        </tr>

        <tr>
          <th>Information du patient:</th>
          <td colspan="2">
            <input name="info" value="o" type="radio" {if $op->info == "o"} checked="checked" {/if}/>Oui
            <input name="info" value="n" type="radio" {if !$op || $op->info == "n"} checked="checked" {/if}/>Non
          </td>
        </tr>
        {/if}

      </table>

    </td>
    <td>

      <table class="form">
        {if !$protocole}
        <tr><th class="category" colspan="3">RDV d'anesthésie</th></tr>

        <tr>
          <th>Date:</th>
          <td class="readonly">
            <input type="hidden" name="_date_rdv_anesth" value="{$op->_date_rdv_anesth}" />
            <input type="text" name="_rdv_anesth" value="{$op->_rdv_anesth}" readonly="readonly" />
            <a href="#" onClick="popCalendar('_rdv_anesth', '_rdv_anesth');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>

        <tr>
          <th>Heure:</th>
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
        {/if}
        
        <tr><th class="category" colspan="3">Admission</th></tr>

        {if !$protocole}
        <tr>
          <th class="mandatory">Date:</th>
          <td class="readonly">
            <input type="hidden" name="_date_rdv_adm" value="{$op->_date_rdv_adm}" />
            <input type="text" name="_rdv_adm" value="{$op->_rdv_adm}" readonly="readonly" />
            <a href="#" onClick="popCalendar( '_rdv_adm', '_rdv_adm');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>

        <tr>
          <th class="mandatory">Heure:</th>
          <td>
            <select name="_hour_adm">
            {foreach from=$hours item=hour}
            	<option {if $op->_hour_adm == $hour} selected="selected" {/if}>{$hour}</option>
            {/foreach}
            </select>
            :
            <select name="_min_adm">
            {foreach from=$mins item=min}
            	<option {if $op->_min_adm == $min} selected="selected" {/if}>{$min}</option>
            {/foreach}
            </select>
          </td>
        </tr>
        {/if}

        <tr>
          <th>Durée d'hospitalisation:</th>
          <td><input type"text" name="duree_hospi" size="1" value="{$op->duree_hospi}"> jours</td>
        </tr>
        <tr>
          <th>Admission en:</th>
          <td>
            <input name="type_adm" value="comp" type="radio" {if !$op || $op->type_adm == "comp"} checked="checked" {/if} />hospitalisation complète<br />
            <input name="type_adm" value="ambu" type="radio" {if $op->type_adm == "ambu"} checked="checked" {/if} />Ambulatoire<br />
			      <input name="type_adm" value="exte" type="radio" {if $op->type_adm == "exte"} checked="checked" {/if} />Externe
          </td>
        </tr>
        
        {if !$protocole}
        <tr>
          <th>Chambre particulière:</th>
          <td>
            <input name="chambre" value="o" type="radio" {if !$op || $op->chambre == "o"} checked="checked" {/if}/>Oui
            <input name="chambre" value="n" type="radio" {if $op->chambre == "n"} checked="checked" {/if}/>Non
          </td>
        </tr>
        <tr><th class="category" colspan="3">Autre</th></tr>
        <tr>
          <th>Risque ATNC:</th>
          <td>
            <input name="ATNC" value="o" type="radio" {if $op->ATNC == "o"} checked="checked" {/if} />Oui
            <input name="ATNC" value="n" type="radio" {if !$op || $op->ATNC == "n"} checked="checked" {/if} />Non
          </td>
        </tr>
        <tr>
          <th>Remarques:</th>
          <td><textarea name="rques" rows="3">{$op->rques}</textarea></td>
        </tr>
        {/if}

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
