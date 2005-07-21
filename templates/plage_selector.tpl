{literal}
<script language="javascript">
function setClose(date) {
  var form = document.frmSelector;
  var list = form.list;
  if(date == '') {
    date = form.fmtdate.value;
  }
  var key = list.options[list.selectedIndex].value;
  var val = date;
  
  if (key == 0) {
  	return;
  }
  var adm = form.admission[0].checked;
  window.opener.setPlage(key,val,adm);
  window.close();
}
</script>
{/literal}

<form action="index.php" target="_self" name="frmSelector" method="get">
<input type="hidden" name="m" value="dPplanningOp" />
<input type="hidden" name="a" value="plage_selector" />
<input type="hidden" name="dialog" value="1" />
<input type="hidden" name="fmtdate" value="" />

<table class="form">

<tr>
  <th class="category" colspan="2">
    <a href="index.php?m=dPplanningOp&amp;a=plage_selector&amp;dialog=1&amp;curr_op_hour={$curr_op_hour}&amp;curr_op_min={$curr_op_min}&amp;chir={$chir}&amp;month={$pmonth}&amp;year={$pyear}">&lt; &lt;</a>
    {$nameMonth} {$year}
    <a href="index.php?m=dPplanningOp&amp;a=plage_selector&amp;dialog=1&amp;curr_op_hour={$curr_op_hour}&amp;curr_op_min={$curr_op_min}&amp;chir={$chir}&amp;month={$nmonth}&amp;year={$nyear}">&gt; &gt;</a>
  </th>
</tr>

<tr>
  <td rowspan="2">
    <select name="list"  size="14">
      <option value="0" selected="selected">&mdash; Choisir une date &mdash;</option>
    {foreach from=$list item=curr_plage}
      <option value="{$curr_plage.id}" ondblclick="setClose('{$curr_plage.date|date_format:"%d/%m/%Y"}')"
      onclick="document.frmSelector.fmtdate.value='{$curr_plage.date|date_format:"%d/%m/%Y"}'"
      {if $curr_plage.id_spec }
        style="background:#aae"
      {elseif $curr_plage.free_time < 0}
        style="background:#eaa"
      {else}
        style="background:transparent"
      {/if}>
        {$curr_plage.date|date_format:"%a %d %b %Y"} - {$curr_plage.nom}
      </option>
    {/foreach}
    </select>
  </td>
  <td>
    <strong>Admission du patient</strong>
    <br />
    <input type="radio" name="admission" value="veille" checked="checked" />
    <label for="frmSelector_admission_veille">La veille</label>
    <br />
    <input type="radio" name="admission" value="jour" />
    <label for="frmSelector_admission_jour">Le jour m�me</label>
    <br />
    
  </td>
</tr>
<tr>
  <td class="text"><i>Remarques :<ul>
    <li>Les jours not�s en rouge repr�sentent des plages pleines</li>
    <li>Les jours not�s en bleu repr�sentent des plages de sp�cialit�</li>
    <li>Par d�faut, une admission la veille se fait � 17h et � 8h pour le jour m�me</li>
  </ul></i></td>
</tr>

<tr>
  <td class="button" colspan="2">
    <input type="button" class="button" value="annuler" onclick="window.close()" />
    <input type="button" class="button" value="selectionner" onclick="setClose('')" />
  </td>
</tr>

</table>

</form>
