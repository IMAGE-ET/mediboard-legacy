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

function popPlanning() {
  var form = document.paramFrm;

  var url = '?m=dPhospi&a=print_planning&dialog=1';
  url += '&deb=' + form.deb.value;
  url += '&fin=' + form.fin.value;
  url += '&ordre=' + form.ordre.value;
  url += '&service=' + form.service.value;
  url += '&type=' + form.type.value;
  url += '&chir=' + form.chir.value;
  url += '&spe=' + form.spe.value;
  url += '&conv=' + form.conv.value;
  
  popup(700, 550, url, 'Planning');
}

function pageMain() {
  regFieldCalendar("paramFrm", "deb", true);
  regFieldCalendar("paramFrm", "fin", true);
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
          <th><label for="paramFrm_deb">Début:</label></th>
          <td class="date" colspan="2">
            <div id="paramFrm_deb_da">{$today|date_format:"%d/%m/%Y %H:%M"}</div>
            <input type="hidden" name="deb" value="{$today}" />
            <img id="paramFrm_deb_trigger" src="./images/calendar.gif" alt="calendar" title="Choisir une date de début"/>
          </td>
        </tr>

        <tr>
          <th><label for="paramFrm_fin">Fin:</label></th>
          <td class="date" colspan="2">
            <div id="paramFrm_fin_da">{$tomorrow|date_format:"%d/%m/%Y %H:%M"}</div>
            <input type="hidden" name="fin" value="{$tomorrow}" />
            <img id="paramFrm_fin_trigger" src="./images/calendar.gif" alt="calendar" title="Choisir une date de fin"/>
          </td>
        </tr>

        <tr>
          <th><label for="paramFrm_ordre">Classement des admissions:</label></th>
          <td>
            <select name="ordre">
              <option value="heure">Par heure d'admission</option>
              <option value="nom">Par nom du patient</option>
            </select>
          </td>
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
              <option value="{$curr_prat->user_id}">{$curr_prat->_view}</option>
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