<table>
  <tr>
    <td valign="top">
      <table class="tbl">
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
      </table>
    </td>
    <td valign="top">
      <table>
        <tr>
          <td valign="top">
            <table class="form">
              <tr><th colspan="2" class="category">Patient</th></tr>
              <tr><th>Nom :</th><td>{$consult->_ref_patient->nom}</td></tr>
              <tr><th>Prénom :</th><td>{$consult->_ref_patient->prenom}</th></tr>
              <tr><th>Age :</th><td>{$consult->_ref_patient->_age} ans</td><tr>
            </table>
            <table class="form">
              <tr><th colspan="2" class="category">Fichiers liés</th></tr>
              <tr><td colspan="2"><ul>
                {foreach from=$consult->_ref_files item=curr_file}
                <li><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></li>
                {/foreach}
              </ul></td></tr>
              <tr><td><form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
              <input type="hidden" name="dosql" value="do_file_aed" />
	          <input type="hidden" name="del" value="0" />
	          <input type="hidden" name="file_consultation" value="{$consult->consultation_id}" />
              <input type="file" name="formfile"></td>
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
                <li>Dr. {$curr_consult->_ref_plageconsult->_ref_chir->user_first_name} {$curr_consult->_ref_plageconsult->_ref_chir->user_last_name}<br />
                le {$curr_consult->_ref_plageconsult->date}</li>
                {/foreach}
              </ul></td>
              <td><ul>
                {foreach from=$consult->_ref_patient->_ref_operations item=curr_op}
                <li>Dr. {$curr_op->_ref_chir->user_first_name} {$curr_op->_ref_chir->user_last_name}<br />
                le {$curr_op->_ref_plageop->date}</li>
                {/foreach}
              </ul></td></tr>
            </table>
            <table class="form">
              <tr><th colspan="2" class="category">Règlement</th></tr>
              <tr><th
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>