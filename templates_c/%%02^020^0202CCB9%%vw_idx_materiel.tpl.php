<?php /* Smarty version 2.6.3, created on 2004-12-13 15:35:08
         compiled from vw_idx_materiel.tpl */ ?>
<!-- $Id$ -->

<table class="main">
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>Date</th>
		  <th>Chirurgien</th>
		  <th>Patient</th>
		  <th>Opération</th>
		  <th>Materiel à commander</th>
		  <th>Valider</th>
		</tr>
		<?php if (count($_from = (array)$this->_tpl_vars['op'])):
    foreach ($_from as $this->_tpl_vars['curr_op']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['curr_op']['dateFormed']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['chir_name']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['pat_name']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['curr_op']['CCAM']; ?>
 <i>(<?php echo $this->_tpl_vars['curr_op']['CCAM_code']; ?>
)</i></td>
		  <td><?php echo $this->_tpl_vars['curr_op']['materiel']; ?>
</td>
		  <td>
			<form name="editFrm<?php echo $this->_tpl_vars['curr_op']['id']; ?>
" action="index.php" method="get">
            <input type="hidden" name="m" value="dPbloc" />
            <input type="hidden" name="a" value="do_edit_mat" />
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['curr_op']['id']; ?>
" />
		    <input type="submit" value="commandé" />
			</form>
		  </td>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	  </table>
	</td>
  </tr>
</table>