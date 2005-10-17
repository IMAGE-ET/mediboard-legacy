<!-- $Id$ -->

{literal}
<script type="text/javascript">
function confirmCreation(id, bDialog, sSiblingsText) {
  if (!confirm(sSiblingsText)) {
    var form = document.editFrm;
    form.del.value = 1;
    form.submit();
  } else {
    url = "index.php?m=dPpatients";
    url += bDialog ? "&a=pat_selector&dialog=1" : ("&tab=vw_idx_patients&id=" + id + "&nom=&prenom=");
    window.location.href = url;
  }
}

function printPatient(id) {
  var url = './index.php?m=dPpatients&a=print_patient&dialog=1';
  url = url + '&patient_id=' + id;
  popup(700, 550, url, 'Patient');
}

function popMed(type) {
  var url = './index.php?m=dPpatients';
  url += '&a=vw_medecins';
  url += '&dialog=1';
  url += '&type=' + type;
  popup(700, 400, url, 'Medecin');
}

function delMed(sElementName) {
  form = document.editFrm;
  
  fieldMedecin = eval("form.medecin" + sElementName);
  fieldMedecinName = eval("form._medecin" + sElementName + "_name");
	
  fieldMedecin.value = "";
  fieldMedecinName.value = "";
}

function setMed( key, nom, prenom, sElementName ){
  form = document.editFrm;
  
  fieldMedecin = eval("form.medecin" + sElementName);
  fieldMedecinName = eval("form._medecin" + sElementName + "_name");
	
  fieldMedecin.value = key;
  fieldMedecinName.value = "Dr. " + nom + " " + prenom;
}

function followUp(field, sFollowFieldName) {
  if (field.value.length == 2) {
    fieldFollow = field.form.elements[sFollowFieldName];
    fieldFollow.focus();
  }  
}

</script>
{/literal}

<table class="main">
  {if $patient->patient_id}
  <tr>
    <td><strong><a href="index.php?m={$m}&amp;patient_id=0">Cr�er un nouveau patient</a></strong></td>
  </tr>
  {/if}
  <tr>
    <td>

      <form name="editFrm" action="index.php?m={$m}" method="post" onsubmit="return checkForm(this)">

      <input type="hidden" name="dosql" value="do_patients_aed" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="patient_id" value="{$patient->patient_id}" />
      {if $dialog}
      <input type="hidden" name="dialog" value="{$dialog}" />
      {/if}
      
      <table class="form">

      <tr>
      {if $patient->patient_id}
        <th class="title" colspan="5" style="color: #f00;">
          <a style="float:right;" href="javascript:view_log('CPatient', {$patient->patient_id})">
            <img src="images/history.gif" alt="historique" />
          </a>
          Modification du dossier de {$patient->_view}
        </th>
      {else}
        <th class="title" colspan="5">Cr�ation d'un dossier</th>
      {/if}
      </tr>

      <tr>
        <th class="category" colspan="2">Identit�</th>
        <th class="category" colspan="3">Informations m�dicales</th>
      </tr>
      
      <tr>
        <th><label for="nom" title="Nom du patient. Obligatoire">Nom:</label></th>
        <td><input tabindex="1" type="text" name="nom" value="{$patient->nom}" title"{$patient->_props.nom}" /></td>
        <th><label for="incapable_majeur" title="Patient reconnu incapable majeur">Incapable majeur:</label></th>
        <td colspan="2">
          <input tabindex="21" type="radio" name="incapable_majeur" value="o" {if $patient->incapable_majeur == "o"} checked="checked" {/if} />oui
          <input tabindex="22" type="radio" name="incapable_majeur" value="n" {if $patient->incapable_majeur == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th><label for="prenom" title="Pr�nom du patient. Obligatoire">Pr�nom:</label></th>
        <td><input tabindex="2" type="text" name="prenom" value="{$patient->prenom}" title"{$patient->_props.prenom}" /></td>
        <th>ATNC:</th>
        <td colspan="2">
          <input tabindex="23" type="radio" name="ATNC" value="o" {if $patient->ATNC == "o"} checked="checked" {/if} />oui
          <input tabindex="24" type="radio" name="ATNC" value="n" {if $patient->ATNC == "n"} checked="checked" {/if} />non
        </td>
      </tr>
      
      <tr>
        <th><label for="nom_jeune_fille" title="Nom de jeune fille d'une femme mari�e">Nom de jeune fille:</label></th>
        <td><input tabindex="3" type="text" name="nom_jeune_fille" title="{$patient->_props.nom_jeune_fille}" value="{$patient->nom_jeune_fille}" /></td>
        <th><label for="matricule" title="Matricule valide d'assur� social (13 chiffres + 2 pour la cl�)">Num�ro d'assur� social:</label></th>
        <td colspan="2">
          <input tabindex="25" type="text" size="15" maxlength="15" name="matricule" title="{$patient->_props.matricule}" value="{$patient->matricule}" />
        </td>
      </tr>
      
      <tr>
        <th><label for="_jour" title="Date de naissance du patient, au format JJ-MM-AAAA">Date de naissance:</label></th>
        <td>
          <input tabindex="4" type="text" name="_jour"  size="2" maxlength="2" value="{if $patient->_jour != "00"}{$patient->_jour}{/if}" onkeyup="followUp(this, '_mois')" /> -
          <input tabindex="5" type="text" name="_mois"  size="2" maxlength="2" value="{if $patient->_mois != "00"}{$patient->_mois}{/if}" onkeyup="followUp(this, '_annee')" /> -
          <input tabindex="6" type="text" name="_annee" size="4" maxlength="4" value="{if $patient->_annee != "0000"}{$patient->_annee}{/if}" />
        </td>
        <th><label for="SHS" title="Code Administratif SHS">Code administratif:</label></th>
        <td colspan="2">
          <input tabindex="26" type="text" size="10" maxlength="10" name="SHS" title="{$patient->_props.SHS}" value="{$patient->SHS}" />
        </td>
      </tr>
      
      <tr>
        <th><label for="sexe" title="Sexe du patient">Sexe:</label></th>
        <td>
          <select tabindex="7" name="sexe" title="{$patient->_props.sexe}">
            <option value="m" {if $patient->sexe == "m"} selected="selected" {/if}>masculin</option>
            <option value="f" {if $patient->sexe == "f"} selected="selected" {/if}>f�minin</option>
            <option value="j" {if $patient->sexe == "j"} selected="selected" {/if}>femme c�libataire</option>
          </select>
        </td>
        <td colspan="3"></td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonn�es</th>
        <th class="category" colspan="3">M�decins correpondants</th>
      </tr>
      
      <tr>
        <th><label for="adresse" title="Adresse du patient">Adresse:</label></th>
        <td><textarea tabindex="8" name="adresse" title="{$patient->_props.adresse}" rows="1">{$patient->adresse}</textarea></td>
        <th>
          <label for="medecin_traitant" title="Choisir un m�decin traitant">Medecin traitant:</label>
          <input type="hidden" name="medecin_traitant" title="{$patient->_props.medecin_traitant}" value="{$patient->medecin_traitant}" />
        </th>
        <td class="readonly">
          <input type="text" name="_medecin_traitant_name" size="30" value="Dr. {$patient->_ref_medecin_traitant->_view}" readonly="readonly" />
          <button type="button" onclick="delMed('_traitant')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="27" type="button" value="choisir un m�decin" onclick="popMed('_traitant')" /></td>
      </tr>
      
      <tr>
        <th><label for="cp" title="Code postal">Code Postal:</label></th>
        <td><input tabindex="9" type="text" name="cp" value="{$patient->cp}" title="{$patient->_props.cp}" /></td>
        <th>
          <label for="medecin1" title="Choisir un m�decin correspondant">M�decin correspondant 1:</label>
          <input type="hidden" name="medecin1" value="{$patient->_ref_medecin1->medecin_id}" title="{$patient->_props.medecin1}" />
        </th>
        <td class="readonly">
          <input type="text" name="_medecin1_name" size="30" value="Dr. {$patient->_ref_medecin1->_view}" readonly="readonly" />
          <button type="button" onclick="delMed('1')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" />
        </td>
        <td class="button"><input tabindex="28" type="button" value="choisir un m�decin" onclick="popMed('1')" /></td>
      </tr>
      
      <tr>
        <th><label for="sexe" title="Ville du patient">Ville:</label></th>
        <td><input tabindex="10" type="text" name="ville" value="{$patient->ville}" title="{$patient->_props.ville}" /></td>
        <th>
          <input type="hidden" name="medecin2" value="{$patient->_ref_medecin2->medecin_id}" title="{$patient->_props.medecin2}" />
          <label for="medecin2" title="Choisir un second m�decin correspondant">M�decin correspondant 2:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin2_name" size="30" value="{if ($patient->_ref_medecin2)}Dr. {$patient->_ref_medecin2->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('2')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="29" type="button" value="choisir un m�decin" onclick="popMed('2')" /></td>
      </tr>
      
      <tr>
        <th><label for="_tel1" title="Num�ro de t�l�phone filaire">T�l�phone:</label></th>
        <td>
          <input tabindex="11" type="text" name="_tel1" size="2" maxlength="2" value="{$patient->_tel1}" title="num|length|2" onkeyup="followUp(this, '_tel2')" /> - 
          <input tabindex="12" type="text" name="_tel2" size="2" maxlength="2" value="{$patient->_tel2}" title="num|length|2" onkeyup="followUp(this, '_tel3')" /> -
          <input tabindex="13" type="text" name="_tel3" size="2" maxlength="2" value="{$patient->_tel3}" title="num|length|2" onkeyup="followUp(this, '_tel4')" /> -
          <input tabindex="14" type="text" name="_tel4" size="2" maxlength="2" value="{$patient->_tel4}" title="num|length|2" onkeyup="followUp(this, '_tel5')" /> -
          <input tabindex="15" type="text" name="_tel5" size="2" maxlength="2" value="{$patient->_tel5}" title="num|length|2" onkeyup="followUp(this, '_tel21')" />
        </td>
        <th>
          <input type="hidden" name="medecin3" value="{$patient->_ref_medecin3->medecin_id}" title="{$patient->_props.medecin3}" />
          <label for="medecin3" title="Choisir un troisi�me m�decin correspondant">M�decin correspondant 3:</label>
        </th>
        <td class="readonly">
          <input type="text" name="_medecin3_name" size="30" value="{if ($patient->_ref_medecin3)}Dr. {$patient->_ref_medecin3->_view}{/if}" readonly="readonly" />
          <button type="button" onclick="delMed('3')"><img src="modules/{$m}/images/cross.png" title="supprimer" alt="supprimer" /></button>
        </td>
        <td class="button"><input tabindex="30" type="button" value="choisir un m�decin" onclick="popMed('3')" /></td>
      </tr>
      
      <tr>
        <th><label for="_tel21" title="Num�ro de t�l�phone portable">Portable:</label></th>
        <td>
          <input tabindex="16" type="text" name="_tel21" size="2" maxlength="2" value="{$patient->_tel21}" title="num|length|2" onkeyup="followUp(this, '_tel22')" /> - 
          <input tabindex="17" type="text" name="_tel22" size="2" maxlength="2" value="{$patient->_tel22}" title="num|length|2" onkeyup="followUp(this, '_tel23')" /> -
          <input tabindex="18" type="text" name="_tel23" size="2" maxlength="2" value="{$patient->_tel23}" title="num|length|2" onkeyup="followUp(this, '_tel24')" /> -
          <input tabindex="19" type="text" name="_tel24" size="2" maxlength="2" value="{$patient->_tel24}" title="num|length|2" onkeyup="followUp(this, '_tel25')" /> -
          <input tabindex="20" type="text" name="_tel25" size="2" maxlength="2" value="{$patient->_tel25}" title="num|length|2" />
        </td>
        <th colspan="3"></th>
      </tr>
      
      <tr>
        <th><label for="rques" title="Remarques g�n�rales concernant le patient"></label>Remarques:</th>
        <td colspan="4">
          <textarea tabindex="31" title="{$patient->_props.rques}" name="rques">{$patient->rques}</textarea>
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="5">
          {if $patient->patient_id}
            <input type="reset" value="R�initialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'le patient', '{$patient->_view|escape:javascript}')"/>
            <input type="button" value="Imprimer" onclick="printPatient({$patient->patient_id})" />
          {else}
            <input tabindex="32" type="submit" value="Cr�er" />
          {/if}
        </td>
      </tr>
      
      </table>

      </form>

    </td>
  </tr>
</table>

<script type="text/javascript">
{if $textSiblings}
  confirmCreation({$created}, {if $dialog}1{else}0{/if}, "{$textSiblings|escape:javascript}");
{/if}
</script>
