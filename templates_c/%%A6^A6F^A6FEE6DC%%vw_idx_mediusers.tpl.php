<?php /* Smarty version 2.6.3, created on 2004-09-17 11:12:46
         compiled from vw_idx_mediusers.tpl */ ?>
<table width="100%">
	<tr>
		<td valign="top" height="100%">
			<a href="index.php?m=mediusers&tab=0&mediuser=0"><b>Créer un utilisateur</b></a>
		</td>
		<td valign="top" rowspan=2>
			<table class="form" align="center">
			<form name="mediuser" action="./index.php?m=mediusers" method="post">
			<input type="hidden" name="dosql" value="do_mediusers_aed">
			<input type="hidden" name="del" value="0">
			<?php if ($this->_tpl_vars['usersel']['exist'] == 0): ?>
				<tr>
					<th colspan=2>
						Création d'un nouvel utilisateur
					</th>
				</tr>
				<tr>
					<td class="propname">
						Login :
					</td>
					<td class="propvalue">
						<input type="text" name="user_username"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Mot de passe :
					</td>
					<td class="propvalue">
						<input type="password" name="user_password"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Mot de passe (vérif.) :
					</td>
					<td class="propvalue">
						<input type="password" name="user_password2"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Nom :
					</td>
					<td class="propvalue">
						<input type="text" name="user_last_name"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Prénom :
					</td>
					<td class="propvalue">
						<input type="text" name="user_first_name"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Fonction :
					</td>
					<td class="propvalue">
						<select name="function_id">
						<?php if (count($_from = (array)$this->_tpl_vars['functions'])):
    foreach ($_from as $this->_tpl_vars['curr_function']):
?>
							<option value="<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
"><?php echo $this->_tpl_vars['curr_function']['text']; ?>
</option>
						<?php endforeach; unset($_from); endif; ?>
						</select> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Email :
					</td>
					<td class="propvalue">
						<input type="text" name="user_email">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Tel :
					</td>
					<td class="propvalue">
						<input type="text" name="user_phone">
					</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<input class="button" type="submit" name="btnFuseAction" value="Créer">
					</td>
				</tr>
				</form>
			<?php else: ?>
				<tr>
					<th colspan=3>
						Modification de l'utilisateur <i><?php echo $this->_tpl_vars['usersel']['username']; ?>
</i>
						<input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['usersel']['id']; ?>
">
					</th>
				</tr>
				<tr>
					<td class="propname">
						Login :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_username" value="<?php echo $this->_tpl_vars['usersel']['username']; ?>
"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Nouveau mot de passe :
					</td>
					<td>
						<input type="password" name="user_password">
					</td>
					<td align="center" rowspan=2>
						<i>pour ne pas changer de mot de passe,<br>
						laissez les champs vides</i>
					</td>
				</tr>
				<tr>
					<td class="propname">
						Nouveau mot de passe (vérif.) :
					</td>
					<td class="propvalue">
						<input type="password" name="user_password2">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Nom :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_last_name" value="<?php echo $this->_tpl_vars['usersel']['lastname']; ?>
"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Prénom :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_first_name" value="<?php echo $this->_tpl_vars['usersel']['firstname']; ?>
"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Fonction :
					</td>
					<td colspan=2 class="propvalue">
						<select name="function_id">
						<?php if (count($_from = (array)$this->_tpl_vars['functions'])):
    foreach ($_from as $this->_tpl_vars['curr_function']):
?>
						<?php if ($this->_tpl_vars['curr_function']['function_id'] == $this->_tpl_vars['usersel']['function']): ?>
							<option value="<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
" selected><?php echo $this->_tpl_vars['curr_function']['text']; ?>
</option>
						<?php else: ?>
							<option value="<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
"><?php echo $this->_tpl_vars['curr_function']['text']; ?>
</option>
						<?php endif; ?>
						<?php endforeach; unset($_from); endif; ?>
						</select> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Email :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_email" value="<?php echo $this->_tpl_vars['usersel']['email']; ?>
">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Tel :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_phone" value="<?php echo $this->_tpl_vars['usersel']['phone']; ?>
">
					</td>
				</tr>
				<tr>
				<tr>
					<td>
						<input class="button" type="submit" name="btnFuseAction" value="Modifier">
						</form>
					</td>
					<td align="right" colspan=2>
						<form name="mediuser" action="./index.php?m=mediusers" method="post">
						<input type="hidden" name="dosql" value="do_mediusers_aed">
						<input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['usersel']['id']; ?>
">
						<input type="hidden" name="del" value="1">
						<input class="button" type="submit" name="btnFuseAction" value="Supprimer">
						</form>
					</td>
				</tr>
			<?php endif; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" align="center">
			<table class="color">
				<th>
					login
				</th>
				<th>
					nom
				</th>
				<th>
					prenom
				</th>
				<th>
					fonction
				</th>
				<?php if (count($_from = (array)$this->_tpl_vars['users'])):
    foreach ($_from as $this->_tpl_vars['curr_user']):
?>
				<tr>
					<td bgcolor="#<?php echo $this->_tpl_vars['curr_user']['color']; ?>
">
						<a href="index.php?m=mediusers&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['username']; ?>
</a>
					</td>
					<td bgcolor="#<?php echo $this->_tpl_vars['curr_user']['color']; ?>
">
						<a href="index.php?m=mediusers&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['lastname']; ?>
</a>
					</td>
					<td bgcolor="#<?php echo $this->_tpl_vars['curr_user']['color']; ?>
">
						<a href="index.php?m=mediusers&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['firstname']; ?>
</a>
					</td>
					<td bgcolor="#<?php echo $this->_tpl_vars['curr_user']['color']; ?>
">
						<a href="index.php?m=mediusers&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['functionname']; ?>
</a>
					</td>
				</tr>
				<?php endforeach; unset($_from); endif; ?>
			</table>
		</td>
	</tr>
</table>