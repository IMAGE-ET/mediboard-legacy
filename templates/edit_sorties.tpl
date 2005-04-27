{literal}
<script language="javascript">
</script>
{/literal}
<table class="main">
<tr>
<th>

<form name="typeVue" action="?m={$m}" method="get">
<input type="hidden" name="m" value="{$m}" />
<select name="vue" onchange="submit()">
  <option value="0" {if $vue == 0}selected="selected"{/if}>Tout afficher</option>
  <option value="1" {if $vue == 1}selected="selected"{/if}>Ne pas afficher les validés</option>
</select>
</form>

</th>
</tr>
<tr>
<td>

<table class="tbl">
  <tr>
    <th>Confirmation</th>
    <th>Patient</th>
    <th>Praticien</th>
    <th>Service</th>
    <th>Chambre</th>
    <th>lit</th>
    <th>Sortie prévue</th>
  </tr>
  {foreach from=$list item=curr_sortie}
  <tr>
    <td>
    <form name="editFrm{$curr_sortie->affectation_id}" action="?m={$m}" method="post">
    <input type="hidden" name="m" value="{$m}" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="dosql" value="do_affectation_aed" />
    <input type="hidden" name="affectation_id" value="{$curr_sortie->affectation_id}" />
    {if $curr_sortie->confirme}
    <input type="hidden" name="confirme" value="0" />
    <button type="submit">
    <img src="modules/{$m}/images/cross.png" alt="Annuler" title="Annuler la sortie">
    Annuler la sortie
    </button>
    {else}
    <input type="hidden" name="confirme" value="1" />
    <button type="submit">
    <img src="modules/{$m}/images/tick.png" alt="Confirmer" title="Confirmer la sortie">
    Confirmer la sortie
    </button>
    {/if}
    </form>
    </td>
    {if $curr_sortie->confirme}
    <td style="background-image:url(modules/{$m}/images/ray.gif); background-repeat:repeat;">
    {else}
    <td>
    {/if}
      <b>{$curr_sortie->_ref_operation->_ref_pat->_view}</b>
    </td>
    <td style="background:#{$curr_sortie->_ref_operation->_ref_chir->_ref_function->color}">
      {$curr_sortie->_ref_operation->_ref_chir->_view}
    </td>
    <td>{$curr_sortie->_ref_lit->_ref_chambre->_ref_service->nom}</td>
    <td>{$curr_sortie->_ref_lit->_ref_chambre->nom}</td>
    <td>{$curr_sortie->_ref_lit->nom}</td>
    <td>{$curr_sortie->sortie|date_format:"%H h %M"}</td>
  </tr>
  {/foreach}
</table>

</td>
</tr>
</table>