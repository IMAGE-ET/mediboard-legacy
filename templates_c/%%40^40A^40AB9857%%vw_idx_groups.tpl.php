<?php /* Smarty version 2.6.3, created on 2004-09-17 11:12:50
         compiled from vw_idx_groups.tpl */ ?>
<table width="100%">
	<tr>
		<td valign="top" width="50%" height="100%">
			<a href="index.php?m=mediusers&tab=2&usergroup=0"><b>Créer un groupe</b></a>
		</td>
		<td valign="top" rowspan=2 width="50%">
			<form name="group" action="./index.php?m=mediusers" method="post">
			<input type="hidden" name="dosql" value="do_groups_aed">
			<input type="hidden" name="del" value="0">
			<table class="form" align="center">
				<?php if ($this->_tpl_vars['groupsel']['exist'] == 0): ?>
				<tr>
					<th colspan=2>
						Création d'un nouveau groupe
					</th>
				</tr>
				<tr>
					<td class="propname">
						Intitulé :
					</td>
					<td class="propvalue">
						<input type="text" name="text">
					</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<input class="button" type="submit" name="btnFuseAction" value="Créer">
					</td>
				</tr>
				<?php else: ?>
				<tr>
					<th colspan=2>
						Modification du groupe <i><?php echo $this->_tpl_vars['groupsel']['text']; ?>
</i>
						<input type="hidden" name="group_id" value="<?php echo $this->_tpl_vars['groupsel']['group_id']; ?>
">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Intitulé :
					</td>
					<td class="propvalue">
						<input type="text" name="text" value="<?php echo $this->_tpl_vars['groupsel']['text']; ?>
">
					</td>
				</tr>
				<tr>
					<td>
						<input class="button" type="submit" name="btnFuseAction" value="Modifier">
						</form>
					</td>
					<td align="right">
						<form name="group" action="./index.php?m=mediusers" method="post">
						<input type="hidden" name="dosql" value="do_groups_aed">
						<input type="hidden" name="group_id" value="<?php echo $this->_tpl_vars['groupsel']['group_id']; ?>
">
						<input type="hidden" name="del" value="1">
						<input class="button" type="submit" name="btnFuseAction" value="Supprimer">
					</td>
				</tr>
				<?php endif; ?>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td valign="top" align="center">
			<table class="tbl">
				<th>
					liste des groupes
				</th>
				<?php if (count($_from = (array)$this->_tpl_vars['groups'])):
    foreach ($_from as $this->_tpl_vars['curr_group']):
?>
				<tr>
					<td bgcolor="#d2e5fb">
						<a href="index.php?m=mediusers&tab=2&usergroup=<?php echo $this->_tpl_vars['curr_group']['group_id']; ?>
"><?php echo $this->_tpl_vars['curr_group']['text']; ?>
</a>
					</td>
				</tr>
				<?php endforeach; unset($_from); endif; ?>
			</table>
		</td>
	</tr>
</table>