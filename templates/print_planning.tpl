<!-- $Id$ -->

{literal}
<script language="javascript">
function checkForm() {
  var form = document.paramFrm;
    
  if (form.date_debut.value > form.date_fin.value) {
    alert("Date de début superieure à la date de fin");
    return false;
  }
  popPlanning();
}

var calendarField = '';
var calWin = null;

function popCalendar( field ){
  calendarField = field;
  idate = eval( 'document.paramFrm.date_' + field + '.value' );
  popup(280, 250, 'index.php?m=public&a=calendar&dialog=1&callback=setCalendar&date=' + idate, 'calwin');
}

function setCalendar( idate, fdate ) {
  fld_date = eval( 'document.paramFrm.date_' + calendarField );
  fld_fdate = eval( 'document.paramFrm.' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}

function popCode(type) {
  var chir = document.paramFrm.chir.value;
  var url = './index.php?m=dPplanningOp&a=code_selector&dialog=1';
  url += '&type='+type;
  url += '&chir='+chir;
  popup(600, 500, url, type);
}

function setCode( key, type ) {
  var f = document.paramFrm;
   if (key != '') {
    if(type == 'ccam'){
      f.CCAM_code.value = key;
        window.CCAM_code = key;
    }
    else{
      f.CIM10_code.value = key;
        window.CIM10_code = key;
    }
  }
}

function popPlanning() {
  var debut = document.paramFrm.date_debut.value;
  var fin = document.paramFrm.date_fin.value;
  var vide = document.paramFrm.vide.checked;
  var CCAM = document.paramFrm.CCAM_code.value;
  var type = document.paramFrm.type.value;
  var chir = document.paramFrm.chir.value;
  var spe = document.paramFrm.spe.value;
  var salle = document.paramFrm.salle.value;
  var url = './index.php?m=dPbloc&a=view_planning&dialog=1';
  url += '&debut=' + debut;
  url += '&fin=' + fin;
  url += '&vide=' + vide;
  url += '&CCAM=' + CCAM;
  url += '&type=' + type;
  url += '&chir=' + chir;
  url += '&spe=' + spe;
  url += '&salle=' + salle;
  popup(700, 550, url, 'Planning');
}
</script>
{/literal}

<form name="paramFrm" action="?m=dPbloc" method="post" onsubmit="return checkForm()">

<table class="main">
  <tr>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">Choix de la periode</th></tr>
        <tr>
          <th><label for="paramFrm_debut" title="Date de début de la recherche">Début:</label></th>
          <td class="readonly" colspan="2">
            <input type="hidden" name="date_debut" value="{$todayi}" />
            <input type="text" name="debut" value="{$todayf}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'debut', 'debut');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
        <tr>
          <th><label for="paramFrm_fin" title="Date de fin de la recherche">Fin:</label></th>
          <td class="readonly" colspan="2">
            <input type="hidden" name="date_fin" value="{$todayi}" />
            <input type="text" name="fin" value="{$todayf}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'fin', 'fin');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
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
	            <option value="{$curr_salle.id}">{$curr_salle.nom}</option>
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