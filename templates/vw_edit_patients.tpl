<table class="main">
  <tr>
    <td>

      <form name="editFrm" action="./index.php?m=dPpatients" method="post">

      <input type="hidden" name="dosql" value="do_patients_aed">
      <input type="hidden" name="del" value="0">
      <input type="hidden" name="patient_id" value="{$patient.patient_id}">
      
      <table class="form">
      
      <tr>
        <th class="category" colspan="2">Identit�</th>
        <th class="category" colspan="2">Information m�dicales</th>
      </tr>
      
      <tr>
        <th class="mandatory">Nom:</th>
        <td><input type="text" name="nom" value="{$patient.nom}" /></td>
        <th>Incapable majeur:</th>
        <td>
          <input type="radio" name="incapable_majeur" value="o" {if $patient.incapable_majeur == "o"} checked="checked" {/if} />oui
          <input type="radio" name="incapable_majeur" value="n" {if $patient.incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th class="mandatory">Pr�nom:</th>
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
          <input type="text" name="_jour"  size="1" value="{$patient.naissance|date_format:"%d"}" /> -
          <input type="text" name="_mois"  size="1" value="{$patient.naissance|date_format:"%m"}" /> -
          <input type="text" name="_annee" size="2" value="{$patient.naissance|date_format:"%Y"}" />
        </td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <select name="sexe">
            <option value="m" {if $patient.sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient.sexe == "f"} selected="selected" {/if}>f�minin</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonn�es</th>
        <th class="category" colspan="2">Information administratives</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><input type="text" name="adresse" value="{$patient.adresse}" /></td>
        
        <th  class="mandatory">Num�ro d'assur� social:</th>
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
        <th>T�l�phone:</td>
        <td>
          <input type="text" name="_tel1" size="1" value="{$patient.tel.0}{$patient.tel.1}"> - 
          <input type="text" name="_tel2" size="1" value="{$patient.tel.2}{$patient.tel.3}"> -
          <input type="text" name="_tel3" size="1" value="{$patient.tel.4}{$patient.tel.5}"> -
          <input type="text" name="_tel4" size="1" value="{$patient.tel.6}{$patient.tel.7}"> -
          <input type="text" name="_tel5" size="1" value="{$patient.tel.8}{$patient.tel.9}">
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="4">
          {if $patient}
            <input type="reset" value="R�initialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
          {else}
            <input type="submit" value="Cr�er" />
          {/if}
          </form>
        </td>
      </tr>
      
      </table>

    </td>
  </tr>
</table>
