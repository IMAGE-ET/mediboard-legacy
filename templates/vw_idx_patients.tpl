<!-- $Id$ -->

{literal}
<script language="javascript">
function checkPatient() {
  var form = document.editFrm;
    
  if (form.nom.value.length == 0) {
    alert("Nom manquant");
    form.nom.focus();
    return false;
  }
    
  return true;
}
</script>
{/literal}

<table class="main">
  <tr>
    <td class="greedyPane">
    
      <form name="find" action="./index.php" method="get">
      <input type="hidden" name="m" value="dPpatients" />
      
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
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
          <th>Nom</th>
          <th>Prénom</th>
          <th>Adresse</th>
          <th>Ville</th>
        </tr>

        {foreach from=$patients item=curr_patient}
        <tr>
          <td><a href="index.php?m={$m}&tab=0&id={$curr_patient.patient_id}">{$curr_patient.nom}</a></td>
          <td><a href="index.php?m={$m}&tab=0&id={$curr_patient.patient_id}">{$curr_patient.prenom}</a></td>
          <td><a href="index.php?m={$m}&tab=0&id={$curr_patient.patient_id}">{$curr_patient.adresse}</a></td>
          <td><a href="index.php?m={$m}&tab=0&id={$curr_patient.patient_id}">{$curr_patient.ville}</a></td>
        </tr>
        {/foreach}
        
      </table>

    </td>
 
    {if $patient != ""}
    <td class="greedyPane">
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
          <th class="category" colspan="2">Information médicales</th>
        </tr>

        <tr>
          <th>Nom:</th>
          <td>{$patient.nom}</td>
          <th>Incapable majeur:</th>
          <td>
            {if $patient.incapable_majeur == "o"} oui {/if}
            {if $patient.incapable_majeur == "n"} non {/if}
          </td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td>{$patient.prenom}</td>
          <th>ATNC:</th>
          <td>
            {if $patient.ATNC == "o"} oui {/if}
            {if $patient.ATNC == "n"} non {/if}
        </tr>
        
        <tr>
          <th>Date de naissance:</th>
          <td>{$patient.dateFormed}</td>
        </tr>
        
        <tr>
          <th>Sexe:</th>
          <td>
            {if $patient.sexe == "m"} masculin {/if}
            {if $patient.sexe == "f"} féminin  {/if} 
          </td>
        </tr>
        
        <tr>
          <th class="category" colspan="2">Coordonnées</th>
          <th class="category" colspan="2">Information administratives</th>
        </tr>
        
        <tr>
          <th>Adresse:</th>
          <td>{$patient.adresse}</td>
          <th>Numéro d'assuré social:</th>
          <td>{$patient.matricule}</td>
        </tr>
        
        <tr>
          <th>Ville:</th>
          <td>{$patient.ville}</td>
          <th>Code administratif:</th>
          <td>{$patient.SHS}</td>
        </tr>
        
        <tr>
          <th>Code Postal:</th>
          <td>{$patient.cp}</td>
        </tr>
        
        <tr>
          <th>Téléphone:</th>
          <td>{$patient.tel}</td>
        </tr>
        
        {if $canEdit}
        <tr>
          <td class="button" colspan="4">
            <form name="modif" action="./index.php" method="get">
            <input type="hidden" name="m" value="dPpatients" />
            <input type="hidden" name="tab" value="1" />
            <input type="hidden" name="id" value="{$patient.patient_id}" />
            <input type="submit" value="Modifier" />
            </form>
          </td>
        </tr>
        {/if}

      </table>
      
    </td>
    {/if}

  </tr>
</table>
      