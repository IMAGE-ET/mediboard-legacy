<!-- $Id$ -->

{literal}
<script language="javascript">
function checkForm() {
  var form = document.paramFrm;
    
  if (form.date_debut.value > form.date_fin.value) {
    alert("Date de début superieure à la date de fin");
    return false;
  }
  popMateriel();
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

function popMateriel() {
  var debut = document.paramFrm.date_debut.value;
  var fin = document.paramFrm.date_fin.value;
  var url = './index.php?m=dPbloc&a=print_materiel&dialog=1';
  url = url + '&debut=' + debut;
  url = url + '&fin=' + fin;
  popup(700, 550, url, 'Materiel');
}
</script>
{/literal}

<table class="main">
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Opération</th>
		  <th>Materiel à commander</th>
		  <th>Valider</th>
		</tr>
		{foreach from=$op item=curr_op}
		<tr>
		  <td>{$curr_op->_ref_plageop->date|date_format:"%a %d %b %Y"}{*$curr_op.date|date_format:"%a %d %b %Y"*}</td>
		  <td class="text">Dr. {$curr_op->_ref_chir->_view}</td>
		  <td class="text">{$curr_op->_ref_pat->_view}</td>
		  <td class="text">{$curr_op->_ext_code_ccam->code} : <i>{$curr_op->_ext_code_ccam->libelleLong}</i> (Côté : {$curr_op->cote})</td>
		  <td class="text">{$curr_op->materiel|nl2br}</td>
		  <td>
			<form name="editFrm{$curr_op.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="dPbloc" />
            <input type="hidden" name="a" value="do_edit_mat" />
            <input type="hidden" name="id" value="{$curr_op.id}" />
            {if $typeAff}
            <input type="hidden" name="value" value="n" />
		    <input type="submit" value="annulé" />
		    {else}
            <input type="hidden" name="value" value="o" />
		    <input type="submit" value="commandé" />
			{/if}
			</form>
		  </td>
		</tr>
		{/foreach}
	  </table>
	</td>
	<td>
      <form name="paramFrm" action="?m=dPbloc" method="post" onsubmit="return checkForm()">
	  <table class="form">
	    <tr><th colspan="2" class="category">Imprimer l'historique</th></tr>
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
	    <tr><td colspan="2" class="button"><input type="button" value="Afficher" onclick="checkForm()"</td></tr>
	  </table>
	  </form>
	  <form name="typeVue" action="?m={$m}" method="get">
        <input type="hidden" name="m" value="{$m}" />
        <select name="typeAff" onchange="submit()">
          <option value="0" {if $typeAff == 0}selected="selected"{/if}>Materiel à commander</option>
          <option value="1" {if $typeAff == 1}selected="selected"{/if}>Materiel annulé</option>
        </select>
      </form>
  </tr>
</table>