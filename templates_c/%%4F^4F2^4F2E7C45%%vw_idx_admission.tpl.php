<?php /* Smarty version 2.6.3, created on 2004-12-13 15:44:53
         compiled from vw_idx_admission.tpl */ ?>
<table class="main">
  <tr>
    <td colspan="2">
	  <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <input type="hidden" name="m" value="dPadmissions">
      <input type="hidden" name="tab" value="0">
      Type d'affichage :
	    <select name="selAff" onchange="this.form.submit()">
	      <option value="0" <?php if ($this->_tpl_vars['selAff'] == '0'): ?> selected = "selected" <?php endif; ?>>Toutes les admissions</option>
		    <option value="o" <?php if ($this->_tpl_vars['selAff'] == 'o'): ?> selected = "selected" <?php endif; ?>>Admissions effectuées</option>
        <option value="n" <?php if ($this->_tpl_vars['selAff'] == 'n'): ?> selected = "selected" <?php endif; ?>>Admissions non effectuées</option>
	    </select>
    </form>
	  </td>
  </tr>
  <tr>
    <th width="50%">
	    <a href="index.php?m=dPadmissions&tab=0&day=<?php echo $this->_tpl_vars['pmonthd']; ?>
&month=<?php echo $this->_tpl_vars['pmonth']; ?>
&year=<?php echo $this->_tpl_vars['pmonthy']; ?>
"><<</a>
	    <?php echo $this->_tpl_vars['title1']; ?>

	    <a href="index.php?m=dPadmissions&tab=0&day=<?php echo $this->_tpl_vars['nmonthd']; ?>
&month=<?php echo $this->_tpl_vars['nmonth']; ?>
&year=<?php echo $this->_tpl_vars['nmonthy']; ?>
">>></a>
	  </th>
	  <th width="50%">
	    <a href="index.php?m=dPadmissions&tab=0&day=<?php echo $this->_tpl_vars['pday']; ?>
&month=<?php echo $this->_tpl_vars['pdaym']; ?>
&year=<?php echo $this->_tpl_vars['pdayy']; ?>
"><<</a>
	    <?php echo $this->_tpl_vars['title2']; ?>

	    <a href="index.php?m=dPadmissions&tab=0&day=<?php echo $this->_tpl_vars['nday']; ?>
&month=<?php echo $this->_tpl_vars['ndaym']; ?>
&year=<?php echo $this->_tpl_vars['ndayy']; ?>
">>></a>
    </th>
  </tr>
  <tr>
    <td>
	  <table class="tbl">
	    <tr>
		  <th>
		    Date
		  </th>
		  <th>
		    Nombre d'admissions
		  </th>
		</tr>
	    <?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['curr_list']):
?>
		<tr>
		  <td align="right">
		    <a href="index.php?m=dPadmissions&tab=0&day=<?php echo $this->_tpl_vars['curr_list']['day']; ?>
&month=<?php echo $this->_tpl_vars['month']; ?>
&year=<?php echo $this->_tpl_vars['year']; ?>
">
			<?php echo $this->_tpl_vars['curr_list']['dateFormed']; ?>

			</a>
		  </td>
		  <td align="center">
		    <?php echo $this->_tpl_vars['curr_list']['num']; ?>

		  </td>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	  </table>
	</td>
	<td>
	  <table class="tbl">
	    <tr>
		  <th>
		    Nom
		  </th>
		  <th>
		    Prénom
		  </th>
		  <th>
		    Chirurgien
		  </th>
		  <th>
		    Heure
		  </th>
		</tr>
	    <?php if (count($_from = (array)$this->_tpl_vars['today'])):
    foreach ($_from as $this->_tpl_vars['curr_adm']):
?>
		<tr>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id=<?php echo $this->_tpl_vars['curr_adm']['operation_id']; ?>
">
		    <?php echo $this->_tpl_vars['curr_adm']['nom']; ?>

			</a>
		  </td>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id=<?php echo $this->_tpl_vars['curr_adm']['operation_id']; ?>
">
		    <?php echo $this->_tpl_vars['curr_adm']['prenom']; ?>

			</a>
		  </td>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id=<?php echo $this->_tpl_vars['curr_adm']['operation_id']; ?>
">
		    Dr. <?php echo $this->_tpl_vars['curr_adm']['chir_lastname']; ?>
 <?php echo $this->_tpl_vars['curr_adm']['chir_firstname']; ?>

			</a>
		  </td>
		  <td>
		    <a href="index.php?m=dPadmissions&tab=1&id=<?php echo $this->_tpl_vars['curr_adm']['operation_id']; ?>
">
		    <?php echo $this->_tpl_vars['curr_adm']['hour']; ?>

			</a>
		  </td>
		  <?php if ($this->_tpl_vars['curr_adm']['admis'] == 'n'): ?>
		  <td>
			<form name="editFrm<?php echo $this->_tpl_vars['curr_adm']['id']; ?>
" action="index.php" method="get">
            <input type="hidden" name="m" value="dPadmissions" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['curr_adm']['operation_id']; ?>
" />
			<input type="submit" value="Admis" />
			</form> 
		  </td>
		  <?php endif; ?>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	  </table>
	</td>
  </tr>
</table>