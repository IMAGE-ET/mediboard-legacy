<?php /* Smarty version 2.6.3, created on 2004-12-13 16:03:33
         compiled from vw_idx_patients.tpl */ ?>
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
    
  return true;
}
//]]>
</script>
'; ?>


<table class="main">
  <tr>
    <td class="greedyPane">
    
      <form name="find" action="./index.php" method="get">
      <input type="hidden" name="m" value="dPpatients" />
      
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
        </tr>
  
        <tr>
          <th>Nom:</th>
          <td><input tabindex="1" type="text" name="nom" value="<?php echo $this->_tpl_vars['nom']; ?>
" /></td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td><input tabindex="2" type="text" name="prenom" value="<?php echo $this->_tpl_vars['prenom']; ?>
" /></td>
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

        <?php if (count($_from = (array)$this->_tpl_vars['patients'])):
    foreach ($_from as $this->_tpl_vars['curr_patient']):
?>
        <tr>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
"><?php echo $this->_tpl_vars['curr_patient']['nom']; ?>
</a></td>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
"><?php echo $this->_tpl_vars['curr_patient']['prenom']; ?>
</a></td>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
"><?php echo $this->_tpl_vars['curr_patient']['adresse']; ?>
</a></td>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&id=<?php echo $this->_tpl_vars['curr_patient']['patient_id']; ?>
"><?php echo $this->_tpl_vars['curr_patient']['ville']; ?>
</a></td>
        </tr>
        <?php endforeach; unset($_from); endif; ?>
        
      </table>

    </td>
 
    <?php if ($this->_tpl_vars['patient']->patient_id): ?>
    <td class="pane">
      <table class="form">
        <tr>
          <th class="category" colspan="2">Identité</th>
          <th class="category" colspan="2">Information médicales</th>
        </tr>

        <tr>
          <th>Nom:</th>
          <td><?php echo $this->_tpl_vars['patient']->nom; ?>
</td>
          <th>Incapable majeur:</th>
          <td>
            <?php if ($this->_tpl_vars['patient']->incapable_majeur == 'o'): ?> oui <?php endif; ?>
            <?php if ($this->_tpl_vars['patient']->incapable_majeur == 'n'): ?> non <?php endif; ?>
          </td>
        </tr>
        
        <tr>
          <th>Prénom:</th>
          <td><?php echo $this->_tpl_vars['patient']->prenom; ?>
</td>
          <th>ATNC:</th>
          <td>
            <?php if ($this->_tpl_vars['patient']->ATNC == 'o'): ?> oui <?php endif; ?>
            <?php if ($this->_tpl_vars['patient']->ATNC == 'n'): ?> non <?php endif; ?>
        </tr>
        
        <tr>
          <th>Date de naissance:</th>
          <td><?php echo $this->_tpl_vars['patient']->dateFormed; ?>
</td>
        </tr>
        
        <tr>
          <th>Sexe:</th>
          <td>
            <?php if ($this->_tpl_vars['patient']->sexe == 'm'): ?> masculin <?php endif; ?>
            <?php if ($this->_tpl_vars['patient']->sexe == 'f'): ?> féminin  <?php endif; ?> 
          </td>
        </tr>
        
        <tr>
          <th class="category" colspan="2">Coordonnées</th>
          <th class="category" colspan="2">Information administratives</th>
        </tr>
        
        <tr>
          <th>Adresse:</th>
          <td><?php echo $this->_tpl_vars['patient']->adresse; ?>
</td>
          <th>Numéro d'assuré social:</th>
          <td><?php echo $this->_tpl_vars['patient']->matricule; ?>
</td>
        </tr>
        
        <tr>
          <th>Ville:</th>
          <td><?php echo $this->_tpl_vars['patient']->ville; ?>
</td>
          <th>Code administratif:</th>
          <td><?php echo $this->_tpl_vars['patient']->SHS; ?>
</td>
        </tr>
        
        <tr>
          <th>Code Postal:</th>
          <td><?php echo $this->_tpl_vars['patient']->cp; ?>
</td>
        </tr>
        
        <tr>
          <th>Téléphone:</th>
          <td><?php echo $this->_tpl_vars['patient']->tel; ?>
</td>
        </tr>
        
        <?php if ($this->_tpl_vars['canEdit']): ?>
        <tr>
          <td class="button" colspan="4">
            <form name="modif" action="./index.php" method="get">
            <input type="hidden" name="m" value="dPpatients" />
            <input type="hidden" name="tab" value="1" />
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['patient']->patient_id; ?>
" />
            <input type="submit" value="Modifier" />
            </form>
          </td>
        </tr>
        <?php endif; ?>

      </table>
      
    </td>
    <?php endif; ?>

  </tr>
</table>
      