{literal}
<script language="javascript">
function popChir() {
		var name = document.addOp.chir_name.value;
        window.open('./index.php?m=dPplanningOp&a=chir_selector&dialog=1&name='+name, 'Chirurgien', 'left=50,top=50,height=250,width=400,resizable');
}
function popPat() {
		var name = document.addOp.pat_name.value;
        window.open('./index.php?m=dPplanningOp&a=pat_selector&dialog=1&name='+name, 'Patient', 'left=50,top=50,height=250,width=400,resizable');
}
function popCode(type) {
		var chir = document.addOp.chir_id.value;
        window.open('./index.php?m=dPplanningOp&a=code_selector&dialog=1&type='+type+'&chir='+chir, 'CIM10', 'left=50,top=50,height=500,width=600,resizable');
}
function popDate() {
        window.open('./index.php?m=dPplanningOp&a=date_selector&dialog=1', 'Date', 'left=50,top=50,height=250,width=400,resizable');
}

function setChir( key, val ){
	var f = document.addOp;
 	if (val != '') {
		f.chir_id.value = key;
    	f.chir_name.value = val;
    	window.chir_id = key;
    	window.chir_name = val;
    }
}
function setPat( key, val ){
	var f = document.addOp;
 	if (val != '') {
		f.pat_id.value = key;
    	f.pat_name.value = val;
    	window.pat_id = key;
    	window.pat_name = val;
    }
}
function setCode( key, type ){
	var f = document.addOp;
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
	var f = document.addOp;
 	if (key != '') {
		f.date.value = key;
    	window.date = val;
    }
}
</script>
{/literal}

<table>
<form name="addOp" action="?m=dPplanningOp" method="post">
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
						Diagnostic (CIM10):
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
						<br>
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
						<input type="text" maxlength="2" size="1" name="day_anesth" value="">
						/
						<input type="text" maxlength="2" size="1" name="month_anesth" value="">
						/
						<input type="text" maxlength="4" size="2" name="year_anesth" value="">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Heure :
					</td>
					<td class="propvalue">
						<input type="text" maxlength="2" size="1" name="hour_anesth" value="">
						:
						<input type="text" maxlength="2" size="1" name="min_anesth" value="">
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
						<input type="text" maxlength="2" size="1" name="day_adm" value="">
						/
						<input type="text" maxlength="2" size="1" name="monthadm" value="">
						/
						<input type="text" maxlength="4" size="2" name="year_adm" value="">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Heure :
					</td>
					<td class="propvalue">
						<input type="text" maxlength="2" size="1" name="hour_adm" value="">
						:
						<input type="text" maxlength="2" size="1" name="min_adm" value="">
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
						<br>
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
						<br>
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
			<input class="button" type="submit" value="Valider">
		</td>
		<td align="center">
			<input class="button" type="reset" value="Annuler">
		</td>
	</tr>
</form>
</table>