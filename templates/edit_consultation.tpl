{literal}
<script language="JavaScript" type="text/javascript">

function editModele(consult, modele) {
  popup(700, 700, './index.php?m=dPcabinet&a=edit_compte_rendu&consult=' + consult + '&modele=' + modele + '&dialog=1', 'Compte-rendu');
}
  
</script>
{/literal}


<table>
  <tr>
    <td valign="top">
      <table align="center" width="100%">
      <tr>
        <th></th>
        <th><a href="?m={$m}&change=1&yearconsult={$pyear}"><</a></th>
        <th>{$year}</th>
        <th><a href="?m={$m}&change=1&yearconsult={$nyear}">></a></th>
        <th></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&change=1&monthconsult={$ppmonth}"><<</a></th>
        <th><a href="?m={$m}&change=1&monthconsult={$pmonth}"><</a></th>
        <th>{$monthName}</th>
        <th><a href="?m={$m}&change=1&monthconsult={$nmonth}">></a></th>
        <th><a href="?m={$m}&change=1&monthconsult={$nnmonth}">>></a></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&change=1&dayconsult={$ppday}"><<</a></th>
        <th><a href="?m={$m}&change=1&dayconsult={$pday}"><</a></th>
        <th>{$dayName} {$day}</th>
        <th><a href="?m={$m}&change=1&dayconsult={$nday}">></a></th>
        <th><a href="?m={$m}&change=1&dayconsult={$nnday}">>></a></th>
      </tr>
      </table>
      <table class="tbl">
      {if $listPlage}
      {foreach from=$listPlage item=curr_plage}
        <tr>
          <th colspan="2"><b>Consultations de {$curr_plage->_hour_deb}h à {$curr_plage->_hour_fin}h</b></th>
        </tr>
        <tr>
          <th>Heure</th>
          <th>Patient</th>
        </tr>
        {foreach from=$curr_plage->_ref_consultations item=curr_consult}
        <tr>
          <td>{if $curr_consult->consultation_id == $consult->consultation_id}<b>{/if}
          <a href="index.php?m={$m}&tab=edit_consultation&selConsult={$curr_consult->consultation_id}">{$curr_consult->heure}</a>
          {if $curr_consult->consultation_id == $consult->consultation_id}</b>{/if}</td>
          <td>{if $curr_consult->consultation_id == $consult->consultation_id}<b>{/if}
          <a href="index.php?m={$m}&tab=edit_consultation&selConsult={$curr_consult->consultation_id}">{$curr_consult->_ref_patient->nom} {$curr_consult->_ref_patient->prenom}</a>
          {if $curr_consult->consultation_id == $consult->consultation_id}</b>{/if}</td>
        </tr>
        {/foreach}
      {/foreach}
      {else}
        <tr>
          <th colspan=2><b>Pas de consultations</b></th>
        </tr>
      {/if}
      </table>
    </td>
    {if $consult->consultation_id}
    <td valign="top">
      <table>
        <tr>
          <td valign="top">
            <table class="form">
              <tr><th colspan="2" class="category"><a href="?m=dPpatients&tab=0&id={$consult->_ref_patient->patient_id}">Patient</a></th></tr>
              <tr><th>Nom :</th><td>{$consult->_ref_patient->nom}</td></tr>
              <tr><th>Prénom :</th><td>{$consult->_ref_patient->prenom}</th></tr>
              <tr><th>Age :</th><td>{$consult->_ref_patient->_age} ans</td><tr>
            </table>
            <table class="form">
              <tr><th colspan="3" class="category">Fichiers liés</th></tr>
              {foreach from=$consult->_ref_files item=curr_file}
              <tr>
                <td><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></td>
                <td>{$curr_file->file_size}</td>
                <td class="button"><form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
                  <input type="hidden" name="dosql" value="do_file_aed" />
	              <input type="hidden" name="del" value="1" />
	              <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	              <input type="button" value="supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.submit();}{/literal}"/>
	              </form></td>
	          </tr>
              {/foreach}
              <tr><td colspan="2"><form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
              <input type="hidden" name="dosql" value="do_file_aed" />
	          <input type="hidden" name="del" value="0" />
	          <input type="hidden" name="file_consultation" value="{$consult->consultation_id}" />
              <input type="file" name="formfile" /></td>
              <td class="button"><input type="submit" value="ajouter">
              </form></td></tr>
            </table>
            <table class="form">
              <tr><th colspan="2" class="category">Consultation</th></tr>
              <tr><th>Motif :</th><td>{$consult->motif|nl2br}</td></tr>
              <tr><th>Remarques :</th><td>{$consult->rques|nl2br}</td></tr>
            </table>
          </td>
          <td valign="top">
            <table class="form">
              <tr><th colspan="2" class="category">Antécédants</th></tr>
              <tr><td>consultations</td>
              <td>interventions</td></tr>
              <tr><td><ul>
                {foreach from=$consult->_ref_patient->_ref_consultations item=curr_consult}
                <li><a href="?m={$m}&selConsult={$curr_consult->consultation_id}">
                Dr. {$curr_consult->_ref_plageconsult->_ref_chir->user_first_name} {$curr_consult->_ref_plageconsult->_ref_chir->user_last_name}<br />
                le {$curr_consult->_ref_plageconsult->date}</a></li>
                {/foreach}
              </ul></td>
              <td><ul>
                {foreach from=$consult->_ref_patient->_ref_operations item=curr_op}
                <li><a href="?m=dPplanningOp&tab=vw_edit_planning&operation_id={$curr_op->operation_id}">
                Dr. {$curr_op->_ref_chir->user_first_name} {$curr_op->_ref_chir->user_last_name}<br />
                le {$curr_op->_ref_plageop->date}</a></li>
                {/foreach}
              </ul></td></tr>
            </table>
            <table class="form">
              <tr><th colspan="2" class="category">Règlement</th></tr>
              <tr><th>Reste à payer: </th><td>le règlement</td></tr>
            </table>
          </td>
          <td valign="top">
            <table class="form">
              <tr><th class="category">Compte-Rendu</th></tr>
              <tr><td>
              {if $consult->compte_rendu}
                <a href="#" onclick="editModele({$consult->consultation_id}, 0)">
                Modifier le compte-rendu</a>
              {else}
                <ul>
                {foreach from=$listModele item=curr_modele}
                  <li><a href="#" onclick="editModele({$consult->consultation_id}, {$curr_modele->compte_rendu_id})">
                  {$curr_modele->nom}</a></li>
                {/foreach}
                </ul>
              {/if}
              </td></tr>
          </td>
        </tr>
      </table>
    </td>
    {/if}
  </tr>
</table>