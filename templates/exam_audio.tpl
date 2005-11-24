{literal}
<script type="text/javascript">

function getCheckedValue(radioObj) {
  if(!radioObj)
    return "";
  var radioLength = radioObj.length;
  if(radioLength == undefined)
    if(radioObj.checked)
      return radioObj.value;
    else
      return "";
  for(var i = 0; i < radioLength; i++) {
    if(radioObj[i].checked) {
      return radioObj[i].value;
    }
  }
  return "";
}

var iMinPerte = -120;
var iMaxPerte = 10;
var iMaxIndexFrequence = 7;
  
function changeValue(sCote, sConduction, iFrequence, iNewValue) {
  if (sConduction == "osseuse" ) sConductionElement = "osseux";
  if (sConduction == "aerienne") sConductionElement = "aerien";
   
  var oForm = document.editFrm;
  var sElementName = printf("_%s_%s[%i]", sCote, sConductionElement, iFrequence);
  var oElement = oForm.elements[sElementName];
  var oLabel = getLabelFor(oElement);
  var sFrequence = oLabel.innerHTML;
  
  if (!iNewValue) {
    sInvite = printf("Modifier la perte pour l'oreille %s en conduction %s à %s'", sCote, sConduction, sFrequence);
    sAdvice = printf("Merci de fournir une valeur comprise (en dB) entre %i et %i", iMinPerte, iMinPerte);
  
    if (iNewValue = prompt(sInvite + "\n" + sAdvice, oElement.value)) {
      if (isNaN(iNewValue) || iNewValue < iMinPerte || iNewValue > iMaxPerte) {
        alert("Valeur incorrecte : " + iNewValue + "\n" + sAdvice);
        return;
      }
    }
    
    return;
  }

  oElement.value = iNewValue;
  oForm.submit();
}

function changeValueMouse(event, sCote) {
  var oImg = document.getElementById("image_" + sCote);

  var oGraphMargins = {
    left  : 45,
    top   : 40,
    right : 20,
    bottom: 20
  }
  
  var oGraphRect = {
    x : oImg.x + oGraphMargins.left,
    y : oImg.y + oGraphMargins.top ,
    w : oImg.width  - oGraphMargins.left - oGraphMargins.right ,
    h : oImg.height - oGraphMargins.top  - oGraphMargins.bottom
  }

  var iStep = oGraphRect.w / (iMaxIndexFrequence+1);
  var iRelatX = event.pageX - oGraphRect.x; 
  var iRelatY = event.pageY - oGraphRect.y;

  var iSelectedIndex = parseInt(iRelatX / iStep);
  var iSelectedDb = parseInt(iMaxPerte - (iRelatY / oGraphRect.h * (iMaxPerte - iMinPerte)));
      
  if (iRelatX < 0 || iRelatX > oGraphRect.w || iSelectedDb < iMinPerte || iSelectedDb > iMaxPerte) {
    alert("Merci de cliquer à l'intérieur de l'audiogramme");
    return;
  }
  
  oForm = document.editFrm;
  oElement = oForm._conduction;
  changeValue(sCote, getCheckedValue(oElement), iSelectedIndex, iSelectedDb);
}

function changeValueMouseGauche(event) {
  changeValueMouse(event, "gauche");
}

function changeValueMouseDroite(event) {
  changeValueMouse(event, "droite");
}

function pageMain() {
  
  initGroups("values");  
}
</script>
  
{/literal}

<form name="editFrm" action="?m=dPcabinet&amp;a=exam_audio&amp;dialog=1" method="post" onsubmit="return checkForm(this)">

<table class="main" id="weber">
  
<tr>
  <th class="title" colspan="2">Audiométrie tonale (Test de Weber)</th>
</tr>

<tr>
  <td>
    {$map_left}
    <img id="image_gauche" src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;consultation_id={$exam_audio->consultation_id}&amp;side=left" usemap="#graph_left" onclick="changeValueMouseGauche(event)" />
  </td>
  <td>
    {$map_right}
    <img id="image_droite" src="?m=dPcabinet&amp;a=graph_audio_tonal&amp;suppressHeaders=1&amp;consultation_id={$exam_audio->consultation_id}&amp;side=right" usemap="#graph_right" onclick="changeValueMouseDroite(event)" />
  </td>
</tr>
<tr>
  <td colspan="2">
    <input type="radio" name="_conduction" value="osseuse" {if $_conduction == "osseuse"}checked="checked"{/if} />
    <label for="_conduction_osseuse" title="Conduction osseuse pour la saisie intéractive">Conduction osseuse</label>
    <input type="radio" name="_conduction" value="aerienne" {if $_conduction == "aerienne"}checked="checked"{/if} />
    <label for="_conduction_aerienne" title="Conduction aérienne pour la saisie intéractive">Conduction aérienne</label>
  </td>
</tr>
<tr>
  <td colspan="2">
    <input type="hidden" name="m" value="dPcabinet" />
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

    
</form>

