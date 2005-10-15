{literal}
<script type="text/javascript">

function pageMain() {
  {/literal}
  regRedirectPopupCal("{$date}", "index.php?m={$m}&tab={$tab}&date=");
  {literal}
}

</script>
{/literal}

<table class="main">
  <tr>
    <td>
      <table>
        <tr>
          <th>
            {$date|date_format:"%A %d %B %Y"}
            <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
          
            <form action="index.php" target="_self" name="selection" method="get">

            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="tab" value="{$tab}" />
            Choisir une salle :

            <select name="salle" onchange="this.form.submit()">
              <option value="0">Aucune salle</option>
              {foreach from=$listSalles item=curr_salle}
              <option value="{$curr_salle->id}" {if $curr_salle->id == $salle} selected="selected" {/if}>
                {$curr_salle->nom}
              </option>
              {/foreach}
            </select>

            </form>
            <hr />
          </th>
        </tr>

        {foreach from=$plages item=curr_plage}
        <tr>
          <td>
            <strong>
            <a href="?m=dPbloc&amp;tab=vw_edit_interventions&amp;id={$curr_plage->id}" title="Administrer la plage">
              Plage du Dr. {$curr_plage->_ref_chir->_view}
              de {$curr_plage->debut|date_format:"%Hh%M"} à {$curr_plage->fin|date_format:"%Hh%M"}
            </a>
            </strong>

            <form name="anesth{$curr_plage->id}" action="index.php" method="post">

            <input type="hidden" name="m" value="dPbloc" />
            <input type="hidden" name="tab" value="{$tab}" />
            <input type="hidden" name="otherm" value="{$m}" />
            <input type="hidden" name="dosql" value="do_plagesop_aed" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="_repeat" value="1" />
            <input type="hidden" name="id" value="{$curr_plage->id}" />
            <input type="hidden" name="chir_id" value="{$curr_plage->chir_id}" />
            Dr.
            <select name="anesth_id" onchange="submit()">
              <option value="0">&mdash; Choisir un anesthésiste</option>
              {foreach from=$listPratAnesth item=curr_anesth}
              <option value="{$curr_anesth->user_id}" {if $curr_plage->anesth_id == $curr_anesth->user_id} selected="selected" {/if}>{$curr_anesth->_view}</option>
              {/foreach}
            </select>
            
            </form>
          </td>
        </tr>
        <tr>
          <td>
	        <table class="tbl">
              <tr>
                <th>Heure</th>
                <th>Patient</th>
                <th>Intervention</th>
                <th>Coté</th>
                <th>Durée</th>
              </tr>
              {foreach from=$curr_plage->_ref_operations item=curr_operation}
              <tr>
                <td>
                  <a href="index.php?m={$m}&amp;op={$curr_operation->operation_id}">
                  {$curr_operation->time_operation|date_format:"%Hh%M"}
                  </a>
                </td>
                <td>{$curr_operation->_ref_pat->_view}</td>
                <td>
                  <a href="?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_operation->operation_id}">
                  {$curr_operation->_ext_code_ccam->code}
                  {if $curr_operation->CCAM_code2}
                  <br />{$curr_operation->_ext_code_ccam2->code}
                  {/if}
                  </a>
                </td>
                <td>{$curr_operation->cote}</td>
                <td>{$curr_operation->temp_operation|date_format:"%Hh%M"}</td>
              </tr>
              {/foreach}
            </table>
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
    <td class="greedyPane">
      <table class="tbl">
        {if $selOp->operation_id}
        <tr>
          <th class="title" colspan="2">
            {$selOp->_ref_pat->_view} - Dr. {$selOp->_ref_chir->_view}
          </th>
        </tr>
        <tr>
          <th>Patient</th>
          <td>{$selOp->_ref_pat->_view} - {$selOp->_ref_pat->_age} ans</td>
        </tr>
        <tr>
          <th>Entrée en salle</th>
          <td>
            <form name="editFrm{$selOp->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="entree" value="{$selOp->operation_id}" />
              {if $selOp->entree_bloc}
              <input type="hidden" name="del" value="1" />
              {$selOp->entree_bloc|date_format:"%Hh%M"}
              <button type="submit"><img src="modules/{$m}/images/cross.png"></button>
              {else}
              <input type="hidden" name="del" value="0" />
              <input type="submit" value="Définir l'heure d'entrée du patient" />
              {/if}
            </form>
          </td>
        </tr>
        <tr>
          <th>
            Intervention
            <br />
            Côté {$selOp->cote}
            <br />
            ({$selOp->temp_operation|date_format:"%Hh%M"})
          </th>
          <td class="text">
            <ul>
            {foreach from=$selOp->_ext_codes_ccam item=curr_code}
            <li>
              <strong>{$curr_code->libelleLong|escape}</strong> 
              <em>(<a class="action" href="?m=dPccam&amp;tab=vw_full_code&amp;codeacte={$curr_code->code}">{$curr_code->code}</a>)</em>

              {foreach from=$curr_code->activites item=curr_activite}
              <ul>
                <li>Activité {$curr_activite->numero} ({$curr_activite->type|escape}) : {$curr_activite->libelle|escape}
                    
                {foreach from=$curr_activite->phases item=curr_phase}
                {assign var="acte" value=$curr_phase->_connected_acte}
                  <form name="formActe-{$acte->_view}" action="?m={$m}" method="post" onsubmit="return checkForm(this)">

                  <input type="hidden" name="m" value="{$m}" />
                  <input type="hidden" name="dosql" value="do_acteccam_aed" />
                  <input type="hidden" name="del" value="0" />
                  <input type="hidden" name="acte_id" value="{$acte->acte_id}" />
                  <input type="hidden" name="operation_id" value="{$selOp->operation_id}" />
                  <input type="hidden" name="code_acte" value="{$acte->code_acte}" />
                  <input type="hidden" name="code_activite" value="{$acte->code_activite}" />
                  <input type="hidden" name="code_phase" value="{$acte->code_phase}" />
                  
                  <table class="form">
                  
                  <tr>
                    <th class="title" colspan="2">Phase {$curr_phase->phase} : {$curr_phase->libelle|escape} : {$curr_phase->tarif}&euro;</th>
                  </tr>
                  
                  <tr>
                    <th><label for="execution" title="Date et heure d'exécution de l'acte">Exécution:</label></th>
                    <td>
                      <input type="text" name="execution" alt="{$acte->_props.execution}" readonly="readonly" value="{$acte->execution}" />
                      <input type="button" value="Maintenant" onclick="this.form.execution.value = makeDATETIMEFromDate(new Date());" /><br />
                    </td>
                  </tr>

                  <tr>
                    <th><label for="modificateurs" title="Modificateurs associés à l'acte">Modificateur(s)</label></th>
                    <td>
                      {foreach from=$curr_phase->_modificateurs item=curr_mod}
                      <input type="checkbox" name="modificateur_{$curr_mod->code}" {if $curr_mod->_value}checked="checked"{/if} />
                      <label for="modificateur_{$curr_mod->code}" title="{$curr_mod->libelle|escape}">{$curr_mod->code}</label>
                      {/foreach}
                    </td>
                  </tr>

                  <tr>
                    <th><label for="montant_depassement" title="Montant du dépassement d'honoraires">Dépassement:</label></th>
                    <td><input type="text" name="montant_depassement" size="6" alt="{$acte->_props.montant_depassement}" value="{$acte->montant_depassement}" />&euro;</td>
                  </tr>
                  
                  <tr>
                    <th><label for="commentaire" title="Commentaires sur l'acte">Commentaire:</label></th>
                    <td><textarea name="commentaire" alt="{$acte->_props.commentaire}">{$acte->commentaire}</textarea></td>
                  </tr>
                  
                  <tr>
                    <td class="button" colspan="2">
                    {if $acte->acte_id}
                    <input type="submit" value="Modifier cet acte" />
                    <input type="button" value="Supprimer cet acte" onclick="confirmDeletion(this.form, 'l\'acte', '{$acte->_view|escape:javascript}')"  />
                    {else}
                    <input type="submit" value="Coder cet acte" />
                    {/if}
                  </tr>
                  
                  </table>
                      
                  </form>
                {/foreach}
                </li>
              </ul>
              {/foreach}
            </li>
            {/foreach}
            </ul>
          </td>
        </tr>
        <tr>
          <th>Anesthésie</th>
          <td>
            <form name="editAnesth" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_set_hours" />
            <input type="hidden" name="id" value="{$selOp->operation_id}" />
            <select name="anesth">
              <option value="null">&mdash; Type d'anesthésie</option>
              {foreach from=$listAnesth item=curr_anesth}
              <option {if $selOp->_lu_type_anesth == $curr_anesth} selected="selected" {/if}>{$curr_anesth}</option>
              {/foreach}
            </select>
            <input type="submit" value="changer" />
            </form>
          </td>
        </tr>
        {if $selOp->materiel}
        <tr>
          <th>Matériel</th>
          <td><strong>{$selOp->materiel|nl2br}</strong></td>
        </tr>
        {/if}
        {if $selOp->rques}
        <tr>
          <th>Remarques</th>
          <td>{$selOp->rques|nl2br}</td>
        </tr>
        {/if}
        <tr>
          <th>Sortie de salle</th>
          <td>
            <form name="editFrm{$selOp->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="sortie" value="{$selOp->operation_id}" />
              {if $selOp->sortie_bloc}
              <input type="hidden" name="del" value="1" />
              {$selOp->sortie_bloc|date_format:"%Hh%M"}
              <button type="submit"><img src="modules/{$m}/images/cross.png"></button>
              {else}
              <input type="hidden" name="del" value="0" />
              <input type="submit" value="Définir l'heure de sortie du patient" />
              {/if}
            </form>
          </td>
        </tr>
        {else}
        <tr>
          <th class="title">
            Selectionnez une opération
          </th>
        </tr>
        {/if}
      </table>
    </td>
  </tr>
</table>