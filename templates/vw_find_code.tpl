<table class="form">
  <tr>
    <th class="category" colspan="2">
      <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <select name="lang" style="float:right;" onchange="this.form.submit()">
        <option value="{$smarty.const.LANG_FR}" {if $lang == $smarty.const.LANG_FR}selected="selected"{/if}>
          Français
        </option>
        <option value="{$smarty.const.LANG_EN}" {if $lang == $smarty.const.LANG_EN}selected="selected"{/if}>
          English
        </option>
        <option value="{$smarty.const.LANG_DE}" {if $lang == $smarty.const.LANG_DE}selected="selected"{/if}>
          Deutsch
        </option>
      </select>
      <input type="hidden" name="m" value="dPcim10" />
      <input type="hidden" name="tab" value="vw_find_code" />
      <input type="hidden" name="keys" value="{$keys}" />
      Critères de recherche
      </form>
    </th>
  </tr>
  <form action="index.php" target="_self" name="selection" method="get" encoding="">
  <tr>
    <th>Mots clefs:</th>
    <td><input tabindex="1" type="text" name="keys" value="{$keys}" /></td>
  </tr>
  
  <tr>
    <td class="button" colspan="2">
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="1" />
      <input tabindex="2" type="reset" value="réinitialiser" />
      <input tabindex="3" type="submit" value="rechercher" />
    </td>
  </tr>
</table>

<table class="findCode">

  <tr>
    <th colspan="4">
      {if $numresults == 100}
      Plus de {$numresults} résultats trouvés, seuls les 100 premiers sont affichés:
      {else}
      {$numresults} résultats trouvés:
      {/if}
    </th>
  </tr>


  <tr>
  {foreach from=$master item=curr_master key=curr_key}
    <td>
      <strong><a href="index.php?m={$m}&amp;tab=vw_full_code&amp;code={$curr_master.code}">{$curr_master.code}</a></strong><br />
			{$curr_master.text}
    </td>
  {if ($curr_key+1) is div by 4}
  </tr><tr>
  {/if}
  {/foreach}
  </tr>

</table>