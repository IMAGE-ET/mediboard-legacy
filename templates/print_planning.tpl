<table class="main">
  <tr><th><a href="javascript:window.print()">Planning du {$date}</a></th></tr>
  {foreach from=$listDays item=curr_day}
  {foreach from=$curr_day.listChirs item=curr_chir}
  {if $curr_chir.admissions}
  <tr>
    <td><b>{$curr_day.date_adm|date_format:"%a %d %b %Y"} - Dr. {$curr_chir.user_last_name} {$curr_chir.user_first_name}</b></td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th colspan="7"><b>Admission</b></th>
		  <th colspan="4"><b>Intervention</b></th>
		  <th colspan="2"><b>Patient</b></th>
		</tr>
		<tr>
		  <th>Heure</th>
		  <th>Type</th>
		  <th>Durée</th>
          <th>Bilan</th>
          <th>Convalescence</th>
		  <th>Chambre</th>
		  <th>Remarques</th>
		  <th>Date</th>
		  <th>Heure</th>
		  <th>Dénomination</th>
		  <th>Coté</th>
		  <th>Nom / Prenom</th>
		  <th>Age</th>
		</tr>
		{foreach from=$curr_chir.admissions item=curr_adm}
		<tr>
		  <td>{$curr_adm->_hour_adm}h{$curr_adm->_min_adm}</td>
		  <td>{$curr_adm->type_adm|truncate:1:""|capitalize}</td>
          <td>{$curr_adm->duree_hospi} j</td>
          <td class="text">{$curr_adm->examen|nl2br}</td>
          <td class="text">{$curr_adm->convalescence|nl2br}</td>
          <td>{$curr_adm->chambre}</td>
          <td class="text">{$curr_adm->rques}</td>
          <td>{$curr_adm->_ref_plageop->date|date_format:"%d/%m/%Y"}</td>
          <td>{if $curr_adm->time_operation != "00:00:00"}{$curr_adm->time_operation|truncate:5:""}{/if}</td>
          <td class="text">{$curr_adm->_ext_code_ccam->libelleLong|truncate:80:"...":false} <i>({$curr_adm->CCAM_code})</i></td>
          <td>{$curr_adm->cote|truncate:1:""|capitalize}</td>
          <td>{$curr_adm->_ref_pat->nom} {$curr_adm->_ref_pat->prenom}</td>
          <td>{$curr_adm->_ref_pat->_age}</td>
		</tr>
		{/foreach}
	  </table>
	</td>
  </tr>
  {/if}
  {/foreach}
  {/foreach}
</table>