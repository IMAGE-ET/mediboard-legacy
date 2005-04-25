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
  var url = "index.php?m=public&a=calendar&dialog=1&callback=setCalendar";
  url += "&date=" + idate;
  popup(280, 250, url, 'calwin');
}

function setCalendar( idate, fdate ) {
  fld_date = eval( 'document.paramFrm.date_' + calendarField );
  fld_fdate = eval( 'document.paramFrm.' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}

function popPlanning() {
  var debut = document.paramFrm.date_debut.value;
  var fin = document.paramFrm.date_fin.value;
  var ordre = document.paramFrm.ordre.value;
  var service = document.paramFrm.service.value;
  var type = document.paramFrm.type.value;
  var chir = document.paramFrm.chir.value;
  var spe = document.paramFrm.spe.value;
  var conv = document.paramFrm.conv.value;
  var url = '?m=dPhospi&a=print_planning&dialog=1';
  url += '&debut=' + debut;
  url += '&fin=' + fin;
  url += '&ordre=' + ordre;
  url += '&service=' + service;
  url += '&type=' + type;
  url += '&chir=' + chir;
  url += '&spe=' + spe;
  url += '&conv=' + conv;
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
          <th><label for="paramFrm_debut">Début:</label></th>
          <td class="readonly" colspan="2">
            <input type="hidden" name="date_debut" value="{$todayi}" />
            <input type="text" name="debut" value="{$todayf}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'debut', 'debut');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
        <tr>
          <th><label for="paramFrm_fin">Fin:</label></th>
          <td class="readonly" colspan="2">
            <input type="hidden" name="date_fin" value="{$todayi}" />
            <input type="text" name="fin" value="{$todayf}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'fin', 'fin');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
        <tr>
          <th><label for="paramFrm_ordre">Classement des admissions:</label></th>
          <td><select name="ordre">
            <option value="heure">par heure d'admission</option>
            <option value="nom">par nom du patient</option>
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_service">Service:</label></th>
          <td><select name="service">
            <option value="0">&mdash; Tous les services &mdash;</option>
            {foreach from=$listServ item=curr_serv}
            <option value="{$curr_serv->service_id}">{$curr_serv->nom}</option>
            {/foreach}
          </select></td>
        </tr>
      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="2">Paramètres de filtrage</th></tr>
        <tr>
          <th><label for="paramFrm_type">Type d'admission:</label></th>
          <td><select name="type">
            <option value="0">&mdash; Tous types d'admission &mdash;</option>
            <option value="ambu">Ambulatoire</option>
            <option value="exte">Externe</option>
            <option value="comp">Complete</option>
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_chir">Praticien:</label></th>
          <td><select name="chir">
            <option value="0">&mdash; Tous les praticiens &mdash;</option>
            {foreach from=$listPrat item=curr_prat}
              <option value="{$curr_prat->user_id}">{$curr_prat->user_last_name} {$curr_prat->user_first_name}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_spe">Specialité:</label></th>
          <td><select name="spe">
            <option value="0">&mdash; Toutes les spécialités &mdash;</option>
            {foreach from=$listSpec item=curr_spec}
              <option value="{$curr_spec->function_id}">{$curr_spec->text}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th><label for="paramFrm_conv">Convalescence:</label></th>
          <td><select name="conv">
            <option value="0">&mdash; Indifférent &mdash;</option>
	        <option value="o">avec</option>
	        <option value="n">sans</option>
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