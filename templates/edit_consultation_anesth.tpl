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
  var url = '?m=dPcim10&a=code_finder&dialog=1';
  url += '&code=' + code;
  popup(800, 500, url, 'CIM10');
}

function putCim10(code) {
  var form = document.editAnesthFrm;
  if(form.listCim10.value == '')
    form.listCim10.value = code;
  else
    form.listCim10.value += "|" + code;
  form.submit();
}

function delCim10(code) {
  var form = document.editAnesthFrm;
  arrayCim10 = form.listCim10.value.split("|");
  arrayCim10.removeByValue(code);
  form.listCim10.value = arrayCim10.join("|");
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
  
  {foreach from=$consult->_ref_consult_anesth->_static_cim10 key=cat item=curr_cat}
  initGroups("{$cat}");
  {/foreach}
  
  {if $consult->consultation_id}
  initElementClass("listConsult", "listConsult")
  {/if}

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
          <th class="category">Informations</th>
          <th class="category">Correpondants</th>
          <th class="category">
            <a style="float:right;" href="javascript:view_log('CConsultation', {$consult->consultation_id})">
              <img src="images/history.gif" alt="historique" />
            </a>
            Historique
          </th>
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
          <td class="text" rowspan="2">
            <form name="editAnesthPatFrm" action="?m={$m}" method="POST" onsubmit="return checkForm(this)">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_consult_anesth_aed" />
            <input type="hidden" name="consultation_anesth_id" value="{$consult->_ref_consult_anesth->consultation_anesth_id}" />
            <table class="form">
              <tr>
                <th><label for="poid" title="Poid du patient">Poid:</label></th>
                <td>
                  <input type="text" size="4" name="poid" title="{$consult->_ref_consult_anesth->_props.poid}" value="{$consult->_ref_consult_anesth->poid}" />
                  kg
                </td>
                <th><label for="tabac" title="Comportement tabagique">Tabac:</label></th>
                <td>
                  <select name="tabac" title="{$consult->_ref_consult_anesth->_props.tabac}">
                    <option value="-" {if $consult->_ref_consult_anesth->tabac == "-"}selected="selected"{/if}>
                      -
                    </option>
                    <option value="+" {if $consult->_ref_consult_anesth->tabac == "+"}selected="selected"{/if}>
                      +
                    </option>
                    <option value="++" {if $consult->_ref_consult_anesth->tabac == "++"}selected="selected"{/if}>
                      ++
                    </option>
                  </select>
                </td>
              </tr>
              <tr>
                <th><label for="taille" title="Taille du patient">Taille:</label></th>
                <td>
                  <input type="text" size="4" name="taille" title="{$consult->_ref_consult_anesth->_props.taille}" value="{$consult->_ref_consult_anesth->taille}" />
                  m
                </td>
                <th><label for="oenolisme" title="Comportement alcoolique">Oenolisme:</label></th>
                <td>
                  <select name="oenolisme" title="{$consult->_ref_consult_anesth->_props.oenolisme}">
                    <option value="-" {if $consult->_ref_consult_anesth->oenolisme == "-"}selected="selected"{/if}>
                      -
                    </option>
                    <option value="+" {if $consult->_ref_consult_anesth->oenolisme == "+"}selected="selected"{/if}>
                      +
                    </option>
                    <option value="++" {if $consult->_ref_consult_anesth->oenolisme == "++"}selected="selected"{/if}>
                      ++
                    </option>
                  </select>
                </td>
              </tr>
              <tr>
                <th><label for="groupe" title="Groupe sanguin">Groupe:</label></th>
                <td>
                  <select name="groupe" title="{$consult->_ref_consult_anesth->_props.groupe}">
                    <option value="A" {if $consult->_ref_consult_anesth->groupe == "A"}selected="selected"{/if}>
                      A
                    </option>
                    <option value="B" {if $consult->_ref_consult_anesth->groupe == "B"}selected="selected"{/if}>
                      B
                    </option>
                    <option value="AB" {if $consult->_ref_consult_anesth->groupe == "AB"}selected="selected"{/if}>
                      AB
                    </option>
                    <option value="O" {if $consult->_ref_consult_anesth->groupe == "O"}selected="selected"{/if}>
                      O
                    </option>
                  </select>
                  /
                  <select name="rhesus" title="{$consult->_ref_consult_anesth->_props.rhesus}">
                    <option value="-" {if $consult->_ref_consult_anesth->rhesus == "-"}selected="selected"{/if}>
                      -
                    </option>
                    <option value="+" {if $consult->_ref_consult_anesth->rhesus == "+"}selected="selected"{/if}>
                      +
                    </option>
                  </select>
                </td>
                <th><label for="transfusions" title="Antécédents de transfusions">Transfusion:</label></th>
                <td>
                  <select name="transfusions" title="{$consult->_ref_consult_anesth->_props.transfusions}">
                    <option value="-" {if $consult->_ref_consult_anesth->transfusions == "-"}selected="selected"{/if}>
                      -
                    </option>
                    <option value="+" {if $consult->_ref_consult_anesth->transfusions == "+"}selected="selected"{/if}>
                      +
                    </option>
                  </select>
                </td>
              </tr>
              <tr>
                <th><label for="tasys" title="Pression arterielle">TA:</label></th>
                <td>
                  <input type="text" size="2" name="tasys" title="{$consult->_ref_consult_anesth->_props.tasys}" value="{$consult->_ref_consult_anesth->tasys}" />
                  -
                  <input type="text" size="2" name="tadias" title="{$consult->_ref_consult_anesth->_props.tadias}" value="{$consult->_ref_consult_anesth->tadias}" />
                </td>
                <td class="button" colspan="2">
                  <button type="submit">Valider</button>
                </td>
              </tr>
            </table>
            </form>
          </td>
          <td class="text" rowspan="2">
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
          <td class="text" rowspan="2">
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
        </tr>
        <tr>
          <td colspan="2" class="text">
            Intervention le <strong>{$consult->_ref_consult_anesth->_ref_operation->_ref_plageop->date|date_format:"%a %d %b %Y"}</strong><br />
            Par le <strong>Dr. {$consult->_ref_consult_anesth->_ref_operation->_ref_chir->_view}</strong><br />
            <i>{$consult->_ref_consult_anesth->_ref_operation->_ext_code_ccam->libelleLong}</i>
            {if $consult->_ref_consult_anesth->_ref_operation->CCAM_code2}
            <br />+ <i>{$consult->_ref_consult_anesth->_ref_operation->_ext_code_ccam2->libelleLong}</i>
            {/if}
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <form name="editAnesthFrm" action="?m={$m}" method="POST">
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="dosql" value="do_consult_anesth_aed" />
      <input type="hidden" name="consultation_anesth_id" value="{$consult->_ref_consult_anesth->consultation_anesth_id}" />
      <table class="form">
        <tr><th class="category" colspan="2">Examen Préanesthésique</th></tr>
        <tr>
          <td class="text">
            <strong>Liste des diagnostics:</strong>
            <table>
            {foreach from=$consult->_ref_consult_anesth->_static_cim10 key=cat item=curr_cat}
              <tr class="groupcollapse" id="{$cat}" onclick="flipGroup('', '{$cat}')">
                <td>{$cat}</td>
              </tr>
              {foreach from=$curr_cat item=curr_code}
              <tr class="{$cat}">
                <td>
                  <button type="button" onclick="selectCim10('{$curr_code->code}')">
                    <img src="modules/dPcabinet/images/tick.png" />
                  </button>
                  {$curr_code->code}: {$curr_code->libelle}
                 </td>
               </tr>
               {/foreach}
            {/foreach}
            </table>
          </td>
          <td class="text">
            <strong>Diagnostics du patient:</strong>
            <input type="hidden" name="listCim10" value="{$consult->_ref_consult_anesth->listCim10}"
            <ul>
              {foreach from=$consult->_ref_consult_anesth->_codes_cim10 item=curr_code}
              <li>
                <button type="button" onclick="delCim10('{$curr_code->code}')">
                  <img src="modules/dPcabinet/images/cross.png" />
                </button>
                {$curr_code->code}: {$curr_code->libelle}
              </li>
              {foreachelse}
              <li>Pas de diagnostic</li>
              {/foreach}
            </ul>
          </td>
        </tr>
        <tr>
          <td class="button" colspan="2"><input type="submit" value="sauver" /></td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <tr>
    <td>
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