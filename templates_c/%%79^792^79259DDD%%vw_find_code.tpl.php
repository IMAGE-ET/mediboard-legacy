<?php /* Smarty version 2.6.3, created on 2004-12-13 15:15:13
         compiled from vw_find_code.tpl */ ?>
<form action="index.php" target="_self" name="selection" method="get" encoding="">
<input type="hidden" name="m" value="dPcim10">
<input type="hidden" name="tab" value="1">

<table class="form">
  <tr>
    <th class="category" colspan="2">Critères de recherche</th>
  </tr>

  <tr>
    <th>Mots clefs:</th>
    <td><input tabindex="1" type="text" name="keys" value="<?php echo $this->_tpl_vars['keys']; ?>
" /></td>
  </tr>
  
  <tr>
    <td class="button" colspan="2">
      <input tabindex="2" type="reset" value="réinitialiser" />
      <input tabindex="3" type="submit" value="rechercher" />
    </td>
  </tr>
</table>

<table class="findCode">

  <tr>
    <th colspan="4">
      <?php if ($this->_tpl_vars['numresults'] == 100): ?>
      Plus de <?php echo $this->_tpl_vars['numresults']; ?>
 résultats trouvés, seuls les 100 premiers sont affichés:
      <?php else: ?>
      <?php echo $this->_tpl_vars['numresults']; ?>
 résultats trouvés:
      <?php endif; ?>
    </th>
  </tr>


  <tr>
  <?php if (count($_from = (array)$this->_tpl_vars['master'])):
    foreach ($_from as $this->_tpl_vars['curr_key'] => $this->_tpl_vars['curr_master']):
?>
    <td>
      <strong><a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_master']['code']; ?>
"><?php echo $this->_tpl_vars['curr_master']['code']; ?>
</a></strong><br />
			<?php echo $this->_tpl_vars['curr_master']['text']; ?>

    </td>
  <?php if (!(( $this->_tpl_vars['curr_key']+1 ) % 4)): ?>
  </tr><tr>
  <?php endif; ?>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

</table>