{literal}
<script language="javascript">
function setClose() {
  var form = document.frmSelector;
  var list = form.list;
  var key = list.options[list.selectedIndex].value;
  var val = list.options[list.selectedIndex].text;
  
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
<input type="hidden" name="m" value="dPplanningOp">
<input type="hidden" name="a" value="plage_selector">
<input type="hidden" name="dialog" value="1">

<table class="form">

<tr>
  <th class="category" colspan="2">
    <a href="index.php?m=dPplanningOp&amp;a=plage_selector&amp;dialog=1&amp;curr_op_hour={$curr_op_hour}&amp;curr_op_min={$curr_op_min}&amp;chir={$chir}&amp;month={$pmonth}&amp;year={$pyear}">&lt; &lt;</a>
    {$nameMonth} {$year}
    <a href="index.php?m=dPplanningOp&amp;a=plage_selector&amp;dialog=1&amp;curr_op_hour={$curr_op_hour}&amp;curr_op_min={$curr_op_min}&amp;chir={$chir}&amp;month={$nmonth}&amp;year={$nyear}">&gt; &gt;</a>
  </th>
</tr>

<tr>
  <td>
    <select name="list"  size="14">
      <option value="0" selected="selected">&mdash; Choisir une date &mdash;</option>
    {foreach from=$list item=curr_elem}
      <option value="{$curr_elem.id}" ondblclick="setClose()">{$curr_elem.dateFormed}</option>
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
    <label for="frmSelector_admission_jour">Le jour même</label>
  </td>
</tr>

<tr>
  <td class="button" colspan="2">
    <input type="button" class="button" value="annuler" onclick="window.close()" />
    <input type="button" class="button" value="selectionner" onclick="setClose()" />
  </td>
</tr>

</table>

</form>
