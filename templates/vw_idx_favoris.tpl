<table class="bookCode">
  <tr />
	{if $codes != ""} <!-- @todo -cCe test n'est pas normal, comportement different de CCAM-->
  <tr>
  {foreach from=$codes item=curr_code key=curr_key}
    <td>
      <strong><a href="index.php?m={$m}&amp;tab=vw_full_code&amp;code={$curr_code.code}">{$curr_code.code}</a></strong><br />
      {$curr_code.text}<br />

      <form name="delFavoris" action="./index.php?m={$m}" method="post">
      <input type="hidden" name="dosql" value="do_favoris_aed">
      <input type="hidden" name="del" value="1">
      <input type="hidden" name="favoris_id" value="{$curr_code.id}">
	  <input class="button" type="submit" name="btnFuseAction" value="Retirer de mes favoris">
	  </form>
    </td>
  {if ($curr_key+1) is div by 4}
  </tr><tr>
  {/if}
  {/foreach}
  </tr>
  {/if}
</table>