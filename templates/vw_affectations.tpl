<script language="JavaScript" type="text/javascript">
{literal}
function flipChambre(chambre_id) {
  flipElementClass("chambre" + chambre_id, "chambrecollapse", "chambreexpand", "chambres");
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

function dateAffectationChanged() {
  if (calendar.dateClicked) {
	  alert(calendar.date);
  }
}

function setupCalendar(affectation_id) {
  var form = eval("document.editAffectation" + affectation_id);

  Calendar.setup( {
      inputField  : form.name + "_sortie",
	  ifFormat    : "%Y-%m-%d %H:%M",
	  button      : form.name + "__trigger_date",
	  showsTime   : true,
	  onUpdate    : function() { 
        if (calendar.dateClicked) {
          form.submit();
        }
	  }
    }
  );
  
  var cookie = new CJL_CookieUtil("chambres");
  chambres = cookie.getAllSubValues();
  for (chambreId in chambres) {
    if (chambre = document.getElementById(chambreId)) {
      chambre.className = chambres[chambreId];
    }
  }
}

function pageMain() {
  Calendar.setup( {
      flat         : "calendar-container",
      flatCallback : dateChanged         ,
      date         : {/literal}new Date({$year}, {$month}, {$day}){literal}
    }
  );
  
{/literal}
{foreach from=$services item=curr_service}
{foreach from=$curr_service->_ref_chambres item=curr_chambre}
{foreach from=$curr_chambre->_ref_lits item=curr_lit}
{foreach from=$curr_lit->_ref_affectations item=curr_affectation}
setupCalendar({$curr_affectation->affectation_id});
{/foreach}
{/foreach}
{/foreach}
{/foreach}
{literal}
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
		      {if $curr_chambre->_overbooking}
		      <img src="modules/{$m}/images/warning.png" alt="warning" title="Over-booking: {$curr_chambre->_overbooking} collisions">
		      {/if}
		      {$curr_chambre->nom} (Dispo: {$curr_chambre->_nb_lits_dispo}/{$curr_chambre->_ref_lits|@count})
		    </th>
		  </tr>
		  {foreach from=$curr_chambre->_ref_lits item=curr_lit}
		  <tr class="lit" >
		    <td>
		      {if $curr_lit->_overbooking}
		      <img src="modules/{$m}/images/warning.png" alt="warning" title="Over-booking: {$curr_lit->_overbooking} collisions">
		      {/if}
		      {$curr_lit->nom}
		    </td>
		    <td class="action">
              <input type="radio" id="lit{$curr_lit->lit_id}" onclick="selectLit({$curr_lit->lit_id})" />
            </td>
		  </tr>
		  {foreach from=$curr_lit->_ref_affectations item=curr_affectation}
		  <tr class="patient">
		    <td>{$curr_affectation->_ref_operation->_ref_pat->_view}</td>
		    <td class="action">
		      {eval var=$curr_affectation->_ref_operation->_ref_pat->_view assign="pat_view"}

              <form name="rmvAffectation{$curr_affectation->affectation_id}" action="?m={$m}" method="post">

              <input type="hidden" name="dosql" value="do_affectation_aed" />
              <input type="hidden" name="del" value="1" />
              <input type="hidden" name="affectation_id" value="{$curr_affectation->affectation_id}" />

              </form>
              
		      <a href="javascript:confirmDeletion(document.rmvAffectation{$curr_affectation->affectation_id}, 'l\'affectation', '{$pat_view}')">
		        <img src="modules/{$m}/images/cancel.png" alt="trash" title="Supprimer l'affectation">
		      </a>
		    </td>
		  </tr>
		  <tr class="dates">
		    <td colspan="2">Entrée: {$curr_affectation->entree|date_format:"%A %d %B %H:%M"}</td>
		  </tr>
		  <tr class="dates">
		    <td>Sortie:  {$curr_affectation->sortie|date_format:"%A %d %B %H:%M"}</td>
		    <td class="action">
		      {eval var=$curr_affectation->_ref_operation->_ref_pat->_view assign="pat_view"}

              <form name="editAffectation{$curr_affectation->affectation_id}" action="?m={$m}" method="post">

              <input type="hidden" name="dosql" value="do_affectation_aed" />
              <input type="hidden" name="affectation_id" value="{$curr_affectation->affectation_id}" />
              <input type="hidden" name="sortie" value="{$curr_affectation->sortie}" />

              </form>
              
		      <a>
		        <img id="editAffectation{$curr_affectation->affectation_id}__trigger_date" src="modules/{$m}/images/planning.png" alt="Planning" title="Modifier la date de sortie">
		      </a>
		    </td>
		  </tr>
		  {foreachelse}
		  <tr class="litdispo"><td colspan="2">Lit disponible</td></tr>
		  <tr class="litdispo">
		    <td colspan="2">
		    depuis:
		    {if $curr_lit->_ref_last_dispo && $curr_lit->_ref_last_dispo->affectation_id}
		    {$curr_lit->_ref_last_dispo->sortie|date_format:"%A %d %B %H:%M"} ({$curr_lit->_ref_last_dispo->_sortie_relative} jours)
		    {else}
		    Toujours
		    {/if}
		    </td>
		  </tr>
		  <tr class="litdispo">
		    <td colspan="2">
		    jusque: 
		    {if $curr_lit->_ref_next_dispo && $curr_lit->_ref_next_dispo->affectation_id}
		    {$curr_lit->_ref_next_dispo->entree|date_format:"%A %d %B %H:%M"} ({$curr_lit->_ref_next_dispo->_entree_relative} jours)
		    {else}
		    Toujours
		    {/if}
		    </td>
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
  
	{foreach from=$groupOpNonAffectees key=group_name item=opNonAffectees}

    <table class="tbl">
      <tr>
        <th class="title">
          Admissions 
          {if $group_name == "jour" }du jour{/if}
          {if $group_name == "avant"}antérieures{/if}
        </th>
      </tr>
    </table>

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
          {$curr_operation->_ref_pat->_view} ({$curr_operation->duree_hospi} jours)
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
    {/foreach}

  </td>
</tr>

</table>
