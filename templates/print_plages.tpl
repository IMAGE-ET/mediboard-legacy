<!-- $Id$ -->

<table class="main">
  <tr>
    <th>
      <a href="javascript:window.print()">
        Rapport du {$deb|date_format:"%d/%m/%Y"}
        {if $deb != $fin}
        au {$fin|date_format:"%d/%m/%Y"}
        {/if}
      </a>
    </th>
  </tr>
  {foreach from=$listPlage item=curr_plage}
  <tr>
    <td><b>{$curr_plage->date|date_format:"%d/%m/%Y"} - Dr. {$curr_plage->_ref_chir->_view}</b></td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th rowspan="2"><b>Heure</b></th>
		  <th colspan="2"><b>Patient</b></th>
		  <th colspan="3"><b>Consultation</b></th>
		</tr>
		<tr>
		  <th>Nom / Prénom</th>
          <th>Age</th>
          <th>Motif</th>
		  <th>Remarques</th>
		  <th>Comptes-rendu</th>
		</tr>
		{foreach from=$curr_plage->_ref_consultations item=curr_consult}
		<tr>
		  <td>{$curr_consult->heure}</td>
		  <td>{$curr_consult->_ref_patient->_view}</td>
          <td>{$curr_consult->_ref_patient->_age} ans</td>
          <td class="text">{$curr_consult->motif|nl2br}</td>
          <td class="text">{$curr_consult->rques|nl2br}</td>
          <td>{if $curr_consult->compte_rendu}oui{else}non{/if}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  {/foreach}
</table>