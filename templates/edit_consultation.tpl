{literal}
<script language="JavaScript" type="text/javascript">

function editModele(consult, modele) {
  var url = '?m=dPcabinet&a=edit_compte_rendu&dialog=1';
  url +='&consult=' + consult;
  url +='&modele=' + modele;
  popup(700, 700, url, 'Compte-rendu');
}

function newOperation() {
  var url = '?m=dPplanningOp&tab=vw_add_planning';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}'
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}'
  window.location.href = url;
}

function newConsultation() {
  var url = '?m=dPcabinet&tab=add_planning';
  url +='&chir_id={/literal}{$consult->_ref_plageconsult->_ref_chir->user_id}{literal}'
  url +='&pat_id={/literal}{$consult->_ref_patient->patient_id}{literal}'
  window.location.href = url;
}

function pasteText(formName) {
  var form = document.editFrm;
  var aide = eval("form._aide_" + formName);
  var area = eval("form." + formName);
  
  area.value += aide.value + '\n';
  aide.value = 0;
  area.focus();
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
        <tr {if $curr_consult->consultation_id == $consult->consultation_id} style="font-weight: bold;" {/if}>
          <td>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">{$curr_consult->heure|truncate:5:"":true}</a>
          </td>
          <td>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">{$curr_consult->_ref_patient->nom} {$curr_consult->_ref_patient->prenom}</a>
          </td>
          <td>
            <a href="index.php?m={$m}&amp;tab=edit_planning&amp;consultation_id={$curr_consult->consultation_id}" title="Modifier le RDV">
              <img src="modules/dPcabinet/images/planning.png" />
            </a>
          </td>
          <td>{$curr_consult->_etat}</td>
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
    <td style="width: 100%">
      <table class="main">
        <tr>
          <td class="greedyPane">
            <form name="editFrm" action="?m={$m}" method="POST">

            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_consultation_aed" />
            <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
            <input type="hidden" name="plageconsult_id" value="{$consult->plageconsult_id}" />
            <input type="hidden" name="patient_id" value="{$consult->patient_id}" />
            <input type="hidden" name="heure" value="{$consult->heure}" />
            <input type="hidden" name="duree" value="{$consult->duree}" />
            <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
            <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
            <input type="hidden" name="compte_rendu" value="{$consult->compte_rendu|escape:"html"}" />

            <table class="form">
              <tr>
              	<th class="category" colspan="3">Consultation</th>
              </tr>
              
              <tr>
                <td style="text-align: center; vertical-align: middle">Etat : {$consult->_etat}</td>
                <td class="button"><button>Commencer</buton></td>
              </tr>
              <tr>
              	<th class="text">
              	  <label for="editFrm_motif" title="Motif de la consultation">Motif</label>
              	</th>
                <th>
                  <select name="_aide_motif" size="1" onchange="pasteText('motif')">
                    <option value="0">&mdash; Choisir une aide</option>
                    {html_options options=$aides}
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
                    {html_options options=$aides}
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
                    {html_options options=$aides selected=0}
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
                    {html_options options=$aides selected=0}
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
	              <input type="button" value="supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.submit();}{/literal}"/>

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
                  {$curr_op->_ref_plageop->date}</a></td>
                <td><a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
                  Dr. {$curr_op->_ref_chir->user_last_name} {$curr_op->_ref_chir->user_first_name}</a></td>
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
                  {$curr_consult->_ref_plageconsult->date}</a></td>
                <td><a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
                  Dr. {$curr_consult->_ref_plageconsult->_ref_chir->user_last_name} {$curr_consult->_ref_plageconsult->_ref_chir->user_first_name}</a></td>
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
          
          <td>
            <table class="form">
            {if $consult->compte_rendu}
              <tr><th colspan="2" class="category">Compte-Rendu</th></tr>
              <tr>
                <td><a href="#" onclick="editModele({$consult->consultation_id}, 0)">
                  Modifier le compte-rendu</a></td>
                <td>
                  <form name="delCompteRenduFrm" action="?m={$m}" method="POST">

                  <input type="hidden" name="m" value="{$m}" />
                  <input type="hidden" name="del" value="0" />
                  <input type="hidden" name="dosql" value="do_consultation_aed" />
                  <input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
                  <input type="hidden" name="plageconsult_id" value="{$consult->plageconsult_id}" />
                  <input type="hidden" name="patient_id" value="{$consult->patient_id}" />
                  <input type="hidden" name="heure" value="{$consult->heure}" />
                  <input type="hidden" name="duree" value="{$consult->duree}" />
                  <input type="hidden" name="motif" value="{$consult->motif}" />
                  <input type="hidden" name="rques" value="{$consult->rques}" />
                  <input type="hidden" name="secteur1" value="{$consult->secteur1}" />
                  <input type="hidden" name="secteur2" value="{$consult->secteur2}" />
                  <input type="hidden" name="compte_rendu" value="" />
                  <img src="modules/dPcabinet/images/trash.png" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {document.delCompteRenduFrm.submit();}{/literal}">

                  </form></td>
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
                  <a href="?m=dPcompteRendu&amp;tab=addedit_modeles&amp;com&amp;te_rendu_id={$curr_modele->compte_rendu_id}">
                    <img src="modules/dPcabinet/images/edit.png" />
                  </a>
                </td>
              </tr>
            {/foreach}
            {/if}
            </table>

            <table class="form">
              <tr><th colspan="2" class="category">Règlement</th></tr>
              <tr><th>Reste à payer: </th><td>le règlement</td></tr>
            </table>

          </td>
          
        </tr>
      </table>
      
    </td>
    {/if}
  </tr>
</table>