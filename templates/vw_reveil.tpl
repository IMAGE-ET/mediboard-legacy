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
    <td class="halfPane">
    
      <form action="index.php" name="selection" method="get">
      <input type="hidden" name="m" value="{$m}" />
      <table class="form">
        <tr>
          <th class="category">{$listOps|@count} patients en attente</th>
          <th class="category" colspan="2">
            {$date|date_format:"%A %d %B %Y"}
            <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
          </th>
        </tr>
      </table>
      </form>

       <table class="tbl">
        <tr>
          <th>Salle</th>
          <th>Praticien</th>
          <th>Patient</th>
          <th>Sortie Salle</th>
          <th>Entrée reveil</th>
        </tr>    
        {foreach from=$listOps item=curr_op}
        <tr>
          <td>{$curr_op->_ref_plageop->_ref_salle->nom}</td>
          <td>Dr. {$curr_op->_ref_chir->_view}</td>
          <td class="text">{$curr_op->_ref_pat->_view}</td>
          <td>{$curr_op->sortie_bloc|date_format:"%Hh%M"}</td>
          <td>
            <form name="editFrm{$curr_op->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
              <input type="hidden" name="type" value="entree_reveil" />
              <input type="hidden" name="del" value="0" />
              <button type="submit"><img src="modules/{$m}/images/tick.png" alt="valider" /></button>
            </form>
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
    <td class="halfPane">
      <table class="form">
        <tr>
          <th class="category">{$listReveil|@count} patients en salle de reveil</th>
        </tr>
      </table>

       <table class="tbl">
        <tr>
          <th>Salle</th>
          <th>Praticien</th>
          <th>Patient</th>
          <th>Sortie Salle</th>
          <th>Entrée reveil</th>
          <th>Sortie reveil</th>
        </tr>    
        {foreach from=$listReveil item=curr_op}
        <tr>
          <td>{$curr_op->_ref_plageop->_ref_salle->nom}</td>
          <td>Dr. {$curr_op->_ref_chir->_view}</td>
          <td class="text">{$curr_op->_ref_pat->_view}</td>
          <td>{$curr_op->sortie_bloc|date_format:"%Hh%M"}</td>
          <td>
            <form name="editFrm{$curr_op->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
              <input type="hidden" name="type" value="entree_reveil" />
              <input type="hidden" name="del" value="0" />
              {$curr_op->entree_reveil|date_format:"%Hh%M"}
              <button type="submit" onclick="this.form.del.value = 1"><img src="modules/{$m}/images/cross.png" alt="supprimer" /></button>
            </form>
          </td>
          <td>
            <form name="editFrm{$curr_op->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
              <input type="hidden" name="type" value="sortie_reveil" />
              <input type="hidden" name="del" value="0" />
              {if $curr_op->sortie_reveil}
              {$curr_op->sortie_reveil|date_format:"%Hh%M"}
              <button type="submit" onclick="this.form.del.value = 1"><img src="modules/{$m}/images/cross.png" alt="supprimer" /></button>
              {else}
              <button type="submit"><img src="modules/{$m}/images/tick.png" alt="valider" /></button>
              {/if}
            </form>
          </td>
        </tr>
        {/foreach}   
        {foreach from=$listOut item=curr_op}
        <tr>
          <td>{$curr_op->_ref_plageop->_ref_salle->nom}</td>
          <td>Dr. {$curr_op->_ref_chir->_view}</td>
          <td class="text">{$curr_op->_ref_pat->_view}</td>
          <td>{$curr_op->sortie_bloc|date_format:"%Hh%M"}</td>
          <td>
            <form name="editFrm{$curr_op->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
              <input type="hidden" name="type" value="entree_reveil" />
              <input type="hidden" name="del" value="0" />
              {$curr_op->entree_reveil|date_format:"%Hh%M"}
              <button type="submit" onclick="this.form.del.value = 1"><img src="modules/{$m}/images/cross.png" alt="supprimer" /></button>
            </form>
          </td>
          <td>
            <form name="editFrm{$curr_op->operation_id}" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
              <input type="hidden" name="type" value="sortie_reveil" />
              <input type="hidden" name="del" value="0" />
              {if $curr_op->sortie_reveil}
              {$curr_op->sortie_reveil|date_format:"%Hh%M"}
              <button type="submit" onclick="this.form.del.value = 1"><img src="modules/{$m}/images/cross.png" alt="supprimer" /></button>
              {else}
              <button type="submit"><img src="modules/{$m}/images/tick.png" alt="valider" /></button>
              {/if}
            </form>
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>