<script type="text/javascript" src="modules/{$m}/javascript/exam_audio.js?build={$mb_version_build}"></script>

<form name="editFrm" action="?m=dPcabinet&amp;a=exam_audio&amp;dialog=1" method="post" onsubmit="return checkForm(this)">

<input type="hidden" name="m" value="dPcabinet" />
<input type="hidden" name="dosql" value="do_exam_audio_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="examaudio_id" value="{$exam_audio->examaudio_id}" />
<input type="hidden" name="consultation_id" value="{$exam_audio->consultation_id}" />

<table class="main" id="weber">

<tr>
  <th class="title" colspan="2">
    Consultation de {$exam_audio->_ref_consult->_ref_patient->_view}
    le {$exam_audio->_ref_consult->_date|date_format:"%A %d/%m/%Y"}
    par le Dr. {$exam_audio->_ref_consult->_ref_chir->_view}
  </th>
</tr>
  
<tr>
  <th class="title" colspan="2">Audiométrie tonale (Test de Weber)</th>
</tr>

<tr>
  <td>
    {$map_tonal_gauche}
    <img id="image_gauche" src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;consultation_id={$exam_audio->consultation_id}&amp;side=gauche" usemap="#graph_tonal_gauche" onclick="changeTonalValueMouseGauche(event)" />
  </td>
  <td>
    {$map_tonal_droite}
    <img id="image_droite" src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;consultation_id={$exam_audio->consultation_id}&amp;side=droite" usemap="#graph_tonal_droite" onclick="changeTonalValueMouseDroite(event)" />
  </td>
</tr>
<tr id="radiointeractive">
  <td colspan="2">
    <input type="radio" name="_conduction" value="aerien" {if $_conduction == "aerien"}checked="checked"{/if} />
    <label for="_conduction_aerien" title="Conduction aérienne pour la saisie intéractive">Conduction aérienne</label>
    <input type="radio" name="_conduction" value="osseux" {if $_conduction == "osseux"}checked="checked"{/if} />
    <label for="_conduction_osseux" title="Conduction osseuse pour la saisie intéractive">Conduction osseuse</label>
    <input type="radio" name="_conduction" value="ipslat" {if $_conduction == "ipslat"}checked="checked"{/if} />
    <label for="_conduction_ipslat" title="Stapédien ipsilatéral pour la saisie intéractive">Stapédien ipsilatéral</label>
    <input type="radio" name="_conduction" value="conlat" {if $_conduction == "conlat"}checked="checked"{/if} />
    <label for="_conduction_conlat" title="Stapédien controlatéral pour la saisie intéractive">Stapédien controlatéral</label>
    <input type="radio" name="_conduction" value="pasrep" {if $_conduction == "pasrep"}checked="checked"{/if} />
    <label for="_conduction_pasrep" title="Pas de réponse pour la saisie intéractive">Pas de réponse</label>
  </td>
</tr>
<tr>
  <td colspan="2">

    <table class="form" id="allvalues">
      <tr class="groupcollapse" id="values" onclick="flipGroup('', 'values');">
        <th class="category" colspan="9">Toutes les valeurs</th>
      </tr>
      <tr class="values">
        <th class="category">Fréquences</th>
        {foreach from=$frequences key=index item=frequence}
        <th class="category">
          {$frequence}
        </th>
        {/foreach}
      </tr>
      <tr class="values">
        <th class="category" colspan="9">Oreille gauche</th>
      </tr>
      <tr class="values">
        <th>Conduction aérienne</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_gauche_aerien[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_gauche_aerien.$index}" tabindex="{$index+10}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>
      
      <tr class="values">
        <th>Conduction osseuse</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_gauche_osseux[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_gauche_osseux.$index}" tabindex="{$index+20}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th>Stapédien ipsilatéral</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_gauche_ipslat[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_gauche_ipslat.$index}" tabindex="{$index+30}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th>Stapédien controlatéral</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_gauche_conlat[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_gauche_conlat.$index}" tabindex="{$index+40}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th>Pas de réponse</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_gauche_pasrep[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_gauche_pasrep.$index}" tabindex="{$index+50}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th class="category" colspan="9">Oreille droite</th>
      </tr>
      <tr class="values">
        <th>Conduction aérienne</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_droite_aerien[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_droite_aerien.$index}" tabindex="{$index+110}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>
      
      <tr class="values">
        <th>Conduction osseuse</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_droite_osseux[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_droite_osseux.$index}" tabindex="{$index+120}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th>stapédien ipsilatéral</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_droite_ipslat[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_droite_ipslat.$index}" tabindex="{$index+130}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th>stapédien controlatéral</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_droite_conlat[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_droite_conlat.$index}" tabindex="{$index+140}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <th>pas de réponse</th>
        {foreach from=$frequences key=index item=frequence}
        <td><input type="text" name="_droite_pasrep[{$index}]" title="num|minMax|-10|120" value="{$exam_audio->_droite_pasrep.$index}" tabindex="{$index+150}" size="4" maxlength="4" /></td>
        {/foreach}
      </tr>

      <tr class="values">
        <td class="button" colspan="9">
          <input type="submit" value="Valider" />
        </td>
      </tr>
    </table>
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
        <th />
        <th colspan="8">Conduction aérienne</th>
      </tr>
      <tr class="moyenne">
        <th class="text">
          Moyenne gauche
        </th>
        <td colspan="2" />
        <td class="aerien" colspan="4">{$exam_audio->_moyenne_gauche_aerien}dB</td>
        <td colspan="2" />
      </tr>
      <tr class="moyenne">
        <th class="text">
          Moyenne droite
        </th>
        <td colspan="2" />
        <td class="aerien" colspan="4">{$exam_audio->_moyenne_droite_aerien}dB</td>
        <td colspan="2" />
      </tr>
      <tr>
        <th class="text">
          Comparaison<br />
          (gauche / droite)
        </th>
        {foreach from=$bilan item=pertes}
        <td>
          {$pertes.aerien.gauche}dB / {$pertes.aerien.droite}dB<br />
          {assign var="delta" value=$pertes.aerien.delta}
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
        <th />
        <th colspan="8">Conduction osseuse</th>
      </tr>
      <tr class="moyenne">
        <th class="text">
          Moyenne gauche
        </th>
        <td colspan="2" />
        <td class="osseux" colspan="4">{$exam_audio->_moyenne_gauche_osseux}dB</td>
        <td colspan="2" />
      </tr>
      <tr class="moyenne">
        <th class="text">
          Moyenne droite
        </th>
        <td colspan="2" />
        <td class="osseux" colspan="4">{$exam_audio->_moyenne_droite_osseux}dB</td>
        <td colspan="2" />
      </tr>
      <tr>
        <th class="text">
          Comparaison<br />
          (gauche / droite)
        </th>
        {foreach from=$bilan item=pertes}
        <td>
          {$pertes.osseux.gauche}dB / {$pertes.osseux.droite}dB<br />
          {assign var="delta" value=$pertes.osseux.delta}
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

  <th class="title" colspan="2"><a name="vocal"></a>Audiométrie vocale</th>
</tr>

<tr>
  <td colspan="2">
    {$map_vocal}
    <img id="image_vocal" src="?m=dPcabinet&amp;a=graph_audio_vocal&amp;suppressHeaders=1&amp;" usemap="#graph_vocal" onclick="changeVocalValueMouse(event)" /></td>
</tr>

<tr id="radiointeractive">
  <td colspan="2">
    <input type="radio" name="_oreille" value="gauche" {if $_oreille == "gauche"}checked="checked"{/if} />
    <label for="_oreille_gauche" title="Oreille gauche pour la saisie intéractive">Oreille gauche</label>
    <input type="radio" name="_oreille" value="droite" {if $_oreille == "droite"}checked="checked"{/if} />
    <label for="_oreille_droite" title="Oreille gauche pour la saisie intéractive">Oreille droite</label>

    <table class="form" id="allvocales">
      <tr class="groupcollapse" id="vocales" onclick="flipGroup('', 'vocales');">
        <th class="category" colspan="9">Toutes les valeurs</th>
      </tr>
      <tr class="vocales">
        <th class="category">Fréquences</th>
        {foreach from=$frequences key=index item=frequence}
        <th class="category">
          Point #{$index}<br />dB / %
        </th>
        {/foreach}
      </tr>
      <tr class="vocales">
        <th>Oreille gauche :</th>
        {foreach from=$frequences key=index item=frequence}
        <td>
          <input type="text" name="_gauche_vocale[{$index}][0]" title="num|minMax|0|120" value="{$exam_audio->_gauche_vocale.$index.0}" tabindex="{$index*2+200}" size="1" maxlength="3" />
          <input type="text" name="_gauche_vocale[{$index}][1]" title="num|minMax|0|100" value="{$exam_audio->_gauche_vocale.$index.1}" tabindex="{$index*2+201}" size="1" maxlength="3" />
        </td>
        {/foreach}
      </tr>
      <tr class="vocales">
        <th>Oreille droite :</th>
        {foreach from=$frequences key=index item=frequence}
        <td>
          <input type="text" name="_droite_vocale[{$index}][0]" title="num|minMax|0|120" value="{$exam_audio->_droite_vocale.$index.0}" tabindex="{$index*2+220}" size="1" maxlength="3" />
          <input type="text" name="_droite_vocale[{$index}][1]" title="num|minMax|0|100" value="{$exam_audio->_droite_vocale.$index.1}" tabindex="{$index*2+221}" size="1" maxlength="3" />
        </td>
        {/foreach}
      </tr>
      <tr class="vocales">
        <td class="button" colspan="9">
          <input type="submit" value="Valider" />
        </td>
      </tr>
          
    </table>

    
  </td>
</tr>

</table>

    
</form>

