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
  neo = window.open( 'index.php?m=public&a=calendar&dialog=1&callback=setCalendar&date=' + idate, 'calwin', 'top=250,left=250,width=280, height=250, scollbars=false' );
  if(neo.window.focus){neo.window.focus();}
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
  var type = document.paramFrm.type.value;
  var chir = document.paramFrm.chir.value;
  var spe = document.paramFrm.spe.value;
  var conv = document.paramFrm.conv.value;
  var url = './index.php?m=dPhospi&a=print_planning&dialog=1';
  url = url + '&debut=' + debut;
  url = url + '&fin=' + fin;
  url = url + '&type=' + type;
  url = url + '&chir=' + chir;
  url = url + '&spe=' + spe;
  url = url + '&conv=' + conv;
  neo = window.open(url, 'Planning', 'left=10,top=10,height=550,width=700,resizable=1,scrollbars=1');
  if(neo.window.focus){neo.window.focus();}
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
          <th>Type d'admission:</th>
          <td><select name="type">
            <option value="0">-- Toutes</option>
            <option value="ambu">Ambulatoire</option>
            <option value="exte">Externe</option>
            <option value="comp">Complete</option>
          </select></td>
        </tr>
        <tr>
          <th>Chirurgien:</th>
          <td><select name="chir">
            <option value="0">-- Tous</option>
            {foreach from=$listChir item=curr_chir}
	            <option value="{$curr_chir.id}">{$curr_chir.lastname} {$curr_chir.firstname}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th>Specialité:</th>
          <td><select name="spe">
            <option value="0">-- Toutes</option>
            {foreach from=$listSpe item=curr_spe}
	            <option value="{$curr_spe.id}">{$curr_spe.text}</option>
            {/foreach}
          </select></td>
        </tr>
        <tr>
          <th>Convalescence:</th>
          <td><select name="conv">
            <option value="0">-- Indifférent</option>
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