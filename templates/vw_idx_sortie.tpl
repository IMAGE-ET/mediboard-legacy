{literal}
<script language="javascript">
</script>
{/literal}
<table class="main">
<tr>

<td>
<form name="typeVue" action="?m={$m}" method="get">
<input type="hidden" name="m" value="{$m}" />
<select name="vue" onchange="submit()">
  <option value="0" {if $vue == 0}selected="selected"{/if}>Tout afficher</option>
  <option value="1" {if $vue == 1}selected="selected"{/if}>Ne pas afficher les validés</option>
</select>
</form>
</td>

<th>
  <a href="index.php?m={$m}&amp;day={$pday|date_format:"%d"}&amp;month={$pday|date_format:"%m"}&amp;year={$pday|date_format:"%Y"}"><<</a>
  {$cday|date_format:"%A %d %B %Y"}
  <a href="index.php?m={$m}&amp;day={$nday|date_format:"%d"}&amp;month={$nday|date_format:"%m"}&amp;year={$nday|date_format:"%Y"}">>></a>
</th>

<th>
  <a href="index.php?m={$m}&amp;day={$now|date_format:"%d"}&amp;month={$now|date_format:"%m"}&amp;year={$now|date_format:"%Y"}">Aujourd'hui : {$now|date_format:"%A %d %B %Y"}</a>
</th>

</tr>
<tr>
<td colspan="3">

<table class="tbl">
  <tr>
    <th>Effectuer la sortie</th>
    <th>Patient</th>
    <th>Sortie prévue</th>
    <th>Praticien</th>
    <th>Service</th>
    <th>Chambre</th>
    <th>lit</th>
  </tr>
  {foreach from=$list item=curr_sortie}
  <tr>
    <td>
    <form name="editFrm{$curr_sortie->affectation_id}" action="?m={$m}" method="post">
    <input type="hidden" name="m" value="{$m}" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="dosql" value="do_affectation_aed" />
    <input type="hidden" name="affectation_id" value="{$curr_sortie->affectation_id}" />
    {if $curr_sortie->effectue}
    <input type="hidden" name="effectue" value="0" />
    <button type="submit">
      <img src="modules/{$m}/images/cross.png" alt="Annuler" title="Annuler la sortie">
      Annuler la sortie
    </button>
    {else}
    <input type="hidden" name="effectue" value="1" />
    <button type="submit">
      <img src="modules/{$m}/images/tick.png" alt="Confirmer" title="Effectuer la sortie">
      Effectuer la sortie
    </button>
    {/if}
    </form>
    </td>
    <td>
      <b>{$curr_sortie->_ref_operation->_ref_pat->_view}</b>
    </td>
    <td>{$curr_sortie->sortie|date_format:"%H h %M"} ({$curr_sortie->_ref_operation->type_adm|truncate:1:""|capitalize})</td>
    <td>
      Dr. {$curr_sortie->_ref_operation->_ref_chir->_view}
    </td>
    <td>{$curr_sortie->_ref_lit->_ref_chambre->_ref_service->nom}</td>
    <td>{$curr_sortie->_ref_lit->_ref_chambre->nom}</td>
    <td>{$curr_sortie->_ref_lit->nom}</td>
  </tr>
  {/foreach}
</table>

</td>
</tr>
</table>