{literal}
<script type="text/javascript">

function editPat(patient_id) {
  var url = new Url;
  url.setModuleAction("dPpatients", "vw_edit_patients");
  url.addParam("patient_id", patient_id);
  url.redirect();
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

function changeList() {
  flipElementClass("listConsult", "show", "hidden", "listConsult");
}

function pageMain() {

  initGroups("consultations");
  initGroups("operations");
  initGroups("hospitalisations");
  {/literal}
  {if $consult->consultation_id}
  {literal}
  initElementClass("listConsult", "listConsult")
  {/literal}
  {/if}
  {literal}

  {/literal}
  regRedirectPopupCal("{$date}", "index.php?m={$m}&tab={$tab}&date=");
  {literal}
  
}

</script>
{/literal}

<table class="main" style="border-spacing:0px;">
  <tr>
    {include file="inc_list_consult.tpl"}
    <td>
      {if $consult->consultation_id}
      <table class="form">
        <tr>
          <th class="category" colspan="2">
            <button type="button" onclick="changeList();" style="float:left">+/-</button>
            Patient
          </th>
          <th class="category">Correpondants</th>
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
            {$consult->_ref_patient->_view}
            <br />
            Age: {$consult->_ref_patient->_age} ans
            <br />
            <a href="index.php?m=dPcabinet&amp;tab=vw_dossier&amp;patSel={$consult->_ref_patient->patient_id}">
              Consulter le dossier
            </a>
          </td>
          <td class="button">
            <button onclick="editPat({$consult->_ref_patient->patient_id})">
              <img src="modules/dPcabinet/images/edit.png" />
            </button>
          </td>
          <td class="text">
            {if $consult->_ref_patient->medecin_traitant}
            Dr. {$consult->_ref_patient->_ref_medecin_traitant->_view}
            {/if}
            {if $consult->_ref_patient->medecin1}
            <br />
            Dr. {$consult->_ref_patient->_ref_medecin1->_view}
            {/if}
            {if $consult->_ref_patient->medecin2}
            <br />
            Dr. {$consult->_ref_patient->_ref_medecin2->_view}
            {/if}
            {if $consult->_ref_patient->medecin3}
            <br />
            Dr. {$consult->_ref_patient->_ref_medecin3->_view}
            {/if}
          </td>
          <td class="text">
            <table class="form">
              <tr class="groupcollapse" id="operations" onclick="flipGroup('', 'operations')">
                <td colspan="2">Interventions ({$consult->_ref_patient->_ref_operations|@count})</td>
              </tr>
              {foreach from=$consult->_ref_patient->_ref_operations item=curr_op}
              <tr class="operations">
                <td>
                  <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
                    {$curr_op->_ref_plageop->date|date_format:"%d %b %Y"}
                  </a>
                </td>
                <td>Dr. {$curr_op->_ref_chir->_view}</td>
              </tr>
              {/foreach}
              <tr class="groupcollapse" id="hospitalisations" onclick="flipGroup('', 'hospitalisations')">
                <td colspan="2">Hospitalisations ({$consult->_ref_patient->_ref_hospitalisations|@count})</td>
              </tr>
              {foreach from=$consult->_ref_patient->_ref_hospitalisations item=curr_op}
              <tr class="hospitalisations">
                <td>
                  <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_op->operation_id}">
                    {$curr_op->_ref_plageop->date|date_format:"%d %b %Y"}
                 </a>
                </td>
                <td>Dr. {$curr_op->_ref_chir->_view}</td>
              </tr>
              {/foreach}
              <tr class="groupcollapse" id="consultations" onclick="flipGroup('', 'consultations')">
                <td colspan="2">Consultations ({$consult->_ref_patient->_ref_consultations|@count})</td>
              </tr>
              {foreach from=$consult->_ref_patient->_ref_consultations item=curr_consult}
              <tr class="consultations">
                <td>
                  <a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
                    {$curr_consult->_ref_plageconsult->date|date_format:"%d %b %Y"}
                  </a>
                </td>
                <td>Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view}</td>
              </tr>
              {/foreach}
            </table>
          </td>
          <td class="button">
            <input type="button" value="intervention"    onclick="newOperation      ({$consult->_ref_plageconsult->chir_id},{$consult->patient_id})" /><br />
            <input type="button" value="hospitalisation" onclick="newHospitalisation({$consult->_ref_plageconsult->chir_id},{$consult->patient_id})" /><br />
            <input type="button" value="consultation"    onclick="newConsultation   ({$consult->_ref_plageconsult->chir_id},{$consult->patient_id})" />
          </td>
        </tr>
      </table>
      <form name="editFrm" action="?m={$m}" method="post">
        <input type="hidden" name="m" value="{$m}" />
        <input type="hidden" name="del" value="0" />
        <input type="hidden" name="dosql" value="do_consultation_aed" />
        <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
        <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />
      <table class="form">
        <tr>
      	  <th class="category" colspan="4">
      	    <input type="hidden" name="chrono" value="{$consult->chrono}" />
      	    Consultation
      	    (Etat : {$consult->_etat}
      	    {if $consult->chrono <= $smarty.const.CC_EN_COURS}
            / <input type="button" value="Terminer" onclick="submitConsultWithChrono({$smarty.const.CC_TERMINE})" />
            {/if})
          </th>
        </tr>
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
      	    <label for="rques" title="Remarques concernant la consultation">Remarques</label>
      	  </th>
          <th>
            <select name="_aide_rques" size="1" onchange="pasteText('rques')">
              <option value="0">&mdash; Choisir une aide</option>
              {html_options options=$aides.rques}
            </select>
          </th>
        </tr>
        <tr>
      	  <td class="text" colspan="2"><textarea name="motif" rows="5">{$consult->motif}</textarea></td>
      	  <td class="text" colspan="2"><textarea name="rques" rows="5">{$consult->rques}</textarea></td>
        </tr>
        <tr>
          <th class="category">
      	    <label for="examen" title="Bilan de l'examen clinique">Examens</label>
      	  </th>
          <th>
            <select name="_aide_examen" size="1" onchange="pasteText('examen')">
              <option value="0">&mdash; Choisir une aide</option>
              {html_options options=$aides.examen}
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
          <td class="text" colspan="2"><textarea name="examen" rows="5">{$consult->examen}</textarea></td>
      	  <td class="text" colspan="2"><textarea name="traitement" rows="5">{$consult->traitement}</textarea></td>
        </tr>
        <tr>
          <td class="button" colspan="4"><input type="submit" value="sauver" /></td>
        </tr>
      </table>
      </form>
      {include file="inc_fdr_consult.tpl"}
      {/if}
    </td>
  </tr>
</table>