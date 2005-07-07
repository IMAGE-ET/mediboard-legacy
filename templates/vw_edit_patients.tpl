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
  {/literal}
  {if $dialog}
  {literal}
  else {
    window.location.href = 'index.php?m=dPpatients&a=pat_selector&dialog=1'
  }
  {/literal}
  {else}
  {literal}
  else {
    window.location.href = 'index.php?m=dPpatients&tab=vw_idx_patients&id=' + id + '&nom=&prenom='
  }
  {/literal}
  {/if}
  {literal}
}

function checkPatient() {
  var form = document.editFrm;
    
  if (form.nom.value.length == 0) {
    alert("Nom manquant");
    form.nom.focus();
    return false;
  }
    
  if (form.prenom.value.length == 0) {
    alert("Pr�nom manquant");
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

function delMed(type) {
  var f = document.editFrm;
  switch(type) {
    case '_traitant' : {
      f.medecin_traitant.value = '';
      f._medecin_traitant_name.value = '';
      window.medecin_traitant = '';
      window._medecin_traitant_name = '';
      break;
    }
    case '1' : {
      f.medecin1.value = '';
      f._medecin1_name.value = '';
      window.medecin1 = '';
      window._medecin1_name = '';
      break;
    }
    case '2' : {
      f.medecin2.value = '';
      f._medecin2_name.value = '';
      window.medecin2 = '';
      window._medecin2_name = '';
      break;
    }
    case '3' : {
      f.medecin3.value = '';
      f._medecin3_name.value = '';
      window.medecin3 = '';
      window._medecin3_name = '';
      break;
    }
  }
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
  {if $patient->patient_id}
  <tr>
    <td><strong><a href="index.php?m={$m}&amp;id=0">Cr�er un nouveau patient</a></strong></td>
  </tr>
  {/if}
  <tr>
    <td>

      <form name="editFrm" action="index.php?m={$m}" method="post" onsubmit="return checkPatient()">

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
        <th class="title" colspan="5">Cr�ation d'un dossier</th>
      {/if}
      </tr>

      <tr>
        <th class="category" colspan="2">Identit�</th>
        <th class="category" colspan="3">Informations m�dicales</th>
      </tr>
      
      <tr>
        <th class="mandatory">Nom:</th>
        <td><input tabindex="1" type="text" name="nom" value="{$patient->nom}" /></td>
        <th>Incapable majeur:</th>
        <td colspan="2">
          <input tabindex="21" type="radio" name="incapable_majeur" value="o" {if $patient->incapable_majeur == "o"} checked="checked" {/if} />oui
          <input tabindex="22" type="radio" name="incapable_majeur" value="n" {if $patient->incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th class="mandatory">Pr�nom:</th>
        <td><input tabindex="2" type="text" name="prenom" value="{$patient->prenom}" /></td>
        <th>ATNC:</th>
        <td colspan="2">
          <input tabindex="23" type="radio" name="ATNC" value="o" {if $patient->ATNC == "o"} checked="checked" {/if} />oui
          <input tabindex="24" type="radio" name="ATNC" value="n" {if $patient->ATNC == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th>Nom de jeune fille:</th>
        <td><input tabindex="3" type="text" name="nom_jeune_fille" value="{$patient->nom_jeune_fille}" /></td>
        <th>Num�ro d'assur� social:</th>
        <td colspan="2"><input tabindex="25" type="text" size="15" maxlength="15" name="matricule" value="{$patient->matricule}" /></td>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td>
          <input tabindex="4" type="text" name="_jour"  size="2" maxlength="2" value="{if $patient->_jour != "00"}{$patient->_jour}{/if}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._mois.focus();{rdelim}" /> -
          <input tabindex="5" type="text" name="_mois"  size="2" maxlength="2" value="{if $patient->_mois != "00"}{$patient->_mois}{/if}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._annee.focus();{rdelim}" /> -
          <input tabindex="6" type="text" name="_annee" size="4" maxlength="4" value="{if $patient->_annee != "0000"}{$patient->_annee}{/if}" />
        </td>
        <th>Code administratif:</th>
        <td colspan="2"><input tabindex="26" type="text" name="SHS" value="{$patient->SHS}" /></td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <select tabindex="7" name="sexe">
            <option value="m" {if $patient->sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient->sexe == "f"} selected="selected" {/if}>f�minin</option>
            <option value="j" {if $patient->sexe == "j"} selected="selected" {/if}>femme c�libataire</option>
          </select>
        </td>
        <td colspan="3"></td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonn�es</th>
        <th class="category" colspan="3">Medecins correpondants</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><textarea tabindex="8" name="adresse" rows="1">{$patient->adresse}</textarea></td>
        <th>
          <input type="hidden" name="medecin_traitant" value="{$patient->_ref_medecin_traitant->medecin_id}" />
          <label for="editFrm_medecin_traitant">Medecin traitant:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin_traitant_name" size="30" value="{if ($patient->_ref_medecin_traitant)}Dr. {$patient->_ref_medecin_traitant->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('_traitant')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="27" type="button" value="choisir un medecin" onclick="popMed('_traitant')"></td>
      </tr>
      
      <tr>
        <th>Code Postal:</th>
        <td><input tabindex="9" type="text" name="cp" value="{$patient->cp}" /></td>
        <th>
          <input type="hidden" name="medecin1" value="{$patient->_ref_medecin1->medecin_id}" />
          <label for="editFrm_medecin1">Medecin correspondant 1:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin1_name" size="30" value="{if ($patient->_ref_medecin1)}Dr. {$patient->_ref_medecin1->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('1')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="28" type="button" value="choisir un medecin" onclick="popMed('1')"></td>
      </tr>
      
      <tr>
        <th>Ville:</th>
        <td><input tabindex="10" type="text" name="ville" value="{$patient->ville}" /></td>
        <th>
          <input type="hidden" name="medecin2" value="{$patient->_ref_medecin2->medecin_id}" />
          <label for="editFrm_medecin2">Medecin correspondant 2:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin2_name" size="30" value="{if ($patient->_ref_medecin2)}Dr. {$patient->_ref_medecin2->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('2')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="29" type="button" value="choisir un medecin" onclick="popMed('2')"></td>
      </tr>
      
      <tr>
        <th>T�l�phone:</th>
        <td>
          <input tabindex="11" type="text" name="_tel1" size="2" maxlength="2" value="{$patient->_tel1}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel2.focus();{rdelim}" /> - 
          <input tabindex="12" type="text" name="_tel2" size="2" maxlength="2" value="{$patient->_tel2}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel3.focus();{rdelim}" /> -
          <input tabindex="13" type="text" name="_tel3" size="2" maxlength="2" value="{$patient->_tel3}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel4.focus();{rdelim}" /> -
          <input tabindex="14" type="text" name="_tel4" size="2" maxlength="2" value="{$patient->_tel4}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel5.focus();{rdelim}" /> -
          <input tabindex="15" type="text" name="_tel5" size="2" maxlength="2" value="{$patient->_tel5}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel21.focus();{rdelim}" />
        </td>
        <th>
          <input type="hidden" name="medecin3" value="{$patient->_ref_medecin3->medecin_id}" />
          <label for="editFrm_medecin3">Medecin correspondant 3:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin3_name" size="30" value="{if ($patient->_ref_medecin3)}Dr. {$patient->_ref_medecin3->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('3')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="30" type="button" value="choisir un medecin" onclick="popMed('3')"></td>
      </tr>
      
      <tr>
        <th>Portable:</th>
        <td>
          <input tabindex="16" type="text" name="_tel21" size="2" maxlength="2" value="{$patient->_tel21}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel22.focus();{rdelim}" /> - 
          <input tabindex="17" type="text" name="_tel22" size="2" maxlength="2" value="{$patient->_tel22}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel23.focus();{rdelim}" /> -
          <input tabindex="18" type="text" name="_tel23" size="2" maxlength="2" value="{$patient->_tel23}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel24.focus();{rdelim}" /> -
          <input tabindex="19" type="text" name="_tel24" size="2" maxlength="2" value="{$patient->_tel24}" onKeyup="if(this.value.length == 2){ldelim}document.editFrm._tel25.focus();{rdelim}" /> -
          <input tabindex="20" type="text" name="_tel25" size="2" maxlength="2" value="{$patient->_tel25}" />
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
            <input type="reset" value="R�initialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'le patient', '{$patient->_view|escape:javascript}')"/>
            <input type="button" value="Imprimer" onclick="printPatient({$patient->patient_id})" />
          {else}
            <input tabindex="32" type="submit" value="Cr�er" />
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
