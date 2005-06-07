<!-- $Id$ -->

{literal}
<script type="text/javascript">
//<![CDATA[
function checkPatient() {
  var form = document.editFrm;
    
  if (form.nom.value.length == 0) {
    alert("Nom manquant");
    form.nom.focus();
    return false;
  }
    
  return true;
}

function printPatient(id) {
  var url = './index.php?m=dPpatients&a=print_patient&dialog=1';
  url = url + '&patient_id=' + id;
  popup(700, 550, url, 'Patient');
}
//]]>
</script>
{/literal}

<table class="main">
  <tr>
    <td class="greedyPane">
    
      <form name="find" action="./index.php" method="get">
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="{$tab}" />
      
      <table class="form">
      <input type="hidden" name="new" value="1" />
        <tr>
          <th class="category" colspan="2">Recherche d'un dossier patient</th>
        </tr>
  
        <tr>
          <th>Nom:</th>
          <td><input tabindex="1" type="text" name="nom" value="{$nom}" /></td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td><input tabindex="2" type="text" name="prenom" value="{$prenom}" /></td>
        </tr>
        
        <tr>
          <td class="button" colspan="2"><input type="submit" value="rechercher" /></td>
        </tr>
      </table>

      </form>
      
      <table class="tbl">
        <tr>
          <th>Nom - Prénom</th>
          <th>Adresse</th>
          <th>Ville</th>
        </tr>

        {foreach from=$patients item=curr_patient}
        <tr>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->_view}</a></td>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->adresse}</a></td>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->ville}</a></td>
        </tr>
        {/foreach}
        
      </table>

    </td>
 
    {if $patient->patient_id}
    <td class="pane">
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
          <th class="category" colspan="2">Information médicales</th>
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
            {if $patient->sexe == "j"} jeune fille {/if} 
          </td>
          <td colspan="2"></td>
        </tr>
        
        <tr>
          <th class="category" colspan="2">Coordonnées</th>
          <th class="category" colspan="2">Remarques</th>
        </tr>
        
        <tr>
          <th>Adresse:</th>
          <td>{$patient->adresse}</td>
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
        
        {if $canEdit}
        <tr>
          <td class="button" colspan="4">
            <form name="modif" action="./index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="tab" value="vw_edit_patients" />
            <input type="hidden" name="id" value="{$patient->patient_id}" />
            <input type="submit" value="Modifier" />

            <input type="button" value="Imprimer" onclick="printPatient({$patient->patient_id})" />

            </form>

          </td>
        </tr>
        {/if}
      </table>

      <table class="form">
        <tr><th class="category" colspan="2">Nouvelle action</th></tr>
        <tr>
          <td><a href="index.php?m=dPcabinet&amp;tab=edit_planning&amp;pat_id={$patient->patient_id}&amp;consultation_id=0">
            Plannifier une consultation</a></th>
          <td><a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;pat_id={$patient->patient_id}&amp;operation_id=0">
            Plannifier une intervention</a></th>
        </tr>
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
        {/if}
        {if $patient->_ref_operations}
        <tr><th colspan="3" class="category">Interventions</th></tr>
        {foreach from=$patient->_ref_operations item=curr_op}
        <tr>
          <td>
            <a href="index.php?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">
            {if $curr_op->plageop_id}
            {$curr_op->_ref_plageop->date|date_format:"%d %b %Y"}
            {else}
            Simple hospi.
            {/if}
            </a>
            <em><a href="index.php?m=dPadmissions&amp;tab=vw_idx_admission&amp;day={$curr_op->date_adm|date_format:"%d"}&amp;month={$curr_op->date_adm|date_format:"%m"}&amp;year={$curr_op->date_adm|date_format:"%Y"}#adm{$curr_op->operation_id}">
            (adm. le {$curr_op->date_adm|date_format:"%d %b %Y"})
            </a></em>
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
    {/if}
  </tr>
</table>
      