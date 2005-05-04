<!-- $Id$ -->

{literal}
<script type="text/javascript">
 //<![CDATA[
function confirmCreation(id) {
  var form = document.editFrm;
  check = confirm("{/literal}{$textSiblings}{literal}");
  if(!check) {
    form.del.value = 1;
    form.submit();
  }
  else {
    window.location.href = 'index.php?m=dPpatients&tab=vw_idx_patients&id=' + id + '&nom=&prenom='
  }
}

function checkPatient() {
  var form = document.editFrm;
    
  if (form.nom.value.length == 0) {
    alert("Nom manquant");
    form.nom.focus();
    return false;
  }
    
  if (form.prenom.value.length == 0) {
    alert("Prénom manquant");
    form.prenom.focus();
    return false;
  }
   
  if (form.matricule.value.length != 15 && form.matricule.value.length != 0) {
    alert("Matricule incorrect");
    form.matricule.focus();
    return false;
  }
   
  return true;
}

function popMed(type) {
  var url = './index.php?m=dPpatients';
  url += '&a=vw_medecins';
  url += '&dialog=1';
  url += '&type=' + type;
  popup(700, 400, url, 'Medecin');
}

function setMed( key, nom, prenom, type ){
  var f = document.editFrm;
  switch(type) {
    case '_traitant' : {
      f.medecin_traitant.value = key;
      f._medecin_traitant_name.value = 'Dr. ' + nom + ' ' + prenom;
      window.medecin_traitant = key;
      window._medecin_traitant_name = 'Dr. ' + nom + ' ' + prenom;
      break;
    }
    case '1' : {
      f.medecin1.value = key;
      f._medecin1_name.value = 'Dr. ' + nom + ' ' + prenom;
      window.medecin1 = key;
      window._medecin1_name = 'Dr. ' + nom + ' ' + prenom;
      break;
    }
    case '2' : {
      f.medecin2.value = key;
      f._medecin2_name.value = 'Dr. ' + nom + ' ' + prenom;
      window.medecin2 = key;
      window._medecin2_name = 'Dr. ' + nom + ' ' + prenom;
      break;
    }
    case '3' : {
      f.medecin3.value = key;
      f._medecin3_name.value = 'Dr. ' + nom + ' ' + prenom;
      window.medecin3 = key;
      window._medecin3_name = 'Dr. ' + nom + ' ' + prenom;
      break;
    }
  }
}

//]]>
</script>
{/literal}

<table class="main">
  <tr>
    <td>

      <form name="editFrm" action="index.php?m={$m}" method="post" onsubmit="return checkPatient()">

      <input type="hidden" name="dosql" value="do_patients_aed" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="patient_id" value="{$patient->patient_id}" />
      
      <table class="form">
      
      <tr>
        <th class="category" colspan="2">Identité</th>
        <th class="category" colspan="3">Informations médicales</th>
      </tr>
      
      <tr>
        <th class="mandatory">Nom:</th>
        <td><input tabindex="1" type="text" name="nom" value="{$patient->nom}" /></td>
        <th>Incapable majeur:</th>
        <td colspan="2">
          <input tabindex="20" type="radio" name="incapable_majeur" value="o" {if $patient->incapable_majeur == "o"} checked="checked" {/if} />oui
          <input tabindex="21" type="radio" name="incapable_majeur" value="n" {if $patient->incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th class="mandatory">Prénom:</th>
        <td><input tabindex="2" type="text" name="prenom" value="{$patient->prenom}" /></td>
        <th>ATNC:</th>
        <td colspan="2">
          <input tabindex="22" type="radio" name="ATNC" value="o" {if $patient->ATNC == "o"} checked="checked" {/if} />oui
          <input tabindex="23" type="radio" name="ATNC" value="n" {if $patient->ATNC == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td>
          <input tabindex="3" type="text" name="_jour"  size="2" maxlength="2" value="{$patient->_jour}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._mois.focus();{rdelim}" /> -
          <input tabindex="4" type="text" name="_mois"  size="2" maxlength="2" value="{$patient->_mois}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._annee.focus();{rdelim}" /> -
          <input tabindex="5" type="text" name="_annee" size="4" maxlength="4" value="{$patient->_annee}" />
        </td>
        <th>Numéro d'assuré social:</th>
        <td colspan="2"><input tabindex="24" type="text" size="15" maxlength="15" name="matricule" value="{$patient->matricule}" /></td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <select tabindex="6" name="sexe">
            <option value="m" {if $patient->sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient->sexe == "f"} selected="selected" {/if}>féminin</option>
          </select>
        </td>
        <th>Code administratif:</th>
        <td colspan="2"><input tabindex="25" type="text" name="SHS" value="{$patient->SHS}" /></td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonnées</th>
        <th class="category" colspan="3">Medecins correpondants</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><input tabindex="7" type="text" name="adresse" value="{$patient->adresse}" /></td>
        <th>
          <input type="hidden" name="medecin_traitant" value="{$patient->_ref_medecin_traitant->medecin_id}" />
          <label for="editFrm_medecin_traitant">Medecin traitant:</label>
        </th>
        <td class="readonly"><input type="text" name="_medecin_traitant_name" size="30" value="{if ($patient->_ref_medecin_traitant)}Dr. {$patient->_ref_medecin_traitant->nom} {$patient->_ref_medecin_traitant->prenom}{/if}" readonly="readonly" /></td>
        <td class="button"><input tabindex="26" type="button" value="choisir un medecin" onclick="popMed('_traitant')"></td>
      </tr>
      
      <tr>
        <th>Code Postal:</th>
        <td><input tabindex="8" type="text" name="cp" value="{$patient->cp}" /></td>
        <th>
          <input type="hidden" name="medecin1" value="{$patient->_ref_medecin1->medecin_id}" />
          <label for="editFrm_medecin1">Medecin correspondant 1:</label>
        </th>
        <td class="readonly"><input type="text" name="_medecin1_name" size="30" value="{if ($patient->_ref_medecin1)}Dr. {$patient->_ref_medecin1->nom} {$patient->_ref_medecin1->prenom}{/if}" readonly="readonly" /></td>
        <td class="button"><input tabindex="27" type="button" value="choisir un medecin" onclick="popMed('1')"></td>
      </tr>
      
      <tr>
        <th>Ville:</th>
        <td><input tabindex="9" type="text" name="ville" value="{$patient->ville}" /></td>
        <th>
          <input type="hidden" name="medecin2" value="{$patient->_ref_medecin2->medecin_id}" />
          <label for="editFrm_medecin2">Medecin correspondant 2:</label>
        </th>
        <td class="readonly"><input type="text" name="_medecin2_name" size="30" value="{if ($patient->_ref_medecin2)}Dr. {$patient->_ref_medecin2->nom} {$patient->_ref_medecin2->prenom}{/if}" readonly="readonly" /></td>
        <td class="button"><input tabindex="28" type="button" value="choisir un medecin" onclick="popMed('2')"></td>
      </tr>
      
      <tr>
        <th>Téléphone:</th>
        <td>
          <input tabindex="10" type="text" name="_tel1" size="2" maxlength="2" value="{$patient->_tel1}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel2.focus();{rdelim}" /> - 
          <input tabindex="11" type="text" name="_tel2" size="2" maxlength="2" value="{$patient->_tel2}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel3.focus();{rdelim}" /> -
          <input tabindex="12" type="text" name="_tel3" size="2" maxlength="2" value="{$patient->_tel3}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel4.focus();{rdelim}" /> -
          <input tabindex="13" type="text" name="_tel4" size="2" maxlength="2" value="{$patient->_tel4}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel5.focus();{rdelim}" /> -
          <input tabindex="14" type="text" name="_tel5" size="2" maxlength="2" value="{$patient->_tel5}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel21.focus();{rdelim}" />
        </td>
        <th>
          <input type="hidden" name="medecin3" value="{$patient->_ref_medecin3->medecin_id}" />
          <label for="editFrm_medecin3">Medecin correspondant 3:</label>
        </th>
        <td class="readonly"><input type="text" name="_medecin3_name" size="30" value="{if ($patient->_ref_medecin3)}Dr. {$patient->_ref_medecin3->nom} {$patient->_ref_medecin3->prenom}{/if}" readonly="readonly" /></td>
        <td class="button"><input tabindex="29" type="button" value="choisir un medecin" onclick="popMed('3')"></td>
      </tr>
      
      <tr>
        <th>Portable:</th>
        <td>
          <input tabindex="15" type="text" name="_tel21" size="2" maxlength="2" value="{$patient->_tel21}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel22.focus();{rdelim}" /> - 
          <input tabindex="16" type="text" name="_tel22" size="2" maxlength="2" value="{$patient->_tel22}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel23.focus();{rdelim}" /> -
          <input tabindex="17" type="text" name="_tel23" size="2" maxlength="2" value="{$patient->_tel23}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel24.focus();{rdelim}" /> -
          <input tabindex="18" type="text" name="_tel24" size="2" maxlength="2" value="{$patient->_tel24}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel25.focus();{rdelim}" /> -
          <input tabindex="19" type="text" name="_tel25" size="2" maxlength="2" value="{$patient->_tel25}" />
        </td>
        <th colspan="3"></th>
      </tr>
      
      <tr>
        <th>Remarques:</th>
        <td colspan="4">
          <textarea tabindex="30" name="rques">{$patient->rques}</textarea>
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="5">
          {if $patient->patient_id}
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'le patient', '{$patient->nom|escape:javascript} {$patient->prenom|escape:javascript}')"/>
          {else}
            <input tabindex="31" type="submit" value="Créer" />
          {/if}
        </td>
      </tr>
      
      </table>

      </form>

    </td>
  </tr>
</table>

{if $textSiblings}
<script type="text/javascript">
  confirmCreation({$created});
</script>
{/if}
