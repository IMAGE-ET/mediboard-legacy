<table class="main">
  <tr>
    <td>
      <table>
        <tr>
          <th>
            <form action="index.php" target="_self" name="selection" method="get" encoding="">
            <input type="hidden" name="m" value="{$m}">
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
            Dr. {$curr_plage->_ref_chir->_view}
            de {$curr_plage->debut|date_format:"%Hh%M"} à {$curr_plage->fin|date_format:"%Hh%M"}
            <form action="index.php" target="_self" name="anesth{$curr_plage->id}" method="post" encoding="">
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
              <option value="0">&mdash; Anesthésiste</option>
              {foreach from=$listPratAnesth item=curr_anesth}
              <option value="{$curr_anesth->user_id}" {if $curr_plage->anesth_id == $curr_anesth->user_id} selected="selected" {/if}>{$curr_anesth->_view}</option>
              {/foreach}
            </select>
            </form>
            </strong>
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
                  {$curr_operation->_ext_code_ccam->code}
                  {if $curr_operation->CCAM_code2}
                  <br />{$curr_operation->_ext_code_ccam2->code}
                  {/if}
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
              <input type="submit" value="Entrée" />
              {/if}
            </form>
          </td>
        </tr>
        <tr>
          <th>
            Intervention
            <br />
            Coté {$selOp->cote}
            <br />
            ({$selOp->temp_operation|date_format:"%Hh%M"})
          </th>
          <td class="text">
            <strong>{$selOp->_ext_code_ccam->libelleLong}</strong> <i>({$selOp->_ext_code_ccam->code})</i>
            <ul>
            {foreach from=$selOp->_ext_code_ccam->activites item=curr_act}
              <li><i>{$curr_act.nom}</i>
              {$curr_act.modificateurs}</li>
            {/foreach}
            </ul>
            {if $selOp->CCAM_code2}
            <br />
            <strong>{$selOp->_ext_code_ccam2->libelleLong}</strong> <i>({$selOp->_ext_code_ccam2->code})</i>
            <ul>
            {foreach from=$selOp->_ext_code_ccam2->activites item=curr_act}
              <li><i>{$curr_act.nom}</i>
              {$curr_act.modificateurs}</li>
            {/foreach}
            </ul>
            {/if}
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
              <input type="submit" value="Sortie" />
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