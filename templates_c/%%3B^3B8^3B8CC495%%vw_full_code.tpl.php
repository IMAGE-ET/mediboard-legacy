<?php /* Smarty version 2.6.3, created on 2004-12-13 18:49:17
         compiled from vw_full_code.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'vw_full_code.tpl', 93, false),)), $this); ?>
<table class="fullCode">
  <tr>
  	<td class="pane">

  		<table>
  			<tr>
   				<td colspan="2">
    				<form action="index.php?m=dPccam&tab=2" target="_self" name="selection" method="get" encoding="">
    				<input type="hidden" name="m" value="dPccam">
    				<input type="hidden" name="tab" value="2">

            <table class="form">
              <tr>
        				<th class="mandatory">Code de l'acte:</th>
                <td>
        					<input tabindex="1" type="text" name="codeacte" value="<?php echo $this->_tpl_vars['codeacte']; ?>
">
        					<input tabindex="2" type="submit" value="afficher">
                </td>
              </tr>
            </table>

    				</form>
          </td>
  			</tr>
        
  			<?php if ($this->_tpl_vars['canEdit']): ?>
  			<tr>
  				<td colspan="2">
  					<form name="addFavoris" action="./index.php?m=dPccam" method="post">
  					<input type="hidden" name="dosql" value="do_favoris_aed">
  					<input type="hidden" name="del" value="0">
  					<input type="hidden" name="favoris_code" value="<?php echo $this->_tpl_vars['codeacte']; ?>
">
  					<input type="hidden" name="favoris_user" value="<?php echo $this->_tpl_vars['user']; ?>
">

            <table class="form">
              <tr>
                <td class="button"><input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris"></td>
              </tr>
            </table>

  					</form>
  				</td>
  			</tr>
  			<?php endif; ?>
        
  			<tr>
  				<td colspan="2"><strong>Description</strong><br /><?php echo $this->_tpl_vars['libelle']; ?>
</td>
        </tr>

  			<?php if (count($_from = (array)$this->_tpl_vars['rq'])):
    foreach ($_from as $this->_tpl_vars['curr_rq']):
?>
  			<tr>
  				<td colspan="2"><em><?php echo $this->_tpl_vars['curr_rq']; ?>
</em></td>
  			</tr>
  			<?php endforeach; unset($_from); endif; ?>
 
  			<tr>
  				<td colspan="2"><strong>Activités associées</strong></td>
  			</tr>
 
  			<?php if (count($_from = (array)$this->_tpl_vars['act'])):
    foreach ($_from as $this->_tpl_vars['curr_act']):
?>
  			<tr>
  				<td valign="top"><strong><?php echo $this->_tpl_vars['curr_act']['code']; ?>
:</strong></td>
  				<td valign="top" width="100%"><?php echo $this->_tpl_vars['curr_act']['nom']; ?>

            <ul>
            	<li><?php echo $this->_tpl_vars['curr_act']['phases']; ?>
 phase(s)</li>
    					<li>modificateurs: <?php echo $this->_tpl_vars['curr_act']['modificateurs']; ?>
</li>
            </ul>
  				</td>
  			</tr>
  			<?php endforeach; unset($_from); endif; ?>
        
  			<tr>
  				<td colspan="2"><strong>Procédure associée:</strong></td>
  			</tr>
        
  			<tr>
  				<td><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['codeproc']; ?>
"><strong><?php echo $this->_tpl_vars['codeproc']; ?>
</strong></a></td>
  				<td><?php echo $this->_tpl_vars['textproc']; ?>
</td>
  			</tr>
  		</table>

  	</td>
  	<td class="pane">

  		<table>
  			<tr>
  				<th class"category" colspan="2">Place dans la CCAM: <?php echo $this->_tpl_vars['place']; ?>
</th>
  			</tr>
        
  			<?php if (count($_from = (array)$this->_tpl_vars['chap'])):
    foreach ($_from as $this->_tpl_vars['curr_chap']):
?>
  			<tr>
  				<th><?php echo $this->_tpl_vars['curr_chap']['rang']; ?>
</th>
  				<td><?php echo $this->_tpl_vars['curr_chap']['nom']; ?>
<br /><em><?php echo ((is_array($_tmp=$this->_tpl_vars['curr_chap']['rq'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</em></td>
  			</tr>
  			<?php endforeach; unset($_from); endif; ?>
        
  		</table>

  	</td>
  </tr>
  <tr>
  	<td class="pane">

  		<table>
  			<tr>
  				<th class="category" colspan="2">Actes associés (<?php echo $this->_foreach['associations']['asso']['total']; ?>
)</th>
  			</tr>
        
  			<?php if (isset($this->_foreach['associations'])) unset($this->_foreach['associations']);
$this->_foreach['associations']['name'] = 'associations';
$this->_foreach['associations']['total'] = count($_from = (array)$this->_tpl_vars['asso']);
$this->_foreach['associations']['show'] = $this->_foreach['associations']['total'] > 0;
if ($this->_foreach['associations']['show']):
$this->_foreach['associations']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['curr_asso']):
        $this->_foreach['associations']['iteration']++;
        $this->_foreach['associations']['first'] = ($this->_foreach['associations']['iteration'] == 1);
        $this->_foreach['associations']['last']  = ($this->_foreach['associations']['iteration'] == $this->_foreach['associations']['total']);
?>
  			<tr>
  				<th><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['curr_asso']['code']; ?>
"><?php echo $this->_tpl_vars['curr_asso']['code']; ?>
</a></th>
  				<td><?php echo $this->_tpl_vars['curr_asso']['texte']; ?>
</td>
  			</tr>
  			<?php endforeach; unset($_from); endif; ?>
  		</table>

  	</td>
  	<td class="pane">

  		<table>
  			<tr>
  				<th class="category" colspan="2">Actes incompatibles (<?php echo $this->_foreach['incompatibilites']['asso']['total']; ?>
)</th>
  			</tr>
        
  			<?php if (isset($this->_foreach['incompatibilites'])) unset($this->_foreach['incompatibilites']);
$this->_foreach['incompatibilites']['name'] = 'incompatibilites';
$this->_foreach['incompatibilites']['total'] = count($_from = (array)$this->_tpl_vars['incomp']);
$this->_foreach['incompatibilites']['show'] = $this->_foreach['incompatibilites']['total'] > 0;
if ($this->_foreach['incompatibilites']['show']):
$this->_foreach['incompatibilites']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['curr_incomp']):
        $this->_foreach['incompatibilites']['iteration']++;
        $this->_foreach['incompatibilites']['first'] = ($this->_foreach['incompatibilites']['iteration'] == 1);
        $this->_foreach['incompatibilites']['last']  = ($this->_foreach['incompatibilites']['iteration'] == $this->_foreach['incompatibilites']['total']);
?>
  			<tr>
  				<th><a href="index.php?m=dPccam&tab=2&codeacte=<?php echo $this->_tpl_vars['curr_incomp']['code']; ?>
"><?php echo $this->_tpl_vars['curr_incomp']['code']; ?>
</a></th>
  				<td><?php echo $this->_tpl_vars['curr_incomp']['texte']; ?>
</td>
  			</tr>
  			<?php endforeach; unset($_from); endif; ?>
  		</table>

  	</td>
  </tr>
</table>