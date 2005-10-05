<form action="index.php" target="_self" name="selection" method="get" encoding="">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="tab" value="{$tab}" />

<table class="form">
  <tr>
    <th class="category" colspan="4">Critères de recherche</th>
  </tr>

  <tr>
    <th>Code Partiel:</th>
    <td><input tabindex="1" type="text" name="code" value="{$code}" maxlength="7" /></td>
    <th>Voie d'accès:</th>
    <td>
      <select tabindex="3" name="selacces" onchange="this.form.submit()">
        {foreach from=$acces item=curr_acces}
        <option value="{$curr_acces.code}" {if $curr_acces.code == $selacces} selected="selected" {/if}>{$curr_acces.texte}</option>
        {/foreach}
      </select>
    </td>
  </tr>

  <tr>
    <th>Mots clefs:</th>
    <td><input tabindex="2" type="text" name="clefs" value="{$clefs}" /></td>
    <th>Appareil:</th>
    <td>
      <select tabindex="4" name="seltopo1" onchange="this.form.submit()">
        {foreach from=$topo1 item=curr_topo1}
        <option value="{$curr_topo1.code}" {if $curr_topo1.code == $seltopo1} selected="selected" {/if}>{$curr_topo1.texte}</option>
        {/foreach}
      </select>
    </td>
  </tr>

  <tr>
    <td class="button" colspan="2">
      <input tabindex="6" type="reset" value="réinitialiser" />
      <input tabindex="7" type="submit" value="rechercher" />
    </td>
    <th>Système:</td>
    <td>
      <select tabindex="5" name="seltopo2" onchange="this.form.submit()">
        {foreach from=$topo2 item=curr_topo2}
        <option value="{$curr_topo2.code}" {if $curr_topo2.code == $seltopo2} selected="selected" {/if}>{$curr_topo2.texte}</option>
        {/foreach}
      </select>
    </td>
  </tr>

</table>
</form>

<table class="findCode">

  <tr>
    <th colspan="4">
      {if $numcodes == 100}
      Plus de {$numcodes} résultats trouvés, seuls les 100 premiers sont affichés:
      {else}
      {$numcodes} résultats trouvés:
      {/if}
    </th>
  </tr>

  <tr>
  {foreach from=$codes item=curr_code key=curr_key}
    <td>
      <strong><a href="index.php?m={$m}&tab=vw_full_code&codeacte={$curr_code.code}">{$curr_code.code}</a></strong><br />
      {$curr_code.texte}
    </td>
  {if ($curr_key+1) is div by 4}
  </tr><tr>
  {/if}
  {/foreach}
  </tr>

</table>
