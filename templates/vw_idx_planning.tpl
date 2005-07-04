<!-- $Id$ -->

{literal}
<script language="javascript">

function selectCR(id, form) {
  var modele = form.modele.value;
  if(modele != 0)
    editModele(id, modele);
}

function editModele(operation, modele) {
  var url = '?m=dPplanningOp&a=edit_compte_rendu&dialog=1';
  url +='&operation=' + operation;
  url +='&modele=' + modele;
  popup(1000, 700, url, 'Compte-rendu');
}

function validerCompteRendu(form) {
  if (confirm('Veuillez confirmer la validation du compte-rendu')) {
    form.cr_valide.value = "1";
    form.submit();
  }
}

function supprimerCompteRendu(form) {
  if (confirm('Veuillez confirmer la suppression')) {
    form.compte_rendu.value = "";
    form.cr_valide.value = "0";
    form.submit();
  }
}

</script>
{/literal}

<table class="main">

  <tr>
    <td colspan="2">
      <form action="index.php" name="selection" method="get">

      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="{$tab}">

      <label for="selection_selChir">Chirurgien:</label>
      <select name="selChir" onchange="this.form.submit()">
        <option value="-1">&mdash; Choisir un chirurgien</option>
        {foreach from=$listChir item=curr_chir}
        <option value="{$curr_chir->user_id}" {if $curr_chir->user_id == $selChir} selected="selected" {/if}>
          {$curr_chir->user_last_name} {$curr_chir->user_first_name}
        </option>
        {/foreach}
      </select>
  
      </form>
    </td>
  </tr>

  <tr>
    <th>
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$pmonthd}&amp;month={$pmonth}&amp;year={$pmonthy}">&lt;&lt</a>
      {$title1}
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$nmonthd}&amp;month={$nmonth}&amp;year={$nmonthy}">&gt;&gt;</a>
    </th>
    <th class="greedyPane">
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$pday}&amp;month={$pdaym}&amp;year={$pdayy}">&lt;&lt</a>
      {$title2}
      <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$nday}&amp;month={$ndaym}&amp;year={$ndayy}">&gt;&gt;</a>
    </th>
  </tr>

  <tr>
    <td>
      <table class="tbl">
        <tr>
          <th>Date</th>
          <th>Plage</th>
          <th>Opérations</th>
          <th>Temps pris</th>
        </tr>

        {foreach from=$listPlages item=curr_plage}
        {if $curr_plage.id_spec}
         <tr>
          <td style="background: #aae" align="right">{$curr_plage.date|date_format:"%a %d %b %Y"}</td>
          <td style="background: #aae" align="center">{$curr_plage.debut|date_format:"%Hh%M"} à {$curr_plage.fin|date_format:"%Hh%M"}</td>
          <td style="background: #aae" align="center">{$curr_plage.total}</td>
          <td style="background: #aae" align="center">Plage de spécialité</td>
        </tr>

        {else}
        <tr>
          <td align="right"><a href="index.php?m={$m}&amp;tab=0&amp;day={$curr_plage.date|date_format:"%d"}&amp;month={$month}&amp;year={$year}">{$curr_plage.date|date_format:"%a %d %b %Y"}</a></td>
          <td align="center">{$curr_plage.debut|date_format:"%Hh%M"} à {$curr_plage.fin|date_format:"%Hh%M"}</td>
          <td align="center">{$curr_plage.total}</td>
          <td align="center">{$curr_plage.duree|date_format:"%Hh%M"}</td>
        </tr>
        {/if}
        {/foreach}
      </table>
    </td>

    <td>
      <table class="tbl">
        <tr>
          <th>Patient</th>
          <th>code CCAM</th>
          <th>Description</th>
          <th>Heure prévue</th>
          <th>Durée</th>
          {if $selChir == $app->user_id}
          <th>Compte-rendu</th>
          {/if}
        </tr>

        {foreach from=$listDay item=curr_plage}
        <tr>
          <th colspan="6">{$curr_plage->_ref_salle->nom} : de {$curr_plage->debut|date_format:"%Hh%M"} à {$curr_plage->fin|date_format:"%Hh%M"}</th>
        </tr>
        {foreach from=$curr_plage->_ref_operations item=curr_op}
        <tr>
          <td class="text"><a href="index.php?m=dPcabinet&amp;tab=vw_dossier&amp;patSel={$curr_op->_ref_pat->patient_id}">{$curr_op->_ref_pat->_view}</a></td>
          <td><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">{$curr_op->_ext_code_ccam->code}{if $curr_op->CCAM_code2}<br />+ {$curr_op->_ext_code_ccam2->code}{/if}</a></td>
          <td class="text"><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">{$curr_op->_ext_code_ccam->libelleLong}{if $curr_op->CCAM_code2}<br />+ {$curr_op->_ext_code_ccam2->libelleLong}{/if}</a></td>
          <td style="text-align: center;">
            {if $curr_op->annulee}
            [ANNULEE]
            {else}
            <a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">{$curr_op->time_operation|date_format:"%Hh%M"}</a></td>
            {/if}
          <td style="text-align: center;"><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op->operation_id}">{$curr_op->temp_operation|date_format:"%Hh%M"}</a></td>
          {if $selChir == $app->user_id}
          <td>
            <form name="editCompteRenduFrm{$curr_op->operation_id}" action="?m={$m}" method="POST">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_planning_aed" />
            <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
            <input type="hidden" name="compte_rendu" value="{$curr_op->compte_rendu|escape:html}" />
            <input type="hidden" name="cr_valide" value="{$curr_op->cr_valide}" />
            {if $curr_op->compte_rendu}
            <button type="button" onclick="editModele({$curr_op->operation_id}, 0)"><img src="modules/dPplanningOp/images/edit.png" /></button>
            {if !$curr_op->cr_valide}
            <button type="button" onclick="validerCompteRendu(this.form)"><img src="modules/dPplanningOp/images/check.png" /></button>
            {/if}
            <button type="button" onclick="supprimerCompteRendu(this.form)"><img src="modules/dPplanningOp/images/trash.png" /></button>
            {else}
            <select name="modele" onchange="selectCR({$curr_op->operation_id}, this.form)">
              <option value="0">&mdash; modeles &mdash;</option>
              {foreach from=$crList item=curr_cr}
              <option value="{$curr_cr->compte_rendu_id}">{$curr_cr->nom}</option>
              {/foreach}
            </select>
            {/if}
            </form>
          </td>
          {/if}
        </tr>
        {/foreach}
        {/foreach}
      </table>
    </td>
  </tr>
</table>