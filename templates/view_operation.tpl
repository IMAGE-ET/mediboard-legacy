<table class="main">
  <tr>
    <td>
	
      <table class="form">
        <tr><th class="category" colspan="2">Informations concernant l'opération</th></tr>
        <tr>
		      <th class="mandatory">Chirurgien:</th>
          <td>{$op.chir_name}</td>
        </tr>
        <tr>
          <th class="mandatory">Patient:</th>
          <td>{$op.pat_name}</td>
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
          <th class="mandatory">Temps opératoire:</th>
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
			{else if $op.type_adm == "ambu"}
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
          <td>
			<form name="editFrm{$curr_op.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="dPbloc" />
            <input type="hidden" name="a" value="do_order_op" />
            <input type="hidden" name="cmd" value="modrques" />
            <input type="hidden" name="id" value="{$op.id}" />
		    <textarea name="rques" cols="30" rows="3">{$op.rques}</textarea>
			<br />
			<input type="submit" value="modifier" />
			</form>
	      </td>
        </tr>

      </table>
    
    </td>
  </tr>

</table>

</form>
