<!-- $Id$ -->

<script language="javascript">
function setClose(user_id,
                  user_last_name,
                  user_first_name,
                  CCAM_code,
                  _hour_op,
                  _min_op,
                  examen,
                  materiel,
                  convalescence,
                  depassement,
                  type_adm,
                  duree_hospi,
                  rques) {ldelim}
  window.opener.setProtocole(user_id,
                             user_last_name,
                             user_first_name,
                             CCAM_code,
                             _hour_op,
                             _min_op,
                             examen,
                             materiel,
                             convalescence,
                             depassement,
                             type_adm,
                             duree_hospi,
                             rques)
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

      <table class="form">
        <tr>
          <th>Chirurgien:</th>
          <td>
            <select name="chir_id" onchange="this.form.submit()">
              <option value="" >Tous les chirurgiens</option>
              {foreach item=curr_chir from=$chirs}
              <option value="{$curr_chir.chir_id}" {if $chir_id == $curr_chir.chir_id} selected="selected" {/if}>
                {$curr_chir.lastname} {$curr_chir.firstname} ({$curr_chir.nb_protocoles})
              </option>
              {/foreach}
            </select>
          </td>
          <th>Code CCAM:</th>
          <td>
            <select name="CCAM_code" onchange="this.form.submit()">
              <option value="" >&mdash; Tous les codes &mdash;</option>
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
    {if $dialog}
    <td class="greedyPane">
    {else}
    <td class="halfPane">
    {/if}

      <table class="tbl">
        <tr>
          <th>Chirurgien &mdash; Acte CCAM</th>
        </tr>
        
        {foreach item=curr_protocole from=$protocoles}
        <tr>    
          <td class="text">
            {if $dialog}
            <a href="#" onclick="setClose('{$curr_protocole->_ref_chir->user_id}',
                                '{$curr_protocole->_ref_chir->user_last_name|escape:javascript}',
                                '{$curr_protocole->_ref_chir->user_first_name|escape:javascript}',
                                '{$curr_protocole->CCAM_code}',
                                '{$curr_protocole->_hour_op}',
                                '{$curr_protocole->_min_op}',
                                '{$curr_protocole->examen|escape:javascript}',
                                '{$curr_protocole->materiel|escape:javascript}',
                                '{$curr_protocole->convalescence|escape:javascript}',
                                '{$curr_protocole->depassement}',
                                '{$curr_protocole->type_adm}',
                                '{$curr_protocole->duree_hospi}',
                                '{$curr_protocole->rques|escape:javascript}')">
            {else}
            <a href="?m={$m}&amp;{if $dialog}a=vw_protocoles&amp;dialog=1{else}tab={$tab}{/if}&amp;protocole_id={$curr_protocole->operation_id}">
            {/if}
              <strong>{$curr_protocole->_ref_chir->user_last_name} {$curr_protocole->_ref_chir->user_first_name} &mdash; {$curr_protocole->_ext_code_ccam->code}</strong>
            </a>
            {$curr_protocole->_ext_code_ccam->libelleLong}
          </td>
        </tr>
        {/foreach}

      </table>

    </td>
    <td class="halfPane">

      {if $protSel && !$dialog}
      <table class="form">
        <tr>
          <th class="category" colspan="2">Détails du protocole</th>
        </tr>

        <tr>
          <th>Chirurgien:</th>
          <td colspan="3"><strong>{$protSel->_ref_chir->user_last_name} {$protSel->_ref_chir->user_first_name}</strong></td>
        </tr>
        
        <tr>
          <th>Code CCAM:</th>
          <td class="text"><strong>{$protSel->_ext_code_ccam->code}</strong><br />{$protSel->_ext_code_ccam->libelleLong}</td>
        </tr>
        
        <tr>
          <th>Temps opératoire</th>
          <td>{$protSel->_hour_op}h{if $protSel->_min_op}{$protSel->_min_op}{/if}</td>
        </tr>
        
		{if $protSel->depassement}
        <tr>	
          <th>Dépassement d'honoraire:</th>
          <td>{$protSel->depassement}€</td>
		<tr>
		{/if}

        {if $protSel->examen}
        <tr>
          <th class="text" colspan="2">Bilan Pré-op</th>
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
        
        {if $protSel->convalescence}
        <tr>
          <th class="text" colspan="2">Convalescence</th>
        </tr>
                 
        <tr>
          <td class="text" colspan="2">{$protSel->convalescence|nl2br}</td>
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
  
        {if $protSel->rques}
        <tr>
          <th class="text" colspan="2">Remarques</th>
        </tr>
                 
        <tr>
          <td class="text" colspan="2">{$protSel->rques|nl2br}</td>
        </tr>
        {/if}

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
      
      </table>
      
      {/if} 
     </td>
  </tr>
</table>

