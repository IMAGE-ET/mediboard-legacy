<table class="main">
  <tr>
    <td>
      <form name="editFrm" action="./index.php?m=dPpatients" method="post">

      <input type="hidden" name="dosql" value="do_patients_aed">
      <input type="hidden" name="del" value="0">
      <input type="hidden" name="patient_id" value="{$patient.patient_id}">
      
      <table class="form">
      
      <tr>
        <th class="category" colspan="2">Identité</th>
        <th class="category" colspan="2">Information médicales</th>
      </tr>
      
      <tr>
        <th>Nom:</th>
        <td><input type="text" name="nom" value="{$patient.nom}" /></td>
        <th>Incapable majeur:</th>
        <td>
          <input type="radio" name="incapable_majeur" value="o" {if $patient.incapable_majeur == "o"} checked="checked" {/if} />oui
          <input type="radio" name="incapable_majeur" value="n" {if $patient.incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th>Prénom:</th>
        <td><input type="text" name="prenom" value="{$patient.prenom}" /></td>
        <th>ATNC:</th>
        <td>
          <input type="radio" name="ATNC" value="o" {if $patient.ATNC == "o"} checked="checked" {/if} />oui
          <input type="radio" name="ATNC" value="n" {if $patient.ATNC == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td>
          <input type="text" name="jour"  size="1" value="{$patient.naissance|date_format:"%d"}" /> -
          <input type="text" name="mois"  size="1" value="{$patient.naissance|date_format:"%m"}" /> -
          <input type="text" name="annee" size="2" value="{$patient.naissance|date_format:"%Y"}" />
        </td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <select name="sexe">
            <option value="m" {if $patient.sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient.sexe == "f"} selected="selected" {/if}>féminin</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonnées</th>
        <th class="category" colspan="2">Information administratives</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><input type="text" name="adresse" value="{$patient.adresse}" /></td>
        <th>Numéro d'assuré social:</th>
        <td><input type="text" name="matricule" value="{$patient.matricule}" /></td>
      </tr>
      
      <tr>
        <th>Ville:</th>
        <td><input type="text" name="ville" value="{$patient.ville}" /></td>
        <th>Code administratif:</th>
        <td><input type="text" name="SHS" value="{$patient.SHS}" /></td>
      </tr>
      
      <tr>
        <th>Code Postal:</td>
        <td><input type="text" name="cp" value="{$patient.cp}" /></td>
      </tr>
      
      <tr>
        <th>Téléphone:</td>
        <td>
          <input type="text" name="tel1" size=1 value="{$patient.tel.0}{$patient.tel.1}"> - 
          <input type="text" name="tel2" size=1 value="{$patient.tel.2}{$patient.tel.3}"> -
          <input type="text" name="tel3" size=1 value="{$patient.tel.4}{$patient.tel.5}"> -
          <input type="text" name="tel4" size=1 value="{$patient.tel.6}{$patient.tel.7}"> -
          <input type="text" name="tel5" size=1 value="{$patient.tel.8}{$patient.tel.9}">
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="2">
          {if $patient}
            <input type="reset" value="réinitialiser" />
            <input type="submit" value="modifier" />
          {else}
            <input type="submit" value="créer" />
          {/if}
          </form>
        </td>
      
      {if $patient}
        <td class="button" colspan="2" />
          <form name="editFrm" action="./index.php?m=dPpatients" method="post">
      
          <input type="hidden" name="dosql" value="do_patients_aed" />
          <input type="hidden" name="del" value="1" />
          <input type="hidden" name="patient_id" value="{$patient.patient_id}" />
          <input type="submit" value="supprimer" />
        
          </form>
        </td>
      {/if}
      
      </tr>
      
      </table>

    </td>
    
    
  </tr>

</table>
