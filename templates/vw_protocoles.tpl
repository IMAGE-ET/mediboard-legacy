<table class="main">
  <tr>
    <td colspan="2">

      <form name="selectFrm" action="" method="get">
      
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="3" />

      <table class="form">
        <tr>
          <th>Choisir un chirurgien</th>
          <td>
            <select name="chir_id" onchange="this.form.submit()">
              <option value="" >-- Tous les chirurgiens</option>
              {foreach item=curr_chir from=$chirs}
              <option value="{$curr_chir.chir_id}" {if $chir_id == $curr_chir.chir_id} selected="selected" {/if}>
                Dr. {$curr_chir.lastname} {$curr_chir.firstname} ({$curr_chir.nb_protocoles})
              </option>
              {/foreach}
            </select>
          </td>
                
          <th>Choisir un code CCAM</th>
          <td>
            <select name="CCAM_code" onchange="this.form.submit()">
              <option value="" >-- Tous les codes</option>
              {foreach item=curr_code from=$codes}
              <option value="{$curr_code.CCAM_code}" {if $CCAM_code == $curr_code.CCAM_code} selected="selected" {/if}>
                {$curr_code.CCAM_code} ({$curr_code.nb_protocoles})
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
      </table>
      </form>    
      
    </td>
  </tr>

  <tr>
    <td style="width: 50%;">

      <table class="tbl">
        <tr>
          <th>Chirurgien</th>
          <th>Acte CCAM</th>
        </tr>
        
        {foreach item=curr_protocole from=$protocoles}
        <tr>    
          <td style="white-space: nowrap"><a href="?m={$m}&amp;tab=3&amp;protocole_id={$curr_protocole.operation_id}">Dr. {$curr_protocole.lastname} {$curr_protocole.firstname}</a></td>
          <td><strong>{$curr_protocole.CCAM_code}</strong><br />{$curr_protocole.CCAM_libelle}</td>
        </tr>
        {/foreach}
      </table>

    </td>
    <td style="width: 50%;">

      {if $protSel}
      <table class="form">
        <tr>
          <th class="category" colspan="4">Détails du protocole</th>
        </tr>

        <tr>
          <th>Chirurgien:</th>
          <td colspan="3"><strong>Dr. {$chirSel->user_last_name} {$chirSel->user_first_name}</strong></td>
        </tr>
        
        <tr>
          <th>Code CCAM:</th>
          <td class="text" colspan="3"><strong>{$ccamSel.CODE}</strong><br />{$ccamSel.LIBELLELONG}</td>
        </tr>
        
        <tr>
          <th>Côté:</th>
          <td>{$protSel->cote}</td>
          <th>Temps opératoire</th>
          <td>{$protSel->_hour_op}:{$protSel->_min_op}</td>
        </tr>
        
        {if $protSel->examen}
        <tr>
          <th class="text" colspan="4">Examens complémentaires</th>
        </tr>
                 
        <tr>
          <td class="text" colspan="4">{$protSel->examen}</td>
        </tr>
        {/if}
        
        <tr>
          <th class="category" colspan="4">Détails de l'hospitalisation</th>
        </tr>
        
        <tr>
          <th>Admission en:</th>
          <td>
            {if $protSel->type_adm == "comp"} Hospitalisation complète{/if}
            {if $protSel->type_adm == "ambu"} Ambulatoire{/if}
			      {if $protSel->type_adm == "exte"} Externe{/if}
          </td>
          <th>Durée d'hospitalisation:</th>
          <td>{$protSel->duree_hospi} jours</td>
        </tr>
  
        {if $canEdit}
        <tr>
          <td class="button" colspan="4">
            <form name="modif" action="./index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="tab" value="5" />
            <input type="hidden" name="protocole_id" value="{$protSel->operation_id}" />
            <input type="submit" value="Modifier" />
            </form>
          </td>
        </tr>
        {/if}
      
      </table>
      
      {/if} 
     </td>
  </tr>
</table>

