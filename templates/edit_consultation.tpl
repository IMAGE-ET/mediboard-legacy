<table>
  <tr>
    <td>
      <table class="tbl">
      {foreach from=$listPlage item=curr_plage}
        <tr>
          <th colspan="4"><b>Consultations de {$curr_plage->_hour_deb}h à {$curr_plage->_hour_fin}h</b></th>
        </tr>
        <tr>
          <th>Heure</th>
          <th>Patient</th>
          <th>Motif</th>
          <th>Remarques</th>
        </tr>
        {foreach from=$curr_plage->_ref_consultations item=curr_consult}
        <tr>
          <td>{$curr_consult->heure}</td>
          <td>{$curr_consult->_ref_patient->nom} {$curr_consult->_ref_patient->prenom}</td>
          <td>{$curr_consult->motif|nl2br}</td>
          <td>{$curr_consult->rques|nl2br}</td>
        </tr>
        {/foreach}
      {/foreach}
      </table>
    </td>
    <td>
      Ici il va falloir foutre des fichiers !!
    </td>
  </tr>
</table>