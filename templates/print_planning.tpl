<!-- $Id$ -->

{literal}
<script language="javascript">
function checkForm() {
  var form = document.paramFrm;
    
  if (form.deb.value > form.fin.value) {
    alert("Date de début superieure à la date de fin");
    return false;
  }
  
  popPlanning();
}

function popCode(type) {
  var chir = document.paramFrm.chir.value;
  var url = './index.php?m=dPplanningOp&a=code_selector&dialog=1';
  url += '&type='+type;
  url += '&chir='+chir;
  popup(600, 500, url, type);
}

function setCode( key, type ) {
  var form = document.paramFrm;
  var field = type == 'ccam' ? form.CCAM_code : form.CIM10_code;
  field.value = key;
}

function popPlanning() {
  form = document.paramFrm;
  var url = './index.php?m=dPbloc&a=view_planning&dialog=1';
  url += '&deb='   + form.deb.value;
  url += '&fin='   + form.fin.value;
  url += '&vide='  + form.vide.checked;
  url += '&CCAM='  + form.CCAM_code.value;
  url += '&type='  + form.type.value;
  url += '&chir='  + form.chir.value;
  url += '&spe='   + form.spe.value;
  url += '&salle=' + form.salle.value;
  popup(700, 550, url, 'Planning');
}

function pageMain() {
  regPopupCalendar("paramFrm", "deb");
  regPopupCalendar("paramFrm", "fin");
}

</script>
{/literal}

<form name="paramFrm" action="?m=dPbloc" method="post" onsubmit="return checkForm()">

<table class="main">
  <tr>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">Choix de la période</th></tr>
        <tr>
          <th><label for="paramFrm_deb" title="Date de début de la recherche">Début:</label></th>
          <td class="date" colspan="2">
            <div id="paramFrm_deb_da">{$deb|date_format:"%d/%m/%Y"}</div>
            <input type="hidden" name="deb" value="{$deb}" />
            <img id="paramFrm_deb_trigger" src="./images/calendar.gif" alt="calendar" title="Choisir une date de début"/>
          </td>
        </tr>
        <tr>
          <th><label for="paramFrm_fin" title="Date de fin de la recherche">Fin:</label></th>
          <td class="date" colspan="2">
            <div id="paramFrm_fin_da">{$fin|date_format:"%d/%m/%Y"}</div>
            <input type="hidden" name="fin" value="{$fin}" />
            <img id="paramFrm_fin_trigger" src="./images/calendar.gif" alt="calendar" title="Choisir une date de fin"/>
          </td>
        </tr>
        <tr>
          <th><label for="paramFrm_vide" title="Afficher ou cacher les plages vides dans le rapport">Afficher les plages vides:</label></th>
          <td colspan="2"><input type="checkbox" name="vide" /></td>
        </tr>
        <tr>
          <th><label for="paramFrm_CCAM_code" title="Rechercher en fonction d'un code CCAM">Code CCAM:</label></th>
          <td><input type="text" name="CCAM_code" size="10" value="" /></td>
          <td class="button"><input type="button" value="sélectionner un code" onclick="popCode('ccam')"/></td>
        </tr>
      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="2">Choix des paramètres de tri</th></tr>
        <tr>
          <th><label for="paramFrm_type" title="Recherche en fonction de la présence dans le planning">Affichage des interventions:</label></th>
          <td><select name="type">
            <option value="0">&mdash; Toutes les interventions &mdash;</option>
            <option value="1">insérées dans le planning</option>
            <option value="2">à insérer dans le planning</option>
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_chir" title="Rechercher en fonction du praticien">Praticiens:</label></th>
          <td><select name="chir">
            <option value="0">&mdash; Tous les praticiens &mdash;</option>
            {foreach from=$listPrat item=curr_prat}
              <option value="{$curr_prat->user_id}">{$curr_prat->_view}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_spe" title="Rechercher en fonction d'une spécialité opératoire">Specialité:</label></th>
          <td><select name="spe">
            <option value="0">&mdash; Toutes les spécialités &mdash;</option>
            {foreach from=$listSpec item=curr_spec}
              <option value="{$curr_spec->function_id}">{$curr_spec->text}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_salle" title="Rechercher en fonciton d'une salle d'opération">Salle:</label></th>
          <td><select name="salle">
            <option value="0">&mdash; Toutes les salles &mdash;</option>
            {foreach from=$listSalles item=curr_salle}
	            <option value="{$curr_salle->id}">{$curr_salle->nom}</option>
            {/foreach}
          </select></td>
        </tr>
      </table>

    </td>
  </tr>
  <tr>
    <td colspan="2">

      <table class="form"><tr><td class="button"><input type="button" value="Afficher" onclick="checkForm()"</td></tr></table>

    </td>
  </tr>
</table>