<?php /* Smarty version 2.6.3, created on 2004-12-13 18:34:43
         compiled from vw_operations.tpl */ ?>
<table class="main">

  <tr>
    <td>
      <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <input type="hidden" name="m" value="<?php echo $this->_tpl_vars['m']; ?>
">
      <input type="hidden" name="tab" value="0">
      Choisir une salle :
      <select name="salle" onchange="this.form.submit()">
        <option value="0">Aucune salle</option>
        <?php if (count($_from = (array)$this->_tpl_vars['listSalles'])):
    foreach ($_from as $this->_tpl_vars['curr_salle']):
?>
        <option value="<?php echo $this->_tpl_vars['curr_salle']['id']; ?>
" <?php if ($this->_tpl_vars['curr_salle']['id'] == $this->_tpl_vars['salle']): ?> selected="selected" <?php endif; ?>>
          <?php echo $this->_tpl_vars['curr_salle']['nom']; ?>

        </option>
        <?php endforeach; unset($_from); endif; ?>
      </select>
      </form>
    </td>
  </tr>

  <?php if (count($_from = (array)$this->_tpl_vars['plages'])):
    foreach ($_from as $this->_tpl_vars['curr_plage']):
?>
  <tr>
    <td>
      <strong>Dr. <?php echo $this->_tpl_vars['curr_plage']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_plage']['firstname']; ?>

      de <?php echo $this->_tpl_vars['curr_plage']['debut']; ?>
 à <?php echo $this->_tpl_vars['curr_plage']['fin']; ?>
</strong>
    </td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
        <tr>
          <th>Heure</th>
          <th>Intervention</th>
          <th>Coté</th>
          <th>Anesthésie</th>
          <th>Remarques</th>
          <th>Patient</th>
          <th>Durée</th>
          <th>Entrée en salle</th>
          <th>Sortie de salle</th>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['curr_plage']['operations'])):
    foreach ($_from as $this->_tpl_vars['curr_operation']):
?>
        <tr>
          <td><?php echo $this->_tpl_vars['curr_operation']['heure']; ?>
</td>
          <td><?php echo $this->_tpl_vars['curr_operation']['CCAM_libelle']; ?>
 (<i><?php echo $this->_tpl_vars['curr_operation']['CCAM_code']; ?>
</i>)</td>
          <td><?php echo $this->_tpl_vars['curr_operation']['cote']; ?>
</td>
          <td><?php echo $this->_tpl_vars['curr_operation']['type_anesth']; ?>
</td>
          <td><?php echo $this->_tpl_vars['curr_operation']['remarques']; ?>
 <?php if ($this->_tpl_vars['curr_operation']['mat']): ?>(<?php echo $this->_tpl_vars['curr_operation']['mat']; ?>
) <?php endif; ?></td>
          <td><?php echo $this->_tpl_vars['curr_operation']['nom']; ?>
 <?php echo $this->_tpl_vars['curr_operation']['prenom']; ?>
</td>
          <td><?php echo $this->_tpl_vars['curr_operation']['duree']; ?>
</td>
          <td align="center">
            <?php if ($this->_tpl_vars['curr_operation']['entree']): ?>
            <?php echo $this->_tpl_vars['curr_operation']['entree']; ?>

            <?php else: ?>
			<form name="editFrm<?php echo $this->_tpl_vars['curr_operation']['id']; ?>
" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="entree" value="<?php echo $this->_tpl_vars['curr_operation']['id']; ?>
" />
              <input type="submit" value="Entrée" />
            </form>
            <?php endif; ?>
          </td>
          <td align="center">
            <?php if ($this->_tpl_vars['curr_operation']['sortie']): ?>
            <?php echo $this->_tpl_vars['curr_operation']['sortie']; ?>

            <?php else: ?>
            <form name="editFrm<?php echo $this->_tpl_vars['curr_operation']['id']; ?>
" action="index.php" method="get">
              <input type="hidden" name="m" value="dPsalleOp" />
              <input type="hidden" name="a" value="do_set_hours" />
              <input type="hidden" name="sortie" value="<?php echo $this->_tpl_vars['curr_operation']['id']; ?>
" />
              <input type="submit" value="Sortie" />
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; unset($_from); endif; ?>
      </table>
    </td>
  </tr>
  <?php endforeach; unset($_from); endif; ?>

</table>