<table class="fullCode">
  <tr>
    <th colspan="2"><h1>&ldquo;{$master.libelle}&rdquo;</h1></th>
  </tr>
  
  <tr>
    <td class="leftPane">
      <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <input type="hidden" name="m" value="{$m}">
      <input type="hidden" name="tab" value="{$tab}">

      <table class="form">
        <tr>
          <th class="mandatory">Code de l'acte:</th>
          <td>
            <input tabindex="1" type="text" name="code" value="{$master.code}">
            <input tabindex="2" type="submit" value="afficher">
          </td>
        </tr>
      </table>

      </form>
    </td>

    {if $canEdit && $master.levelinf.0.sid == 0}
    <td class="rightPane">
      <form name="addFavoris" action="./index.php?m={$m}" method="post">
      <input type="hidden" name="dosql" value="do_favoris_aed">
      <input type="hidden" name="del" value="0">
      <input type="hidden" name="favoris_code" value="{$master.code}">
      <input type="hidden" name="favoris_user" value="{$user}">
      <input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris">
      </form>
    </td>
    {/if}
  </tr>

  <tr>
    <td class="pane" colspan="2">
      <strong>Informations sur ce code:</strong>
      <ul>
        {if $master.descr != ""}
        <li>
          Description:
          <ul>
            {foreach from=$master.descr item=curr_descr}
            <li>{$curr_descr}</li>
            {/foreach}
          </ul>
        </li>
        {/if}
        {if $master.exclude != ""}
        <li>
          Exclusions:
          <ul>
            {foreach from=$master.exclude item=curr_exclude}
            <li>{$curr_exclude.text} (code: <a href="index.php?m={$m}&amp;t{$tab}&amp;code={$curr_exclude.code}"><strong>{$curr_exclude.code}</strong></a>)</li>
            {/foreach}
          </ul>
        </li>
        {/if}
        {if $master.glossaire != ""}
        <li>
          Glossaire:
          <ul>
            {foreach from=$master.glossaire item=curr_glossaire}
            <li>{$curr_glossaire}</li>
            {/foreach}
          </ul>
        </li>
        {/if}
        {if $master.include != ""}
        <li>
          Inclusions:
          <ul>
            {foreach from=$master.include item=curr_include}
            <li>{$curr_include}</li>
            {/foreach}
          </ul>
        </li>
        {/if}
        {if $master.indir != ""}
        <li>
          Exclusions indirectes:
          <ul>
            {foreach from=$master.indir item=curr_indir}
            <li>{$curr_indir}</li>
            {/foreach}
          </ul>
        </li>
        {/if}
        {if $master.note != ""}
        <li>
          Notes:
          <ul>
            {foreach from=$master.note item=curr_note}
            <li>{$curr_note}</li>
            {/foreach}
          </ul>
        </li>
        {/if}
      </ul>
    </td>
  </tr>

  <tr>
    {if $master.levelsup.0.sid != 0}
    <td class="pane">
      <strong>Codes de niveau supérieur:</strong>
      <ul>
        {foreach from=$master.levelsup item=curr_level}
        {if $curr_level.sid != 0}
        <li><a href="index.php?m={$m}&amp;tab={$tab}&amp;code={$curr_level.code}"><strong>{$curr_level.code}</strong></a>: {$curr_level.text}</li>
        {/if}
        {/foreach}
      </ul>
    </td>
    {/if}
    {if $master.levelinf.0.sid != 0}
    <td class="pane">
      <strong>Codes de niveau inferieur :</strong>
      <ul>
        {foreach from=$master.levelinf item=curr_level}
        {if $curr_level.sid != 0}
        <li><a href="index.php?m={$m}&amp;tab={$tab}&amp;code={$curr_level.code}"><strong>{$curr_level.code}</strong></a> : {$curr_level.text}</li>
        {/if}
        {/foreach}
      </ul>
    </td>
    {/if}
  </tr>
</table>