<?php /* Smarty version 2.6.3, created on 2004-12-13 15:45:59
         compiled from vw_edit_interventions.tpl */ ?>
<?php echo '
<script language="javascript">
function popOp(id) {
  window.open(\'./index.php?m=dPbloc&a=view_operation&dialog=1&id=\'+id, \'Intervention\', \'left=50,top=50,height=320,width=700,resizable\');
}
</script>
'; ?>


<table class="main">
  <tr>
    <th colspan=2>
	  Dr. <?php echo $this->_tpl_vars['title']['firstname']; ?>
 <?php echo $this->_tpl_vars['title']['lastname']; ?>

	  <br />
	  <?php echo $this->_tpl_vars['title']['dateFormed']; ?>

	  <br />
	  <?php echo $this->_tpl_vars['title']['salle']; ?>
 : <?php echo $this->_tpl_vars['title']['plage']; ?>

	</th>
  </tr>
  <tr>
    <td width="50%">
	  <table class="tbl">
	    <tr>
		  <th colspan=3>
		    Patients à placer
		  </th>
		</tr>
		<?php if (count($_from = (array)$this->_tpl_vars['list1'])):
    foreach ($_from as $this->_tpl_vars['curr_op']):
?>
		<tr>
		  <td width="50%">
		    <b><a href="#" onclick="popOp( '<?php echo $this->_tpl_vars['curr_op']['id']; ?>
');"><?php echo $this->_tpl_vars['curr_op']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_op']['firstname']; ?>
</a></b>
			<br />
			Code CCAM : <?php echo $this->_tpl_vars['curr_op']['CCAM_code']; ?>

			<br />
      Côté : <?php echo $this->_tpl_vars['curr_op']['cote']; ?>

      <br />
			Durée : <?php echo $this->_tpl_vars['curr_op']['duree']; ?>

		  </td>
		  <td>
			<i><?php echo $this->_tpl_vars['curr_op']['CCAM']; ?>
</i>
		  </td>
		  <td>
		    <a href="index.php?m=<?php echo $this->_tpl_vars['module']; ?>
&a=do_order_op&cmd=insert&id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
">
		    <img src="./modules/<?php echo $this->_tpl_vars['module']; ?>
/images/tick.png" width="12" height="12" alt="ajouter" border="0" />
			</a>
		  </td>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	  </table>
	</td>
	<td width="50%">
	  <table class="tbl"
	    <tr>
		  <th colspan=3>
		    Ordre des interventions
		  </th>
		</tr>
		<?php if (count($_from = (array)$this->_tpl_vars['list2'])):
    foreach ($_from as $this->_tpl_vars['curr_op']):
?>
		<tr>
		  <td width="50%">
			<form name="editFrm<?php echo $this->_tpl_vars['curr_op']['id']; ?>
" action="index.php" method="get">
            <input type="hidden" name="m" value="<?php echo $this->_tpl_vars['module']; ?>
" />
            <input type="hidden" name="a" value="do_order_op" />
            <input type="hidden" name="cmd" value="sethour" />
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['curr_op']['id']; ?>
" />
		    <b><a href="#" onclick="popOp( '<?php echo $this->_tpl_vars['curr_op']['id']; ?>
');"><?php echo $this->_tpl_vars['curr_op']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_op']['firstname']; ?>
</a></b>
			<br />
			Code CCAM : <?php echo $this->_tpl_vars['curr_op']['CCAM_code']; ?>

			<br />
      Côté : <?php echo $this->_tpl_vars['curr_op']['cote']; ?>

      <br />
			Durée : <?php echo $this->_tpl_vars['curr_op']['duree']; ?>

			<br />
			<select name="hour">
			  <option selected="selected"><?php echo $this->_tpl_vars['curr_op']['hour']; ?>
</option>
			  <?php if (count($_from = (array)$this->_tpl_vars['curr_op']['listhour'])):
    foreach ($_from as $this->_tpl_vars['curr_hour']):
?>
			  <option><?php echo $this->_tpl_vars['curr_hour']; ?>
</option>
			  <?php endforeach; unset($_from); endif; ?>
			</select>
			h
			<select name="min">
			  <option selected="selected"><?php echo $this->_tpl_vars['curr_op']['min']; ?>
</option>
			  <option>00</option>
			  <option>15</option>
			  <option>30</option>
			  <option>45</option>
			</select>
			<input type="submit" value="changer" />
			</form>
      <br />
      <form name="editFrm<?php echo $this->_tpl_vars['curr_op']['id']; ?>
" action="index.php" method="get">
            <input type="hidden" name="m" value="<?php echo $this->_tpl_vars['module']; ?>
" />
            <input type="hidden" name="a" value="do_order_op" />
            <input type="hidden" name="cmd" value="setanesth" />
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['curr_op']['id']; ?>
" />
      <select name="type">
        <option value="NULL">-- Anesthésie</option>
        <?php if (count($_from = (array)$this->_tpl_vars['anesth'])):
    foreach ($_from as $this->_tpl_vars['curr_anesth']):
?>
        <option <?php if ($this->_tpl_vars['curr_op']['lu_type_anesth'] == $this->_tpl_vars['curr_anesth']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['curr_anesth']; ?>
</option>
        <?php endforeach; unset($_from); endif; ?>
      </select>
      <input type="submit" value="changer" />
      </form>
		  </td>
		  <td>
			<i><?php echo $this->_tpl_vars['curr_op']['CCAM']; ?>
</i>
		  </td>
		  <td>
		    <?php if ($this->_tpl_vars['curr_op']['rank'] != 1): ?>
		    <a href="index.php?m=<?php echo $this->_tpl_vars['module']; ?>
&a=do_order_op&cmd=up&id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
">
		    <img src="./modules/<?php echo $this->_tpl_vars['module']; ?>
/images/uparrow.png" width="12" height="12" alt="monter" border="0" />
			</a>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['curr_op']['rank'] != 1 && $this->_tpl_vars['curr_op']['rank'] != $this->_tpl_vars['max']): ?>
			<br />
			<?php endif; ?>
			<?php if ($this->_tpl_vars['curr_op']['rank'] != $this->_tpl_vars['max']): ?>
		    <a href="index.php?m=<?php echo $this->_tpl_vars['module']; ?>
&a=do_order_op&cmd=down&id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
">
		    <img src="./modules/<?php echo $this->_tpl_vars['module']; ?>
/images/downarrow.png" width="12" height="12" alt="descendre" border="0" />
			</a>
			<?php endif; ?>
		  </td>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	  </table>
	</td>
  </tr>
</table>