<!-- $Id$ -->

{literal}
<script language="javascript">
function createPat(){
  window.location = "index.php?m=dPpatients&a=vw_edit_patients&dialog=1&id=0";
}

function setClose(key, val){
  window.opener.setPat(key,val);
  window.close();
}
</script>
{/literal}

<form action="index.php" target="_self" name="frmSelector" method="get">

<input type="hidden" name="m" value="dPpatients">
<input type="hidden" name="a" value="pat_selector">
<input type="hidden" name="dialog" value="1">

<table class="form">

<tr>
  <th class="category" colspan="3">Critères de tri</th>
</tr>

<tr>
  <th>Nom:</th>
  <td><input name="name" value="{$name}" size="30" /></td>
  <td></td>
</tr>
<tr>
  <th>Prénom:</th>
  <td><input name="firstName" value="{$firstName}" size="30" /></td>
  <td><input type="submit" value="rechercher" /></td>
</tr>

<tr>
  <th class="category" colspan="3">Choisissez un patient dans la liste</th>
</tr>

</table>

<table class="tbl">
<tr>
  <th align="center">Patient</th>
  <th align="center">Date de naissance</th>
  <th align="center">Selectionner</th>
</tr>
{foreach from=$list item=curr_patient}
<tr>
  <td>{$curr_patient->_view}</td>
  <td>{$curr_patient->_naissance}</td>
  <td class="button"><input type="button" class="button" value="selectionner" onclick="setClose({$curr_patient->patient_id}, '{$curr_patient->_view|escape:javascript}')" /></td>
</tr>
{/foreach}
</table>

<table class="form">

<tr>
  <td class="button" colspan="2">
    <input type="button" class="button" value="Créer un patient" onclick="createPat()" />
    <input type="button" class="button" value="Annuler" onclick="window.close()" />
  </td>
</tr>

</table>

</form>
