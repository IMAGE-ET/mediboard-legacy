{literal}
<script language="javascript">
function popChir() {
  window.open('./index.php?m=dPplanningOp&a=chir_selector&dialog=1', 'Chirurgien', 'left=50,top=50,height=250,width=400,resizable');
}

function popPat() {
  window.open('./index.php?m=dPplanningOp&a=pat_selector&dialog=1', 'Patient', 'left=50,top=50,height=250,width=400,resizable');
}

function popCode(type) {
  var chir = document.editFrm.chir_id.value;
  window.open('./index.php?m=dPplanningOp&a=code_selector&dialog=1&type='+type+'&chir='+chir, 'CIM10', 'left=50,top=50,height=500,width=600,resizable');
}

function popPlage() {
  var chir = document.editFrm.chir_id.value;
  var hour = document.editFrm.hour_op.value;
  var min = document.editFrm.min_op.value;
  window.open('./index.php?m=dPplanningOp&a=plage_selector&dialog=1&hour='+hour+'&min='+min+'&chir='+chir, 'Plage', 'left=50,top=50,height=250,width=400,resizable');
}

function setChir( key, val ){
  var f = document.editFrm;
   if (val != '') {
    f.chir_id.value = key;
      f.chir_name.value = val;
      window.chir_id = key;
      window.chir_name = val;
    }
}

function setPat( key, val ){
  var f = document.editFrm;
   if (val != '') {
    f.pat_id.value = key;
      f.pat_name.value = val;
      window.pat_id = key;
      window.pat_name = val;
    }
}

function setCode( key, type ){
  var f = document.editFrm;
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

function setPlage( key, val ){
  var f = document.editFrm;
   if (key != '') {
    f.plageop_id.value = key
    f.date.value = val;
    window.plageop_id = key;
    window.date = val;
    }
}

var calendarField = '';
var calWin = null;

function popCalendar( field ){
  calendarField = field;
  idate = eval( 'document.editFrm.date_' + field + '.value' );
  window.open( 'index.php?m=public&a=calendar&dialog=1&callback=setCalendar&date=' + idate, 'calwin', 'top=250,left=250,width=280, height=250, scollbars=false' );
}

function setCalendar( idate, fdate ) {
  fld_date = eval( 'document.editFrm.date_' + calendarField );
  fld_fdate = eval( 'document.editFrm.' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}
</script>
{/literal}

<form name="editFrm" action="?m=dPplanningOp" method="post">
<input type="hidden" name="dosql" value="do_planning_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="operation_id" value="{$op.id}" />
<input type="hidden" name="chir_id" value="{$op.chir_id}" />
<input type="hidden" name="pat_id" value="{$op.pat_id}" />
<input type="hidden" name="rank" value="{$op.rank}" />

<table class="main">
  <tr>
    <td>
	
      <table class="form">
        <tr><th class="category" colspan="3">Informations concernant l'opération</th></tr>
        <tr>
		      <th class="mandatory">Chirurgien:</th>
          <td class="readonly"><input type="text" name="chir_name" size="30" value="{$op.chir_name}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()" /></td>
        </tr>
        <tr>
          <th class="mandatory">Patient:</th>
          <td class="readonly"><input type="text" name="pat_name" size="30" value="{$op.pat_name}" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        <tr>
          <th>Diagnostic (CIM10):</th>
          <td><input type="text" name="CIM10_code" size="10" value="{$op.CIM10_code}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('cim10')" /></td>
        </tr>
        <tr>
          <th>Code CCAM:</th>
          <td><input type="text" name="CCAM_code" size="10" value="{$op.CCAM_code}" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam')"/></td>
        </tr>
		<tr>
		  <th>Coté:</th>
		  <td colspan=2>
		    <select name="cote">
			  <option selected>{$op.cote}</option>
			  <option>total</option>
			  <option>droit</option>
			  <option>gauche</option>
			  <option>bilatéral</option>
			</select>
		  </td>
		</tr>
        <tr>
          <th class="mandatory">Temps opératoire:</th>
          <td colspan="2">
            <select name="hour_op">
			  <option selected>{$op.hour_op}</option>
              <option>0</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
            </select>
            :
            <select name="min_op">
			  <option selected>{$op.min_op}</option>
              <option>00</option>
              <option>15</option>
              <option>30</option>
              <option>45</option>
            </select>
          </td>
        </tr>
        <tr>
          <th class="mandatory">Date de l'intervention:</th>
          <td class="readonly">
            <input type="hidden" name="plageop_id" value="{$op.plageop_id}" />
            <input type="text" name="date" readonly="readonly" size="10" value="{$op.date_op}" />
          </td>
          <td class="button"><input type="button" value="choisir une date" onclick="popPlage()" /></td>
        </tr>
        <tr>
          <th>Examens complémentaires:</th>
          <td colspan="2"><textarea name="examen" rows="3">{$op.examen}</textarea></td>
        </tr>
        <tr>
          <th>Materiel à prévoir:</th>
          <td colspan="2"><textarea name="materiel" rows="3">{$op.materiel}</textarea></td>
        </tr>
        <tr>
          <th>Information du patient:</th>
          <td  colspan="2">
		    {if $op.info == "o"}
            <input name="info" value="o" type="radio" checked="checked" />Oui
            <input name="info" value="n" type="radio" />Non
			{else}
			<input name="info" value="o" type="radio" />Oui
            <input name="info" value="n" type="radio" checked="checked" />Non
			{/if}
          </td>
        </tr>
      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">RDV d'anesthésie</th></tr>
        <tr>
          <th>Date:</th>
          <td class="readonly">
            <input type="hidden" name="date_rdv_anesth" value="{$op.date_rdv_anesth}" />
            <input type="text" name="rdv_anesth" value="{$op.rdv_anesth}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'rdv_anesth', 'rdv_anesth');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
        <tr>
          <th>Heure:</th>
          <td>
            <select name="hour_anesth">
			  <option selected>{$op.hour_anesth}</option>
              <option>08</option>
              <option>09</option>
              <option>10</option>
              <option>11</option>
              <option>12</option>
              <option>13</option>
              <option>14</option>
              <option>15</option>
              <option>16</option>
              <option>17</option>
              <option>18</option>
              <option>19</option>
            </select>
            :
            <select name="min_anesth">
			  <option selected>{$op.min_anesth}</option>
              <option>00</option>
              <option>15</option>
              <option>30</option>
              <option>45</option>
            </select>
          </td>
        </tr>
        <tr><th class="category" colspan="3">Admission</th></tr>
        <tr>
          <th>Date:</th>
          <td class="readonly">
            <input type="hidden" name="date_rdv_adm" value="{$op.date_rdv_adm}" />
            <input type="text" name="rdv_adm" value="{$op.rdv_adm}" readonly="readonly" />
            <a href="#" onClick="popCalendar( 'rdv_adm', 'rdv_adm');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>
        <tr>
          <th>Heure:</th>
          <td>
            <select name="hour_adm">
			  <option selected>{$op.hour_adm}</option>
              <option>08</option>
              <option>09</option>
              <option>10</option>
              <option>11</option>
              <option>12</option>
              <option>13</option>
              <option>14</option>
              <option>15</option>
              <option>16</option>
              <option>17</option>
              <option>18</option>
              <option>19</option>
            </select>
            :
            <select name="min_adm">
			  <option selected>{$op.min_adm}</option>
              <option>00</option>
              <option>15</option>
              <option>30</option>
              <option>45</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Durée d'hospitalisation:</th>
          <td><input type"text" name="duree_hospi" size="1" value="{$op.duree_hospi}">jours</td>
        </tr>
        <tr>
          <th>Admission en:</th>
          <td>
		    {if $op.type_adm == "comp"}
            <input name="type_adm" value="comp" type="radio" checked="checked" />hospitalisation complète<br />
            <input name="type_adm" value="ambu" type="radio" />Ambulatoire
			{else}
            <input name="type_adm" value="comp" type="radio" />hospitalisation complète<br />
            <input name="type_adm" value="ambu" type="radio" checked="checked" />Ambulatoire
			{/if}
          </td>
        </tr>
        <tr>
          <th>Chambre particulière:</th>
          <td>
		    {if $op.chambre == "o"}
            <input name="chambre" value="o" type="radio" checked="checked" />Oui
            <input name="chambre" value="n" type="radio" />Non
			{else}
            <input name="chambre" value="o" type="radio" />Oui
            <input name="chambre" value="n" type="radio" checked="checked" />Non
			{/if}
          </td>
        </tr>
        <tr><th class="category" colspan="3">Autre</th></tr>
        <tr>
          <th>Risque ATNC:</th>
          <td>
		    {if $op.ATNC == "o"}
            <input name="ATNC" value="o" type="radio" checked="checked" />Oui
            <input name="ATNC" value="n" type="radio" />Non
			{else}
            <input name="ATNC" value="o" type="radio" />Oui
            <input name="ATNC" value="n" type="radio" checked="checked" />Non
			{/if}
          </td>
        </tr>
        <tr>
          <th>Remarques:</th>
          <td><textarea name="rques" rows="3">{$op.rques}</textarea></td>
        </tr>

      </table>
    
    </td>
  </tr>

  <tr>
    <td colspan="2">

      <table class="form">
        <tr>
          <td class="button">
            <input class="button" type="reset" value="Réinitialiser" />
            <input class="button" type="submit" value="Modifier" />
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table>

</form>
