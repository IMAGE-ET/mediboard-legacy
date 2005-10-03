<!-- $Id$ -->

{literal}
<script type="text/javascript">
function confirmCreation(id, bDialog, sSiblingsText) {
  if (!confirm(sSiblingsText)) {
    var form = document.editFrm;
    form.del.value = 1;
    form.submit();
  } else {
    url = "index.php?m=dPpatients";
    url += bDialog ? "&a=pat_selector&dialog=1" : ("&tab=vw_idx_patients&id=" + id + "&nom=&prenom=");
    window.location.href = url;
  }
}

function printPatient(id) {
  var url = './index.php?m=dPpatients&a=print_patient&dialog=1';
  url = url + '&patient_id=' + id;
  popup(700, 550, url, 'Patient');
}

function popMed(type) {
  var url = './index.php?m=dPpatients';
  url += '&a=vw_medecins';
  url += '&dialog=1';
  url += '&type=' + type;
  popup(700, 400, url, 'Medecin');
}

function delMed(sElementName) {
  form = document.editFrm;
  
  fieldMedecin = eval("form.medecin" + sElementName);
  fieldMedecinName = eval("form._medecin" + sElementName + "_name");
	
  fieldMedecin.value = "";
  fieldMedecinName.value = "";
}

function setMed( key, nom, prenom, sElementName ){
  form = document.editFrm;
  
  fieldMedecin = eval("form.medecin" + sElementName);
  fieldMedecinName = eval("form._medecin" + sElementName + "_name");
	
  fieldMedecin.value = key;
  fieldMedecinName.value = "Dr. " + nom + " " + prenom;
}

function followUp(field, sFollowFieldName) {
  if (field.value.length == 2) {
    fieldFollow = field.form.elements[sFollowFieldName];
    fieldFollow.focus();
  }  
}

</script>
{/literal}

<table class="main">
  {if $patient->patient_id}
  <tr>
    <td><strong><a href="index.php?m={$m}&amp;patient_id=0">Créer un nouveau patient</a></strong></td>
  </tr>
  {/if}
  <tr>
    <td>

      <form name="editFrm" action="index.php?m={$m}" method="post" onsubmit="return checkForm(this)">

      <input type="hidden" name="dosql" value="do_patients_aed" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="patient_id" value="{$patient->patient_id}" />
      {if $dialog}
      <input type="hidden" name="dialog" value="{$dialog}" />
      {/if}
      
      <table class="form">

      <tr>
      {if $patient->patient_id}
        <th class="title" colspan="5" style="color: #f00;">Modification du dossier de {$patient->_view}</th>
      {else}
        <th class="title" colspan="5">Création d'un dossier</th>
      {/if}
      </tr>

      <tr>
        <th class="category" colspan="2">Identité</th>
        <th class="category" colspan="3">Informations médicales</th>
      </tr>
      
      <tr>
        <th><label for="editFrm_nom" title="Nom du patient. Obligatoire">Nom:</label></th>
        <td><input tabindex="1" type="text" name="nom" value="{$patient->nom}" alt="{$patient->_props.nom}" /></td>
        <th>Incapable majeur:</th>
        <td colspan="2">
          <input tabindex="21" type="radio" name="incapable_majeur" value="o" {if $patient->incapable_majeur == "o"} checked="checked" {/if} />oui
          <input tabindex="22" type="radio" name="incapable_majeur" value="n" {if $patient->incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th><label for="editFrm_prenom" title="Prénom du patient. Obligatoire">Prénom:</label></th>
        <td><input tabindex="2" type="text" name="prenom" value="{$patient->prenom}" alt="{$patient->_props.prenom}" /></td>
        <th>ATNC:</th>
        <td colspan="2">
          <input tabindex="23" type="radio" name="ATNC" value="o" {if $patient->ATNC == "o"} checked="checked" {/if} />oui
          <input tabindex="24" type="radio" name="ATNC" value="n" {if $patient->ATNC == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th>Nom de jeune fille:</th>
        <td><input tabindex="3" type="text" name="nom_jeune_fille" value="{$patient->nom_jeune_fille}" alt="{$patient->_props.nom_jeune_fille}" /></td>
        <th><label for="editFrm_matricule" title="Matricule valide d'assuré social (13 chiffres + 2 pour la clé)">Numéro d'assuré social:</label></th>
        <td colspan="2">
          <input tabindex="25" type="text" size="15" maxlength="15" name="matricule" value="{$patient->matricule}"  alt="{$patient->_props.matricule}" />
        </td>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td>
          <input tabindex="4" type="text" name="_jour"  size="2" maxlength="2" value="{if $patient->_jour != "00"}{$patient->_jour}{/if}" onKeyup="followUp(this, '_mois')" /> -
          <input tabindex="5" type="text" name="_mois"  size="2" maxlength="2" value="{if $patient->_mois != "00"}{$patient->_mois}{/if}" onKeyup="followUp(this, '_annee')" /> -
          <input tabindex="6" type="text" name="_annee" size="4" maxlength="4" value="{if $patient->_annee != "0000"}{$patient->_annee}{/if}" />
        </td>
        <th>Code administratif:</th>
        <td colspan="2"><input tabindex="26" type="text" name="SHS" value="{$patient->SHS}" /></td>
      </tr>
      
      <tr>
        <th><label for="editFrm_sexe" title="Sexe du patient">Sexe:</label></th>
        <td>
          <select tabindex="7" name="sexe" alt="{$patient->_props.sexe}">
            <option value="m" {if $patient->sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient->sexe == "f"} selected="selected" {/if}>féminin</option>
            <option value="j" {if $patient->sexe == "j"} selected="selected" {/if}>femme célibataire</option>
          </select>
        </td>
        <td colspan="3"></td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonnées</th>
        <th class="category" colspan="3">Médecins correpondants</th>
      </tr>
      
      <tr>
        <th><label for="editFrm_adresse" title="Adresse du patient">Adresse:</label></th>
        <td><textarea tabindex="8" name="adresse" alt="{$patient->_props.adresse}" rows="1">{$patient->adresse}</textarea></td>
        <th>
          <input type="hidden" name="medecin_traitant" value="{$patient->medecin_traitant}" alt="{$patient->_props.medecin_traitant}" />
          <label for="editFrm_medecin_traitant" title="Merci de choisir un médecin traitant">Medecin traitant:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin_traitant_name" size="30" value="Dr. {$patient->_ref_medecin_traitant->_view}" readonly="readonly" />
          <button type="button" onclick="delMed('_traitant')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="27" type="button" value="choisir un médecin" onclick="popMed('_traitant')"></td>
      </tr>
      
      <tr>
        <th><label for="editFrm_cp" title="Code postal">Code Postal:</label></th>
        <td><input tabindex="9" type="text" name="cp" value="{$patient->cp}" alt="{$patient->_props.cp}" /></td>
        <th>
          <input type="hidden" name="medecin1" value="{$patient->_ref_medecin1->medecin_id}" alt="{$patient->_props.medecin1}" />
          <label for="editFrm_medecin1" title="Merci de choisir un médecin correspondant">Médecin correspondant 1:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin1_name" size="30" value="Dr. {$patient->_ref_medecin1->_view}" readonly="readonly" />
          <button type="button" onclick="delMed('1')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" />
        </td>
        <td class="button"><input tabindex="28" type="button" value="choisir un médecin" onclick="popMed('1')"></td>
      </tr>
      
      <tr>
        <th><label for="editFrm_sexe" title="Ville du patient">Ville:</label></th>
        <td><input tabindex="10" type="text" name="ville" value="{$patient->ville}" alt="{$patient->_props.ville}" /></td>
        <th>
          <input type="hidden" name="medecin2" value="{$patient->_ref_medecin2->medecin_id}" alt="{$patient->_props.medecin2}" />
          <label for="editFrm_medecin2">Médecin correspondant 2:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin2_name" size="30" value="{if ($patient->_ref_medecin2)}Dr. {$patient->_ref_medecin2->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('2')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="29" type="button" value="choisir un médecin" onclick="popMed('2')"></td>
      </tr>
      
      <tr>
        <th>Téléphone:</th>
        <td>
          <input tabindex="11" type="text" name="_tel1" size="2" maxlength="2" value="{$patient->_tel1}" alt="num|length|2" onkeyup="followUp(this, '_tel2')" /> - 
          <input tabindex="12" type="text" name="_tel2" size="2" maxlength="2" value="{$patient->_tel2}" alt="num|length|2" onkeyup="followUp(this, '_tel3')" /> -
          <input tabindex="13" type="text" name="_tel3" size="2" maxlength="2" value="{$patient->_tel3}" alt="num|length|2" onkeyup="followUp(this, '_tel4')" /> -
          <input tabindex="14" type="text" name="_tel4" size="2" maxlength="2" value="{$patient->_tel4}" alt="num|length|2" onkeyup="followUp(this, '_tel5')" /> -
          <input tabindex="15" type="text" name="_tel5" size="2" maxlength="2" value="{$patient->_tel5}" alt="num|length|2" onkeyup="followUp(this, '_tel21')" />
        </td>
        <th>
          <input type="hidden" name="medecin3" value="{$patient->_ref_medecin3->medecin_id}" alt="{$patient->_props.medecin3}" />
          <label for="editFrm_medecin3">Médecin correspondant 3:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin3_name" size="30" value="{if ($patient->_ref_medecin3)}Dr. {$patient->_ref_medecin3->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('3')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="30" type="button" value="choisir un médecin" onclick="popMed('3')"></td>
      </tr>
      
      <tr>
        <th>Portable:</th>
        <td>
          <input tabindex="16" type="text" name="_tel21" size="2" maxlength="2" value="{$patient->_tel21}" alt="num|length|2" onkeyup="followUp(this, '_tel22')" /> - 
          <input tabindex="17" type="text" name="_tel22" size="2" maxlength="2" value="{$patient->_tel22}" alt="num|length|2" onkeyup="followUp(this, '_tel23')" /> -
          <input tabindex="18" type="text" name="_tel23" size="2" maxlength="2" value="{$patient->_tel23}" alt="num|length|2" onkeyup="followUp(this, '_tel24')" /> -
          <input tabindex="19" type="text" name="_tel24" size="2" maxlength="2" value="{$patient->_tel24}" alt="num|length|2" onkeyup="followUp(this, '_tel25')" /> -
          <input tabindex="20" type="text" name="_tel25" size="2" maxlength="2" value="{$patient->_tel25}" alt="num|length|2" />
        </td>
        <th colspan="3"></th>
      </tr>
      
      <tr>
        <th>Remarques:</th>
        <td colspan="4">
          <textarea tabindex="31" name="rques">{$patient->rques}</textarea>
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="5">
          {if $patient->patient_id}
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'le patient', '{$patient->_view|escape:javascript}')"/>
            <input type="button" value="Imprimer" onclick="printPatient({$patient->patient_id})" />
          {else}
            <input tabindex="32" type="submit" value="Créer" />
          {/if}
        </td>
      </tr>
      
      </table>

      </form>

    </td>
  </tr>
</table>

<script type="text/javascript">
{if $textSiblings}
  confirmCreation({$created}, {if $dialog}1{else}0{/if}, "{$textSiblings|escape:javascript}");
{/if}
</script>
