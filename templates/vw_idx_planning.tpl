<!-- $Id$ -->

<table class="tbl">
  <tr><th>Liste des spécialités</th></tr>
  {foreach from=$listSpec item=curr_spec}
  <tr>
    <td class="text" style="background: #{$curr_spec->color};">{$curr_spec->text}</td>
  </tr>
  {/foreach}
</table>
