<!-- $Id$ -->

{literal}
<script type="text/javascript">

function pageMain() {
  initGroups("hospi");
  initGroups("op");
}

function popPat() {
  var url = './index.php?m=dPpatients';
  url += '&a=pat_selector';
  url += '&dialog=1';
  popup(500, 500, url, 'Patient');
}

function setPat( key, val ) {
  var f = document.patFrm;
  if (val != '') {
    f.pat_id.value = key;
    f.patNom.value = val;
  }
  f.submit();
}

function imprimerDocument(doc_id) {
  var url = '?m=dPcompteRendu&a=print_cr&dialog=1';
  url += '&compte_rendu_id=' + doc_id;
  popup(700, 600, url, 'Compte-rendu');
}

</script>
{/literal}

<table class="main">
  <tr>
    <td>
      <form name="patFrm" action="index.php" method="get">
      <table class="form">
        <tr><th>Choix du patient :</th>
          <td class="readonly">
            <input type="hidden" name="m" value="dPpmsi" />
            <input type="hidden" name="pat_id" value="{$patient->patient_id}" />
            <input type="text" readonly="readonly" name="patNom" value="{$patient->_view}" />
          </td>
          <td class="button">
            <input type="button" value="chercher" onclick="popPat()" />
          </td>
        </tr>
      </table>
      </form>
      {if $patient->patient_id}
      {include file="../../dPpatients/templates/inc_vw_patient.tpl"}
      {/if}
    </td>
    {if $patient->patient_id}
    <td>
      <table class="form">
        <tr><th class="title" colspan="4">Interventions</th></tr>
        {foreach from=$patient->_ref_operations item=curr_op}
        <tr class="groupcollapse" id="op{$curr_op->operation_id}" onclick="flipGroup({$curr_op->operation_id}, 'op')">
          <td colspan="4">
            <strong>
            Dr. {$curr_op->_ref_chir->_view} &mdash;
            {$curr_op->_ref_plageop->date|date_format:"%A %d %B %Y"}
            </strong>
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th colspan="4" class="category">Hospitalisation</th>
        </tr>
        {if $curr_op->_ref_affectations|@count} 
        <tr class="op{$curr_op->operation_id}">
          <th>Séjour</th>
          <td class="text" colspan="3">
            Du {$curr_op->_ref_first_affectation->entree|date_format:"%A %d/%m/%Y (%Hh%M)"}
            au {$curr_op->_ref_last_affectation->sortie|date_format:"%A %d/%m/%Y (%Hh%M)"}
          </td>
        </tr>
        {else}
        <tr class="op{$curr_op->operation_id}">
          <th>Admission prévue</th>
          <td class="text" colspan="3">
            Le {$curr_op->date_adm|date_format:"%A %d/%m/%Y"} {$curr_op->time_adm|date_format:"(%Hh%M)"}
            pour {$curr_op->duree_hospi} jour(s)
          </td>
        </tr>
        {/if}
        {foreach from=$curr_op->_ext_codes_ccam item=curr_code}
        <tr class="op{$curr_op->operation_id}">
          <th>{$curr_code->code}</th>
          <td class="text" colspan="3">{$curr_code->libelleLong}</td>
        </tr>
        {/foreach}
        {if $curr_op->_ref_consult_anesth->consultation_anesth_id}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="4" class="category">Consultation pré-anesthésique</th>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th>Consultation</th>
          <td class="text" colspan="3">
            Le {$curr_op->_ref_consult_anesth->_ref_plageconsult->date|date_format:"%A %d/%m/%Y"}
            avec le Dr. {$curr_op->_ref_consult_anesth->_ref_plageconsult->_ref_chir->_view}
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <td class="button">Poids</td>
          <td class="button">Taille</td>
          <td class="button">Groupe</td>
          <td class="button">Tension</td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <td class="button">{$curr_op->_ref_consult_anesth->poid} kg</td>
          <td class="button">{$curr_op->_ref_consult_anesth->taille} m</td>
          <td class="button">{$curr_op->_ref_consult_anesth->groupe} {$curr_op->_ref_consult_anesth->rhesus}</td>
          <td class="button">{$curr_op->_ref_consult_anesth->tasys}/{$curr_op->_ref_consult_anesth->tadias}</td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th>Diagnostics</th>
          <td class="text" colspan="3">
            <ul>
              {foreach from=$curr_op->_ref_consult_anesth->_codes_cim10 item=curr_code}
              <li>
                {$curr_code->code}: {$curr_code->libelle}
              </li>
              {foreachelse}
              <li>Pas de diagnostic</li>
              {/foreach}
            </ul>
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th>Antécedents</th>
          <td class="text" colspan="3">
            <ul>
              {foreach from=$patient->_ref_antecedents item=curr_ant}
              <li>
                {$curr_ant->type} le {$curr_ant->date|date_format:"%d/%m/%Y"} :
                <i>{$curr_ant->rques}</i>
              </li>
              {foreachelse}
              <li>Pas d'antécédents</li>
              {/foreach}
            </ul>
          </td>
        </tr>
        {/if}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="4" class="category">Intervention</th>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th>Date</th>
          <td class="text" colspan="3">
            Le {$curr_op->_ref_plageop->date|date_format:"%A %d/%m/%Y"}
            par le Dr. {$curr_op->_ref_chir->_view},
            {$curr_op->_ref_plageop->_ref_salle->nom}
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th>Heures</th>
          <td class="text" colspan="3">
            Entrée en salle à {$curr_op->entree_bloc|date_format:"%Hh%M"},
            sortie à {$curr_op->sortie_bloc|date_format:"%Hh%M"}
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <td class="button">Code</td>
          <td class="button">Activité</td>
          <td class="button">Phase</td>
          <td class="button">Modificateurs</td>
        </tr>
        {foreach from=$curr_op->_ref_actes_ccam item=curr_acte}
        <tr class="op{$curr_op->operation_id}">
          <td class="button">{$curr_acte->code_acte}</td>
          <td class="button">{$curr_acte->code_activite}</td>
          <td class="button">{$curr_acte->code_phase}</td>
          <td class="button">{$curr_acte->modificateurs}</td>
        </tr>
        {/foreach}
        {foreach from=$curr_op->_ref_documents item=document}
        <tr class="op{$curr_op->operation_id}">
          <th>{$document->nom}</th>
          {if $document->source}
          <td colspan="3">
            <button onclick="imprimerDocument({$document->compte_rendu_id})">
              <img src="modules/dPpmsi/images/print.png" />
            </button>
          </td>
          {else}
          <td colspan="3">
            -
          </td>
          {/if}
        </tr>
        {/foreach}
        {/foreach}
        <tr>
          <th class="title" colspan="4">Hospitalisations</th>
        </tr>
        {foreach from=$patient->_ref_hospitalisations item=curr_hospi}
        <tr class="groupcollapse" id="hospi{$curr_hospi->operation_id}" onclick="flipGroup({$curr_hospi->operation_id}, 'hospi')">
          <td colspan="4">
            <strong>
            Dr. {$curr_hospi->_ref_chir->_view} &mdash;
            {$curr_hospi->date_adm|date_format:"%A %d %B %Y"}
            </strong>
          </td>
        </tr>
        {if $curr_op->_ref_affectations|@count}
        <tr class="hospi{$curr_op->operation_id}">
          <th>Séjour</th>
          <td class="text" colspan="3">
            Du {$curr_hospi->_ref_first_affectation->entree|date_format:"%A %d/%m/%Y (%Hh%M)"}
            au {$curr_hospi->_ref_last_affectation->sortie|date_format:"%A %d/%m/%Y (%Hh%M)"}
          </td>
        </tr>
        {else}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th>Admission prévue</th>
          <td class="text" colspan="3">
            Le {$curr_hospi->date_adm|date_format:"%A %d/%m/%Y"} {$curr_hospi->time_adm|date_format:"(%Hh%M)"}
            pour {$curr_hospi->duree_hospi} jour(s)
          </td>
        </tr>
        {/if}
        {foreach from=$curr_hospi->_ext_codes_ccam item=curr_code}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th>{$curr_code->code}</th>
          <td class="text" colspan="3">{$curr_code->libelleLong}</td>
        </tr>
        {/foreach}
        {foreach from=$curr_hospi->_ref_documents item=document}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th>{$document->nom}</th>
          {if $document->source}
          <td colspan="3">
            <button onclick="imprimerDocument({$document->compte_rendu_id})">
              <img src="modules/dPpmsi/images/print.png" />
            </button>
          </td>
          {else}
          <td colspan="3">
            -
          </td>
          {/if}
        </tr>
        {/foreach}
        {/foreach}
       </table>
      {/if}
    </td>
  </tr>
</table>

