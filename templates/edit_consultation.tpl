{literal}
<script language="JavaScript" type="text/javascript">

function cancelTarif() {
  var form = document.tarifFrm;
  form.secteur1.value=0;
  form.secteur2.value=0;
  form.tarif.value="";
  form.paye.value=0;
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
    for(i=0;i<form.choix.length;++i)
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
  if(form._tiers.checked)
    form.type_tarif.value = "tiers";
  else
    form.type_tarif.value = "null";
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

function editModele(consult, modele) {
  var url = '?m=dPcabinet&a=edit_compte_rendu&dialog=1';
  url +='&consult=' + consult;
  url +='&modele=' + modele;
  popup(700, 700, url, 'Compte-rendu');
}

function editPat() {
  var url = '?m=dPpatients&tab=vw_edit_patients';
  url += '&id={/literal}{$consult->_ref_patient->patient_id}{literal}';
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
  url +='&operation_id=0';
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

function validerCompteRendu() {
  if (confirm('Veuillez confirmer la validation du compte-rendu')) {
    var form = document.editCompteRenduFrm;
    form.cr_valide.value = "1";
    form.submit();
  }
}

function supprimerCompteRendu() {
  if (confirm('Veuillez confirmer la suppression')) {
    var form = document.editCompteRenduFrm;
    form.compte_rendu.value = "";
    form.cr_valide.value = "0";
    form.submit();
  }
}

</script>
{/literal}

<table>
  <tr>
    <td style="vertical-align: top">

    <table align="center" width="100%">
      <tr>
        <th></th>
        <th><a href="?m={$m}&amp;change=1&amp;yearconsult={$pyear}"><</a></th>
        <th>{$year}</th>
        <th><a href="?m={$m}&amp;change=1&amp;yearconsult={$nyear}">></a></th>
        <th></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$ppmonth}"><<</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$pmonth}"><</a></th>
        <th>{$monthName}</th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$nmonth}">></a></th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$nnmonth}">>></a></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$ppday}"><<</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$pday}"><</a></th>
        <th>{$dayName} {$day}</th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$nday}">></a></th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$nnday}">>></a></th>
      </tr>
    </table>

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

{if $consult->consultation_id}
    <td  style="width: 100%; vertical-align: top">
      <table class="main">
        <tr>
          <td>
            <table class="form">
              <tr>
                <th class="category" colspan="2">Patient</th>
                <th class="category">Correpondants</th>
                <th class="category">Historique</th>
                <th class="category">Plannification</th>
              </tr>
              <tr>
                <td class="readonly">
                  {$consult->_ref_patient->_view}
                  <br />
                  <form><input type="text" readonly size="3" name="titre" value="{$consult->_ref_patient->_age}" /> ans</form>
                  <br />
                  <a href="index.php?m=dPcabinet&amp;tab=idx_compte_rendus&amp;patSel={$consult->_ref_patient->patient_id}">
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
                  {if $consult->_ref_patient->_ref_operations|@count}
                  Interventions :
                  <ul>
                  {foreach from=$consult->_ref_patient->_ref_operations item=curr_op}
                    <li><a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
                      {$curr_op->_ref_plageop->date|date_format:"%d %b %Y"}
                      -
                      Dr. {$curr_op->_ref_chir->_view}
                    </a></li>
                  {/foreach}
                  </ul>
                  {/if}
                  {if $consult->_ref_patient->_ref_hospitalisations|@count}
                  Hospitalisations :
                  <ul>
                  {foreach from=$consult->_ref_patient->_ref_hospitalisations item=curr_op}
                    <li><a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_op->operation_id}">
                      {$curr_op->date_adm|date_format:"%d %b %Y"}
                      -
                      Dr. {$curr_op->_ref_chir->_view}
                    </a></li>
                  {/foreach}
                  </ul>
                  {/if}
                  {if $consult->_ref_patient->_ref_consultations|@count}
                  Consultations :
                  <ul>
                  {foreach from=$consult->_ref_patient->_ref_consultations item=curr_consult}
                    <li><a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
                      {$curr_consult->_ref_plageconsult->date|date_format:"%d %b %Y"}
                      -
                      Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view}
                    </a></li>
                  {/foreach}
                  </ul>
                  {/if}
                </td>
                <td class="button">
                  <input type="button" value="intervention" onclick="newOperation()" />
                  <br />
                  <input type="button" value="hospitalisation" onclick="newHospitalisation()" />
                  <br />
                  <input type="button" value="consultation" onclick="newConsultation()" />
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <form name="editFrm" action="?m={$m}" method="POST">

            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_consultation_aed" />
            <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
            <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />

            <table class="form">
              <tr>
                <input type="hidden" name="chrono" value="{$consult->chrono}" />
              	<th class="category" colspan="4">
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
                <td class="button" colspan="4"><input type="submit" value="sauver"></td>
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
                <th class="category">
                {if $consult->compte_rendu}
                  Compte-Rendu
                {else}
                  Modèles dispo.
                {/if}
                </th>
                <th colspan="2" class="category">Règlement</th>
              </tr>
              <tr>
                <td><ul>
                {foreach from=$consult->_ref_files item=curr_file}
                <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
                <li>
                  <a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a>
                  ({$curr_file->_file_size})
                  <input type="hidden" name="dosql" value="do_file_aed" />
	              <input type="hidden" name="del" value="1" />
	              <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	              <button type="button" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
	                <img src="modules/dPcabinet/images/cross.png" />
	              </button>
	            </li>
                {/foreach}
                </ul>
	            </form>
                <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
                  <input type="hidden" name="dosql" value="do_file_aed" />
                  <input type="hidden" name="del" value="0" />
	              <input type="hidden" name="file_consultation" value="{$consult->consultation_id}" />
                  <input type="file" name="formfile" size="0" /><br />
                  <input type="submit" value="ajouter">
                </form>
                </td>
                {if $consult->compte_rendu}
                <td class="button">
                  <form name="editCompteRenduFrm" action="?m={$m}" method="POST">

                  <input type="hidden" name="m" value="{$m}" />
                  <input type="hidden" name="del" value="0" />
                  <input type="hidden" name="dosql" value="do_consultation_aed" />
                  <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
                  <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />
                  <input type="hidden" name="compte_rendu" value="{$consult->compte_rendu|escape:html}" />
                  <input type="hidden" name="cr_valide" value="{$consult->cr_valide}" />

                  </form>
                  
                  <button onclick="editModele({$consult->consultation_id}, 0)"><img src="modules/dPcabinet/images/edit.png" /> Modifier</button>
                  {if !$consult->cr_valide}
                  <button onclick="validerCompteRendu()"><img src="modules/dPcabinet/images/check.png" /> Valider</button>
                  {/if}
                  <button onclick="supprimerCompteRendu()"><img src="modules/dPcabinet/images/trash.png" /> Supprimer</button>
                </td>
                {else}
                <td><ul>
                  {foreach from=$listModele item=curr_modele}
                  <li>
                  <a href="#" onclick="editModele({$consult->consultation_id}, {$curr_modele->compte_rendu_id})">
                    {$curr_modele->nom}
                  </a>
                  <a href="?m=dPcompteRendu&amp;tab=addedit_modeles&amp;compte_rendu_id={$curr_modele->compte_rendu_id}">
                    <img src="modules/dPcabinet/images/edit.png" />
                  </a>
                  </li>
                  {/foreach}
                </ul></td>
                {/if}
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
                      <th>Choix du tarif :<input type="hidden" name="paye" value="0" /></th>
                      <td><select name="choix" onchange="modifTarif()">
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
                      </select></td>
                    </tr>
                    {/if}
                    {if !$consult->paye}
                    <tr><th>Somme à régler :</th>
                      <td class="readonly">
                        <input type="text" readonly size="4" name="_somme" value="{$consult->secteur1+$consult->secteur2}" /> €
                        <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
                        <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
                        <input type="hidden" name="tarif" value="{$consult->tarif}" />
                      </td>
                    </tr>
                    {else}
                    <tr>
                      <td colspan="2" class="button">
                        <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
                        <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
                        <input type="hidden" name="tarif" value="{$consult->tarif}" />
                        <input type="hidden" name="paye" value="{$consult->paye}" />
                        <strong>{$consult->secteur1+$consult->secteur2} € ont été réglés : {$consult->type_tarif}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" class="button">
                        <input type="button" value="Annuler" onclick="cancelTarif()" />
                      </td>
                    {/if}
                    {if $consult->tarif && !$consult->paye}
                    <tr><th>Moyen de paiement :<input type="hidden" name="paye" value="1" /></th>
                      <td><select name="type_tarif">
                        <option value="cheque" {if $consult->type_tarif == "cheque"}selected="selected"{/if}>Chèques</option>
                        <option value="CB" {if $consult->type_tarif == "CB"}selected="selected"{/if}>CB</option>
                        <option value="especes" {if $consult->type_tarif == "especes"}selected="selected"{/if}>Espèces</option>
                        <option value="tiers" {if $consult->type_tarif == "tiers"}selected="selected"{/if}>Tiers-payant</option>
                        <option value="autre" {if $consult->type_tarif == "autre"}selected="selected"{/if}>Autre</option>
                      </select></td>
                    </tr>
                    <tr><td colspan="2" class="button">
                      <input type="submit" value="Reglement effectué" />
                      <input type="button" value="Annuler" onclick="cancelTarif()"/>
                    </td></tr>
                    {elseif !$consult->paye}
                    <tr><th>Tiers-payant ?</th><td><input type="checkbox" name="_tiers" onchange="putTiers()" />
                      <input type="hidden" name="type_tarif" value="null" /></td></tr>
                    <tr><td colspan="2" class="button">
                      <input type="submit" value="Valider ce tarif" />
                      <input type="button" value="Annuler" onclick="cancelTarif()"/>
                    </td></tr>
                    {/if}
                    </tr>
                  </table>
                  </form>
                </td>
              </tr>
            </table>    
          {/if}
        </tr>
      </table>
      
    </td>
  </tr>
</table>