<script language="javascript">
function setClose() {ldelim}
  window.opener.setProtocole(
    "{$chirSel->user_id}",
    "{$chirSel->user_last_name}",
    "{$chirSel->user_first_name}",
    "{$protSel->CCAM_code}",
    "{$protSel->_hour_op}",
    "{$protSel->_min_op}",
    "{$protSel->examen|escape:javascript}",
    "{$protSel->materiel|escape:javascript}",
    "{$protSel->type_adm}",
    "{$protSel->duree_hospi}");
  window.close();
{rdelim}
</script>

<table class="main">
  <tr>
    <td colspan="2">

      <form name="selectFrm" action="index.php" method="get">
      
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" {if $dialog} name="a" {else} name="tab" {/if} value="vw_protocoles" />
      <input type="hidden" name="dialog" value="{$dialog}" />

      <table>
        <tr>
          <td style="white-space: nowrap;">
            Choisir un chirurgien :
            <select name="chir_id" onchange="this.form.submit()">
              <option value="" >Tous les chirurgiens</option>
              {foreach item=curr_chir from=$chirs}
              <option value="{$curr_chir.chir_id}" {if $chir_id == $curr_chir.chir_id} selected="selected" {/if}>
                Dr. {$curr_chir.lastname} {$curr_chir.firstname} ({$curr_chir.nb_protocoles})
              </option>
              {/foreach}
            </select>
          </td>
          <td style="white-space: nowrap;">
            Choisir un code CCAM :
            <select name="CCAM_code" onchange="this.form.submit()">
              <option value="" >Tous les codes</option>
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
    <td class="halfPane">

      <table class="tbl">
        <tr>
          <th>Chirurgien &mdash; Acte CCAM</th>
        </tr>
        
        {foreach item=curr_protocole from=$protocoles}
        <tr>    
          <td>
            <a href="?m={$m}&amp;{if $dialog}a=vw_protocoles&amp;dialog=1{else}tab={$tab}{/if}&amp;protocole_id={$curr_protocole.operation_id}">
              <strong>Dr. {$curr_protocole.lastname} {$curr_protocole.firstname} &mdash; {$curr_protocole.CCAM_code}</strong>
            </a>
            <br />{$curr_protocole.CCAM_libelle}
          </td>
        </tr>
        {/foreach}

      </table>

    </td>
    <td class="halfPane">

      {if $protSel}
      <table class="form">
        <tr>
          <th class="category" colspan="2">Détails du protocole</th>
        </tr>

        <tr>
          <th>Chirurgien:</th>
          <td colspan="3"><strong>Dr. {$chirSel->user_last_name} {$chirSel->user_first_name}</strong></td>
        </tr>
        
        <tr>
          <th>Code CCAM:</th>
          <td class="text"><strong>{$ccamSel.CODE}</strong><br />{$ccamSel.LIBELLELONG}</td>
        </tr>
        
        <tr>
          <th>Temps opératoire</th>
          <td>{$protSel->_hour_op}h{if $protSel->_min_op}{$protSel->_min_op}{/if}</td>
        </tr>
        
        {if $protSel->examen}
        <tr>
          <th class="text" colspan="2">Examens complémentaires</th>
        </tr>
                 
        <tr>
          <td class="text" colspan="2">{$protSel->examen|nl2br}</td>
        </tr>
        {/if}
        
        {if $protSel->materiel}
        <tr>
          <th class="text" colspan="2">Matériel à prévoir</th>
        </tr>
                 
        <tr>
          <td class="text" colspan="2">{$protSel->materiel|nl2br}</td>
        </tr>
        {/if}

        <tr>
          <th class="category" colspan="2">Détails de l'hospitalisation</th>
        </tr>
        
        <tr>
          <th>Admission en:</th>
          <td>
            {if $protSel->type_adm == "comp"} Hospitalisation complète{/if}
            {if $protSel->type_adm == "ambu"} Ambulatoire{/if}
            {if $protSel->type_adm == "exte"} Externe{/if}
          </td>
        </tr>

        <tr>
          <th>Durée d'hospitalisation:</th>
          <td>{$protSel->duree_hospi} jours</td>
        </tr>
  
        {if $dialog}
          <tr>
            <td class="button" colspan="3">
              <input type="button" value="Sélectionner ce protocole" onclick="setClose()" />
            </td>
        {else}
          {if $canEdit}
          <tr>
            <td class="button" colspan="2">
              <form name="modif" action="./index.php" method="get">
              
              <input type="hidden" name="m" value="{$m}" />
              <input type="hidden" name="tab" value="vw_edit_protocole" />
              <input type="hidden" name="protocole_id" value="{$protSel->operation_id}" />
              <input type="submit" value="Modifier" />
              
              </form>
            </td>
          </tr>
          {/if}
        {/if}
      
      </table>
      
      {/if} 
     </td>
  </tr>
</table>

