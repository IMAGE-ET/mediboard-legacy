{literal}
<script type="text/javascript">

function editPat(patient_id) {
  var url = new Url;
  url.setModuleTab("dPpatients", "vw_edit_patients");
  url.addParam("patient_id", patient_id);
  url.redirect();
}

function showAll(patient_id) {
  var url = new Url;
  url.setModuleAction("dPcabinet", "vw_resume");
  url.addParam("dialog", 1);
  url.addParam("patient_id", patient_id);
  url.popup(800, 500, "Resume");
}

function newOperation(chir_id, pat_id) {
  var url = new Url;
  url.setModuleTab("dPplanningOp", "vw_edit_planning");
  url.addParam("chir_id", chir_id);
  url.addParam("pat_id", pat_id);
  url.addParam("operation_id", 0);
  url.redirect();
}

function newHospitalisation(chir_id, pat_id) {
  var url = new Url;
  url.setModuleTab("dPplanningOp", "vw_edit_hospi");
  url.addParam("chir_id", chir_id);
  url.addParam("pat_id", pat_id);
  url.addParam("hospitalisation_id", 0);
  url.redirect();
}

function newConsultation(chir_id, pat_id) {
  var url = new Url;
  url.setModuleTab("dPcabinet", "edit_planning");
  url.addParam("chir_id", chir_id);
  url.addParam("pat_id", pat_id);
  url.addParam("consultation_id", 0);
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

function pageMain() {
  incPatientHistoryMain();

  {/literal}
  {if $consult->consultation_id}
  {literal}
  initEffectClass("listConsult", "triggerList");
  {/literal}
  {/if}
  {literal}

  {/literal}
  regRedirectPopupCal("{$date}", "index.php?m={$m}&tab={$tab}&date=");
  {literal}
  
}

</script>
{/literal}

<table class="main">
  <tr>
    <td id="listConsult" class="effectShown" style="vertical-align: top">
      {include file="inc_list_consult.tpl"}
    </td>
    <td>

      {if $consult->consultation_id}
      {assign var="patient" value=$consult->_ref_patient}

      <table class="form">
        <tr>
          <th class="category" colspan="2">
            <button id="triggerList" class="triggerHide" type="button" onclick="flipEffectElement('listConsult', 'Appear', 'Fade', 'triggerList');" style="float:left">+/-</button>
            Patient
          </th>
          <th class="category">Correspondants</th>
          <th class="category">
            <a style="float:right;" href="javascript:view_log('CConsultation',{$consult->consultation_id})">
              <img src="images/history.gif" alt="historique" />
            </a>
            Historique
          </th>
          <th class="category">Planification</th>
        </tr>
        <tr>
          <td class="readonly">
            {$patient->_view}
            <br />
            Age: {$patient->_age} ans
            <br />
            <a href="javascript:showAll({$patient->patient_id})">
              Résumé
            </a>
            <br />
            <a href="index.php?m=dPcabinet&amp;tab=vw_dossier&amp;patSel={$patient->patient_id}">
              Consulter le dossier complet
            </a>
          </td>
          <td class="button">
            <button onclick="editPat({$patient->patient_id})">
              <img src="modules/dPcabinet/images/edit.png" alt="editer" />
            </button>
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
          <td class="button">
            <input type="button" value="intervention"    onclick="newOperation      ({$consult->_ref_plageconsult->chir_id},{$consult->patient_id})" /><br />
            <input type="button" value="hospitalisation" onclick="newHospitalisation({$consult->_ref_plageconsult->chir_id},{$consult->patient_id})" /><br />
            <input type="button" value="consultation"    onclick="newConsultation   ({$consult->_ref_plageconsult->chir_id},{$consult->patient_id})" />
          </td>
        </tr>
      </table>

      {include file="inc_main_consultform.tpl"}

      {include file="inc_fdr_consult.tpl"}

      {/if}

    </td>
  </tr>
</table>
