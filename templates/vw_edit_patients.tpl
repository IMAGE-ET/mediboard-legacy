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
        <th class="category" colspan="2">Information médicales</th>
      </tr>
      
      <tr>
        <th class="mandatory">Nom:</th>
        <td><input tabindex="1" type="text" name="nom" value="{$patient->nom}" /></td>
        <th>Incapable majeur:</th>
        <td>
          <input tabindex="15" type="radio" name="incapable_majeur" value="o" {if $patient->incapable_majeur == "o"} checked="checked" {/if} />oui
          <input tabindex="16" type="radio" name="incapable_majeur" value="n" {if $patient->incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th class="mandatory">Prénom:</th>
        <td><input tabindex="2" type="text" name="prenom" value="{$patient->prenom}" /></td>
        <th>ATNC:</th>
        <td>
          <input tabindex="17" type="radio" name="ATNC" value="o" {if $patient->ATNC == "o"} checked="checked" {/if} />oui
          <input tabindex="18" type="radio" name="ATNC" value="n" {if $patient->ATNC == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td>
          <input tabindex="3" type="text" name="_jour"  size="1" maxlength="2" value="{$patient->_jour}" /> -
          <input tabindex="4" type="text" name="_mois"  size="1" maxlength="2" value="{$patient->_mois}" /> -
          <input tabindex="5" type="text" name="_annee" size="2" maxlength="4" value="{$patient->_annee}" />
        </td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <select tabindex="6" name="sexe">
            <option value="m" {if $patient->sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient->sexe == "f"} selected="selected" {/if}>féminin</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonnées</th>
        <th class="category" colspan="2">Information administratives</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><input tabindex="7" type="text" name="adresse" value="{$patient->adresse}" /></td>
        
        <th>Numéro d'assuré social:</th>
        <td><input tabindex="19" type="text" size="15" maxlength="15" name="matricule" value="{$patient->matricule}" /></td>
      </tr>
      
      <tr>
        <th>Code Postal:</th>
        <td><input tabindex="8" type="text" name="cp" value="{$patient->cp}" /></td>
        <th>Code administratif:</th>
        <td><input tabindex="20" type="text" name="SHS" value="{$patient->SHS}" /></td>
      </tr>
      
      <tr>
        <th>Ville:</th>
        <td><input tabindex="9" type="text" name="ville" value="{$patient->ville}" /></td>
      </tr>
      
      <tr>
        <th>Téléphone:</th>
        <td>
          <input tabindex="10" type="text" name="_tel1" size="1" maxlength="2" value="{$patient->_tel1}" /> - 
          <input tabindex="11" type="text" name="_tel2" size="1" maxlength="2" value="{$patient->_tel2}" /> -
          <input tabindex="12" type="text" name="_tel3" size="1" maxlength="2" value="{$patient->_tel3}" /> -
          <input tabindex="13" type="text" name="_tel4" size="1" maxlength="2" value="{$patient->_tel4}" /> -
          <input tabindex="14" type="text" name="_tel5" size="1" maxlength="2" value="{$patient->_tel5}" />
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="4">
          {if $patient->patient_id}
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
          {else}
            <input type="submit" value="Créer" />
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
  confirmCreation();
</script>
{/if}
