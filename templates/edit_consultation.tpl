{literal}
<script language="JavaScript" type="text/javascript">

function cancelTarif() {
  var form = document.tarifFrm;
  form.secteur1.value = 0;
  form.secteur2.value =0;
  form.tarif.value = "";
  form.paye.value = 0;
  form.date_paiement.value = null;
  form.submit();
}

function modifTarif() {
  var form = document.tarifFrm;
  var secteurs = form.choix.value;
  if(secteurs != '') {
    var pos = secteurs.indexOf("/");
    var size = secteurs.length;
    var secteur1 = eval(secteurs.substring(0, pos));
    var secteur2 = eval(secteurs.substring(pos+1, size));
    form.secteur1.value = secteur1;
    form.secteur2.value = secteur2;
    form._somme.value = secteur1 + secteur2;
    for (i = 0;i < form.choix.length;++i)
    if(form.choix.options[i].selected == true)
     form.tarif.value = form.choix.options[i].text;
   } else {
     form.secteur1.value = 0;
     form.secteur2.value = 0;
     form._somme.value = '';
     form.tarif.value = '';
   }  
}

function putTiers() {
  var form = document.tarifFrm;
  form.type_tarif.value = form._tiers.checked ? "tiers" : "";
}

function checkTarif() {
  var form = document.tarifFrm;
  if(form.tarif.value == '') {
    alert('Vous devez choisir un tarif');
    form.tarif.focus();
    return false;
  }
  return true
}

function editPat() {
  var url = '?m=dPpatients&tab=vw_edit_patients';
  url += '&patient_id={/literal}{$consult->_ref_patient->patient_id}{literal}';
  window.location.href = url;
}

function newOperation() {
  var url = '?m=dPplanningOp&tab=vw_edit_planning';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}';
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}';
  url +='&operation_id=0';
  window.location.href = url;
}

function newHospitalisation() {
  var url = '?m=dPplanningOp&tab=vw_edit_hospi';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}';
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}';
  url +='&hospitalisation_id=0';
  window.location.href = url;
}

function newConsultation() {
  var url = '?m=dPcabinet&tab=edit_planning';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}';
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}';
  url +='&consultation_id=0';
  window.location.href = url;
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

function editDocument(compte_rendu_id) {
  var url = '?m=dPcompteRendu&a=edit_compte_rendu&dialog=1';
  url += '&compte_rendu_id=' + compte_rendu_id;
  popup(700, 700, url, 'Document');
}

function createDocument(modele_id, consultation_id) {
  var url = '?m=dPcompteRendu&a=edit_compte_rendu&dialog=1';
  url += '&modele_id=' + modele_id;
  url += '&object_id=' + consultation_id;
  popup(700, 700, url, 'Document');
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
    <td rowspan="3" id="listConsult" class="show">
      <form name="changeView">
        <table class="form">
          <tr>
            <td colspan="6" style="text-align: center; width: 100%; font-weight: bold;">
              {$date|date_format:"%A %d %B %Y"}
              <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
            </td>
          </tr>
          <tr>
            <th>Type de vue:</th>
            <td colspan="5">
              <form action="index.php" name="type" method="get">
                <input type="hidden" name="m" value="{$m}" />
                <input type="hidden" name="tab" value="{$tab}" />
                <select name="vue2" onchange="this.form.submit()">
                  <option value="0"{if !$vue}selected="selected"{/if}>Tout afficher</option>
                  <option value="1"{if $vue}selected="selected"{/if}>Cacher les Terminées</option>
                </select>
              </form>
            </td>
          </tr>
        </table>
      </form>
      <table class="tbl">
      {if $listPlage}
      {foreach from=$listPlage item=curr_plage}
        <tr>
          <th colspan="4" style="font-weight: bold;">Consultations de {$curr_plage->_hour_deb}h à {$curr_plage->_hour_fin}h</th>
        </tr>
        <tr>
          <th>Heure</th>
          <th>Patient</th>
          <th>RDV</th>
          <th>Etat</th>
        </tr>
        {foreach from=$curr_plage->_ref_consultations item=curr_consult}
          {if $curr_consult->premiere} 
            {assign var="style" value="style='background: #faa;'"}
          {else} 
            {assign var="style" value=""}
          {/if}
        <tr {if $curr_consult->consultation_id == $consult->consultation_id} style="font-weight: bold;" {/if}>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">{$curr_consult->heure|truncate:5:"":true}</a>
          </td>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">{$curr_consult->_ref_patient->_view}</a>
          </td>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_planning&amp;consultation_id={$curr_consult->consultation_id}" title="Modifier le RDV">
              <img src="modules/dPcabinet/images/planning.png" />
            </a>
          </td>
          <td {$style}>{$curr_consult->_etat}</td>
        </tr>
        {/foreach}
      {/foreach}
      {else}
        <tr>
          <th colspan="2" style="font-weight: bold;">Pas de consultations</th>
        </tr>
      {/if}
      </table>
    </td>
    <td width="100%" height="1px">
    {if $consult->consultation_id}
      <table class="form">
        <tr>
          <th class="category" colspan="2">
            <button type="button" onclick="changeList();" style="float:left">+/-</button>
            Patient
          </th>
          <th class="category">Correpondants</th>
          <th class="category">Historique</th>
          <th class="category">Planification</th>
        </tr>
        <tr>
          <td class="readonly">
            {$consult->_ref_patient->_view}
            <br />
            <form><input type="text" readonly size="3" name="titre" value="{$consult->_ref_patient->_age}" /> ans</form>
            <br />
            <a href="index.php?m=dPcabinet&amp;tab=vw_dossier&amp;patSel={$consult->_ref_patient->patient_id}">
              Consulter le dossier
            </a>
          </td>
          <td class="button">
            <button onclick="editPat()">
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
            <input type="button" value="intervention" onclick="newOperation()" /><br />
            <input type="button" value="hospitalisation" onclick="newHospitalisation()" /><br />
            <input type="button" value="consultation" onclick="newConsultation()" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td height="1px">
      <form name="editFrm" action="?m={$m}" method="POST">
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
      	    <label for="editFrm_motif" title="Motif de la consultation">Motif</label>
      	  </th>
          <th>
            <select name="_aide_motif" size="1" onchange="pasteText('motif')">
              <option value="0">&mdash; Choisir une aide</option>
              {html_options options=$aides.motif}
            </select>
          </th>
      	  <th class="category">
      	    <label for="editFrm_rques" title="Remarques concernant la consultation">Remarques</label>
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
      	    <label for="editFrm_examen" title="Bilan de l'examen clinique">Examens</label>
      	  </th>
          <th>
            <select name="_aide_examen" size="1" onchange="pasteText('examen')">
              <option value="0">&mdash; Choisir une aide</option>
              {html_options options=$aides.examen}
            </select>
          </th>
          <th class="category">
      	    <label for="editFrm_traitement" title="title">Traitements</label>
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
    </td>
  </tr>
  <tr>
    <td height="0px">
      <table class="form">
        <tr>
          <th class="category">Fichiers liés</th>
          <th class="category">Documents</th>
          <th colspan="2" class="category">Règlement</th>
        </tr>
        <tr>
          <td>
            <form>
              Examens complémentaires :
              <select onchange="javascript:popup(700, 700, 'index.php?m={$m}&a=exam_audio&dialog=1', 'audiogramme')">
                <option value="0">&mdash Choix</option>
                <option value="exam_audio.php">Audiogramme</option>
              </select>
            </form>
            <ul>
              {foreach from=$consult->_ref_files item=curr_file}
              <li>
                <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
                  <a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a>
                  ({$curr_file->_file_size})
                  <input type="hidden" name="dosql" value="do_file_aed" />
                  <input type="hidden" name="del" value="1" />
                  <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
                  <button type="button" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
                    <img src="modules/dPcabinet/images/cross.png" />
                  </button>
                </form>
              </li>
              {/foreach}
            </ul>
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
              <input type="hidden" name="dosql" value="do_file_aed" />
              <input type="hidden" name="del" value="0" />
              <input type="hidden" name="file_consultation" value="{$consult->consultation_id}" />
              <input type="file" name="formfile" size="0" /><br />
              <input type="submit" value="ajouter" />
            </form>
          </td>
          <td>
          <table class="form">
            {foreach from=$consult->_ref_documents item=document}
            <tr>
              <th>{$document->nom}</th>
              <td class="button">
                <form name="editDocumentFrm{$document->compte_rendu_id}" action="?m={$m}" method="POST">
                <input type="hidden" name="m" value="dPcompteRendu" />
                <input type="hidden" name="del" value="0" />
                <input type="hidden" name="dosql" value="do_modele_aed" />
                <input type="hidden" name="object_id" value="{$consult->consultation_id}" />
                <input type="hidden" name="compte_rendu_id" value="{$document->compte_rendu_id}" />
                <input type="hidden" name="valide" value="{$document->valide}" />
                <button type="button" onclick="editDocument({$document->compte_rendu_id})">
                  <img src="modules/dPcabinet/images/edit.png" /> 
                </button>
                {if !$document->valide}
                <button type="button" onclick="this.form.valide.value = 1; this.form.submit()">
                  <img src="modules/dPcabinet/images/check.png" /> 
                </button>
                {/if}
                <button type="button" onclick="this.form.del.value = 1; this.form.submit()">
                  <img src="modules/dPcabinet/images/trash.png" /> 
                </button>
                </form>
              </td>
            </tr>
            {/foreach}
          <table>
          <form name="newDocumentFrm" action="?m={$m}" method="POST">
          <table class="form">
            <tr>
              <td>
                <select name="_choix_modele" onchange="if (this.value) createDocument(this.value, {$consult->consultation_id})">
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
              </td>
            </tr>
          </table>
          </form>
          </td>
          <td>
            <form name="tarifFrm" action="?m={$m}" method="POST" onsubmit="return checkTarif()">
              <input type="hidden" name="m" value="{$m}" />
              <input type="hidden" name="del" value="0" />
              <input type="hidden" name="dosql" value="do_consultation_aed" />
              <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
              <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />
            <table width="100%">
              <tr>
                {if !$consult->tarif}
                <th>
                  Choix du tarif :
                  <input type="hidden" name="paye" value="0" />
                  <input type="hidden" name="date_paiement" value="" />
                </th>
                <td>
                  <select name="choix" onchange="modifTarif()">
                    <option value="" selected="selected">&mdash; Choix du tarif &mdash;</option>
                    <optgroup label="Tarifs praticien">
                    {foreach from=$tarifsChir item=curr_tarif}
                      <option value="{$curr_tarif->secteur1}/{$curr_tarif->secteur2}">{$curr_tarif->description}</option>
                    {/foreach}
                    </optgroup>
                    <optgroup label="Tarifs cabinet">
                    {foreach from=$tarifsCab item=curr_tarif}
                      <option value="{$curr_tarif->secteur1}/{$curr_tarif->secteur2}">{$curr_tarif->description}</option>
                    {/foreach}
                    </optgroup>
                  </select>
                </td>
              </tr>
              {/if}
              {if !$consult->paye}
              <tr>
                <th>Somme à régler :</th>
                <td>
                  <input type="text" size="4" name="_somme" value="{$consult->secteur1+$consult->secteur2}" /> €
                  <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
                  <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
                  <input type="hidden" name="tarif" value="{if $consult->tarif != null}{$consult->tarif}{/if}" />
                </td>
              </tr>
              {else}
              <tr>
                <td colspan="2" class="button">
                  <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
                  <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
                  <input type="hidden" name="tarif" value="{$consult->tarif}" />
                  <input type="hidden" name="paye" value="{$consult->paye}" />
                  <input type="hidden" name="date_paiement" value="{$consult->date_paiement}" />
                  <strong>{$consult->secteur1+$consult->secteur2} € ont été réglés : {$consult->type_tarif}</strong>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="button">
                  <input type="button" value="Annuler" onclick="cancelTarif()" />
                </td>
              </tr>
              {/if}
              {if $consult->tarif && !$consult->paye}
              <tr>
                <th>
                  Moyen de paiement :
                  <input type="hidden" name="paye" value="1" />
                  <input type="hidden" name="date_paiement" value="{$today}" />
                </th>
                <td>
                  <select name="type_tarif">
                    <option value="cheque"  {if $consult->type_tarif == "cheque" }selected="selected"{/if}>Chèques     </option>
                    <option value="CB"      {if $consult->type_tarif == "CB"     }selected="selected"{/if}>CB          </option>
                    <option value="especes" {if $consult->type_tarif == "especes"}selected="selected"{/if}>Espèces     </option>
                    <option value="tiers"   {if $consult->type_tarif == "tiers"  }selected="selected"{/if}>Tiers-payant</option>
                    <option value="autre"   {if $consult->type_tarif == "autre"  }selected="selected"{/if}>Autre       </option>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="button">
                  <input type="submit" value="Reglement effectué" />
                  <input type="button" value="Annuler" onclick="cancelTarif()"/>
                </td>
              </tr>
              {elseif !$consult->paye}
              <tr>
                <th>Tiers-payant ?</th>
                <td>
                  <input type="checkbox" name="_tiers" onchange="putTiers()" />
                  <input type="hidden" name="type_tarif" value="" />
                </td>
              </tr>
              <tr>
                <td colspan="2" class="button">
                  <input type="submit" value="Valider ce tarif" />
                  <input type="button" value="Annuler" onclick="cancelTarif()"/>
                </td>
              </tr>
              {/if}
            </table>
            </form>
          </td>
          {/if}
        </td>
      </tr>
    </table>
  </tr>
</table>