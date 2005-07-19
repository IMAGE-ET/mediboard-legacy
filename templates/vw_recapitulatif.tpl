{literal}
<script language="javascript">

function pageMain() {
  initGroups("allocated");
  initGroups("notallocated");
}

</script>
{/literal}

<table class="tbl">
  <tr>
    <th />
    {foreach from=$listDays item=curr_day}
    {assign var="myMonth" value=$curr_day|date_format:"%m"}
    <th>
      <a href="index.php?m={$m}&amp;tab=vw_affectations&amp;date={$curr_day}">
    {$curr_day|date_format:"%a %d %b %y"}
      </a>
    </th>
    {/foreach}
  </tr>
  {foreach from=$mainTab.allocated.functions item=curr_function}
  {if $curr_function.class == "allocated"}
  <tr class="{$curr_function.class}">
  {else}
  <tr class="{$curr_function.class}" id="allocated" onclick="flipGroup('', 'allocated')">
  {/if}
    <td style="background:#{$curr_function.color}">{$curr_function.text}</td>
    {foreach from=$curr_function.days item=curr_day}
      <td>{$curr_day.nombre}</td>
    {/foreach}
  </tr>
  {/foreach}
  {foreach from=$mainTab.notallocated.functions item=curr_function}
  {if $curr_function.class == "notallocated"}
  <tr class="{$curr_function.class}">
  {else}
  <tr class="{$curr_function.class}" id="notallocated" onclick="flipGroup('', 'notallocated')">
  {/if}
    <td style="background:#{$curr_function.color}">{$curr_function.text}</td>
    {foreach from=$curr_function.days item=curr_day}
      <td>{$curr_day.nombre}</td>
    {/foreach}
  </tr>
  {/foreach}
</table>