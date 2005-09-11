<!-- $Id$ -->

<script language="javascript">
{literal}
function checkForm() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id) {
    if (field.value == 0) {
      alert("Chirurgien manquant");
      popChir();
      return false;
    }
  }

  if (field = form.pat_id) {
    if (field.value == 0) {
      alert("Patient manquant");
      popPat();
      return false;
    }
  }

{/literal}{if !$hospitalisation}{literal}

  if (field = form.CCAM_code) {
    if (field.value.length == 0) {
      alert("Code CCAM Manquant");
      popCode('ccam');
      return false;
    }
  }
  
{/literal}{/if}{literal}

/* Bug in IE
  field1 = form._hour_op;
  field2 = form._min_op;
  if (field1 && field2) {
    if (field1.value == 0 && field2.value == 0) {
      alert("Temps opératoire invalide");
      form.hour_op.focus();
      return false;
    }
  }
*/

  if (field = form.plageop_id) {
    if (field.value == 0) {
      alert("Intervention non planifiée");
      popPlage();
      return false;
    }
  }

  if (field = form.date_adm) {
    if (field.value.length == 0) {
      alert("Admission: date manquante");
      return false;
    }
  }
    
/* Bug in IE
  if (field = form._hour_adm) {
    if (field.value.length == 0) {
      alert("Admission: heure manquante");
      field.focus();
      return false;
    }
  }
*/

  field1 = form.type_adm;
  field2 = form.duree_hospi;
  if (field1 && field2) {
    if (field1[0].checked && (field2.value == 0 || field2.value == '')) {
      field2.value = prompt("Veuillez saisir un temps d'hospitalisation prévu superieur à 0", "");
      field2.focus();
      return false;
    }
  }

  return true;
}

function modifOp() {
  f = document.editFrm;
  if (f.saisie.value == 'o') {
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

function setCode( key, type ) {
  if (key) {
    var form = document.editFrm;
    var field = form.CIM10_code;
    if (type == 'ccam')  field = form.CCAM_code;
    if (type == 'ccam2') field = form.CCAM_code2;
    field.value = key;
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
  }

  return true;
}

function popPlage() {
  var url = './index.php?m=dPplanningOp';
  url += '&a=plage_selector';
  url += '&dialog=1';
  url += '&chir=' + document.editFrm.chir_id.value;
  url += '&curr_op_hour=' + document.editFrm._hour_op.value;
  url += '&curr_op_min=' + document.editFrm._min_op.value;
  if (checkChir())
    popup(400, 250, url, 'Plage');
}

function setPlage(plage_id, sDate, bAdm) {
  var form = document.editFrm;

  if (plage_id) {
    form.plageop_id.value = plage_id;
    form.date.value = sDate;
    
    // Initialize adminission date according to operation date
    var dAdm = makeDateFromLocaleDate(sDate);
    switch(bAdm) {
      case 0 :
        dAdm.setHours(17);
        dAdm.setDate(dAdm.getDate()-1);
        break;
      case 1 :
        dAdm.setHours(8);
        break;
    }
    
    if(bAdm != 2) {
      form._hour_adm.value = dAdm.getHours();
      form._min_adm.value = dAdm.getMinutes();
      form.date_adm.value = makeDATEFromDate(dAdm);
    
      var div_rdv_adm = document.getElementById("editFrm_date_adm_da");
      div_rdv_adm.innerHTML = makeLocaleDateFromDate(dAdm);
    }
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

  var form = document.editFrm;
  
  form.chir_id.value       = chir_id;
  form._chir_name.value    = chir_last_name + " " + chir_first_name;
  form.CCAM_code.value     = prot_CCAM_code;
  form._hour_op.value      = prot_hour_op;
  form._min_op.value       = prot_min_op;
  form.examen.value        = prot_examen;
  form.materiel.value      = prot_materiel;
  form.convalescence.value = prot_convalescence;
  form.depassement.value   = prot_depassement;
  form.type_adm.value      = prot_type_adm;
  form.duree_hospi.value   = prot_duree_hospi;
  form.rques.value         = prot_rques;
}

function printDocument() {
  form = document.editFrm;
  if(checkForm() && (form._choix_modele.value != 0)) {
    url = './index.php?m=dPplanningOp';
    url += '&a=print_document';
    url += '&dialog=1';
    url += '&operation_id=' + eval('form.operation_id.value');
    url += '&document_id='  + eval('form._choix_modele.value'     );
    popup(700, 500, url, 'printAdm');
    return true
  }
  else {
    return false;
  }
}

function printPack() {
  form = document.editFrm;
  if(checkForm() && (form._choix_pack.value != 0)) {
    url = './index.php?m=dPplanningOp';
    url += '&a=print_document';
    url += '&dialog=1';
    url += '&operation_id=' + eval('form.operation_id.value');
    url += '&pack_id='  + eval('form._choix_pack.value'     );
    popup(700, 500, url, 'printAdm');
    return true
  }
  else {
    return false;
  }
}

function printForm() {
  // @todo Pourquoi ne pas seulement passer le operation_id? ca parait bcp moins régressif
  // Rque : il est maintenant possible de passer l'operation_id, mais l'ancienne possibilité
  //        est gardée pour éviter la régréssion et surtout imprimer avant de créer...
  if (checkForm()) {
    form = document.editFrm;
    
    chambre = form.chambre[0].checked ? 'o' : 'n';
    if (form.type_adm[0].checked) type_adm = 'comp';
    if (form.type_adm[1].checked) type_adm = 'ambu';
    if (form.type_adm[2].checked) type_adm = 'exte';
    
    url = './index.php?m=dPplanningOp';
    url += '&a=view_planning';
    url += '&dialog=1';
    
    url += makeURLParam(form.chir_id);
    url += makeURLParam(form.pat_id);
    url += makeURLParam(form.CCAM_code);
    url += makeURLParam(form.CCAM_code2);
    url += makeURLParam(form.cote , "cote" );
    url += makeURLParam(form._hour_op, "hour_op");
    url += makeURLParam(form._min_op , "min_op" );
    url += makeURLParam(form.date);
    url += makeURLParam(form.info);
    
    if (element = document.getElementById("editFrm_date_anesth_da")) {
      url += '&rdv_anesth='  + element.innerHTML;
      url += makeURLParam(form._hour_anesth, "hour_anesth");
      url += makeURLParam(form._min_anesth , "min_anesth" );
    }
    
    if (element = document.getElementById("editFrm_date_adm_da")) {
      url += '&rdv_adm='  + element.innerHTML;
      url += makeURLParam(form._hour_adm, "hour_adm");
      url += makeURLParam(form._min_adm , "min_adm" );
      url += makeURLParam(form.duree_hospi);
      url += '&type_adm='    + type_adm;
      url += '&chambre='     + chambre;
    }

    popup(700, 500, url, 'printAdm');
    return true;
  }
  
  return false;
}

function pageMain() {
  regFieldCalendar("editFrm", "date_anesth");
  regFieldCalendar("editFrm", "date_adm");
}

{/literal}
</script>

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
    {if $hospitalisation}
    <td>
      <strong>
        <a href="index.php?m={$m}&amp;hospitalisation_id=0">Créer une nouvelle hospitalisation</a>
      </strong>
    </td>
    <td>
      <strong>
        <a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$op->operation_id}&amp;trans=1">Programmer une intervention pour ce patient</a>
      </strong>
    </td>
    {else}
    <td colspan="2">
      <strong>
       {if $protocole}
       <a href="index.php?m={$m}&amp;protocole_id=0">Créer un nouveau protocole</a>
       {else}
       <a href="index.php?m={$m}&amp;operation_id=0">Programmer une nouvelle intervention</a>
       {/if}
      </strong>
    </td>
    {/if}
  </tr>
  {/if}

  <tr>
    {if $op->operation_id}
      <th colspan="2" class="title" colspan="5" style="color: #f00;">
      {if $protocole}
      Modification du protocole {$op->CCAM_code} du Dr. {$chir->_view}
      {elseif $hospitalisation}
      Modification de l'hospitalisation de {$pat->_view} par le Dr. {$chir->_view}
      {else}
      Modification de l'intervention de {$pat->_view} par le Dr. {$chir->_view}
      {/if}
      </th>
    {else}
      <th colspan="2" class="title" colspan="5">      
      {if $protocole}
      Création d'un protocole
      {elseif $hospitalisation}
      Création d'une hospitalisation
      {else}
      Création d'une intervention
      {/if}
      </th>
    {/if}
  </tr>
  
  <tr>
    <td>
  
      <table class="form">
        <tr>
          <th class="category" colspan="3">
            {if $protocole}
            Informations concernant le protocole
            {elseif $hospitalisation}
            Informations concernant l'hospitalisation
            {else}
            Informations concernant l'opération
            {/if}
          </th>
        </tr>

        {if !$protocole}
        <tr>
          <td class="button" colspan="3">
            <input type="button" value="Choisir un protocole" onclick="popProtocole()" />
          </td>
        </tr>
        {/if}
        
        <tr>
          <th class="mandatory">
            <input type="hidden" name="chir_id" value="{$chir->user_id}" />
            <label for="editFrm_chir_id">Chirurgien:</label>
          </th>
          <td class="readonly"><input type="text" name="_chir_name" size="30" value="{if $chir->user_id}{$chir->_view}{/if}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()"></td>
        </tr>

        {if !$protocole}
        <tr>
          <th class="mandatory">
            <input type="hidden" name="pat_id" value="{$pat->patient_id}" />
            <label for="editFrm_chir_id">Patient:</label>
          </th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="{$pat->_view}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        
        {if !$hospitalisation}
        <tr>
          <th class="mandatory">
            <input type="hidden" name="plageop_id" value="{$plage->id}" />
            <label for="editFrm_date">Date de l'intervention:</label>
          </th>
          <td class="readonly"><input type="text" name="date" readonly="readonly" size="10" value="{$plage->_date}" /></td>
          <td class="button"><input type="button" value="choisir une date" onclick="popPlage()" /></td>
        </tr>
        {/if}
        
        <tr>
          <th><label for="editFrm_CIM10_code">Diagnostic (CIM10):</label></th>
          <td><input type="text" name="CIM10_code" size="10" value="{$op->CIM10_code}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('cim10')" /></td>
        </tr>
        {/if}

        <tr>
          <th {if !$hospitalisation}class="mandatory"{/if}><label for="editFrm_CCAM_code">Acte médical (CCAM):</label></th>
          <td><input type="text" name="CCAM_code" size="10" value="{$op->CCAM_code}" onchange="modifOp()" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam')"/></td>
        </tr>

        <tr>
          <th><label for="editFrm_CCAM_code2">Acte secondaire (CCAM):</label></th>
          <td><input type="text" name="CCAM_code2" size="10" value="{$op->CCAM_code2}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam2')"/></td>
        </tr>
        
        <tr>
          <th><label for="editFrm_libelle">Libellé:</label></th>
          <td colspan="2"><input type="text" name="libelle" size="70" value="{$op->libelle}"/></td>
        </tr>
        
        {if !$protocole}
        <tr>
          <th class="mandatory"><label for="editFrm_cote">Côté:</label></th>
          <td colspan="2">
            <select name="cote" onchange="modifOp()">
              <option value="total"     {if !$op || $op->cote == "total"} selected="selected" {/if} >total</option>
              <option value="droit"     {if $op->cote == "droit"        } selected="selected" {/if} >droit    </option>
              <option value="gauche"    {if $op->cote == "gauche"       } selected="selected" {/if} >gauche   </option>
              <option value="bilatéral" {if $op->cote == "bilatéral"    } selected="selected" {/if} >bilatéral</option>
            </select>
          </td>
        </tr>
        {/if}

        {if !$hospitalisation}
        <tr>
          <th class="mandatory"><label for="editFrm__hour_op">Temps opératoire:</label></th>
          <td colspan="2">
            <select name="_hour_op">
            {foreach from=$hours key=key item=hour}
              <option value="{$key}" {if (!$op && $key == 1) || $op->_hour_op == $key} selected="selected" {/if}>{$key}</option>
            {/foreach}
            </select>
            :
            <select name="_min_op">
            {foreach from=$mins item=min}
              <option value="{$min}" {if (!$op && $min == 0) || $op->_min_op == $min} selected="selected" {/if}>{$min}</option>
            {/foreach}
            </select>
          </td>
        </tr>
        {/if}
        
        {if $hospitalisation}
        <tr>
          <th><label for="editFrm_examen">Bilan pré-op</label></th>
          <td colspan="2"><textarea name="examen" rows="3">{$op->examen}</textarea></td>
        </tr>
        {else}
        <tr>
          <td class="text"><label for="editFrm_examen">Bilan pré-op</label></td>
          <td class="text"><label for="editFrm_materiel">Matériel à prévoir / examens per-op</label></td>
          <td class="text"><label for="editFrm_convalescence">Convalescence</label></td>
        </tr>

        <tr>
          <td><textarea name="examen" rows="3">{$op->examen}</textarea></td>
          <td><textarea name="materiel" rows="3">{$op->materiel}</textarea></td>
          <td><textarea name="convalescence" rows="3">{$op->convalescence}</textarea></td>
        </tr>
        {/if}
        
        <tr>
          <th><label for="editFrm_depassement">Dépassement d'honoraire:</label></th>
          <td colspan="2"><input name="depassement" type="text" size="4" value="{$op->depassement}" /> €</td>
        </tr>

        {if !$protocole}
        <tr>
          <th><label for="editFrm_info_n">Information du patient:</label></th>
          <td colspan="2">
            <input name="info" value="o" type="radio" {if $op->info == "o"} checked="checked" {/if}/>
            <label for="editFrm_info_o">Oui</label>
            <input name="info" value="n" type="radio" {if !$op || $op->info == "n"} checked="checked" {/if}/>
            <label for="editFrm_info_n">Non</label>
          </td>
        </tr>
        {/if}

      </table>

    </td>
    <td>

      <table class="form">
        {if !$protocole && !$hospitalisation}
        <tr><th class="category" colspan="3">RDV d'anesthésie</th></tr>

        <tr>
          <th><label for="editFrm_date_anesth_trigger">Date:</label></th>
          <td class="date">
            <div id="editFrm_date_anesth_da">{$op->date_anesth|date_format:"%d/%m/%Y"}</div>
            <input type="hidden" name="date_anesth" value="{$op->date_anesth}" onchange="modifOp()" />
            <img id="editFrm_date_anesth_trigger" src="./images/calendar.gif" alt="calendar" title="Choisir une date de début" />
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
        {/if}
        
        <tr><th class="category" colspan="3">Admission</th></tr>

        {if !$protocole}
        <tr>
          <th class="mandatory"><label for="editFrm_date_adm_trigger">Date:</label></th>
          <td class="date">
            <div id="editFrm_date_adm_da">{$op->date_adm|date_format:"%d/%m/%Y"}</div>
            <input type="hidden" name="date_adm" value="{$op->date_adm}" onchange="modifOp()"/>
            <img id="editFrm_date_adm_trigger" src="./images/calendar.gif" alt="calendar" title="Choisir une date de début" />
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
        {/if}

        <tr>
          <th class="mandatory"><label for="editFrm_duree_hospi">Durée d'hospitalisation:</label></th>
          <td><input type"text" name="duree_hospi" size="2" value="{$op->duree_hospi}"> jours</td>
        </tr>
        <tr>
          <th><label for="editFrm_type_adm_comp">{tr}type_adm{/tr}:</label></th>
          <td>
            <input name="type_adm" value="comp" type="radio" {if !$op->operation_id || $op->type_adm == "comp"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_type_adm_comp">{tr}comp{/tr}</label><br />
            <input name="type_adm" value="ambu" type="radio" {if $op->type_adm == "ambu"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_type_adm_ambu">{tr}ambu{/tr}</label><br />
            <input name="type_adm" value="exte" type="radio" {if $op->type_adm == "exte"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_type_adm_exte">{tr}exte{/tr}</label><br />
          </td>
        </tr>
        
        {if !$protocole}
        <tr>
          <th><label for="editFrm_chambre_o">Chambre particulière:</label></th>
          <td>
            <input name="chambre" value="o" type="radio" {if $op->chambre == "o"} checked="checked" {/if} onchange="modifOp()" />
            <label for="editFrm_chambre_o">Oui</label>
            <input name="chambre" value="n" type="radio" {if !$op->operation_id || $op->chambre == "n"} checked="checked" {/if} onchange="modifOp()" />
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
        {/if}
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
          {if $op->operation_id}
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Modifier" />
            <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'l\'intervention du Dr', '{$op->_ref_chir->_view}')" />
            <input type="button" value="Annuler" onclick="{literal}if (confirm('Veuillez confirmer l\'annulation')) {var f = this.form; f.annulee.value = 1; f.rank.value = 0; f.submit();}{/literal}" />
          {else}
            <input type="submit" value="Créer" />
          {/if}
          {if !$protocole}
            {if $op->operation_id}
            <input type="button" value="Imprimer" onClick="printForm();" />
            <select name="_choix_modele" onchange="printDocument()">
              <option value="">&mdash; Choisir un modèle</option>
              <optgroup label="Modèles du praticien">
              {foreach from=$listModelePrat item=curr_modele}
                <option value="{$curr_modele->compte_rendu_id}">{$curr_modele->nom}</option>
              {/foreach}
              </optgroup>
              <optgroup label="Modèles du cabinet">
              {foreach from=$listModeleFunc item=curr_modele}
                <option value="{$curr_modele->compte_rendu_id}">{$curr_modele->nom}</option>
              {/foreach}
              </optgroup>
            </select>
            <select name="_choix_pack" onchange="printPack()">
              <option value="">&mdash; Choisir un pack</option>
              {foreach from=$listPack item=curr_pack}
                <option value="{$curr_pack->pack_id}">{$curr_pack->nom}</option>
              {/foreach}
            </select>
            {else}
            <input type="button" value="Imprimer et créer" onClick="if (printForm()) this.form.submit()" />
            {/if}
          {/if}
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table>

</form>
