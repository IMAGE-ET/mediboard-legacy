{literal}
<script language="javascript">

function popCode(type) {
  var url = './index.php?m=dPplanningOp';
  url += '&a=code_selector';
  url += '&dialog=1';
  url += '&chir='+ {/literal}{$app->user_id}{literal};
  url += '&type='+ type;
  popup(600, 500, url, type);
}

function setCode( key, type ){
  var f = document.codeFrm;

  if (key != '') {
    if(type == 'ccam') {
      f.CCAM_code.value = key;
      window.CCAM_code = key;
    }
    else if(type == 'ccam2') {
      f.CCAM_code2.value = key;
      window.CCAM_code2 = key;
    }
    else {
      f.CIM10_code.value = key;
      window.CIM10_code = key;
    }
  }
}

function pageMain() {
  Calendar.setup( {
    displayArea : "paramFrm_deb_fr",
    inputField  : "paramFrm_deb",
    ifFormat    : "%Y-%m-%d",
    daFormat    : "%d %b %Y",
    button      : "trigger_paramFrm_deb",
    showsTime   : true
    } );
  Calendar.setup( {
    displayArea : "paramFrm_fin_fr",
    inputField  : "paramFrm_fin",
    ifFormat    : "%Y-%m-%d",
    daFormat    : "%d %b %Y",
    button      : "trigger_paramFrm_fin",
    showsTime   : true
    } );
}

</script>
{/literal}

<form name="codeFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">
<table class="form">
  <tr>
    <th><label for="codeFrm_CIM10_code">Diagnostic (CIM10):</label></th>
    <td><input type="text" name="CIM10_code" size="10" value="" /><button type="button" onclick="popCode('cim10')">Choisir un code</button></td>
    <th><label for="codeFrm_debut">Début:</label></th>
    <td class="date">
      <div id="paramFrm_deb_fr"></div>
      <input type="hidden" name="deb" value="" />
      <a id="trigger_paramFrm_deb" href="#" title="Choisir une date de début">
        <img src="./images/calendar.gif" width="24" height="12" alt="calendar" />
      </a>
    </td>
  </tr>
  <tr>
    <th>Actif:</th>
    <td><input type="checkbox"></td>
    <th><label for="codeFrm_fin">Fin:</label></th>
    <td class="date">
      <div id="paramFrm_fin_fr"></div>
      <input type="hidden" name="fin" value="" />
      <a id="trigger_paramFrm_fin" href="#" title="Choisir une date de fin">
        <img src="./images/calendar.gif" width="24" height="12" alt="calendar" />
      </a>
    </td>
  </tr>
  <tr>
    <td colspan="4" class="button"><button type="submit">Ajouter</button></td>
  </tr>
</table>
</form>
<table class="tbl">
  <tr>
    <th>Diagnostic CIM10</th><th>Date</th><th>Fin</th>
  </tr>
  {foreach from=$listAnt item=curr_ant}
  {if $curr_ant->type == "CIM10"}
  <tr>
    <td>
      <ul>
        {foreach from=$curr_ant->_ref_cim10.levelsup item=curr_level}
        {if ($curr_level.sid != 0) && ($curr_level.code|truncate:1:"":true != "(")}
        <li><strong>{$curr_level.code}</strong>: {$curr_level.text}</li>
        {/if}
        {/foreach}
      </ul>
    </td>
    <td>{$curr_ant->debut|date_format:"%b %Y"}</td>
    {if $curr_ant->actif}
    <td>Actif</td>
    {else}
    <td>{$curr_ant->fin|date_format:"%b %Y"}</td>
    {/if}
  </tr>
  {/if}
  {/foreach}
</table>