{literal}
<style type="text/css">

table#weber td {
  text-align: center;
}

</style>
{/literal}


<table class="main" id="weber">
  
<tr>
  <th class="title" colspan="2">Audiométrie tonale (Test de Weber)</th>
</tr>

<tr>
  <td>
  {$map_left}
  <img src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;side=left" usemap="#graph_left" />
  </td>
  <td>
  {$map_right}
  <img src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;side=right" usemap="#graph_right" />
  </td>
</tr>

<tr>
  <th class="title" colspan="2">Bilan comparé</th>
</tr>

<tr>
  <td colspan="2">
  
    <table class="tbl">
      <tr>
        <th class="text">Fréquences</th>
        {foreach from=$bilan key=frequence item=pertes}
          <th>{$frequence}Hz</th>
        {/foreach}
      </tr>
      <tr>
        <th class="text">
          Conduction aérienne<br />
          (gauche / droite)
        </th>
        {foreach from=$bilan item=pertes}
        <td>
          {$pertes.aerienne.left}dB / {$pertes.aerienne.right}dB<br />
          {assign var="delta" value=$pertes.aerienne.delta}
          &Delta; : {$delta}dB<br />
          {if $delta lt -20}&lt;&lt;
          {elseif $delta lt 0}&lt;=
          {elseif $delta eq 0}==
          {elseif $delta lt 20}=&gt;
          {else}&gt;&gt;
          {/if}
        </td>
        {/foreach}
      </tr>
      <tr>
        <th class="text">
          Conduction aérienne<br />
          (gauche / droite)
        </th>
        {foreach from=$bilan item=pertes}
        <td>
          {$pertes.osseuse.left}dB / {$pertes.osseuse.right}dB<br />
          {assign var="delta" value=$pertes.osseuse.delta}
          &Delta; : {$delta}dB<br />
          {if $delta lt -20}&lt;&lt;
          {elseif $delta lt 0}&lt;=
          {elseif $delta eq 0}==
          {elseif $delta lt 20}=&gt;
          {else}&gt;&gt;
          {/if}
        </td>
        {/foreach}
      </tr>
    </table>

  </td>
</tr>
</table>
