<?php /* Smarty version 2.6.3, created on 2004-12-13 15:45:17
         compiled from view_planning.tpl */ ?>
<table class="main">
  <tr><th><a href="javascript:window.print()">Planning du <?php echo $this->_tpl_vars['date']; ?>
</a></th></tr>
  <?php if (count($_from = (array)$this->_tpl_vars['plagesop'])):
    foreach ($_from as $this->_tpl_vars['curr_plageop']):
?>
  <tr>
    <td>
	  <b>Dr. <?php echo $this->_tpl_vars['curr_plageop']['firstname']; ?>
 <?php echo $this->_tpl_vars['curr_plageop']['lastname']; ?>
 :
	  <?php echo $this->_tpl_vars['curr_plageop']['salle']; ?>
 de
	  <?php echo $this->_tpl_vars['curr_plageop']['debut']; ?>
 - <?php echo $this->_tpl_vars['curr_plageop']['fin']; ?>

    le <?php echo $this->_tpl_vars['curr_plageop']['date']; ?>
</b>
	</td>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th colspan="5"><b>Intervention</b></th>
		  <th colspan="3"><b>Patient</b></th>
		</tr>
		<tr>
		  <th>Heure</th>
		  <th>Intervention</th>
		  <th>Coté</th>
      <th>Anesthésie</th>
		  <th>Remarques</th>
		  <th>Nom</th>
		  <th>Prénom</th>
		  <th>Age</th>
		</tr>
		<?php if (count($_from = (array)$this->_tpl_vars['curr_plageop']['operations'])):
    foreach ($_from as $this->_tpl_vars['curr_op']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['curr_op']['heure']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['CCAM']; ?>
 <i>(<?php echo $this->_tpl_vars['curr_op']['CCAM_code']; ?>
)</i></td>
		  <td><?php echo $this->_tpl_vars['curr_op']['cote']; ?>
</td>
      <td><?php echo $this->_tpl_vars['curr_op']['lu_type_anesth']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['rques']; ?>
 <?php if ($this->_tpl_vars['curr_op']['mat']): ?>(<?php echo $this->_tpl_vars['curr_op']['mat']; ?>
)<?php endif; ?></td>
		  <td><?php echo $this->_tpl_vars['curr_op']['lastname']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['firstname']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['age']; ?>
 ans</td>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	  </table>
	</td>
  </tr>
  <?php endforeach; unset($_from); endif; ?>
</table>