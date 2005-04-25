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

function submitAffectationSplit(form) {
  form._new_lit_id.value = selected_lit;
  if (!selected_lit) {
    alert("Veuillez sélectionner un nouveau lit et revalider la date");
    return;
  }
  
  if (form._date_split.value <= form.entree.value || 
      form._date_split.value >= form.sortie.value) {
    var msg = "La date de déplacement (" + form._date_split.value + ") doit être comprise entre";
    msg += "\n- la date d'entrée: " + form.entree.value; 
    msg += "\n- la date de sortie: " + form.sortie.value;
    alert(msg);
    return;
  }
  
  form.submit();
}

function setupCalendar(affectation_id) {
  var form = null;
  form = eval("document.editAffectation" + affectation_id);

  Calendar.setup( {
      inputField  : form.name + "_sortie",
	  ifFormat    : "%Y-%m-%d %H:%M",
	  button      : form.name + "__trigger_sortie",
	  showsTime   : true,
	  onUpdate    : function() { 
        if (calendar.dateClicked) {
          form = eval("document.editAffectation" + affectation_id);
          form.submit();
        }
	  }
    }
  );
  
  form = eval("document.splitAffectation" + affectation_id);

  Calendar.setup( {
      inputField  : form.name + "__date_split",
	  ifFormat    : "%Y-%m-%d %H:%M",
	  button      : form.name + "__trigger_split",
	  showsTime   : true,
	  onUpdate    : function() { 
        if (calendar.dateClicked) {
          form = eval("document.splitAffectation" + affectation_id);
          submitAffectationSplit(form)
        }
	  }
    }
  );
}

{/literal}
{if $dialog != 1}
{literal}

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

  var cookie = new CJL_CookieUtil("chambres");
  chambres = cookie.getAllSubValues();
  for (chambreId in chambres) {
    if (chambre = document.getElementById(chambreId)) {
      chambre.className = chambres[chambreId];
    }
  }
}

{/literal}
{/if}
{literal}

function popPlanning() {
  var url = '?m=dPhospi&a=vw_affectations&dialog=1';
  popup(700, 550, url, 'Planning');
}

{/literal}  
</script>

<script type="text/javascript" src="lib/jscalendar/calendar.js"></script>
<script type="text/javascript" src="lib/jscalendar/lang/calendar-fr.js"></script>
<script type="text/javascript" src="lib/jscalendar/calendar-setup.js"></script>

<table class="main">

<tr>
  <th>
    {if $dialog != 1}
    <a href="#" onclick="popPlanning()">
    Planning du {$date|date_format:"%A %d %B %Y"} : {$totalLits} place(s) de libre
    </a>
    {else}
    <a href="javascript:window.print()">
    Planning du {$date|date_format:"%A %d %B %Y"} : {$totalLits} place(s) de libre
    </a>
    {/if}
  </th>
  <td>
    <form name="chgMode" action="?m={$m}" method="get">
    <input type="hidden" name="m" value="{$m}" />
    <select name="mode" onchange="submit()">
      <option value="0" {if $mode == 0}selected="selected"{/if}>Vue instantanée</option>
      <option value="1" {if $mode == 1}selected="selected"{/if}>Vue de la journée</option>
    </select>
    </form>
  </td>
</tr>

<tr>
  <td class="greedyPane">

    <table class="tbl">

    <tr>
    {foreach from=$services item=curr_service}
      <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;service_id={$curr_service->service_id}">{$curr_service->nom} / {$curr_service->_nb_lits_dispo} lit(s) dispo</a></td>
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
		      <strong>{$curr_chambre->nom}</strong> (Dispo: {$curr_chambre->_nb_lits_dispo}/{$curr_chambre->_ref_lits|@count})
		    </th>
		  </tr>
		  {foreach from=$curr_chambre->_ref_lits item=curr_lit}
		  <tr class="lit" >
		    <td colspan="1">
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
		    {if $curr_affectation->confirme}
		    <td style="background-image:url(modules/{$m}/images/ray.gif); background-repeat:repeat;">
		    {else}
		    <td>
		    {/if}
		    {if $curr_affectation->_ref_operation->admis == "o"}
		    <font>
		    {else}
		    <font style="color:#a33">
		    {/if}
		      {if $curr_affectation->_ref_operation->type_adm == "ambu"}
		      {*if $curr_affectation->sortie|date_format:"%H:%M:%S" >= $heureLimit && $curr_affectation->sortie|date_format:"%Y-%m-%d" == $date*}
		      <img src="modules/{$m}/images/X.png" alt="X" title="Sortant ce soir">
		      {*/if*}
		      <strong><i>{$curr_affectation->_ref_operation->_ref_pat->_view}</i></strong>
		      {else}
		      {if $curr_affectation->sortie|date_format:"%Y-%m-%d" == $demain}
		      <img src="modules/{$m}/images/O.png" alt="O" title="Sortant demain">
		      {/if}
		      <strong>{$curr_affectation->_ref_operation->_ref_pat->_view}</strong>
		      {/if}
		      {if $curr_affectation->_ref_operation->type_adm == "ambu"}(A){/if}
		    </font>
		    </td>
		    <td class="action" style="background:#{$curr_affectation->_ref_operation->_ref_chir->_ref_function->color}">
		      {eval var=$curr_affectation->_ref_operation->_ref_pat->_view assign="pat_view"}

              <form name="rmvAffectation{$curr_affectation->affectation_id}" action="?m={$m}" method="post">

              <input type="hidden" name="dosql" value="do_affectation_aed" />
              <input type="hidden" name="del" value="1" />
              <input type="hidden" name="affectation_id" value="{$curr_affectation->affectation_id}" />

              </form>
              
		      <a href="javascript:confirmDeletion(document.rmvAffectation{$curr_affectation->affectation_id}, 'l\'affectation', '{$pat_view}')">
		        <img src="modules/{$m}/images/trash.png" alt="trash" title="Supprimer l'affectation">
		      </a>
		    </td>
		  </tr>
		  <tr class="dates">
		    <td class="text" colspan="2">
		      {if $curr_affectation->_ref_prev->affectation_id}
		      <strong><i>Déplacé</i></strong> (chambre: {$curr_affectation->_ref_prev->_ref_lit->_ref_chambre->nom})
		      {else}
		      <strong><i>Entrée</i></strong>
		      {/if}:
		      {$curr_affectation->entree|date_format:"%A %d %B %H:%M"}
		      ({$curr_affectation->_entree_relative} jours)
		    </td>
		  </tr>
		  <tr class="dates">
		    <td class="text">
              <form name="splitAffectation{$curr_affectation->affectation_id}" action="?m={$m}" method="post">

              <input type="hidden" name="dosql" value="do_affectation_split" />
              <input type="hidden" name="affectation_id" value="{$curr_affectation->affectation_id}" />
              <input type="hidden" name="operation_id" value="{$curr_affectation->operation_id}" />
              <input type="hidden" name="entree" value="{$curr_affectation->entree}" />
              <input type="hidden" name="sortie" value="{$curr_affectation->sortie}" />
              <input type="hidden" name="_new_lit_id" value="" />
              <input type="hidden" name="_date_split" value="{$curr_affectation->sortie}" />

              </form>
              
		      <a style="float: right;">
		        <img id="splitAffectation{$curr_affectation->affectation_id}__trigger_split" src="modules/{$m}/images/move.gif" alt="Move" title="Déplacer un patient" />
		      </a>

		      {if $curr_affectation->_ref_next->affectation_id}
		      <strong><i>Déplacé</i></strong> (chambre: {$curr_affectation->_ref_next->_ref_lit->_ref_chambre->nom})
		      {else}
		      <strong><i>Sortie</i></strong>
		      {/if}:
		      {$curr_affectation->sortie|date_format:"%A %d %B %H:%M"}
		      ({$curr_affectation->_sortie_relative} jours)
		    </td>
		    <td class="action">
		      {eval var=$curr_affectation->_ref_operation->_ref_pat->_view assign="pat_view"}

              <form name="editAffectation{$curr_affectation->affectation_id}" action="?m={$m}" method="post">

              <input type="hidden" name="dosql" value="do_affectation_aed" />
              <input type="hidden" name="affectation_id" value="{$curr_affectation->affectation_id}" />
              <input type="hidden" name="sortie" value="{$curr_affectation->sortie}" />

              </form>
              
		      <a>
		        <img id="editAffectation{$curr_affectation->affectation_id}__trigger_sortie" src="modules/{$m}/images/planning.png" alt="Planning" title="Modifier la date de sortie" />
		      </a>
		    </td>
		  </tr>
		  <tr class="dates">
		    <td colspan="2"><strong><i>Age</i></strong>: {$curr_affectation->_ref_operation->_ref_pat->_age} ans</td>
		  </tr>
	      <tr class="dates">
	        <td class="text" colspan="2"><strong><i>Dr. {$curr_affectation->_ref_operation->_ref_chir->_view}</i></strong></td>
	      </tr>
	      <tr class="dates">
	        <td class="text" colspan="2">
	          <strong><i>{$curr_affectation->_ref_operation->_ext_code_ccam->code}</i></strong>:
	          {$curr_affectation->_ref_operation->_ext_code_ccam->libelleLong}
	          {if $curr_affectation->_ref_operation->CCAM_code2}
	          <br />
	          <strong><i>{$curr_affectation->_ref_operation->_ext_code_ccam2->code}</i></strong>:
	          {$curr_affectation->_ref_operation->_ext_code_ccam2->libelleLong}
	          {/if}         
	        </td>
	      </tr>
	      {if $curr_affectation->_ref_operation->rques != ""}
          <tr class="dates">
            <td class="text" colspan="2">
              <strong><i>Remarques</i></strong>: {$curr_affectation->_ref_operation->rques|escape:javascript}
            </td>
          </tr>
          {/if}
          {if $curr_affectation->_ref_operation->chambre == "o"}
          <tr class="dates">
            <td class="text" colspan="2">
              Chambre seule
            </td>
          </tr>
          {/if}
		  {foreachelse}
		  <tr class="litdispo"><td colspan="2">Lit disponible</td></tr>
		  <tr class="litdispo">
		    <td class="text" colspan="2">
		    depuis:
		    {if $curr_lit->_ref_last_dispo && $curr_lit->_ref_last_dispo->affectation_id}
		    {$curr_lit->_ref_last_dispo->sortie|date_format:"%A %d %B %H:%M"} 
		    ({$curr_lit->_ref_last_dispo->_sortie_relative} jours)
		    {else}
		    Toujours
		    {/if}
		    </td>
		  </tr>
		  <tr class="litdispo">
		    <td class="text" colspan="2">
		    jusque: 
		    {if $curr_lit->_ref_next_dispo && $curr_lit->_ref_next_dispo->affectation_id}
		    {$curr_lit->_ref_next_dispo->entree|date_format:"%A %d %B %H:%M"}
		    ({$curr_lit->_ref_next_dispo->_entree_relative} jours)
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
  {if $dialog != 1}
  <td class="pane">
    
    <div id="calendar-container"></div>
  
	{foreach from=$groupOpNonAffectees key=group_name item=opNonAffectees}

    <table class="tbl">
      <tr>
        <th class="title">
          Admissions 
          {if $group_name == "veille" }de la veille{/if}
          {if $group_name == "matin" }du matin{/if}
          {if $group_name == "soir" }du soir{/if}
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
        <td class="selectoperation" style="background:#{$curr_operation->_ref_chir->_ref_function->color}">
          <input type="radio" id="hospitalisation{$curr_operation->operation_id}" onclick="selectHospitalisation({$curr_operation->operation_id})" />
        </td>
        <td class="patient" onclick="flipOperation({$curr_operation->operation_id})">
          <strong>{$curr_operation->_ref_pat->_view}</strong>
          {if $curr_operation->type_adm == "comp"}
          ({$curr_operation->duree_hospi}j)
          {else}
          ({$curr_operation->type_adm|truncate:1:""|capitalize})
          {/if}
        </td>
      </tr>
      <tr>
        <td class="date" colspan="2"><strong><i>Entrée</i></strong>: {$curr_operation->_entree_adm|date_format:"%A %d %B %H:%M"}</td>
      </tr>
      <tr>
        <td class="date" colspan="2"><strong><i>Sortie</i></strong>: {$curr_operation->_sortie_adm|date_format:"%A %d %B"}</td>
      </tr>
      <tr>
        <td class="date" colspan="2"><strong><i>Age:</i></strong>: {$curr_operation->_ref_pat->_age} ans
      </tr>
      <tr>
        <td class="date" colspan="2"><strong><i>Dr. {$curr_operation->_ref_chir->_view}</i></strong></td>
      </tr>
      <tr>
        <td class="date" colspan="2">
          <strong><i>{$curr_operation->_ext_code_ccam->code}</i></strong>:
          {$curr_operation->_ext_code_ccam->libelleLong}
          {if $curr_operation->CCAM_code2}
          <br />
          <strong><i>{$curr_operation->_ext_code_ccam2->code}</i></strong>:
          {$curr_operation->_ext_code_ccam2->libelleLong}
          {/if}         
        </td>
      </tr>
      {if $curr_operation->rques != ""}
      <tr>
        <td class="date" colspan="2">
          <strong><i>Remarques</i></strong>: {$curr_operation->rques|escape:javascript}
        </td>
      </tr>
      {/if}
      {if $curr_operation->chambre == "o"}
      <tr>
        <td class="date" colspan="2">
          Chambre seule
        </td>
      </tr>
      {/if}
    </table>
    
    {/foreach}
    {/foreach}

  </td>
  {/if}
</tr>

</table>