<?php /* Smarty version 2.6.3, created on 2004-12-13 16:03:31
         compiled from vw_find_code.tpl */ ?>
<form action="index.php" target="_self" name="selection" method="get" encoding="">
<input type="hidden" name="m" value="dPccam">
<input type="hidden" name="tab" value="1">

<table class="form">
  <tr>
    <th class="category" colspan="4">Critères de recherche</th>
  </tr>

  <tr>
    <th>Code Partiel:</th>
    <td><input tabindex="1" type="text" name="code" value="<?php echo $this->_tpl_vars['code']; ?>
" maxlength="7" /></td>
    <th>Voie d'accès:</th>
    <td>
      <select tabindex="3" name="selacces" onchange="this.form.submit()">
        <?php if (count($_from = (array)$this->_tpl_vars['acces'])):
    foreach ($_from as $this->_tpl_vars['curr_acces']):
?>
        <option value="<?php echo $this->_tpl_vars['curr_acces']['code']; ?>
" <?php if ($this->_tpl_vars['curr_acces']['code'] == $this->_tpl_vars['selacces']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['curr_acces']['texte']; ?>
</option>
        <?php endforeach; unset($_from); endif; ?>
      </select>
    </td>
  </tr>

  <tr>
    <th>Mots clefs:</th>
    <td><input tabindex="2" type="text" name="clefs" value="<?php echo $this->_tpl_vars['clefs']; ?>
" /></td>
    <th>Appareil:</th>
    <td>
      <select tabindex="4" name="seltopo1" onchange="this.form.submit()">
        <?php if (count($_from = (array)$this->_tpl_vars['topo1'])):
    foreach ($_from as $this->_tpl_vars['curr_topo1']):
?>
        <option value="<?php echo $this->_tpl_vars['curr_topo1']['code']; ?>
" <?php if ($this->_tpl_vars['curr_topo1']['code'] == $this->_tpl_vars['seltopo1']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['curr_topo1']['texte']; ?>
</option>
        <?php endforeach; unset($_from); endif; ?>
      </select>
    </td>
  </tr>

  <tr>
    <td class="button" colspan="2">
      <input tabindex="6" type="reset" value="réinitialiser" />
      <input tabindex="7" type="submit" value="rechercher" />
    </td>
    <th>Système:</td>
    <td>
      <select tabindex="5" name="seltopo2" onchange="this.form.submit()">
        <?php if (count($_from = (array)$this->_tpl_vars['topo2'])):
    foreach ($_from as $this->_tpl_vars['curr_topo2']):
?>
        <option value="<?php echo $this->_tpl_vars['curr_topo2']['code']; ?>
" <?php if ($this->_tpl_vars['curr_topo2']['code'] == $this->_tpl_vars['seltopo2']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['curr_topo2']['texte']; ?>
</option>
        <?php endforeach; unset($_from); endif; ?>
      </select>
    </td>
  </tr>

</table>
</form>

<table class="findCode">

  <tr>
    <th colspan="4">
      <?php if ($this->_tpl_vars['numcodes'] == 100): ?>
      Plus de <?php echo $this->_tpl_vars['numcodes']; ?>
 résultats trouvés, seuls les 100 premiers sont affichés:
      <?php else: ?>
      <?php echo $this->_tpl_vars['numcodes']; ?>
 résultats trouvés:
      <?php endif; ?>
    </th>
  </tr>

  <tr>
  <?php if (count($_from = (array)$this->_tpl_vars['codes'])):
    foreach ($_from as $this->_tpl_vars['curr_key'] => $this->_tpl_vars['curr_code']):
?>
    <td>
      <strong><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['curr_code']['code']; ?>
"><?php echo $this->_tpl_vars['curr_code']['code']; ?>
</a></strong><br />
      <?php echo $this->_tpl_vars['curr_code']['texte']; ?>

    </td>
  <?php if (!(( $this->_tpl_vars['curr_key']+1 ) % 4)): ?>
  </tr><tr>
  <?php endif; ?>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

</table>