<script language="JavaScript" type="text/javascript">
{literal}
function flipChambre(chambre_id) {
  flipElementClass("chambre" + chambre_id, "chambrecollapse", "chambreexpand");
}

function flipOperation(operation_id) {
  flipElementClass("operation" + operation_id, "operationcollapse", "operationexpand");
}

var selected_hospitalisation = null;

function selectHospitalisation(operation_id) {
  var element = document.getElementById("hospitalisation" + selected_hospitalisation);
  if (element) {
    element.checked = false;
  }

  selected_hospitalisation = operation_id;
 
  submitAffectation();
}

var selected_lit = null;

function selectLit(lit_id) {
  var element = document.getElementById("lit" + selected_lit);
  if (element) {
    element.checked = false;
  }

  selected_lit = lit_id;
  
  submitAffectation();
}

function submitAffectation() {
  if (selected_lit && selected_hospitalisation) {
	var form = eval("document.addAffectation" + selected_hospitalisation);
	form.lit_id.value = selected_lit;
	form.submit();
  }
}

function dateChanged(calendar) {
  if (calendar.dateClicked) {
    var y = calendar.date.getFullYear();
    var m = calendar.date.getMonth();
    var d = calendar.date.getDate();
    
    var url = "index.php?m={/literal}{$m}{literal}";
    url += "&tab={/literal}{$tab}{literal}";
    url += "&year="  + y;
    url += "&month=" + m;
    url += "&day="   + d;

    window.location = url;
  }
}

function pageMain() {
  Calendar.setup(
    {
      flat         : "calendar-container",
      flatCallback : dateChanged         ,
      date         : {/literal}new Date({$year}, {$month}, {$day}){literal}
    }
  );
}

{/literal}  
</script>

<style type="text/css">@import url(lib/jscalendar/calendar-win2k-1.css);</style>
<script type="text/javascript" src="lib/jscalendar/calendar.js"></script>
<script type="text/javascript" src="lib/jscalendar/lang/calendar-fr.js"></script>
<script type="text/javascript" src="lib/jscalendar/calendar-setup.js"></script>

<table class="main">

<tr>
  <td class="greedyPane">

    <table class="tbl">

    <tr>
    {foreach from=$services item=curr_service}
      <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;service_id={$curr_service->service_id}">{$curr_service->nom}</a></td>
    {/foreach}
    </tr>

	<tr>
    {foreach from=$services item=curr_service}
      <td>
      {foreach from=$curr_service->_ref_chambres item=curr_chambre}
        <table class="chambrecollapse" id="chambre{$curr_chambre->chambre_id}">
		  <tr>
		    <th class="chambre" colspan="2" onclick="flipChambre({$curr_chambre->chambre_id})">
		      {$curr_chambre->nom} (Dispo: {$curr_chambre->_nb_lits_dispo}/{$curr_chambre->_ref_lits|@count})
		    </th>
		  </tr>
		  {foreach from=$curr_chambre->_ref_lits item=curr_lit}
		  <tr class="lit" >
		    <td>
		      {if $curr_lit->_warning}
		      <img src="modules/{$m}/images/warning.png" alt="warning" title="{$curr_lit->_warning}">
		      {/if}
		      {$curr_lit->nom}
		    </td>
		    <td class="selectlit">
              <input type="radio" id="lit{$curr_lit->lit_id}" onclick="selectLit({$curr_lit->lit_id})" />
            </td>
		  </tr>
		  {foreach from=$curr_lit->_ref_affectations item=curr_affectation}
		  <tr class="patient">
		    <td colspan="2">{$curr_affectation->_ref_operation->_ref_pat->_view}</td>
		  </tr>
		  <tr class="dates">
		    <td colspan="2">Entrée: {$curr_affectation->entree|date_format:"%A %d %B %H:%M"}</td>
		  </tr>
		  <tr class="dates">
		    <td colspan="2">Sortie:  {$curr_affectation->sortie|date_format:"%A %d %B %H:%M"}</td>
		  </tr>
		  {/foreach}
		  {/foreach}
        </table>
      {/foreach}
      </td>
    {/foreach}
    </tr>
    
    </table>
    
  </td>
  <td class="pane">
    
    <div id="calendar-container"></div>
  
    <table class="tbl"><tr><th class="title">Séléctionner un patient à placer</th></tr></table>

    {foreach from=$opNonAffectees item=curr_operation}
    <form name="addAffectation{$curr_operation->operation_id}" action="?m={$m}" method="post">

    <input type="hidden" name="dosql" value="do_affectation_aed" />
    <input type="hidden" name="lit_id" value="" />
    <input type="hidden" name="operation_id" value="{$curr_operation->operation_id}" />
    <input type="hidden" name="entree" value="{$curr_operation->_entree_adm}" />
    <input type="hidden" name="sortie" value="{$curr_operation->_sortie_adm}" />

    </form>

	<table class="operationcollapse" id="operation{$curr_operation->operation_id}">
      <tr>
        <td class="patient" onclick="flipOperation({$curr_operation->operation_id})">
          {$curr_operation->_ref_pat->_view}
        </td>
        <td class="selectoperation">
          <input type="radio" id="hospitalisation{$curr_operation->operation_id}" onclick="selectHospitalisation({$curr_operation->operation_id})" />
        </td>
      </tr>
      <tr>
        <td class="date" colspan="2">Entrée: {$curr_operation->_entree_adm|date_format:"%A %d %B %H:%M"}</td>
      </tr>
      <tr>
        <td class="date" colspan="2">Sortie: {$curr_operation->_sortie_adm|date_format:"%A %d %B %H:%M"}</td>
      </tr>
    </table>
    
    {/foreach}

  </td>
</tr>

</table>
