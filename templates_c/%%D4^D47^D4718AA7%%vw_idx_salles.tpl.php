<?php /* Smarty version 2.6.3, created on 2004-12-13 15:35:11
         compiled from vw_idx_salles.tpl */ ?>
<!-- $Id$ -->

<?php echo '
<script language="javascript">
function checkSalle() {
  var form = document.salle;
    
  if (form.nom.value.length == 0) {
    alert("Intitulé manquant");
    form.nom.focus();
    return false;
  }
    
  return true;
}
</script>
'; ?>


<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=4&usersalle=0"><strong>Créer une salle</strong></a>

    <table class="color">
      
    <tr>
      <th>liste des salles</th>
    </tr>
    
    <?php if (count($_from = (array)$this->_tpl_vars['salles'])):
    foreach ($_from as $this->_tpl_vars['curr_salle']):
?>
    <tr>
      <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=4&usersalle=<?php echo $this->_tpl_vars['curr_salle']['id']; ?>
"><?php echo $this->_tpl_vars['curr_salle']['nom']; ?>
</a></td>
    </tr>
    <?php endforeach; unset($_from); endif; ?>
      
    </table>

  </td>
  
  <td class="pane">

    <form name="salle" action="./index.php?m=<?php echo $this->_tpl_vars['m']; ?>
" method="post" onsubmit="return checkSalle()">
    <input type="hidden" name="dosql" value="do_salle_aed" />
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['sallesel']['id']; ?>
" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      <?php if ($this->_tpl_vars['sallesel']['exist']): ?>
        Modification de la salle &lsquo;<?php echo $this->_tpl_vars['sallesel']['nom']; ?>
&rsquo;
      <?php else: ?>
        Création d'une salle
      <?php endif; ?>
      </th>
    </tr>

    <tr>
      <th class="mandatory">Intitulé:</th>
      <td><input type="text" name="nom" value="<?php echo $this->_tpl_vars['sallesel']['nom']; ?>
" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        <?php if ($this->_tpl_vars['sallesel']['exist']): ?>
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="<?php echo 'if (confirm(\'Veuillez confirmer la suppression\')) {this.form.del.value = 1; this.form.submit();}'; ?>
"/>
        <?php else: ?>
        <input type="submit" name="btnFuseAction" value="Créer">
        <?php endif; ?>
      </td>
    </tr>

    </table>

  </td>
</tr>

</table>