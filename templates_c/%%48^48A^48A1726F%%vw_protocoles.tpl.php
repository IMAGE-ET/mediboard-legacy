<?php /* Smarty version 2.6.3, created on 2004-12-13 15:46:33
         compiled from vw_protocoles.tpl */ ?>
<script language="javascript">
function setClose() {
  window.opener.setProtocole(
    "<?php echo $this->_tpl_vars['chirSel']->user_id; ?>
",
    "<?php echo $this->_tpl_vars['chirSel']->user_last_name; ?>
",
    "<?php echo $this->_tpl_vars['chirSel']->user_first_name; ?>
",
    "<?php echo $this->_tpl_vars['protSel']->CCAM_code; ?>
",
    "<?php echo $this->_tpl_vars['protSel']->_hour_op; ?>
",
    "<?php echo $this->_tpl_vars['protSel']->_min_op; ?>
",
    "<?php echo $this->_tpl_vars['protSel']->examen; ?>
",
    "<?php echo $this->_tpl_vars['protSel']->type_adm; ?>
",
    "<?php echo $this->_tpl_vars['protSel']->duree_hospi; ?>
");
  window.close();
}
</script>

<table class="main">
  <tr>
    <td colspan="2">

      <form name="selectFrm" action="index.php" method="get">
      
      <input type="hidden" name="m" value="<?php echo $this->_tpl_vars['m']; ?>
" />
      <input type="hidden" <?php if ($this->_tpl_vars['dialog']): ?> name="a" value="vw_protocoles" <?php else: ?> name="tab" value="3" <?php endif; ?>  />
      <input type="hidden" name="dialog" value="<?php echo $this->_tpl_vars['dialog']; ?>
" />

      <table>
        <tr>
          <td style="white-space: nowrap;">
            Choisir un chirurgien :
            <select name="chir_id" onchange="this.form.submit()">
              <option value="" >Tous les chirurgiens</option>
              <?php if (count($_from = (array)$this->_tpl_vars['chirs'])):
    foreach ($_from as $this->_tpl_vars['curr_chir']):
?>
              <option value="<?php echo $this->_tpl_vars['curr_chir']['chir_id']; ?>
" <?php if ($this->_tpl_vars['chir_id'] == $this->_tpl_vars['curr_chir']['chir_id']): ?> selected="selected" <?php endif; ?>>
                Dr. <?php echo $this->_tpl_vars['curr_chir']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_chir']['firstname']; ?>
 (<?php echo $this->_tpl_vars['curr_chir']['nb_protocoles']; ?>
)
              </option>
              <?php endforeach; unset($_from); endif; ?>
            </select>
          </td>
          <td style="white-space: nowrap;">
            Choisir un code CCAM :
            <select name="CCAM_code" onchange="this.form.submit()">
              <option value="" >Tous les codes</option>
              <?php if (count($_from = (array)$this->_tpl_vars['codes'])):
    foreach ($_from as $this->_tpl_vars['curr_code']):
?>
              <option value="<?php echo $this->_tpl_vars['curr_code']['CCAM_code']; ?>
" <?php if ($this->_tpl_vars['CCAM_code'] == $this->_tpl_vars['curr_code']['CCAM_code']): ?> selected="selected" <?php endif; ?>>
                <?php echo $this->_tpl_vars['curr_code']['CCAM_code']; ?>
 (<?php echo $this->_tpl_vars['curr_code']['nb_protocoles']; ?>
)
              </option>
              <?php endforeach; unset($_from); endif; ?>
            </select>
          </td>
        </tr>
      </table>
      </form>    
      
    </td>
  </tr>

  <tr>
    <td class="halfPane">

      <table class="tbl">
        <tr>
          <th>Chirurgien &mdash; Acte CCAM</th>
        </tr>
        
        <?php if (count($_from = (array)$this->_tpl_vars['protocoles'])):
    foreach ($_from as $this->_tpl_vars['curr_protocole']):
?>
        <tr>    
          <td>
            <a href="?m=<?php echo $this->_tpl_vars['m']; ?>
&amp;<?php if ($this->_tpl_vars['dialog']): ?>a=vw_protocoles&amp;dialog=1<?php else: ?>tab=3<?php endif; ?>&amp;protocole_id=<?php echo $this->_tpl_vars['curr_protocole']['operation_id']; ?>
">
              <strong>Dr. <?php echo $this->_tpl_vars['curr_protocole']['lastname']; ?>
 <?php echo $this->_tpl_vars['curr_protocole']['firstname']; ?>
 &mdash; <?php echo $this->_tpl_vars['curr_protocole']['CCAM_code']; ?>
</strong>
            </a>
            <br /><?php echo $this->_tpl_vars['curr_protocole']['CCAM_libelle']; ?>

          </td>
        </tr>
        <?php endforeach; unset($_from); endif; ?>

      </table>

    </td>
    <td class="halfPane">

      <?php if ($this->_tpl_vars['protSel']): ?>
      <table class="form">
        <tr>
          <th class="category" colspan="2">Détails du protocole</th>
        </tr>

        <tr>
          <th>Chirurgien:</th>
          <td colspan="3"><strong>Dr. <?php echo $this->_tpl_vars['chirSel']->user_last_name; ?>
 <?php echo $this->_tpl_vars['chirSel']->user_first_name; ?>
</strong></td>
        </tr>
        
        <tr>
          <th>Code CCAM:</th>
          <td class="text"><strong><?php echo $this->_tpl_vars['ccamSel']['CODE']; ?>
</strong><br /><?php echo $this->_tpl_vars['ccamSel']['LIBELLELONG']; ?>
</td>
        </tr>
        
        <tr>
          <th>Temps opératoire</th>
          <td><?php echo $this->_tpl_vars['protSel']->_hour_op; ?>
:<?php echo $this->_tpl_vars['protSel']->_min_op; ?>
</td>
        </tr>
        
        <?php if ($this->_tpl_vars['protSel']->examen): ?>
        <tr>
          <th class="text" colspan="2">Examens complémentaires</th>
        </tr>
                 
        <tr>
          <td class="text" colspan="2"><?php echo $this->_tpl_vars['protSel']->examen; ?>
</td>
        </tr>
        <?php endif; ?>
        
        <tr>
          <th class="category" colspan="2">Détails de l'hospitalisation</th>
        </tr>
        
        <tr>
          <th>Admission en:</th>
          <td>
            <?php if ($this->_tpl_vars['protSel']->type_adm == 'comp'): ?> Hospitalisation complète<?php endif; ?>
            <?php if ($this->_tpl_vars['protSel']->type_adm == 'ambu'): ?> Ambulatoire<?php endif; ?>
			      <?php if ($this->_tpl_vars['protSel']->type_adm == 'exte'): ?> Externe<?php endif; ?>
          </td>
        </tr>

        <tr>
          <th>Durée d'hospitalisation:</th>
          <td><?php echo $this->_tpl_vars['protSel']->duree_hospi; ?>
 jours</td>
        </tr>
  
        <?php if ($this->_tpl_vars['dialog']): ?>
          <tr>
            <td class="button" colspan="3">
              <input type="button" value="Sélectionner ce protocole" onclick="setClose()" />
            </td>
        <?php else: ?>
          <?php if ($this->_tpl_vars['canEdit']): ?>
          <tr>
            <td class="button" colspan="2">
              <form name="modif" action="./index.php" method="get">
              <input type="hidden" name="m" value="<?php echo $this->_tpl_vars['m']; ?>
" />
              <input type="hidden" name="tab" value="5" />
              <input type="hidden" name="protocole_id" value="<?php echo $this->_tpl_vars['protSel']->operation_id; ?>
" />
              <input type="submit" value="Modifier" />
              </form>
            </td>
          </tr>
          <?php endif; ?>
        <?php endif; ?>
      
      </table>
      
      <?php endif; ?> 
     </td>
  </tr>
</table>
