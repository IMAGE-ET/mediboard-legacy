<?php /* Smarty version 2.6.3, created on 2004-12-13 18:50:06
         compiled from vw_idx_functions.tpl */ ?>
<?php echo '
<script language="javascript">
function checkForm() {
  var form = document.editFrm;
    
  if (form.text.value.length == 0) {
    alert("Intitulé manquant");
    form.text.focus();
    return false;
  }
    
  return true;
}

function setColor(color) {
	var f = document.editFrm;

	if (color) {
		f.color.value = color;
	}

	document.getElementById(\'test\').style.background = \'#\' + f.color.value;
}
</script>
'; ?>


<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m=mediusers&tab=1&userfunction=0"><strong>Créer une fonction</strong></a>

    <table class="color">
      
    <tr>
      <th>listes des fonctions</th>
      <th>groupe</th>
      <th>couleur</th>
    </tr>
    
		<?php if (count($_from = (array)$this->_tpl_vars['functions'])):
    foreach ($_from as $this->_tpl_vars['curr_function']):
?>
    <tr>
      <td><a href="index.php?m=mediusers&tab=1&userfunction=<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
"><?php echo $this->_tpl_vars['curr_function']['text']; ?>
</a></td>
      <td><a href="index.php?m=mediusers&tab=1&userfunction=<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
"><?php echo $this->_tpl_vars['curr_function']['mygroup']; ?>
</a></td>
      <td style="background: #<?php echo $this->_tpl_vars['curr_function']['color']; ?>
" />
    </tr>
    <?php endforeach; unset($_from); endif; ?>
      
    </table>

  </td>
  
  <td class="pane">

  	<form name="editFrm" action="./index.php?m=mediusers" method="post" onSubmit="return checkForm()">
  	<input type="hidden" name="dosql" value="do_functions_aed">
		<input type="hidden" name="function_id" value="<?php echo $this->_tpl_vars['functionsel']['function_id']; ?>
">
  	<input type="hidden" name="del" value="0">

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      <?php if ($this->_tpl_vars['functionsel']['exist']): ?>
        Modification de la fonction &lsquo;<?php echo $this->_tpl_vars['functionsel']['text']; ?>
&rsquo;
      <?php else: ?>
        Création d'une fonction
      <?php endif; ?>
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_text" title="Intitulé de la fonction. Obligatoire">Intitulé:</label></th>
      <td><input type="text" name="text" value="<?php echo $this->_tpl_vars['functionsel']['text']; ?>
" /></td>
    </tr>
    
    <tr>
      <th class="mandatory"><label for="editFrm_group_id" title="Groupe auquel se rattache la fonction">Groupe:</label></th>
      <td>
      	<select name="group_id">
      	<?php if (count($_from = (array)$this->_tpl_vars['groups'])):
    foreach ($_from as $this->_tpl_vars['curr_group']):
?>
     			<option value="<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
" <?php if ($this->_tpl_vars['curr_group']['group_id'] == $this->_tpl_vars['functionsel']['group_id']): ?> selected="selected" <?php endif; ?>>
            <?php echo $this->_tpl_vars['curr_group']['text']; ?>

          </option>
      	<?php endforeach; unset($_from); endif; ?>
      	</select>
      </td>
    </tr>

    <tr>
      <th><label for="editFrm_color" title="Couleur de visualisation des fonctions dans les plannings">Couleur:</label></th>
      <td>
        <span id="test" title="test" style="background: #<?php echo $this->_tpl_vars['functionsel']['color']; ?>
;">
          <a href="#" onClick="window.open('./index.php?m=public&a=color_selector&dialog=1&callback=setColor', 'calwin', 'width=320, height=300, scollbars=false');">cliquez ici</a>
        </span>
        <input type="hidden" name="color" value="<?php echo $this->_tpl_vars['functionsel']['color']; ?>
" />
      </td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
      <?php if ($this->_tpl_vars['functionsel']['exist']): ?>
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

    </form>
  </td>
</tr>

</table>