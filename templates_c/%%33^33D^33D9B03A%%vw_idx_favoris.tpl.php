<?php /* Smarty version 2.6.3, created on 2004-12-13 15:12:02
         compiled from vw_idx_favoris.tpl */ ?>
<table class="bookCode">
  <tr />
	<?php if ($this->_tpl_vars['codes'] != ""): ?> <!-- @todo -cCe test n'est pas normal, comportement different de CCAM-->
  <tr>
  <?php if (count($_from = (array)$this->_tpl_vars['codes'])):
    foreach ($_from as $this->_tpl_vars['curr_key'] => $this->_tpl_vars['curr_code']):
?>
    <td>
      <strong><a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_code']['code']; ?>
"><?php echo $this->_tpl_vars['curr_code']['code']; ?>
</a></strong><br />
      <?php echo $this->_tpl_vars['curr_code']['text']; ?>
<br />

      <form name="delFavoris" action="./index.php?m=dPcim10" method="post">
      <input type="hidden" name="dosql" value="do_favoris_aed">
      <input type="hidden" name="del" value="1">
      <input type="hidden" name="favoris_id" value="<?php echo $this->_tpl_vars['curr_code']['id']; ?>
">
	  <input class="button" type="submit" name="btnFuseAction" value="Retirer de mes favoris">
	  </form>
    </td>
  <?php if (!(( $this->_tpl_vars['curr_key']+1 ) % 4)): ?>
  </tr><tr>
  <?php endif; ?>
  <?php endforeach; unset($_from); endif; ?>
  </tr>
  <?php endif; ?>
</table>