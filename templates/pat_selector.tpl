<!-- $Id$ -->

{literal}
<script language="javascript">
function createPat(){
  window.opener.location = "index.php?m=dPpatients&tab=vw_add_planning";
  window.close();
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
  <td><input name="name" value="{$name}" size=30 /></td>
  <td><input type="submit" value="rechercher" /></td>
</tr>

<tr>
  <th class="category" colspan="3">Choisissez un patient dans la liste</th>
</tr>

</table>

<table class="tbl">
<tr>
  <th align="center">Nom</th>
  <th align="center">Prénom</th>
  <th align="center">Date de naissance</th>
  <th align="center">Selectionner</th>
</tr>
{foreach from=$list item=curr_patient}
<tr>
  <td>{$curr_patient.lastname}</td>
  <td>{$curr_patient.firstname}</td>
  <td>{$curr_patient.naissance}</td>
  <td class="button"><input type="button" class="button" value="selectionner" onclick="setClose({$curr_patient.id}, '{$curr_patient.lastname|escape:javascript} {$curr_patient.firstname|escape:javascript}')" /></td>
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
