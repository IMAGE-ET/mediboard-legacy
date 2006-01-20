<script type="text/javascript">
{literal}

function printFiche() {
  var url = new Url;
  url.setModuleAction("dPcabinet", "print_fiche"); 
  url.addElement(document.editFrm.consultation_id);
  url.popup(700, 500, url, "printFiche");
  return;
}

function editPat(patient_id) {
  var url = new Url;
  url.setModuleAction("dPpatients", "vw_edit_patients");
  url.addParam("patient_id", patient_id);
  url.redirect();
}

function pasteText(formName) {
  var form = document.editFrm;
  var aide = eval("form._aide_" + formName);
  var area = eval("form." + formName);
  insertAt(area, aide.value + '\n')
  aide.value = 0;
}

function submitConsultWithChrono(chrono) {
  var form = document.editFrm;
  form.chrono.value = chrono;
  form.submit();
}

function selectCim10(code) {
  var url = new Url;
  url.setModuleAction("dPcim10", "code_finder");
  url.addParam("code", code);
  url.popup(800, 500, "CIM10");
}

function putCim10(code) {
  var oForm = document.editDiagFrm;
  aCim10 = oForm.listCim10.value.split("|");
  // Si la chaine est vide, il crée un tableau à un élément vide donc :
  aCim10.removeByValue("");
  aCim10.push(code);
  aCim10.removeDuplicates();
  oForm.listCim10.value = aCim10.join("|");
  oForm.submit();
}

function delCim10(code) {
  var oForm = document.editDiagFrm;
  var aCim10 = oForm.listCim10.value.split("|");
  aCim10.removeByValue(code);
  oForm.listCim10.value = aCim10.join("|");
  oForm.submit();
}

function finTrmt() {
  var oForm = document.editTrmtFrm;
  var oEnCours = oForm._en_cours;
  var oHiddenField = oForm.fin;
  var oViewField = document.getElementById('editTrmtFrm_fin_da');
  var oTriggerField = document.getElementById('editTrmtFrm_fin_trigger');
  if (oEnCours.checked) {
    {/literal}
    oHiddenField.value = "{$today}";
    oViewField.innerHTML = "{$today|date_format:"%d/%m/%Y"}";
    oTriggerField.style.display = "inline";
    {literal}
  } else {
    oHiddenField.value = "";
    oViewField.innerHTML = "En cours";
    oTriggerField.style.display = "none";
  }
}

function pageMain() {
  incPatientHistoryMain();
  
  {/literal}
  
  {foreach from=$consult_anesth->_static_cim10 key=cat item=curr_cat}
  initEffectClass("group{$cat}"   , "trigger{$cat}");

  {/foreach}
  
  {if $consult->consultation_id}
  initEffectClass("listConsult", "triggerList");
  {/if}

  regRedirectPopupCal("{$date}", "index.php?m={$m}&tab={$tab}&date=", "changeDate");
  regFieldCalendar("editAntFrm", "date");
  regFieldCalendar("editTrmtFrm", "debut");
  regFieldCalendar("editTrmtFrm", "fin");
  
  {literal}
  
}

{/literal}
</script>

<table class="main">
  <tr>
    <td id="listConsult" style="vertical-align: top">
{include file="inc_list_consult.tpl"}
    </td>
    <td>
    
{if $consult->consultation_id}
{assign var="patient" value=$consult->_ref_patient}

<form name="editFrm" action="?m={$m}" method="post">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_consultation_aed" />
<input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
<input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />

<table class="form">
  <tr>
    <th class="category" colspan="4">
      <button type="button" style="float: left;" onclick="printFiche()">
        <img src="modules/dPcabinet/images/print.png" alt="historique" />
        Imprimer la fiche
        <img src="modules/dPcabinet/images/print.png" alt="historique" />
      </button>
      <input type="hidden" name="chrono" value="{$consult->chrono}" />
      Consultation
      (Etat : {$consult->_etat}
      {if $consult->chrono <= $smarty.const.CC_EN_COURS}
      / <input type="button" value="Terminer" onclick="submitConsultWithChrono({$smarty.const.CC_TERMINE})" />
      {/if})
    </th>
  </tr>
<!--
  <tr>
    <th class="category">
      <label for="motif" title="Motif de la consultation">Motif</label>
    </th>
    <th>
      <select name="_aide_motif" size="1" onchange="pasteText('motif')">
        <option value="0">&mdash; Choisir une aide</option>
        {html_options options=$aides.motif}
      </select>
    </th>
    <th class="category">
      <label for="traitement" title="title">Traitements</label>
    </th>
    <th>
      <select name="_aide_traitement" size="1" onchange="pasteText('traitement')">
        <option value="0">&mdash; Choisir une aide</option>
        {html_options options=$aides.traitement}
      </select>
    </th>
  </tr>
  <tr>
    <td class="text" colspan="2">
      {if $consult->motif}
      <textarea name="motif" rows="5">{$consult->motif}</textarea>
      {else}
      <textarea name="motif" rows="5">Intervention le {$consult_anesth->_ref_operation->_ref_plageop->date|date_format:"%a %d %b %Y"}
Par le Dr. {$consult_anesth->_ref_operation->_ref_chir->_view}
{foreach from=$consult_anesth->_ref_operation->_ext_codes_ccam item=curr_code}
- {$curr_code->libelleLong} ({$curr_code->code})
{/foreach}</textarea>
    {/if}
    </td>
    <td class="text" colspan="2">
      <textarea name="traitement" rows="5">{$consult->traitement}</textarea>
    </td>
  </tr>
-->
</table>

</form>

<table class="form">
  <tr>
    <th class="category" colspan="2">
      <button id="triggerList" class="triggerHide" type="button" onclick="flipEffectElement('listConsult', 'Appear', 'Fade', 'triggerList');" style="float:left">+/-</button>
      Patient
    </th>
    <th class="category">Informations</th>
    <th class="category">Correpondants</th>
    <th class="category">
      <a style="float:right;" href="javascript:view_log('CConsultation',{$consult->consultation_id})">
        <img src="images/history.gif" alt="historique" />
      </a>
      Historique
    </th>
  </tr>
  <tr>
    <td class="readonly">
      {$patient->_view}
      <br />
      Age: {$patient->_age} ans
      <br />
      <a href="index.php?m=dPcabinet&amp;tab=vw_dossier&amp;patSel={$patient->patient_id}">
        Consulter le dossier
      </a>
    </td>
    <td class="button">
      <button onclick="editPat({$patient->patient_id})">
        <img src="modules/dPcabinet/images/edit.png" />
      </button>
    </td>
    <td class="text" rowspan="2">
      {if $consult_anesth->consultation_anesth_id}
        <form name="editAnesthPatFrm" action="?m={$m}" method="post" onsubmit="return checkForm(this)">
        <input type="hidden" name="m" value="{$m}" />
        <input type="hidden" name="del" value="0" />
        <input type="hidden" name="dosql" value="do_consult_anesth_aed" />
        <input type="hidden" name="consultation_anesth_id" value="{$consult_anesth->consultation_anesth_id}" />
        <table class="form">
          <tr>
            <th><label for="poid" title="Poids du patient">Poids:</label></th>
            <td>
              <input type="text" size="4" name="poid" title="{$consult_anesth->_props.poid}" value="{$consult_anesth->poid}" />
              kg
            </td>
            <th><label for="tabac" title="Comportement tabagique">Tabac:</label></th>
            <td>
              <select name="tabac" title="{$consult_anesth->_props.tabac}">
                {html_options values=$consult_anesth->_enums.tabac output=$consult_anesth->_enums.tabac selected=$consult_anesth->tabac}
              </select>
            </td>
          </tr>
          <tr>
            <th><label for="taille" title="Taille du patient">Taille:</label></th>
            <td>
              <input type="text" size="4" name="taille" title="{$consult_anesth->_props.taille}" value="{$consult_anesth->taille}" />
              m
            </td>
            <th><label for="oenolisme" title="Comportement alcoolique">Oenolisme:</label></th>
            <td>
              <select name="oenolisme" title="{$consult_anesth->_props.oenolisme}">
                {html_options values=$consult_anesth->_enums.oenolisme output=$consult_anesth->_enums.oenolisme selected=$consult_anesth->oenolisme}
              </select>
            </td>
          </tr>
          <tr>
            <th><label for="groupe" title="Groupe sanguin">Groupe:</label></th>
            <td>
              <select name="groupe" title="{$consult_anesth->_props.groupe}">
                {html_options values=$consult_anesth->_enums.groupe output=$consult_anesth->_enums.groupe selected=$consult_anesth->groupe}
              </select>
              /
              <select name="rhesus" title="{$consult_anesth->_props.rhesus}">
                {html_options values=$consult_anesth->_enums.rhesus output=$consult_anesth->_enums.rhesus selected=$consult_anesth->rhesus}
              </select>
            </td>
            <th><label for="transfusions" title="Antécédents de transfusions">Transfusion:</label></th>
            <td>
              <select name="transfusions" title="{$consult_anesth->_props.transfusions}">
                {html_options values=$consult_anesth->_enums.transfusions output=$consult_anesth->_enums.transfusions selected=$consult_anesth->transfusions}
              </select>
            </td>
          </tr>
          <tr>
            <th><label for="tasys" title="Pression arterielle">TA:</label></th>
            <td>
              <input type="text" size="2" name="tasys" title="{$consult_anesth->_props.tasys}" value="{$consult_anesth->tasys}" />
              -
              <input type="text" size="2" name="tadias" title="{$consult_anesth->_props.tadias}" value="{$consult_anesth->tadias}" />
            </td>
            <th><label for="ASA" title="Score ASA">ASA:</label></th>
            <td>
              <select name="ASA">
                {html_options values=$consult_anesth->_enums.ASA output=$consult_anesth->_enums.ASA selected=$consult_anesth->ASA}
              </select>
            </td>
          </tr>
          <tr>
            <td class="button" colspan="4">
              <button type="submit">Valider</button>
            </td>
          </tr>
        </table>
        </form>
      {else}
        <form name="addOpFrm" action="?m={$m}" method="post">
        <input type="hidden" name="dosql" value="do_consultation_aed" />
        <input type="hidden" name="del" value="0" />
        <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
        <table width="100%">
          <tr>
            <td><strong>Veuillez séléctionner une intervention</strong></td>
          </tr>
          {foreach from=$patient->_ref_operations item=curr_op}
          <tr>
            <td class="text">
              <input type="radio" name="_operation_id" value="{$curr_op->operation_id}" />
              Intervention le {$curr_op->_ref_plageop->date|date_format:"%d/%m/%Y"}
              avec le Dr. {$curr_op->_ref_chir->_view}
              {if $curr_op->_ext_codes_ccam|@count}
              <ul>
                {foreach from=$curr_op->_ext_codes_ccam item=curr_code}
                <li><i>{$curr_code->libelleLong}</i></li>
                {/foreach}
              </ul>
              {/if}
            </td>
          </tr>
          {foreachelse}
          <tr>
            <td>Aucune intervention de prévu</td>
          </tr>
          {/foreach}
          <tr>
            <td class="button">
              <input type="submit" value="valider" />
        </table>
        </form>
      {/if}
    </td>
    <td class="text">
      {if $patient->medecin_traitant}
      Dr. {$patient->_ref_medecin_traitant->_view}
      {/if}
      {if $patient->medecin1}
      <br />
      Dr. {$patient->_ref_medecin1->_view}
      {/if}
      {if $patient->medecin2}
      <br />
      Dr. {$patient->_ref_medecin2->_view}
      {/if}
      {if $patient->medecin3}
      <br />
      Dr. {$patient->_ref_medecin3->_view}
      {/if}
    </td>
    <td class="text">
      {include file="inc_patient_history.tpl"}
    </td>
  </tr>
</table>

{include file="inc_ant_consult.tpl"}

{if $consult_anesth->consultation_anesth_id}
<table class="form">
  <tr>
    <th class="category" colspan="2">Intervention</th>
  </tr>
  <tr>
    <td class="text">
      Intervention le <strong>{$consult_anesth->_ref_operation->_ref_plageop->date|date_format:"%a %d %b %Y"}</strong>
      par le <strong>Dr. {$consult_anesth->_ref_operation->_ref_chir->_view}</strong><br />
      <ul>
        {foreach from=$consult_anesth->_ref_operation->_ext_codes_ccam item=curr_code}
        <li><em>{$curr_code->libelleLong}</em> ({$curr_code->code})</li>
        {/foreach}
      </ul>
    </td>
    <td class="text">
      <form name="editOpFrm" action="?m=dPcabinet" method="post">
      
      <input type="hidden" name="m" value="dPplanningOp" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="dosql" value="do_planning_aed" />
      <input type="hidden" name="operation_id" value="{$consult_anesth->_ref_operation->operation_id}" />
      <label for="type_anesth" title="Type d'anesthésie pour l'intervention">Type d'anesthésie :</label>
      <select name="type_anesth" onchange="this.form.submit()">
        <option value="">&mdash; Choisir un type d'anesthésie</option>
        {html_options options=$anesth selected=$consult_anesth->_ref_operation->type_anesth}
      </select>
      
      </form>
    </td>
  </tr>
</table>
{/if}

{include file="inc_fdr_consult.tpl"}
{/if}

    </td>
  </tr>
</table>

