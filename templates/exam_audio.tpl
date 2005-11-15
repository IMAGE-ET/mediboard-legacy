{literal}
<style type="text/css">

table#weber td {
  text-align: center;
}

</style>

<script type="text/javascript">
function pageMain() {

  initGroups("values");
}
</script>
  
{/literal}


<table class="main" id="weber">
  
<tr>
  <th class="title" colspan="2">Audiométrie tonale (Test de Weber)</th>
</tr>

<tr>
  <td>
    {$map_left}
    <img src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;consultation_id={$exam_audio->consultation_id}&amp;side=left" usemap="#graph_left" />
  </td>
  <td>
    {$map_right}
    <img src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;consultation_id={$exam_audio->consultation_id}&amp;side=right" usemap="#graph_right" />
  </td>
</tr>
<tr>
  <td colspan="2">
    <form name="editGauche" action="?m={$m}&amp;a=exam_audio&amp;dialog=1" method="post" onsubmit="return checkForm(this)">
    
    <input type="hidden" name="dosql" value="do_exam_audio_aed" />
    <input type="hidden" name="del" value="0" />
    <input type="hidden" name="examaudio_id" value="{$exam_audio->examaudio_id}" />
    <input type="hidden" name="consultation_id" value="{$exam_audio->consultation_id}" />

    <table class="form">
      <tr class="groupcollapse" id="values" onclick="flipGroup('', 'values');">
        <th class="category" colspan="8">Toutes les valeurs</th>
      </tr>
      <tr class="values">
        <th class="category" colspan="4">Oreille gauche</th>
        <th class="category" colspan="4">Oreille Droite</th>
      </tr>
      <tr class="values">
        <th class="category" colspan="2">Conduction aérienne</th>
        <th class="category" colspan="2">Conduction osseuse</th>
        <th class="category" colspan="2">Conduction aérienne</th>
        <th class="category" colspan="2">Conduction osseuse</th>
      </tr>
      {foreach from=$frequences key=index item=frequence}
      <tr class="values">
        <th><label for="_gauche_aerien[{$index}]" title="Perte de l'oreille gauche pour la fréquence {$frequence} en conduction aérienne">{$frequence} :</label></th>
        <td><input type="text" name="_gauche_aerien[{$index}]" title="num|minMax|-120|10" value="{$exam_audio->_gauche_aerien.$index}" tabindex="{$index+0}" size="4" maxlength="4" /></td>
        <th><label for="_gauche_osseux[{$index}]" title="Perte de l'oreille droite pour la fréquence {$frequence} en conduction osseuse">{$frequence} :</label></th>
        <td><input type="text" name="_gauche_osseux[{$index}]" title="num|minMax|-120|10" value="{$exam_audio->_gauche_osseux.$index}" tabindex="{$index+10}" size="4" maxlength="4" /></td>
        <th><label for="_droite_aerien[{$index}]" title="Perte de l'oreille droite pour la fréquence {$frequence} en conduction aérienne">{$frequence} :</label></th>
        <td><input type="text" name="_droite_aerien[{$index}]" title="num|minMax|-120|10" value="{$exam_audio->_droite_aerien.$index}" tabindex="{$index+20}" size="4" maxlength="4" /></td>
        <th><label for="_droite_osseux[{$index}]" title="Perte de l'oreille droite pour la fréquence {$frequence} en conduction osseuse">{$frequence} :</label></th>
        <td><input type="text" name="_droite_osseux[{$index}]" title="num|minMax|-120|10" value="{$exam_audio->_droite_osseux.$index}" tabindex="{$index+30}" size="4" maxlength="4" /></td>
      </tr>
      {/foreach}
      <tr class="values">
        <td class="button" colspan="8">
          <input type="submit" value="Valider" />
        </td>
      </tr>
    </table>
    
    </form>
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
          <th>{$frequence}</th>
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

<tr>
  <th class="title" colspan="2">Audiométrie vocale</th>
</tr>

<tr>
  <td colspan="2"><img src="?m=dPcabinet&amp;a=graph_audio_vocal&amp;suppressHeaders=1&amp;"/></td>
</tr>

</table>
