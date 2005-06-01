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

/* Bug in IE
  if (form._hour_op.value == 0 && form._min_op.value == 0) {
    alert("Temps opératoire invalide");
    form.hour_op.focus();
    return false;
  }
*/

  if (field = form._date_rdv_adm)
    if (field.value.length == 0) {
      alert("Admission: date manquante");
      popCalendar('_rdv_adm', '_rdv_adm');
      return false;
    }
/* Bug in IE
  if (field = form._hour_adm)
    if (field.value.length == 0) {
      alert("Admission: heure manquante");
      field.focus();
      return false;
    }
*/

  if((field1 = form.type_adm) && (field2 = form.duree_hospi))
    if(field1[0].checked && ((field2.value == 0) || (field2.value == ''))) {
      field2.value = prompt("Veuillez saisir un temps d'hospitalisation prévu superieur à 0", "");
      field2.focus();
      return false;
    }

  return true;
}

function modifOp() {
  f = document.editFrm;
  if(f.saisie.value == 'o') {
    f.modifiee.value = 1;
    f.saisie.value = 'n';
  }
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
  popup(800, 500, url, 'Patient');
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

function popCode(type) {
  var url = './index.php?m=dPplanningOp';
  url += '&a=code_selector';
  url += '&dialog=1';
  url += '&chir='+ document.editFrm.chir_id.value;
  url += '&type='+ type;
  popup(600, 500, url, type);
}

function setCode( key, type ){
  var f = document.editFrm;

  if (key != '') {
    if(type == 'ccam') {
      if(f.CCAM_code.value != key) {
        modifOp();
      }
      f.CCAM_code.value = key;
      window.CCAM_code = key;
    }
    else if(type == 'ccam2') {
      f.CCAM_code2.value = key;
      window.CCAM_code2 = key;
    }
    else {
      f.CIM10_code.value = key;
      window.CIM10_code = key;
    }
  }
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

function popPlage() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=plage_selector';
  url += '&dialog=1';
  url += '&chir=' + document.editFrm.chir_id.value;
  url += '&curr_op_hour=' + document.editFrm._hour_op.value;
  url += '&curr_op_min=' + document.editFrm._min_op.value;
  if(checkChir())
    popup(400, 250, url, 'Plage');
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
      f._hour_adm.value = 17;
    }
    else {
      f._hour_adm.value = 8;
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
    if(f._rdv_adm.value != day + "/" + month + "/" + year)
      modifOp();
    f._rdv_adm.value = day + "/" + month + "/" + year;
    f._date_rdv_adm.value = year + month + day;
  }
}

function popProtocole() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=vw_protocoles';
  url += '&dialog=1';
  url += '&chir_id='   + document.editFrm.chir_id.value;
  popup(700, 500, url, 'Protocole');
}

function setProtocole(
    chir_id,
    chir_last_name,
    chir_first_name,
    prot_CCAM_code,
    prot_hour_op,
    prot_min_op,
    prot_examen,
    prot_materiel,
    prot_convalescence,
    prot_depassement,
    prot_type_adm,
    prot_duree_hospi,
    prot_rques) {

  var f = document.editFrm;
  
  f.chir_id.value       = chir_id;
  f._chir_name.value    = chir_last_name + " " + chir_first_name;
  f.CCAM_code.value     = prot_CCAM_code;
  f._hour_op.value      = prot_hour_op;
  f._min_op.value       = prot_min_op;
  f.examen.value        = prot_examen;
  f.materiel.value      = prot_materiel;
  f.convalescence.value = prot_convalescence;
  f.depassement.value   = prot_depassement;
  f.type_adm.value      = prot_type_adm;
  f.duree_hospi.value   = prot_duree_hospi;
  f.rques.value         = prot_rques;
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
  if((calendarField == '_rdv_adm') && (fld_date.value != idate))
    modifOp();
  fld_date.value = idate;
  fld_fdate.value = fdate;
}
  
function printForm() {
  // @todo Pourquoi ne pas seulement passer le operation_id? ca parait bcp moins régressif
  if (checkForm()) {
    form = document.editFrm;
    if(form.chambre[0].checked)
      chambre = 'o';
    else
      chambre = 'n';
    if(form.type_adm[0].checked)
      type_adm = 'comp';
    else if(form.type_adm[1].checked)
      type_adm = 'ambu';
    else
      type_adm = 'exte';
    url = './index.php?m=dPplanningOp';
    url += '&a=view_planning';
    url += '&dialog=1';
    url += '&chir_id='     + eval('form.chir_id.value'     );
    url += '&pat_id='      + eval('form.pat_id.value'      );
    url += '&CCAM_code='   + eval('form.CCAM_code.value'   );
    url += '&cote='        + eval('form.cote.value'        );
    url += '&hour_op='     + eval('form._hour_op.value'    );
    url += '&min_op='      + eval('form._min_op.value'     );
    url += '&date='        + eval('form.date.value'        );
    url += '&info='        + eval('form.info.value'        );
    url += '&rdv_anesth='  + eval('form._rdv_anesth.value' );
    url += '&hour_anesth=' + eval('form._hour_anesth.value');
    url += '&min_anesth='  + eval('form._min_anesth.value' );
    url += '&rdv_adm='     + eval('form._rdv_adm.value'    );
    url += '&hour_adm='    + eval('form._hour_adm.value'   );
    url += '&min_adm='     + eval('form._min_adm.value'    );
    url += '&duree_hospi=' + eval('form.duree_hospi.value' );
    url += '&type_adm='    + eval('type_adm'               );
    url += '&chambre='     + eval('chambre'                ); 
    popup(700, 500, url, 'printAdm');
    return true
  }
  else {
    return false;
  }
}
</script>
{/literal}

<form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">

<input type="hidden" name="dosql" value="do_planning_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="operation_id" value="{$op->operation_id}" />
<input type="hidden" name="commande_mat" value="{$op->commande_mat}" />
<input type="hidden" name="rank" value="{$op->rank}" />
<input type="hidden" name="saisie" value="{$op->saisie}" />
<input type="hidden" name="annulee" value="0" />
<input type="hidden" name="modifiee" value="{$op->modifiee}" />

<table class="main" style="margin: 4px; border-spacing: 0px;">
  {if $op->operation_id}
  <tr>
    <td><strong><a href="index.php?m={$m}&amp;hospitalisation_id=0">Créer une nouvelle hospitalisation</a></strong></td>
    <td><strong><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$op->operation_id}&amp;trans=1">Programmer une intervention pour ce patient</a></strong></td>
  </tr>
  {/if}
  <tr>
    {if $op->operation_id}
      <th colspan="2" class="title" colspan="5">Modification de l'hospitalisation de {$pat->_view} par le Dr. {$chir->_view}</th>
    {else}
      <th colspan="2" class="title" colspan="5">Création d'une hospitalisation</th>
    {/if}
  </tr>
  <tr>
    <td>
  
      <table class="form">
        <tr><th class="category" colspan="3">Informations concernant l'hospitalisation</th></tr>
     
        <tr>
          <th class="mandatory">
            <input type="hidden" name="chir_id" value="{$chir->user_id}" />
            <label for="editFrm_chir_id">Chirurgien:</label>
          </th>
          <td class="readonly"><input type="text" name="_chir_name" size="30" value="{if $chir->user_id}{$chir->_view}{/if}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()"></td>
        </tr>

        <tr>
          <th class="mandatory">
            <input type="hidden" name="pat_id" value="{$pat->patient_id}" />
            <label for="editFrm_chir_id">Patient:</label>
          </th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="{$pat->_view}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        
        <tr>
          <th><label for="editFrm_CIM10_code">Diagnostic (CIM10):</label></th>
          <td><input type="text" name="CIM10_code" size="10" value="{$op->CIM10_code}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('cim10')" /></td>
        </tr>

        <tr>
          <th><label for="editFrm_CCAM_code">Acte médical (CCAM):</label></th>
          <td><input type="text" name="CCAM_code" size="10" value="{$op->CCAM_code}"/></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam')"/></td>
        </tr>

        <tr>
          <th><label for="editFrm_CCAM_code">Acte secondaire (CCAM):</label></th>
          <td><input type="text" name="CCAM_code2" size="10" value="{$op->CCAM_code2}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam2')"/></td>
        </tr>
        
        <tr>
          <th class="mandatory"><label for="editFrm_cote">Coté:</label></th>
          <td colspan="2">
            <select name="cote" onchange="modifOp()";>
              <option value="total"     {if !$op || $op->cote == "total"} selected="selected" {/if} >total</option>
              <option value="droit"     {if $op->cote == "droit"        } selected="selected" {/if} >droit    </option>
              <option value="gauche"    {if $op->cote == "gauche"       } selected="selected" {/if} >gauche   </option>
              <option value="bilatéral" {if $op->cote == "bilatéral"    } selected="selected" {/if} >bilatéral</option>
            </select>
          </td>
        </tr>
        
        <tr>
          <th><label for="editFrm_examen">Examens:</label></th>
          <td colspan="2"><textarea name="examen" rows="3">{$op->examen}</textarea></td>
        </tr>
        
        <tr>
          <th><label for="editFrm_depassement">Dépassement d'honoraire:</label></th>
          <td colspan="2"><input name="depassement" type="text" size="4" value="{$op->depassement}" /> €</td>
        </tr>

        <tr>
          <th><label for="editFrm_info_n">Information du patient:</label></th>
          <td colspan="2">
            <input name="info" value="o" type="radio" {if $op->info == "o"} checked="checked" {/if}/>
            <label for="editFrm_info_o">Oui</label>
            <input name="info" value="n" type="radio" {if !$op || $op->info == "n"} checked="checked" {/if}/>
            <label for="editFrm_info_n">Non</label>
          </td>
        </tr>

      </table>

    </td>
    <td>

      <table class="form">        
        <tr><th class="category" colspan="3">Admission</th></tr>

        <tr>
          <th class="mandatory"><label for="editFrm__rdv_adm">Date:</label></th>
          <td class="readonly">
            <input type="hidden" name="_date_rdv_adm" value="{$op->_date_rdv_adm}" />
            <input type="text" name="_rdv_adm" value="{$op->_rdv_adm}" readonly="readonly" />
            <a href="#" onClick="popCalendar( '_rdv_adm', '_rdv_adm');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>

        <tr>
          <th class="mandatory"><label for="editFrm__hour_adm">Heure:</label></th>
          <td>
            <select name="_hour_adm">
            {foreach from=$hours item=hour}
              <option value="{$hour}" {if $op->_hour_adm == $hour} selected="selected" {/if}>{$hour}</option>
            {/foreach}
            </select>
            :
            <select name="_min_adm">
            {foreach from=$mins item=min}
              <option value="{$min}" {if $op->_min_adm == $min} selected="selected" {/if}>{$min}</option>
            {/foreach}
            </select>
          </td>
        </tr>

        <tr>
          <th><label for="editFrm_duree_hospi">Durée d'hospitalisation:</label></th>
          <td><input type"text" name="duree_hospi" size="1" value="{$op->duree_hospi}"> jours</td>
        </tr>
        <tr>
          <th><label for="editFrm_type_adm_comp">{tr}type_adm{/tr}:</label></th>
          <td>
            <input name="type_adm" value="comp" type="radio" {if !$op || $op->type_adm == "comp"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_type_adm_comp">{tr}comp{/tr}</label><br />
            <input name="type_adm" value="ambu" type="radio" {if $op->type_adm == "ambu"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_type_adm_ambu">{tr}ambu{/tr}</label><br />
            <input name="type_adm" value="exte" type="radio" {if $op->type_adm == "exte"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_type_adm_exte">{tr}exte{/tr}</label><br />
          </td>
        </tr>
        
        <tr>
          <th><label for="editFrm_chambre_o">Chambre particulière:</label></th>
          <td>
            <input name="chambre" value="o" type="radio" {if $op->chambre == "o"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_chambre_o">Oui</label>
            <input name="chambre" value="n" type="radio" {if !$op || $op->chambre == "n"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_chambre_n">Non</label>
          </td>
        </tr>
        <tr><th class="category" colspan="3">Autre</th></tr>
        <tr>
          <th><label for="editFrm_ATNC_n">Risque ATNC:</th>
          <td>
            <input name="ATNC" value="o" type="radio" {if $op->ATNC == "o"} checked="checked" {/if} />
            <label for="editFrm_ATNC_o">Oui</label>
            <input name="ATNC" value="n" type="radio" {if !$op || $op->ATNC == "n"} checked="checked" {/if} />
            <label for="editFrm_ATNC_n">Non</label>
          </td>
        </tr>
        <tr>
          <th><label for="editFrm_rques">Remarques:</label></th>
          <td><textarea name="rques" rows="3">{$op->rques}</textarea></td>
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
            <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'l\'intervention du Dr', '{$op->_ref_chir->_view}')" />
            <input type="button" value="Annuler" onclick="{literal}if (confirm('Veuillez confirmer l\'annulation')) {var f = this.form; f.annulee.value = 1; f.rank.value = 0; f.submit();}{/literal}" />
          {else}
            <input type="submit" value="Créer" />
          {/if}
          {if $op}
          <input type="button" value="Imprimer" onClick="printForm();" />
          {else}
          <input type="button" value="Imprimer et créer" onClick="if(printForm()) this.form.submit()" />
          {/if}
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table></tr></td></table>

</form>
