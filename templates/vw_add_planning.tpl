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
function popDate() {
        window.open('./index.php?m=dPplanningOp&a=date_selector&dialog=1', 'Date', 'left=50,top=50,height=250,width=400,resizable');
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
function setDate( key, val ){
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

<table>
<form name="editFrm" action="?m=dPplanningOp" method="post">
<input type="hidden" name="chir_id" value="">
<input type="hidden" name="pat_id" value="">
	<tr>
		<td valign="top">
			<table class="form">
				<tr>
					<th colspan=3>
						Informations concernant l'opération
					</th>
				</tr>
				<tr>
					<td class="propname">
						Chirurgien :
					</td>
					<td class="propvalue">
						<input type="text" name="chir_name" size="30" value="">
					</td>
					<td>
						<input type="button" value="choisir un chirurgien" onclick="popChir()">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Patient :
					</td>
					<td>
						<input type="text" name="pat_name" size="30" value="">
					</td>
					<td>
						<input type="button" value="rechercher un patient" onclick="popPat()">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Diagnostic (CIM10) :
					</td>
					<td>
						<input type="text" name="CIM10_code" size="10" value="">
					</td>
					<td>
						<input type="button" value="selectionner un code" onclick="popCode('cim10')">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Code CCAM :
					</td>
					<td>
						<input type="text" name="CCAM_code" size="10" value="">
					</td>
					<td>
						<input type="button" value="selectionner un code" onclick="popCode('ccam')">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Date de l'intervention :
					</td>
					<td>
						<input type="hidden" name="plageop_id" value="">
						<input type="text" name="date" readonly size="15" value="JJ / MM / YYYY">
					</td>
					<td>
						<input type="button" value="choisir une date" onclick="popDate()">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Examens complémentaires :
					</td>
					<td class="propvalue">
						<textarea name="examen" rows="3"></textarea>
					</td>
				</tr>
				<tr>
					<td class="propname">
						Materiel à prévoir :
					</td>
					<td class="propvalue">
						<textarea name="materiel" rows="3"></textarea>
					</td>
				</tr>
				<tr>
					<td class="propname">
						Information du patient :
					</td>
					<td class="propvalue">
						<input name="info" value="oui" type="radio">
						Oui
						<input name="info" value="non" type="radio" checked>
						Non
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table class="form">
				<tr>
					<th colspan="3">
						RDV d'anesthésie
					</th>
				</tr>
				<tr>
					<td class="propname">
						Date :
					</td>
					<td class="propvalue">
						<input type="hidden" name="date_rdv_anesth" value="{$todayi}">
						<input type="text" name="rdv_anesth" value="{$todayf}" readonly>
						<a href="#" onClick="popCalendar( 'rdv_anesth', 'rdv_anesth');">
						<img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" border="0" />
						</a>
					</td>
				</tr>
				<tr>
					<td class="propname">
						Heure :
					</td>
					<td class="propvalue">
						<select name="hour_anesth">
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
							<option>00</option>
							<option>15</option>
							<option>30</option>
							<option>45</option>
						</select>
					</td>
				</tr>
				<tr>
					<th colspan="3">
						Admission
					</th>
				</tr>
				<tr>
					<td class="propname">
						Date :
					</td>
					<td class="propvalue">
						<input type="hidden" name="date_rdv_adm" value="{$todayi}">
						<input type="text" name="rdv_adm" value="{$todayf}" readonly>
						<a href="#" onClick="popCalendar( 'rdv_adm', 'rdv_adm');">
						<img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" border="0" />
						</a>
					</td>
				</tr>
				<tr>
					<td class="propname">
						Heure :
					</td>
					<td class="propvalue">
						<select name="hour_adm">
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
							<option>00</option>
							<option>15</option>
							<option>30</option>
							<option>45</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="propname">
						Durée d'hospitalisation :
					</td>
					<td class="propvalue">
						<input type"text" name="duree_hospi" size="1" value="0"> jours
					</td>
				</tr>
				<tr>
					<td class="propname">
						Admission en :
					</td>
					<td class="propvalue">
						<input name="type_adm" value="comp" type="radio" checked> 
						hospitalisation complète
						<br>
						<input name="type_adm" value="ambu" type="radio">
						Ambulatoire
					</td>
				</tr>
				<tr>
					<td class="propname">
						Chambre particulière :
					</td>
					<td class="propvalue">
						<input name="chambre" value="oui" type="radio" checked>
						Oui
						<input name="chambre" value="non" type="radio">
						Non
					</td>
				</tr>
				<tr>
					<th colspan=3>Autre</th>
				</tr>
				<tr>
					<td class="propname">
						Risque ATNC :
					</td>
					<td class="propvalue">
						<input name="ATNC" value="oui" type="radio">
						Oui
						<input name="ATNC" value="non" type="radio" checked>
						Non
					</td>
				</tr>
				<tr>
					<td class="propname">
						Remarques :
					</td>
					<td class="propvalue">
						<textarea name="rque" rows="3"></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input class="button" type="reset" value="Annuler">
		</td>
		<td align="center">
			<input class="button" type="submit" value="Valider">
		</td>
	</tr>
</form>
</table>