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
			{if $usersel.exist == 0}
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
						{foreach from=$functions item=curr_function}
							<option value="{$curr_function.function_id}">{$curr_function.text}</option>
						{/foreach}
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
			{else}
				<tr>
					<th colspan=3>
						Modification de l'utilisateur <i>{$usersel.username}</i>
						<input type="hidden" name="user_id" value="{$usersel.id}">
					</th>
				</tr>
				<tr>
					<td class="propname">
						Login :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_username" value="{$usersel.username}"> *
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
						<input type="text" name="user_last_name" value="{$usersel.lastname}"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Prénom :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_first_name" value="{$usersel.firstname}"> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Fonction :
					</td>
					<td colspan=2 class="propvalue">
						<select name="function_id">
						{foreach from=$functions item=curr_function}
						{if $curr_function.function_id == $usersel.function}
							<option value="{$curr_function.function_id}" selected>{$curr_function.text}</option>
						{else}
							<option value="{$curr_function.function_id}">{$curr_function.text}</option>
						{/if}
						{/foreach}
						</select> *
					</td>
				</tr>
				<tr>
					<td class="propname">
						Email :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_email" value="{$usersel.email}">
					</td>
				</tr>
				<tr>
					<td class="propname">
						Tel :
					</td>
					<td colspan=2 class="propvalue">
						<input type="text" name="user_phone" value="{$usersel.phone}">
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
						<input type="hidden" name="user_id" value="{$usersel.id}">
						<input type="hidden" name="del" value="1">
						<input class="button" type="submit" name="btnFuseAction" value="Supprimer">
						</form>
					</td>
				</tr>
			{/if}
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
				{foreach from=$users item=curr_user}
				<tr>
					<td bgcolor="#{$curr_user.color}">
						<a href="index.php?m=mediusers&tab=0&mediuser={$curr_user.id}">{$curr_user.username}</a>
					</td>
					<td bgcolor="#{$curr_user.color}">
						<a href="index.php?m=mediusers&tab=0&mediuser={$curr_user.id}">{$curr_user.lastname}</a>
					</td>
					<td bgcolor="#{$curr_user.color}">
						<a href="index.php?m=mediusers&tab=0&mediuser={$curr_user.id}">{$curr_user.firstname}</a>
					</td>
					<td bgcolor="#{$curr_user.color}">
						<a href="index.php?m=mediusers&tab=0&mediuser={$curr_user.id}">{$curr_user.functionname}</a>
					</td>
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
</table>