<table class="bookCode">
  <tr>
    <th colspan="4">
      <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <select name="lang" style="float:right;" onchange="this.form.submit()">
        <option value="{$smarty.const.LANG_FR}" {if $lang == $smarty.const.LANG_FR}selected="selected"{/if}>
          Fran�ais
        </option>
        <option value="{$smarty.const.LANG_EN}" {if $lang == $smarty.const.LANG_EN}selected="selected"{/if}>
          English
        </option>
        <option value="{$smarty.const.LANG_DE}" {if $lang == $smarty.const.LANG_DE}selected="selected"{/if}>
          Deutsch
        </option>
      </select>
      <input type="hidden" name="m" value="dPcim10" />
      <input type="hidden" name="tab" value="vw_idx_favoris" />
      Codes favoris
      </form>
    </th>
  </tr>
  <tr>
  {foreach from=$codes item=curr_code key=curr_key}
    <td>
      <strong><a href="index.php?m={$m}&amp;tab=vw_full_code&amp;code={$curr_code->code}">{$curr_code->code}</a></strong><br />
      {$curr_code->libelle}<br />
      <form name="delFavoris" action="./index.php?m={$m}" method="post">
      <input type="hidden" name="dosql" value="do_favoris_aed" />
      <input type="hidden" name="del" value="1" />
      <input type="hidden" name="favoris_id" value="{$curr_code->_favoris_id}" />
	  <input class="button" type="submit" name="btnFuseAction" value="Retirer de mes favoris" />
	  </form>
    </td>
  {if ($curr_key+1) is div by 4}
  </tr><tr>
  {/if}
  {/foreach}
  </tr>
</table>