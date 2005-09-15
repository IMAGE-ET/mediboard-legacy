<!-- $Id$ -->

{literal}
<script language="javascript">

var http_request = false;

function makeRequest(url) {

  http_request = false;

  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    http_request = new XMLHttpRequest();
    if (http_request.overrideMimeType) {
      http_request.overrideMimeType('text/xml');
    }
  } else if (window.ActiveXObject) { // IE
    try {
      http_request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        http_request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
    }
  }

  if (!http_request) {
    alert('Giving up :( Cannot create an XMLHTTP instance');
    return false;
  }
  http_request.onreadystatechange = alertContents;
  http_request.open('GET', url, true);
  http_request.send(null);
}

function alertContents() {
  if (http_request.readyState == 4) {
    if (http_request.status == 200) {
      myNode = document.getElementById("infoPat");
      myNode.innerHTML = http_request.responseText;
    } else {
      alert('There was a problem with the request.');
    }
  }
}

function checkForm() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id)
    if (field.value == 0) {
      alert("Praticien manquant");
      popChir();
      return false;
    }

  if (field = form.patient_id)
    if (field.value == 0) {
      alert("Patient manquant");
      popPat();
      return false;
    }

  if (field = form.plageconsult_id)
    if (field.value == 0) {
      alert("Jour de consultation non selectionné");
      popRDV();
      return false;
    }

  return true;
}

function checkChir() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id) {
    if (field.value == 0) {
      alert("Praticien manquant");
      popChir();
      return false;
    }
  }

  return true;
}

function popChir() {
  var url = './index.php?m=mediusers';
  url += '&a=chir_selector';
  url += '&dialog=1';
  popup(400, 250, url, 'Praticien');
}

function setChir( key, val ){
  var f = document.editFrm;
  f.chir_id.value = key;
  f._chir_name.value = val;
  window.chir_id = key;
  window._chir_name = val;
}

function popPat() {
  var url = './index.php?m=dPpatients';
  url += '&a=pat_selector';
  url += '&dialog=1';
  popup(800, 500, url, 'Patient');
  myNode = document.getElementById("infoPat");
  myNode.innerHTML = "";
}

function setPat( key, val ) {
  var f = document.editFrm;

  if (val != '') {
    f.patient_id.value = key;
    f._pat_name.value = val;
    myNode = document.getElementById("clickPat");
    myNode.setAttribute("onclick", "makeRequest('index.php?m=dPpatients&dialog=1&a=httpreq_get_last_refs&patient_id=" + key + "')");
    myNode.innerHTML = "++ Infos patient (cliquez pour afficher) ++";
  }
}

function popRDV() {
  var url = './index.php?m=dPcabinet';
  url += '&a=plage_selector';
  url += '&dialog=1';
  url += '&chir=' + document.editFrm.chir_id.value;
  popup(800, 600, url, 'Plage');
}

function setRDV( hour, min, id, date, freq, chirid, chirname ) {
  var f = document.editFrm;
  f.plageconsult_id.value = id;
  f._date.value = date;
  f._hour.value = hour;
  f._min.value = min;
  f.duree.value = freq;
  f.chir_id.value = chirid;
  f._chir_name.value = chirname;
}

</script>
{/literal}

<form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">

<input type="hidden" name="dosql" value="do_consultation_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
<input type="hidden" name="compte_rendu" value="{$consult->compte_rendu|escape:"html"}" />
<input type="hidden" name="annule" value="0" />
<input type="hidden" name="chrono" value="{$smarty.const.CC_PLANIFIE}" />

<table class="main" style="margin: 4px; border-spacing: 0px;">
  {if $consult->consultation_id}
  <tr>
    <td coslpan="2"><strong><a href="index.php?m={$m}&amp;consultation_id=0">Créer une nouvelle consultation</a></strong></td>
  </tr>
  {/if}
  <tr>
    {if $consult->consultation_id}
      <th colspan="2" class="title" colspan="5" style="color: #f00;">Modification de la consultation de {$pat->_view} pour le Dr. {$chir->_view}</th>
    {else}
      <th colspan="2" class="title" colspan="5">Création d'une consultation</th>
    {/if}
  </tr>
  <tr>
    <td>
  
      <table class="form">
        <tr><th class="category" colspan="3">Informations sur la consultation</th></tr>
        
        <tr>
          <th class="mandatory">
            <input type="hidden" name="chir_id" value="{$chir->user_id}" />
            <label for="editFrm_chir_id">Praticien:</label>
          </th>
            <td class="readonly">
              <input type="text" name="_chir_name" size="30" value="{$chir->_view}" readonly="readonly" />
              <button type="button" onclick="setChir(0, '')">
                <img src="modules/{$m}/images/cross.png" />
              </button>
            </td>
            <td class="button"><input type="button" value="choisir un praticien" onclick="popChir()"></td>
        </tr>

        <tr>
          <th class="mandatory">
            <input type="hidden" name="patient_id" value="{$pat->patient_id}" />
            <label for="editFrm_chir_id">Patient:</label>
          </th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="{$pat->_view}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        
        <tr>
          <th><label for="editFrm_motif">Motif</label></th>
          <td colspan="2"><textarea name="motif" rows="3">{$consult->motif}</textarea></td>
        </tr>

        <tr>
          <th><label for="editFrm_rques">Remarques</label></th>
          <td colspan="2"><textarea name="rques" rows="3">{$consult->rques}</textarea></td>
        </tr>

      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">Rendez-vous</th></tr>

        <tr>
          <th><label for="editFrm_premiere">Consultation:</label></th>
          <td>
            <input type="checkbox" name="_check_premiere" value="1" {if $consult->_check_premiere} checked="checked" {/if} />
            <label for="editFrm__check_premiere">Première consultation</label>
          </td>
          <td rowspan="4" class="button">
            <input type="button" value="Selectionner" onclick="popRDV()" />
          </td>
        </tr>

        <tr>
          <th><label for="editFrm__date">Date:</label></th>
          <td class="readonly">
            <input type="text" name="_date" value="{$consult->_date|date_format:"%d/%m/%Y"}" readonly="readonly" />
            <input type="hidden" name="plageconsult_id" value="{$consult->plageconsult_id}" />
          </td>
        </tr>

        <tr>
          <th><label for="editFrm__hour">Heure:</label></th>
          <td class="readonly">
            <input type="text" name="_hour" value="{$consult->_hour}" size="3" readonly="readonly" /> h
            <input type="text" name="_min" value="{$consult->_min}" size="3" readonly="readonly" />
          </td>
        </tr>
        <tr>
          <th><label for="editFrm__duree">Durée:</label></th>
          <td>
            <select name="duree">
              <option value="1" {if $consult->duree == 1} selected="selected" {/if}>simple</option>
              <option value="2" {if $consult->duree == 2} selected="selected" {/if}>double</option>
              <option value="3" {if $consult->duree == 3} selected="selected" {/if}>triple</option>
            </select>
          </td>
        </tr>

      </table>
      
      <table class="form">
        <tr>
          {if $pat->patient_id}
          <th id="clickPat" class="category" onclick="makeRequest('index.php?m=dPpatients&dialog=1&a=httpreq_get_last_refs&patient_id={$pat->patient_id}')">
            ++ Infos patient (cliquez pour afficher) ++
          {else}
          <th id="clickPat" class="category">
            Infos patient (indisponibles)
          {/if}
          </th>
        </tr>
        <tr>
          <td id="infoPat"></td>
        </tr>
      </table>
    
    </td>
  </tr>

  <tr>
    <td colspan="2">

      <table class="form">
        <tr>
          <td class="button">
          {if $consult->consultation_id}
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Modifier" />
            <input type="button" value="Supprimer" style="cursor: pointer;" onclick="confirmDeletion(this.form, 'la consultation de', '{$consult->_ref_patient->_view|escape:javascript}')" />
          {else}
            <input type="submit" value="Créer" />
          {/if}
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table>

</form>