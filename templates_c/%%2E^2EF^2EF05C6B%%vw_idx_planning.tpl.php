<?php /* Smarty version 2.6.3, created on 2004-12-13 15:33:56
         compiled from vw_idx_planning.tpl */ ?>
<table class="main">

  <tr>
    <td colspan="2">
      <form action="index.php" target="_self" name="selection" method="get" encoding="">

      <input type="hidden" name="m" value="<?php echo $this->_tpl_vars['m']; ?>
">
      <input type="hidden" name="tab" value="0">
      Choisir un chirurgien :
      <select name="selChir" onchange="this.form.submit()">
        <option value="0">Aucun chirurgien</option>
        <?php if (count($_from = (array)$this->_tpl_vars['listChir'])):
    foreach ($_from as $this->_tpl_vars['curr_chir']):
?>
        <option value="<?php echo $this->_tpl_vars['curr_chir']['id']; ?>
" <?php if ($this->_tpl_vars['curr_chir']['id'] == $this->_tpl_vars['selChir']): ?> selected="selected" <?php endif; ?>>
          Dr. <?php echo $this->_tpl_vars['curr_chir']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_chir']['firstname']; ?>

        </option>
        <?php endforeach; unset($_from); endif; ?>
      </select>
  
      </form>
    </td>
  </tr>

  <tr>
    <th>
      <a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&day=<?php echo $this->_tpl_vars['pmonthd']; ?>
&month=<?php echo $this->_tpl_vars['pmonth']; ?>
&year=<?php echo $this->_tpl_vars['pmonthy']; ?>
"><<</a>
      <?php echo $this->_tpl_vars['title1']; ?>

      <a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&day=<?php echo $this->_tpl_vars['nmonthd']; ?>
&month=<?php echo $this->_tpl_vars['nmonth']; ?>
&year=<?php echo $this->_tpl_vars['nmonthy']; ?>
">>></a>
    </th>
    <th>
      <a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&day=<?php echo $this->_tpl_vars['pday']; ?>
&month=<?php echo $this->_tpl_vars['pdaym']; ?>
&year=<?php echo $this->_tpl_vars['pdayy']; ?>
"><<</a>
      <?php echo $this->_tpl_vars['title2']; ?>

      <a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&day=<?php echo $this->_tpl_vars['nday']; ?>
&month=<?php echo $this->_tpl_vars['ndaym']; ?>
&year=<?php echo $this->_tpl_vars['ndayy']; ?>
">>></a>
    </th>
  </tr>

  <tr>
    <td>
      <table class="color">
        <tr>
          <th>Date</th>
          <th>Plage</th>
          <th>Opérations</th>
          <th>Temps pris</th>
        </tr>

        <?php if (count($_from = (array)$this->_tpl_vars['list'])):
    foreach ($_from as $this->_tpl_vars['curr_plage']):
?>
        <?php if ($this->_tpl_vars['curr_plage']['spe']): ?>
         <tr style="background: #ddd">
          <td align="right"><?php echo $this->_tpl_vars['curr_plage']['date']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['curr_plage']['horaires']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['curr_plage']['operations']; ?>
</td>
          <td align="center">Plage de spécialité</td>
        </tr>

        <?php else: ?>
        <tr style="background: #fff">
          <td align="right"><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&day=<?php echo $this->_tpl_vars['curr_plage']['day']; ?>
&month=<?php echo $this->_tpl_vars['month']; ?>
&year=<?php echo $this->_tpl_vars['year']; ?>
"><?php echo $this->_tpl_vars['curr_plage']['date']; ?>
</a></td>
          <td align="center"><?php echo $this->_tpl_vars['curr_plage']['horaires']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['curr_plage']['operations']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['curr_plage']['occupe']; ?>
</td>
        </tr>
        <?php endif; ?>
        <?php endforeach; unset($_from); endif; ?>
      </table>
    </td>

    <td>
      <table class="tbl">
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>code CCAM</th>
          <th width="300">Description</th>
          <th>Heure prévue</th>
          <th>Durée</th>
        </tr>

        <?php if (count($_from = (array)$this->_tpl_vars['today'])):
    foreach ($_from as $this->_tpl_vars['curr_op']):
?>
        <tr>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=1&operation_id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
"><?php echo $this->_tpl_vars['curr_op']['nom']; ?>
      </a></td>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=1&operation_id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
"><?php echo $this->_tpl_vars['curr_op']['prenom']; ?>
   </a></td>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=1&operation_id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
"><?php echo $this->_tpl_vars['curr_op']['CCAM_code']; ?>
</a></td>
          <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=1&operation_id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
"><?php echo $this->_tpl_vars['curr_op']['CCAM']; ?>
     </a></td>
          <td style="text-align: center;"><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=1&operation_id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
"><?php echo $this->_tpl_vars['curr_op']['heure']; ?>
</a></td>
          <td style="text-align: center;"><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=1&operation_id=<?php echo $this->_tpl_vars['curr_op']['id']; ?>
"><?php echo $this->_tpl_vars['curr_op']['temps']; ?>
</a></td>
        </tr>
        <?php endforeach; unset($_from); endif; ?>
      </table>
    </td>
  </tr>
</table>