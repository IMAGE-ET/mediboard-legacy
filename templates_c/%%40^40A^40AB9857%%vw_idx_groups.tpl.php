<?php /* Smarty version 2.6.3, created on 2004-12-13 18:50:06
         compiled from vw_idx_groups.tpl */ ?>
<?php echo '
<script language="javascript">
function checkGroup() {
  var form = document.group;
    
  if (form.text.value.length == 0) {
    alert("Intitulé manquant");
    form.text.focus();
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
&tab=2&usergroup=0"><strong>Créer un groupe</strong></a>

    <table class="color">
      
    <tr>
      <th>liste des groupes</th>
    </tr>
    
		<?php if (count($_from = (array)$this->_tpl_vars['groups'])):
    foreach ($_from as $this->_tpl_vars['curr_group']):
?>
    <tr>
      <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=2&usergroup=<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
"><?php echo $this->_tpl_vars['curr_group']['text']; ?>
</a></td>
    </tr>
    <?php endforeach; unset($_from); endif; ?>
      
    </table>

  </td>
  
  <td class="pane">

    <form name="group" action="./index.php?m=<?php echo $this->_tpl_vars['m']; ?>
" method="post" onsubmit="return checkGroup()">
    <input type="hidden" name="dosql" value="do_groups_aed" />
		<input type="hidden" name="group_id" value="<?php echo $this->_tpl_vars['groupsel']['group_id']; ?>
" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      <?php if ($this->_tpl_vars['groupsel']['exist']): ?>
        Modification du groupe &lsquo;<?php echo $this->_tpl_vars['groupsel']['text']; ?>
&rsquo;
      <?php else: ?>
        Création d'un groupe
      <?php endif; ?>
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="group_text" title="intitulé du groupe, obligatoire.">Intitulé:</label></th>
      <td><input type="text" name="text" id="group_text" value="<?php echo $this->_tpl_vars['groupsel']['text']; ?>
" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        <?php if ($this->_tpl_vars['groupsel']['exist']): ?>
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="<?php echo 'if (confirm(\'Veuillez confirmer la suppression\')) {this.form.del.value = 1; this.form.submit();}'; ?>
" />
        <?php else: ?>
        <input type="submit" name="btnFuseAction" value="Créer" />
        <?php endif; ?>
      </td>
    </tr>

    </table>

  </td>
</tr>

</table>