<table class="main">
  <tr>
  <td>
  
  
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
          <th class="category" colspan="2">Information médicales</th>
        </tr>

        <tr>
          <th>Nom:</th>
          <td>{$op.pat_lastname}</td>
          <th>Incapable majeur:</th>
          <td>
            {if $op.incapable_majeur == "o"} oui {/if}
            {if $op.incapable_majeur == "n"} non {/if}
          </td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td>{$op.pat_firstname}</td>
          <th>ATNC:</th>
          <td>
            {if $op.ATNC == "o"} oui {/if}
            {if $op.ATNC == "n"} non {/if}
        </tr>
        
        <tr>
          <th>Date de naissance:</th>
          <td>{$op.dateFormed}</td>
        </tr>
        
        <tr>
          <th>Sexe:</th>
          <td>
            {if $op.sexe == "m"} masculin {/if}
            {if $op.sexe == "f"} féminin  {/if} 
          </td>
        </tr>
        
        <tr>
          <th class="category" colspan="2">Coordonnées</th>
          <th class="category" colspan="2">Information administratives</th>
        </tr>
        
        <tr>
          <th>Adresse:</th>
          <td>{$op.adresse}</td>
          <th>Numéro d'assuré social:</th>
          <td>{$op.matricule}</td>
        </tr>
        
        <tr>
          <th>Ville:</th>
          <td>{$op.ville}</td>
          <th>Code administratif:</th>
          <td>{$op.SHS}</td>
        </tr>
        
        <tr>
          <th>Code Postal:</th>
          <td>{$op.cp}</td>
        </tr>
        
        <tr>
          <th>Téléphone:</th>
          <td>{$op.tel}</td>
        </tr>

      </table>
  
  </td>
    <td>
	
      <table class="form">
        <tr><th class="category" colspan="2">Informations concernant l'opération</th></tr>
        <tr>
		      <th>Chirurgien:</th>
          <td>{$op.chir_name}</td>
        </tr>
        <tr>
          <th>Diagnostic (CIM10):</th>
          <td>{$op.CIM10_code}</td>
        </tr>
        <tr>
          <th>Code CCAM:</th>
          <td>{$op.CCAM_code}</td>
        </tr>
        <tr>
          <th>Temps opératoire:</th>
          <td colspan="2">{$op.hour_op}:{$op.min_op}</td>
        </tr>
        <tr>
          <th class="mandatory">Date de l'intervention:</th>
          <td>{$op.date_op}</td>
        </tr>
        <tr>
          <th>Examens complémentaires:</th>
          <td colspan="2">{$op.examen}</td>
        </tr>
        <tr>
          <th>Materiel à prévoir:</th>
          <td colspan="2">{$op.materiel}</td>
        </tr>
        <tr>
          <th>Information du patient:</th>
          <td  colspan="2">
		    {if $op.info == "o"}
			Oui
			{else}
			Non
			{/if}
          </td>
        </tr>
      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">RDV d'anesthésie</th></tr>
        <tr>
          <th>Date:</th>
          <td>{$op.rdv_anesth}</td>
        </tr>
        <tr>
          <th>Heure:</th>
          <td>{$op.hour_anesth}:{$op.min_anesth}</td>
        </tr>
        <tr><th class="category" colspan="3">Admission</th></tr>
        <tr>
          <th>Date:</th>
          <td>{$op.rdv_adm}</td>
        </tr>
        <tr>
          <th>Heure:</th>
          <td>{$op.hour_adm}:{$op.min_adm}</td>
        </tr>
        <tr>
          <th>Durée d'hospitalisation:</th>
          <td>{$op.duree_hospi} jours</td>
        </tr>
        <tr>
          <th>Admission en:</th>
          <td>
		    {if $op.type_adm == "comp"}
			hospitalisation complète
			{elseif $op.type_adm == "ambu"}
			Ambulatoire
			{else}
			Externe
			{/if}
          </td>
        </tr>
        <tr>
          <th>Chambre particulière:</th>
          <td>
		    {if $op.chambre == "o"}
            Oui
            {else}
            Non
			{/if}
          </td>
        </tr>
        <tr><th class="category" colspan="3">Autre</th></tr>
        <tr>
          <th>Risque ATNC:</th>
          <td>
		    {if $op.ATNC == "o"}
            Oui
			{else}
            Non
			{/if}
          </td>
        </tr>
        <tr>
          <th>Remarques:</th>
          <td>{$op.rques}</td>
        </tr>

      </table>
    
    </td>
  </tr>

</table>

</form>
