<form action="index.php" target="_self" name="selection" method="get" encoding="">
<input type="hidden" name="m" value="dPcim10">
<input type="hidden" name="tab" value="1">

<table class="form">
  <tr>
    <th class="category" colspan="2">Critères de recherche</th>
  </tr>

  <tr>
    <th>Mots clefs:</th>
    <td><input type="text" name="keys" value="{$keys}" /></td>
  </tr>
  
  <tr>
    <td class="button" colspan="2">
      <input type="submit" value="rechercher" />
      <input type="reset" value="recommencer" />
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
      <strong><a href="index.php?m=dPcim10&tab=2&code={$curr_master.code}">{$curr_master.code}</a></strong><br />
			{$curr_master.text}
    </td>
  {if ($curr_key+1) is div by 4}
  </tr><tr>
  {/if}
  {/foreach}
  </tr>

</table>