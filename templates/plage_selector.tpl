{literal}
<script language="javascript">
function setClose(){
  var form = document.frmSelector;
  var list = form.list;
  var key = list.options[list.selectedIndex].value;
  var val = list.options[list.selectedIndex].text;
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
    <a href="index.php?m=dPplanningOp&a=plage_selector&dialog=1&hour={$hour}&min={$min}&chir={$chir}&month={$pmonth}&year={$pyear}"><<</a>
    {$nameMonth} {$year}
    <a href="index.php?m=dPplanningOp&a=plage_selector&dialog=1&hour={$hour}&min={$min}&chir={$chir}&month={$nmonth}&year={$nyear}">>></a>
  </th>
</tr>

<tr>
  <td>
    <select name="list"  size="14">
    {foreach from=$list item=curr_elem}
      <option value="{$curr_elem.id}">{$curr_elem.dateFormed}</option>
    {/foreach}
    </select>
  </td>
  <td>
    <strong>Admission du patient</strong>
    <br />
    <input type="radio" name="admission" checked="checked" /> La veille
    <br />
    <input type="radio" name="admission" /> Le jour même
  </td>
</tr>

<tr>
  <td class="button">
    <input type="button" class="button" value="annuler" onclick="window.close()" />
    <input type="button" class="button" value="selectionner" onclick="setClose()" />
  </td>
</tr>

</table>

</form>
