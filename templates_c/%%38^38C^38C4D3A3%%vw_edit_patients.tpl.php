<?php /* Smarty version 2.6.3, created on 2004-12-13 18:49:24
         compiled from vw_edit_patients.tpl */ ?>
<!-- $Id$ -->

<?php echo '
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
   
  if (form.matricule.value.length != 15 && form.matricule.value.length != 0) {
    alert("Matricule incorrect");
    form.matricule.focus();
    return false;
  }
   
  return true;
}
//]]>
</script>
'; ?>


<table class="main">
  <tr>
    <td>

      <form name="editFrm" action="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
" method="post" onsubmit="return checkPatient()">

      <input type="hidden" name="dosql" value="do_patients_aed" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="patient_id" value="<?php echo $this->_tpl_vars['patient']->patient_id; ?>
" />
      
      <table class="form">
      
      <tr>
        <th class="category" colspan="2">Identité</th>
        <th class="category" colspan="2">Information médicales</th>
      </tr>
      
      <tr>
        <th class="mandatory">Nom:</th>
        <td><input tabindex="1" type="text" name="nom" value="<?php echo $this->_tpl_vars['patient']->nom; ?>
" /></td>
        <th>Incapable majeur:</th>
        <td>
          <input tabindex="15" type="radio" name="incapable_majeur" value="o" <?php if ($this->_tpl_vars['patient']->incapable_majeur == 'o'): ?> checked="checked" <?php endif; ?> />oui
          <input tabindex="16" type="radio" name="incapable_majeur" value="n" <?php if ($this->_tpl_vars['patient']->incapable_majeur == 'n'): ?> checked="checked" <?php endif; ?> />non
        </td>
      </tr>
      
      <tr>
        <th class="mandatory">Prénom:</th>
        <td><input tabindex="2" type="text" name="prenom" value="<?php echo $this->_tpl_vars['patient']->prenom; ?>
" /></td>
        <th>ATNC:</th>
        <td>
          <input tabindex="17" type="radio" name="ATNC" value="o" <?php if ($this->_tpl_vars['patient']->ATNC == 'o'): ?> checked="checked" <?php endif; ?> />oui
          <input tabindex="18" type="radio" name="ATNC" value="n" <?php if ($this->_tpl_vars['patient']->ATNC == 'n'): ?> checked="checked" <?php endif; ?> />non
        </td>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td>
          <input tabindex="3" type="text" name="_jour"  size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->_jour; ?>
" /> -
          <input tabindex="4" type="text" name="_mois"  size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->_mois; ?>
" /> -
          <input tabindex="5" type="text" name="_annee" size="2" maxlength="4" value="<?php echo $this->_tpl_vars['patient']->_annee; ?>
" />
        </td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <select tabindex="6" name="sexe">
            <option value="m" <?php if ($this->_tpl_vars['patient']->sexe == 'm'): ?> selected="selected" <?php endif; ?>>masculin</option>
            <option value="f" <?php if ($this->_tpl_vars['patient']->sexe == 'f'): ?> selected="selected" <?php endif; ?>>féminin</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonnées</th>
        <th class="category" colspan="2">Information administratives</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><input tabindex="7" type="text" name="adresse" value="<?php echo $this->_tpl_vars['patient']->adresse; ?>
" /></td>
        
        <th>Numéro d'assuré social:</th>
        <td><input tabindex="19" type="text" size="15" maxlength="15" name="matricule" value="<?php echo $this->_tpl_vars['patient']->matricule; ?>
" /></td>
      </tr>
      
      <tr>
        <th>Ville:</th>
        <td><input tabindex="8" type="text" name="ville" value="<?php echo $this->_tpl_vars['patient']->ville; ?>
" /></td>
        <th>Code administratif:</th>
        <td><input tabindex="20" type="text" name="SHS" value="<?php echo $this->_tpl_vars['patient']->SHS; ?>
" /></td>
      </tr>
      
      <tr>
        <th>Code Postal:</th>
        <td><input tabindex="9" type="text" name="cp" value="<?php echo $this->_tpl_vars['patient']->cp; ?>
" /></td>
      </tr>
      
      <tr>
        <th>Téléphone:</th>
        <td>
          <input tabindex="10" type="text" name="_tel1" size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->tel['0'];  echo $this->_tpl_vars['patient']->tel['1']; ?>
" /> - 
          <input tabindex="11" type="text" name="_tel2" size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->tel['2'];  echo $this->_tpl_vars['patient']->tel['3']; ?>
" /> -
          <input tabindex="12" type="text" name="_tel3" size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->tel['4'];  echo $this->_tpl_vars['patient']->tel['5']; ?>
" /> -
          <input tabindex="13" type="text" name="_tel4" size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->tel['6'];  echo $this->_tpl_vars['patient']->tel['7']; ?>
" /> -
          <input tabindex="14" type="text" name="_tel5" size="1" maxlength="2" value="<?php echo $this->_tpl_vars['patient']->tel['8'];  echo $this->_tpl_vars['patient']->tel['9']; ?>
" />
        </td>
      </tr>
      
      <tr>
        <td class="button" colspan="4">
          <?php if ($this->_tpl_vars['patient']->patient_id): ?>
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Valider" />
            <input type="button" value="Supprimer" onclick="<?php echo 'if (confirm(\'Veuillez confirmer la suppression\')) {this.form.del.value = 1; this.form.submit();}'; ?>
"/>
          <?php else: ?>
            <input type="submit" value="Créer" />
          <?php endif; ?>
        </td>
      </tr>
      
      </table>

      </form>

    </td>
  </tr>
</table>