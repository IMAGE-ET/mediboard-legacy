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

        {foreach from=$list item=curr_plage}
        {if $curr_plage.spe}
         <tr>
          <td style="background: #aae" align="right">{$curr_plage.date}</td>
          <td style="background: #aae" align="center">{$curr_plage.horaires}</td>
          <td style="background: #aae" align="center">{$curr_plage.operations}</td>
          <td style="background: #aae" align="center">Plage de spécialité</td>
        </tr>

        {else}
        <tr>
          <td align="right"><a href="index.php?m={$m}&amp;tab=0&amp;day={$curr_plage.day}&amp;month={$month}&amp;year={$year}">{$curr_plage.date}</a></td>
          <td align="center">{$curr_plage.horaires}</td>
          <td align="center">{$curr_plage.operations}</td>
          <td align="center">{$curr_plage.occupe}</td>
        </tr>
        {/if}
        {/foreach}
      </table>
    </td>

    <td>
      <table class="tbl">
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>code CCAM</th>
          <th>Description</th>
          <th>Heure prévue</th>
          <th>Durée</th>
          {if $selChir == $app->user_id}
          <th>Compte-rendu</th>
          {/if}
        </tr>

        {foreach from=$today item=curr_op}
        <tr>
          <td><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op.id}">{$curr_op.nom}      </a></td>
          <td><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op.id}">{$curr_op.prenom}   </a></td>
          <td><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op.id}">{$curr_op.CCAM_code}</a></td>
          <td class="text"><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op.id}">{$curr_op.CCAM}     </a></td>
          <td style="text-align: center;"><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op.id}">{$curr_op.heure}</a></td>
          <td style="text-align: center;"><a href="index.php?m={$m}&amp;tab=vw_edit_planning&amp;operation_id={$curr_op.id}">{$curr_op.temps}</a></td>
          {if $selChir == $app->user_id}
          <td>
            <form name="editCompteRenduFrm{$curr_op->operation_id}" action="?m={$m}" method="POST">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_planning_aed" />
            <input type="hidden" name="operation_id" value="{$curr_op.id}" />
            <input type="hidden" name="compte_rendu" value="{$curr_op.compte_rendu|escape:html}" />
            <input type="hidden" name="cr_valide" value="{$curr_op.cr_valide}" />
            {if $curr_op.compte_rendu}
            <button type="button" onclick="editModele({$curr_op.id}, 0)"><img src="modules/dPplanningOp/images/edit.png" /></button>
            {if !$curr_op.cr_valide}
            <button type="button" onclick="validerCompteRendu(this.form)"><img src="modules/dPplanningOp/images/check.png" /></button>
            {/if}
            <button type="button" onclick="supprimerCompteRendu(this.form)"><img src="modules/dPplanningOp/images/trash.png" /></button>
            {else}
            <select name="modele" onchange="selectCR({$curr_op.id}, this.form)">
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
      </table>
    </td>
  </tr>
</table>