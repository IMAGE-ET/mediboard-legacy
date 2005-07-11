<!-- $Id$ -->

{literal}
<script language="javascript">
function checkForm() {
  var form = document.paramFrm;
    
  if (form.date_debut.value > form.date_fin.value) {
    alert("Date de début superieure à la date de fin");
    return false;
  }
  popPlages();
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

function popPlages() {
  var debut = document.paramFrm.date_debut.value;
  var fin = document.paramFrm.date_fin.value;
  var chir = document.paramFrm.chir.value;
  var url = './index.php?m=dPcabinet&a=print_plages&dialog=1';
  url = url + '&debut=' + debut;
  url = url + '&fin=' + fin;
  url = url + '&chir=' + chir;
  popup(700, 550, url, 'Planning');
}
</script>
{/literal}

<form name="paramFrm" action="?m=dPcabinet" method="post" onsubmit="return checkForm()">

<table class="main">
  <tr>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">Choix de la periode</th></tr>
        <tr>
          <th>Début:</th>
          <td class="readonly" colspan="2">
            <input type="hidden" name="date_debut" value="{$todayi}" />
            <input type="text" name="debut" value="{$todayf}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'debut', 'debut');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
        <tr>
          <th>Fin:</th>
          <td class="readonly" colspan="2">
            <input type="hidden" name="date_fin" value="{$todayi}" />
            <input type="text" name="fin" value="{$todayf}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'fin', 'fin');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="2">Choix des paramètres de tri</th></tr>
        <tr>
          <th>Chirurgien:</th>
          <td><select name="chir">
            <option value="0">-- Tous</option>
            {foreach from=$listChir item=curr_chir}
	            <option value="{$curr_chir->user_id}">{$curr_chir->_view}</option>
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