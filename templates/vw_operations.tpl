{literal}
<script type="text/javascript">

function pageMain() {
  {/literal}
  regRedirectPopupCal("{$date}", "index.php?m={$m}&tab={$tab}&date=");

  initGroups("acte");
  {literal}
}

</script>
{/literal}

<table class="main">
  <tr>
    <td style="width: 200px;">
    
      <form action="index.php" name="selection" method="get">
      
      <input type="hidden" name="m" value="{$m}" />
  
      <table class="form">
        <tr>
          <th class="category" colspan="2">
            {$date|date_format:"%A %d %B %Y"}
            <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
          </th>
        </tr>
        
        <tr>
          <th><label for="salle" title="Salle d'opération">Salle :</label></th>
          <td>
            <select name="salle" onchange="this.form.submit()">
              <option value="0">&mdash; Aucune salle</option>
              {foreach from=$listSalles item=curr_salle}
              <option value="{$curr_salle->id}" {if $curr_salle->id == $salle} selected="selected" {/if}>
              {$curr_salle->nom}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
      </table>
      
      </form>
            
      {foreach from=$plages item=curr_plage}
      <hr />
      
      <form name="anesth{$curr_plage->id}" action="index.php" method="post">

      <input type="hidden" name="m" value="dPbloc" />
      <input type="hidden" name="otherm" value="{$m}" />
      <input type="hidden" name="dosql" value="do_plagesop_aed" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="_repeat" value="1" />
      <input type="hidden" name="id" value="{$curr_plage->id}" />
      <input type="hidden" name="chir_id" value="{$curr_plage->chir_id}" />

      <table class="form">
        <tr>
          <th class="category" colspan="2">
            <a href="?m=dPbloc&amp;tab=vw_edit_interventions&amp;id={$curr_plage->id}" title="Administrer la plage">
              Plage du Dr. {$curr_plage->_ref_chir->_view}
              de {$curr_plage->debut|date_format:"%Hh%M"} à {$curr_plage->fin|date_format:"%Hh%M"}
            </a>
          </th>
        </tr>
      
        <tr>
          <th><label for="anesth_id" title="Anesthésiste associé à la plage d'opération">Anesthésiste :</label></th>
          <td>
            <select name="anesth_id" onchange="submit()">
              <option value="0">&mdash; Choisir un anesthésiste</option>
              {foreach from=$listAnesths item=curr_anesth}
              <option value="{$curr_anesth->user_id}" {if $curr_plage->anesth_id == $curr_anesth->user_id} selected="selected" {/if}>{$curr_anesth->_view}</option>
              {/foreach}
            </select>
          </td>
        </tr>
        
      </table>

      </form>

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
            <a href="index.php?m={$m}&amp;op={$curr_operation->operation_id}" title="Coder l'intervention">
            {$curr_operation->time_operation|date_format:"%Hh%M"}
            </a>
          </td>
          <td>{$curr_operation->_ref_pat->_view}</td>
          <td>
            <a href="?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$curr_operation->operation_id}" title="Modifier l'intervention">
              {foreach from=$curr_operation->_ext_codes_ccam item=curr_code}
              {$curr_code->code}<br />
              {/foreach}
            </a>
          </td>
          <td>{$curr_operation->cote}</td>
          <td>{$curr_operation->temp_operation|date_format:"%Hh%M"}</td>
        </tr>
        {/foreach}
      </table>
      {/foreach}
    </td>
    <td class="greedyPane">
      <table class="tbl">
        {if $selOp->operation_id}
        <tr>
          <th class="title" colspan="2">
            {$selOp->_ref_pat->_view} &mdash; Dr. {$selOp->_ref_chir->_view}
          </th>
        </tr>
        <tr>
          <th>Patient</th>
          <td>{$selOp->_ref_pat->_view} &mdash; {$selOp->_ref_pat->_age} ans</td>
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
              <button type="submit"><img src="modules/{$m}/images/cross.png" /></button>
              {else}
              <input type="hidden" name="del" value="0" />
              <input type="submit" value="Définir l'heure d'entrée du patient" />
              {/if}
            </form>
          </td>
        </tr>
        <tr>
          <th>Actes</th>
          <td class="text">
          {include file="inc_manage_codes.tpl"}
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
          {include file="inc_codage_actes.tpl"}
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
              {foreach from=$listAnesthType item=curr_type}
              <option {if $selOp->_lu_type_anesth == $curr_type} selected="selected" {/if}>{$curr_type}</option>
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
              <button type="submit"><img src="modules/{$m}/images/cross.png" /></button>
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