<!-- $Id$ -->

{literal}
<script language="javascript">

function pageMain() {
  initGroups("hospi");
  initGroups("op");
  initGroups("consult");
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
    f.patSel.value = key;
    f.patNom.value = val;
    window.patSel = key;
    window.patName = val;
  }
  
  f.submit();
}

function imprimerDocument(doc_id) {
  var url = '?m=dPcompteRendu&a=print_cr&dialog=1';
  url += '&compte_rendu_id=' + doc_id;
  popup(700, 600, url, 'Compte-rendu');
}

function printPatient(id) {
  var url = './index.php?m=dPpatients&a=print_patient&dialog=1';
  url = url + '&patient_id=' + id;
  popup(700, 550, url, 'Patient');
}

function printIntervention(id) {
  var url = './index.php?m=dPplanningOp&a=view_planning&dialog=1';
  url = url + '&operation_id=' + id;
  popup(700, 550, url, 'Admission');
}

</script>
{/literal}

<table class="main">
  <tr>
    <td class="greedyPane" colspan="2">
      <form name="patFrm" action="index.php" method="get">
      <table class="form">
        <tr><th>Choix du patient :</th>
          <td class="readonly">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="patSel" value="{$patSel->patient_id}" />
            <input type="text" readonly="readonly" name="patNom" value="{$patSel->_view}" />
          </td>
          <td class="button">
            <input type="button" value="chercher" onclick="popPat()" />
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  {if $patSel->patient_id}
  <tr>
    <td>
      <table class="form">
        <tr><th class="category" colspan="4">Consultations</th></tr>
        {foreach from=$patSel->_ref_consultations item=curr_consult}
        <tr class="groupcollapse" id="consult{$curr_consult->consultation_id}" onclick="flipGroup({$curr_consult->consultation_id}, 'consult')">
          <td colspan="4">
            <strong>
            Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view} &mdash;
            {$curr_consult->_ref_plageconsult->date|date_format:"%A %d %B %Y"} &mdash;
            {$curr_consult->_etat} &mdash;
            {$curr_consult->_ref_files|@count} fichier(s)
            </strong>
          </td>
        </tr>
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">Motif :</th>
          <td class="text" colspan="2">{$curr_consult->motif}</td>
        </tr>
        {if $curr_consult->rques}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">Remarques :</th>
          <td class="text" colspan="2">{$curr_consult->rques}</td>
        </tr>
        {/if}
        {if $curr_consult->examen}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">Examen :</th>
          <td class="text" colspan="2">{$curr_consult->examen}</td>
        </tr>
        {/if}
        {if $curr_consult->traitement}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">Traitement :</th>
          <td class="text" colspan="2">{$curr_consult->traitement}</td>
        </tr>
        {/if}
        {foreach from=$curr_consult->_ref_documents item=document}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">{$document->nom} :</th>
          {if $document->source}
          <td colspan="2" class="greedyPane">
            <button onclick="imprimerDocument({$document->compte_rendu_id})">
              <img src="modules/dPcabinet/images/print.png" />
            </button>
          </td>
          {else}
          <td colspan="2">
            -
          </td>
          {/if}
        </tr>
        {/foreach}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2"><i>Fichiers associés :</i></th>
          <td colspan="2" />
        </tr>
        {foreach from=$curr_consult->_ref_files item=curr_file}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2"><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></th>
          <td>{$curr_file->_file_size}</td>
          <td>
            <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
	        <input type="hidden" name="del" value="1" />
	        <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
            </form>
          </td>
	    </tr>
        {/foreach}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="3">
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
            <input type="hidden" name="del" value="0" />
	        <input type="hidden" name="file_consultation" value="{$curr_consult->consultation_id}" />
            <input type="file" name="formfile" />
          </th>
          <td class="greedyPane">
            <input type="submit" value="ajouter" />
            </form>
          </td>
        </tr>
        {/foreach}
        <tr><th class="category" colspan="4">Interventions</th></tr>
        {foreach from=$patSel->_ref_operations item=curr_op}
        <tr class="groupcollapse" id="op{$curr_op->operation_id}" onclick="flipGroup({$curr_op->operation_id}, 'op')">
          <td colspan="4">
            <strong>
            Dr. {$curr_op->_ref_chir->_view} &mdash;
            {$curr_op->_ref_plageop->date|date_format:"%A %d %B %Y"} &mdash;
            {$curr_op->_ref_files|@count} fichier(s)
            </strong>
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">
            CCAM 1 :
          </th>
          <td class="text" colspan="2">
            {$curr_op->_ext_code_ccam->code} : {$curr_op->_ext_code_ccam->libelleLong}
          </td>
        </tr>
        {if $curr_op->CCAM_code2}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">
            CCAM 2 :
          </th>
          <td class="text" colspan="2">
            {$curr_op->_ext_code_ccam2->code} : {$curr_op->_ext_code_ccam2->libelleLong}
          </td>
        </tr>
        {/if}
        {foreach from=$curr_op->_ref_documents item=document}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">{$document->nom} :</th>
          {if $document->source}
          <td colspan="2" class="greedyPane">
            <button onclick="imprimerDocument({$document->compte_rendu_id})">
              <img src="modules/dPcabinet/images/print.png" />
            </button>
          </td>
          {else}
          <td colspan="2">
            -
          </td>
          {/if}
        </tr>
        {/foreach}
        {foreach from=$curr_op->_ref_files item=curr_file}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">
            <a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a>
          </th>
          <td>{$curr_file->_file_size}</td>
          <td>
            <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
	        <input type="hidden" name="del" value="1" />
	        <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
            </form>
          </td>
	    </tr>
        {/foreach}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="3">
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
            <input type="hidden" name="del" value="0" />
	        <input type="hidden" name="file_operation" value="{$curr_op->operation_id}" />
            <input type="file" name="formfile" />
          </th>
          <td class="greedyPane">
            <input type="submit" value="ajouter" />
            </form>
          </td>
        </tr>
        {/foreach}
        <tr>
          <th class="category" colspan="4">Hospitalisations</th>
        </tr>
        {foreach from=$patSel->_ref_hospitalisations item=curr_hospi}
        <tr class="groupcollapse" id="hospi{$curr_hospi->operation_id}" onclick="flipGroup({$curr_hospi->operation_id}, 'hospi')">
          <td colspan="4">
            <strong>
            Dr. {$curr_hospi->_ref_chir->_view} &mdash;
            {$curr_hospi->date_adm|date_format:"%A %d %B %Y"}
            </strong>
          </td>
        </tr>
        {if $curr_hospi->CCAM_code}
        <tr class="hospi{$curr_hospi->operation_id}">
          <td class="text" colspan="4">
            {$curr_hospi->_ext_code_ccam->code} : {$curr_hospi->_ext_code_ccam->libelleLong}
          </td>
        </tr>
        {else}
        <tr class="hospi{$curr_hospi->operation_id}">
          <td class="text" colspan="4">Simple observation</td>
        </tr>
        {/if}
        {if $curr_hospi->CCAM_code2}
        <tr class="hospi{$curr_hospi->operation_id}">
          <td class="text" colspan="4">
            {$curr_hospi->_ext_code_ccam2->code} : {$curr_hospi->_ext_code_ccam2->libelleLong}
          </td>
        </tr>
        {/if}
        {if $chirSel}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2">Pack de sortie :</th>
          <td colspan="2">
            <form name="printPackFrm{$curr_hospi->operation_id}" action="?m=dPhospi" method="POST">
            <select name="pack" onchange="printPack({$curr_hospi->operation_id}, this.form)">
              <option value="0">&mdash; packs &mdash;</option>
              {foreach from=$packs item=curr_pack}
              <option value="{$curr_pack->pack_id}">{$curr_pack->nom}</option>
              {/foreach}
            </select>
          </td>
        </tr>
        {/if}
        {foreach from=$curr_hospi->_ref_documents item=document}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2">{$document->nom} :</th>
          {if $document->source}
          <td colspan="2" class="greedyPane">
            <button onclick="imprimerDocument({$document->compte_rendu_id})">
              <img src="modules/dPcabinet/images/print.png" />
            </button>
          </td>
          {else}
          <td colspan="2">
            -
          </td>
          {/if}
        </tr>
        {/foreach}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2"><i>Fichiers associés :</i></th>
          <td colspan="2" />
        </tr>
        {foreach from=$curr_hospi->_ref_files item=curr_file}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2"><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></th>
          <td>{$curr_file->_file_size}</td>
          <td>
            <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
	        <input type="hidden" name="del" value="1" />
	        <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
            </form>
          </td>
	    </tr>
        {/foreach}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="3">
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
            <input type="hidden" name="del" value="0" />
	        <input type="hidden" name="file_operation" value="{$curr_hospi->operation_id}" />
            <input type="file" name="formfile" />
          </th>
          <td>
            <input type="submit" value="ajouter" />
            </form>
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
    <td>
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
          <th class="category" colspan="2">Informations médicales</th>
        </tr>
        <tr>
          <th>Nom:</th>
          <td>{$patient->nom}</td>
          <th>Incapable majeur:</th>
          <td>
            {if $patient->incapable_majeur == "o"} oui {/if}
            {if $patient->incapable_majeur == "n"} non {/if}
          </td>
        </tr>
        <tr>
          <th>Prénom:</th>
          <td>{$patient->prenom}</td>
          <th>ATNC:</th>
          <td>
            {if $patient->ATNC == "o"} oui {/if}
            {if $patient->ATNC == "n"} non {/if}
          </td>
        </tr>
        <tr>
          <th>Nom de jeune fille:</th>
          <td>{$patient->nom_jeune_fille}</td>
          <th>Code administratif:</th>
          <td>{$patient->SHS}</td>
        </tr>
        <tr>
          <th>Date de naissance:</th>
          <td>{$patient->_jour} / {$patient->_mois} / {$patient->_annee}</td>
          <th>Numéro d'assuré social:</th>
          <td>{$patient->matricule}</td>
        </tr>
        <tr>
          <th>Sexe:</th>
          <td>
            {if $patient->sexe == "m"} masculin {/if}
            {if $patient->sexe == "f"} féminin {/if}
            {if $patient->sexe == "j"} femme célibataire {/if} 
          </td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <th class="category" colspan="2">Coordonnées</th>
          <th class="category" colspan="2">Remarques</th>
        </tr>
        <tr>
          <th>Adresse:</th>
          <td>{$patient->adresse|nl2br:php}</td>
          <td rowspan="5" colspan="2" class="text">{$patient->rques|nl2br:php}</td>
        </tr>
        <tr>
          <th>Code Postal:</th>
          <td>{$patient->cp}</td>
        </tr>
        <tr>
          <th>Ville:</th>
          <td>{$patient->ville}</td>
        </tr>
        <tr>
          <th>Téléphone:</th>
          <td>{$patient->_tel1} {$patient->_tel2} {$patient->_tel3} {$patient->_tel4} {$patient->_tel5}</td>
        </tr>
        <tr>
          <th>Portable:</th>
          <td>{$patient->tel2}</td>
        </tr>
        <tr>
          <td class="button" colspan="4">
            <form name="modif" action="./index.php" method="get">
            <input type="hidden" name="m" value="dPpatients" />
            <input type="hidden" name="tab" value="vw_edit_patients" />
            <input type="hidden" name="id" value="{$patient->patient_id}" />
            <input type="submit" value="Modifier" />
            <input type="button" value="Imprimer" onclick="printPatient({$patient->patient_id})" />
            </form>
          </td>
        </tr>
      </table>

      <table class="form">
        <tr><th class="category" colspan="3">Planifier</th></tr>
        <tr>
          <td class="button">
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;pat_id={$patient->patient_id}&amp;operation_id=0">
              une intervention
            </a>
          </td>
          <td class="button">
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;pat_id={$patient->patient_id}&amp;operation_id=0">
              une hospitalisation
            </a>
          </td>
          <td class="button">
            <a href="index.php?m=dPcabinet&amp;tab=edit_planning&amp;pat_id={$patient->patient_id}&amp;consultation_id=0">
              une consultation
            </a>
          </td>
        </tr>
        {if $listPrat|@count && $canEditCabinet}
        <tr><th class="category" colspan="3">Consultation immédiate</th></tr>
        <tr>
          <td class="button" colspan="3">
            <form name="addConsFrm" action="index.php?m=dPcabinet" method="post" onsubmit="return checkForm(this)">
            <input type="hidden" name="m" value="dPcabinet" />
            <input type="hidden" name="dosql" value="do_consult_now" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="patient_id" alt="notNull|ref" value="{$patient->patient_id}" />
            <label for="addConsFrm_prat_id" title="Praticien pour la consultation immédiate. Obligatoire">Praticien:</label>
            <select name="prat_id" alt="notNull|ref">
              <option value="">&mdash; Choisir un praticien</option>
              {foreach from=$listPrat item=curr_prat}
                <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $app->user_id} selected="selected" {/if}>
                  {$curr_prat->_view}
                </option>
              {/foreach}
            </select>
            <input type="submit" value="Consulter maintenant" />
            </form>
          </td>
        </tr>
        {/if}
      </table>

      <table class="form">
        {if $patient->_ref_curr_affectation->affectation_id}
        <tr><th colspan="3" class="category">Chambre actuelle</th></tr>
        <tr>
          <td colspan="3">
            {$patient->_ref_curr_affectation->_ref_lit->_ref_chambre->_ref_service->nom}
            - {$patient->_ref_curr_affectation->_ref_lit->_ref_chambre->nom}
            - {$patient->_ref_curr_affectation->_ref_lit->nom}
          </td>
        </tr>
        {elseif $patient->_ref_next_affectation->affectation_id}
        <tr><th colspan="3" class="category">Chambre à partir du {$patient->_ref_next_affectation->entree|date_format:"%d %b %Y"}</th></tr>
        <tr>
          <td colspan="3">
            {$patient->_ref_next_affectation->_ref_lit->_ref_chambre->_ref_service->nom}
            - {$patient->_ref_next_affectation->_ref_lit->_ref_chambre->nom}
            - {$patient->_ref_next_affectation->_ref_lit->nom}
          </td>
        </tr>
        {/if}
        {if $patient->_ref_operations}
        <tr><th colspan="4" class="category">Interventions</th></tr>
        {foreach from=$patient->_ref_operations item=curr_op}
        <tr>
          <td>
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
            {$curr_op->_ref_plageop->date|date_format:"%d %b %Y"}
            </a>
            <em><a href="index.php?m=dPadmissions&amp;tab=vw_idx_admission&amp;date={$curr_op->date_adm|date_format:"%Y-%m-%d"}#adm{$curr_op->operation_id}">
            (adm. le {$curr_op->date_adm|date_format:"%d %b %Y"})
            </a></em>
            <a href="javascript:printIntervention({$curr_op->operation_id})">
            <img src="modules/dPpatients/images/print.png" title="imprimer"/>
            </a>
          </td>
          <td>
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
            Dr. {$curr_op->_ref_chir->_view}
            </a>
          </td>
          <td>{if $curr_op->annulee}[ANNULE]{else}
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
            <img src="modules/dPpatients/images/planning.png" title="modifier"></a>{/if}
          </td>
        </tr>
        {/foreach}
        {/if}
        {if $patient->_ref_hospitalisations}
        <tr><th colspan="4" class="category">hospitalisations</th></tr>
        {foreach from=$patient->_ref_hospitalisations item=curr_op}
        <tr>
          <td>
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_op->operation_id}">
            Simple hospi.
            </a>
            <em><a href="index.php?m=dPadmissions&amp;tab=vw_idx_admission&amp;date={$curr_op->date_adm|date_format:"%Y-%m-%d"}#adm{$curr_op->operation_id}">
            (adm. le {$curr_op->date_adm|date_format:"%d %b %Y"})
            </a></em>
            <img src="modules/dPpatients/images/print.png" title="imprimer" onclick="printIntervention({$curr_op->operation_id})"/>
          </td>
          <td>
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_op->operation_id}">
            Dr. {$curr_op->_ref_chir->_view}
            </a>
          </td>
          <td>{if $curr_op->annulee}[ANNULE]{else}
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_hospi&amp;hospitalisation_id={$curr_op->operation_id}">
            <img src="modules/dPpatients/images/planning.png" title="modifier"></a>{/if}
          </td>
        </tr>
        {/foreach}
        {/if}
        {if $patient->_ref_consultations}
        <tr><th class="category" colspan="3">Consultations</th></tr>
        {foreach from=$patient->_ref_consultations item=curr_consult}
        <tr>
          <td><a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
            {$curr_consult->_ref_plageconsult->date|date_format:"%d %b %Y"}</a></td>
          <td><a href="index.php?m=dPcabinet&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_id}">
            Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view}</a></td>
          <td>{if $curr_consult->annule}[ANNULE]{else}
          <a href="index.php?m=dPcabinet&amp;tab=edit_planning&amp;consultation_id={$curr_consult->consultation_id}">
          <img src="modules/dPpatients/images/planning.png" title="modifier"></a>{/if}</td>
        </tr>
        {/foreach}
        {/if}
      </table>
    </td>
  </tr>
  {/if}
</table>

