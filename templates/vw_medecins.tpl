<!-- $Id$ -->

{literal}
<script type="text/javascript">
//<![CDATA[
function checkPatient() {
  var form = document.editFrm;
    
  if (form.nom.value.length == 0) {
    alert("Nom manquant");
    form.nom.focus();
    return false;
  }
    
  if (form.prenom.value.length == 0) {
    alert("Prénom manquant");
    form.prenom.focus();
    return false;
  }
   
  return true;
}
//]]>
</script>
{/literal}

<table class="main">
  <tr>
    <td class="greedyPane">
    
      <form name="find" action="./index.php" method="get">
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="{$tab}" />
      
      <table class="form">
      <input type="hidden" name="new" value="1" />
        <tr>
          <th class="category" colspan="2">Recherche d'un médecin</th>
        </tr>
  
        <tr>
          <th>Nom:</th>
          <td><input tabindex="1" type="text" name="medecin_nom" value="{$nom}" /></td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td><input tabindex="2" type="text" name="medecin_prenom" value="{$prenom}" /></td>
        </tr>
        
        <tr>
          <td class="button" colspan="2"><input type="submit" value="rechercher" /></td>
        </tr>
      </table>

      </form>
      
      <table class="tbl">
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Adresse</th>
          <th>Ville</th>
        </tr>

        {foreach from=$medecins item=curr_medecin}
        <tr>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;medecin_id={$curr_medecin.medecin_id}">{$curr_medecin.nom}</a></td>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;medecin_id={$curr_medecin.medecin_id}">{$curr_medecin.prenom}</a></td>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;medecin_id={$curr_medecin.medecin_id}">{$curr_medecin.adresse}</a></td>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;medecin_id={$curr_medecin.medecin_id}">{$curr_medecin.ville}</a></td>
        </tr>
        {/foreach}
        
      </table>

    </td>

    <td class="pane">
      <form name="editFrm" action="index.php?m={$m}" method="post" onsubmit="return checkPatient()">
      <input type="hidden" name="dosql" value="do_medecins_aed" />
      <input type="hidden" name="del" value="0" />
      <table class="form">
        <tr>
          <td colspan="2"><a href="index.php?m={$m}&amp;tab={$tab}&amp;new=1">Créer un nouveau médecin</a></td>
        </tr>
        <tr>
          <th class="category" colspan="2">Fiche médecin</th>
        </tr>

        <tr>
          <th>Nom:</th>
          <td><input type="text" name="nom" value="{$medecin->nom}" /></td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td><input type="text" name="prenom" value="{$medecin->prenom}" /></td>
        </tr>
        
        <tr>
          <th>Adresse:</th>
          <td><input type="text" name="adresse" value="{$medecin->adresse}" /></td>
        </tr>
        
        <tr>
          <th>Code Postal:</th>
          <td><input type="text" name="cp" value="{$medecin->cp}" /></td>
        </tr>
        
        <tr>
          <th>Ville:</th>
          <td><input type="text" name="ville" value="{$medecin->ville}" /></td>
        </tr>
        
        <tr>
          <th>Tel:</th>
          <td>
            <input type="text" size="2" maxlength="2" name="_tel1" value="{$medecin->_tel1}" /> -
            <input type="text" size="2" maxlength="2" name="_tel2" value="{$medecin->_tel2}" /> -
            <input type="text" size="2" maxlength="2" name="_tel3" value="{$medecin->_tel3}" /> -
            <input type="text" size="2" maxlength="2" name="_tel4" value="{$medecin->_tel4}" /> -
            <input type="text" size="2" maxlength="2" name="_tel5" value="{$medecin->_tel5}" />
          </td>
        </tr>
        
        <tr>
          <th>Fax:</th>
          <td>
            <input type="text" size="2" maxlength="2" name="_fax1" value="{$medecin->_fax1}" /> -
            <input type="text" size="2" maxlength="2" name="_fax2" value="{$medecin->_fax2}" /> -
            <input type="text" size="2" maxlength="2" name="_fax3" value="{$medecin->_fax3}" /> -
            <input type="text" size="2" maxlength="2" name="_fax4" value="{$medecin->_fax4}" /> -
            <input type="text" size="2" maxlength="2" name="_fax5" value="{$medecin->_fax5}" />
          </td>
        </tr>
        
        <tr>
          <th>Email:</th>
          <td><input type="text" name="email" value="{$medecin->email}" /></td>
        </tr>

        <tr>
          <td class="button" colspan="4">
        {if $medecin->medecin_id}
            <input type="hidden" name="medecin_id" value="{$medecin->medecin_id}" />
            <input type="submit" value="Modifier" />
            <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
        {else}
            <input type="submit" value="Créer" />
        {/if}
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
      