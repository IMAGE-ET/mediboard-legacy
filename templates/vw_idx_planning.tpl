<table class="main">

  <tr>
    <td colspan="2">
      <form action="index.php" name="selection" method="get">

      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="0">

      <label for="selection_selChir">Choisir un chirurgien:</label>
      <select name="selChir" onchange="this.form.submit()">
        <option value="-1">Aucun chirurgien</option>
        {foreach from=$listChir item=curr_chir}
        <option value="{$curr_chir.id}" {if $curr_chir.id == $selChir} selected="selected" {/if}>
          Dr. {$curr_chir.lastname} {$curr_chir.firstname}
        </option>
        {/foreach}
      </select>
  
      </form>
    </td>
  </tr>

  <tr>
    <th>
      <a href="index.php?m={$m}&amp;tab=0&amp;day={$pmonthd}&amp;month={$pmonth}&amp;year={$pmonthy}">&lt;&lt</a>
      {$title1}
      <a href="index.php?m={$m}&amp;tab=0&amp;day={$nmonthd}&amp;month={$nmonth}&amp;year={$nmonthy}">&gt;&gt;</a>
    </th>
    <th>
      <a href="index.php?m={$m}&amp;tab=0&amp;day={$pday}&amp;month={$pdaym}&amp;year={$pdayy}">&lt;&lt</a>
      {$title2}
      <a href="index.php?m={$m}&amp;tab=0&amp;day={$nday}&amp;month={$ndaym}&amp;year={$ndayy}">&gt;&gt;</a>
    </th>
  </tr>

  <tr>
    <td>
      <table class="color">
        <tr>
          <th>Date</th>
          <th>Plage</th>
          <th>Op�rations</th>
          <th>Temps pris</th>
        </tr>

        {foreach from=$list item=curr_plage}
        {if $curr_plage.spe}
         <tr style="background: #ddd">
          <td align="right">{$curr_plage.date}</td>
          <td align="center">{$curr_plage.horaires}</td>
          <td align="center">{$curr_plage.operations}</td>
          <td align="center">Plage de sp�cialit�</td>
        </tr>

        {else}
        <tr style="background: #fff">
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
          <th>Pr�nom</th>
          <th>code CCAM</th>
          <th width="300">Description</th>
          <th>Heure pr�vue</th>
          <th>Dur�e</th>
        </tr>

        {foreach from=$today item=curr_op}
        <tr>
          <td><a href="index.php?m={$m}&amp;tab=1&amp;operation_id={$curr_op.id}">{$curr_op.nom}      </a></td>
          <td><a href="index.php?m={$m}&amp;tab=1&amp;operation_id={$curr_op.id}">{$curr_op.prenom}   </a></td>
          <td><a href="index.php?m={$m}&amp;tab=1&amp;operation_id={$curr_op.id}">{$curr_op.CCAM_code}</a></td>
          <td><a href="index.php?m={$m}&amp;tab=1&amp;operation_id={$curr_op.id}">{$curr_op.CCAM}     </a></td>
          <td style="text-align: center;"><a href="index.php?m={$m}&amp;tab=1&amp;operation_id={$curr_op.id}">{$curr_op.heure}</a></td>
          <td style="text-align: center;"><a href="index.php?m={$m}&amp;tab=1&amp;operation_id={$curr_op.id}">{$curr_op.temps}</a></td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>