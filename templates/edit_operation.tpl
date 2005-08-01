{literal}
<script language="JavaScript" type="text/javascript">

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

function newOperation() {
  var url = '?m=dPplanningOp&tab=vw_edit_planning';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}'
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}'
  url +='&operation_id=0'
  window.location.href = url;
}

function newConsultation() {
  var url = '?m=dPanesth&tab=edit_planning';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}'
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}'
  url +='&consultation_id=0'
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

</script>
{/literal}

<table>
  <tr>
    <td style="vertical-align: top">

    <table align="center" width="100%">
      <tr>
        <th></th>
        <th><a href="?m={$m}&amp;change=1&amp;yearconsult={$pyear}">&lt;&lt;</a></th>
        <th>{$year}</th>
        <th><a href="?m={$m}&amp;change=1&amp;yearconsult={$nyear}">&gt;&gt;</a></th>
        <th></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$ppmonth}">&lt;&lt;&lt;&lt;</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$pmonth}">&lt;&lt;</a></th>
        <th>{$monthName}</th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$nmonth}">&gt;&gt;</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$nnmonth}">&gt;&gt;&gt;&gt;</a></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$ppday}">&lt;&lt;&lt;&lt;<</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$pday}">&lt;&lt;</a></th>
        <th>{$dayName} {$day}</th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$nday}">&gt;&gt;</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$nnday}">&gt;&gt;&gt;&gt;</a></th>
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
        {foreach from=$curr_plage->_ref_consultations_anesth item=curr_consult}
          {if $curr_consult->premiere} 
            {assign var="style" value="style='background: #faa;'"}
          {else} 
            {assign var="style" value=""}
          {/if}
        
        <tr {if $curr_consult->consultation_anesth_id == $consult->consultation_anesth_id} style="font-weight: bold;" {/if}>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_anesth_id}">{$curr_consult->heure|truncate:5:"":true}</a>
          </td>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_anesth_id}">{$curr_consult->_ref_patient->_view}</a>
          </td>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_planning&amp;consultation_anesth_id={$curr_consult->consultation_anesth_id}" title="Modifier le RDV">
              <img src="modules/{$m}/images/planning.png" />
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
<!--
{if $consult->consultation_id}
    <td  style="width: 100%; vertical-align: top">
      <table class="main">
        <tr>
          <td class="greedyPane">
            <form name="editFrm" action="?m={$m}" method="POST">

            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_consultation_aed" />
            <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
            <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />

            <table class="form">
              <tr>
              	<th class="category" colspan="3">Consultation</th>
              </tr>
              
              <tr>
                <input type="hidden" name="chrono" value="{$consult->chrono}" />
                <td style="text-align: center; vertical-align: middle">Etat : {$consult->_etat}</td>
                <td class="button">
                  {if $consult->chrono <= $smarty.const.CC_EN_COURS}
                  <input type="button" value="Terminer" onclick="submitConsultWithChrono({$smarty.const.CC_TERMINE})" />
                  {/if}
                </td>
              </tr>
              <tr>
              	<th class="text">
              	  <label for="editFrm_motif" title="Motif de la consultation">Motif</label>
              	</th>
                <th>
                  <select name="_aide_motif" size="1" onchange="pasteText('motif')">
                    <option value="0">&mdash; Choisir une aide</option>
                    {html_options options=$aides.motif}
                  </select>
                </th>
              </tr>
              
              <tr>
              	<td class="text" colspan="2"><textarea name="motif">{$consult->motif}</textarea></td>
              </tr>
              
              <tr>
              	<th class="text">
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
              	<td class="text" colspan="2"><textarea name="rques">{$consult->rques}</textarea></td>
              </tr>
              
              <tr>
              	<th class="text">
              	  <label for="editFrm_examen" title="Bilan de l'examen clinique">Examens</label>
              	</th>
                <th>
                  <select name="_aide_examen" size="1" onchange="pasteText('examen')">
                    <option value="0">&mdash; Choisir une aide</option>
                    {html_options options=$aides.examen}
                  </select>
                </th>
              </tr>
              
              <tr>
              	<td class="text" colspan="2"><textarea name="examen">{$consult->examen}</textarea></td>
              </tr>
              
              <tr>
              	<th class="text">
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
              	<td class="text" colspan="2"><textarea name="traitement">{$consult->traitement}</textarea></td>
              </tr>
              
              <tr>
                <td class="button" colspan="2"><input type="submit" value="modifier"></td>
              </tr>
            </table>

            </form>
            
          </td>
          
          <td>
            <table class="form">
              <tr><th colspan="2" class="category"><a href="?m=dPpatients&amp;tab=0&amp;id={$consult->_ref_patient->patient_id}">Patient</a></th></tr>
              <tr><th>Nom :</th><td>{$consult->_ref_patient->nom}</td></tr>
              <tr><th>Prénom :</th><td>{$consult->_ref_patient->prenom}</th></tr>
              <tr><th>Age :</th><td>{$consult->_ref_patient->_age} ans</td><tr>
            </table>

            <table class="form">
              <tr>
              	<th colspan="2" class="category">Interventions</th>
              </tr>
              
              {foreach from=$consult->_ref_patient->_ref_operations item=curr_op}
              <tr>
                <td><a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
                  {$curr_op->_ref_plageop->date|date_format:"%a %d %b %Y"}</a></td>
                <td><a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
                  Dr. {$curr_op->_ref_chir->_view}</a></td>
              </tr>
              {/foreach}
              
              <tr>
                <td class="button" colspan="2">
                  <input type="button" value="Planifier une intervention" onclick="newOperation()" />
                </td>
              </tr>
			</table>
			
            <table class="form">
              {if $consult->_ref_patient->_ref_consultations}
              <tr><th class="category" colspan="2">Consultations</th></tr>
              {foreach from=$consult->_ref_patient->_ref_consultations item=curr_consult}
              <tr>
                <td><a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
                  {$curr_consult->_ref_plageconsult->date|date_format:"%a %d %b %Y"}</a></td>
                <td><a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
                  Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view}</a></td>
              </tr>
              {/foreach}
              {/if}

              <tr>
                <td class="button" colspan="2">
                  <input type="button" value="Planifier une consultation" onclick="newConsultation()" />
                </td>
              </tr>
            </table>
            
          </td>
        
          {if !$consult->annule}
          <td>

            <table class="form">
              <tr><th colspan="3" class="category">Fichiers liés</th></tr>
              {foreach from=$consult->_ref_files item=curr_file}
              <tr>
                <td><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></td>
                <td>{$curr_file->_file_size}</td>
                <td class="button">
                  <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">

                  <input type="hidden" name="dosql" value="do_file_aed" />
	              <input type="hidden" name="del" value="1" />
	              <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	              <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>

	              </form>
	            </td>
	          </tr>
              {/foreach}
              <tr>
                <td colspan="2">
                  <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
 
                  <input type="hidden" name="dosql" value="do_file_aed" />
	              <input type="hidden" name="del" value="0" />
	              <input type="hidden" name="file_consultation" value="{$consult->consultation_id}" />
                  <input type="file" name="formfile" />
                </td>

                <td class="button">
                  <input type="submit" value="ajouter">
                  
                  </form>
                </td>
              </tr>
            </table>
     
            <table class="form">
            {if $consult->compte_rendu}
              <tr><th class="category">Compte-Rendu</th></tr>
              <tr>
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
              </tr>
            {else}
              <tr>
                <th colspan="2" class="category">Modèles dispo.</th>
              </tr>
            {foreach from=$listModele item=curr_modele}
              <tr>
                <td>
                  <a href="#" onclick="editModele({$consult->consultation_id}, {$curr_modele->compte_rendu_id})">
                    {$curr_modele->nom}
                  </a>
                </td>
                <td>
                  <a href="?m=dPcompteRendu&amp;tab=addedit_modeles&amp;compte_rendu_id={$curr_modele->compte_rendu_id}">
                    <img src="modules/dPcabinet/images/edit.png" />
                  </a>
                </td>
              </tr>
            {/foreach}
            {/if}
            </table>
            
            
            <form name="tarifFrm" action="?m={$m}" method="POST" onsubmit="return checkTarif()">

            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_consultation_aed" />
            <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
            <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />

            <table class="form">
              <tr><th colspan="2" class="category">Règlement</th></tr>
              {if !$consult->tarif}
              <tr><th>Choix du tarif :<input type="hidden" name="paye" value="0" /></th>
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
                <td class="readonly"><input type="text" readonly size="4" name="_somme" value="{$consult->secteur1+$consult->secteur2}" /> €
                  <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
                  <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
                  <input type="hidden" name="tarif" value="{$consult->tarif}" /></td>
              </tr>
              {else}
              <tr><td colspan="2">{$consult->secteur1+$consult->secteur2} € ont été réglés</td></tr>
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
              </td></tr>
              {elseif !$consult->paye}
              <tr><th>Tiers-payant ?</th><td><input type="checkbox" name="_tiers" onchange="putTiers()" />
                <input type="hidden" name="type_tarif" value="null" /></td></tr>
              <tr><td colspan="2" class="button"><input type="submit" value="Valider ce tarif" /></td></tr>
              {/if}
            </table>
            
            </form>

          </td>
          
          {/if}
          
        </tr>
      </table>
      
    </td>
    {/if}-->
  </tr>
</table>