<table width="100%" bgcolor="#ff9999">
	<th bgcolor="#ff9999" align="center" colspan=2>
		<h1>{$master.libelle}</h1>
	</th>
	<tr>
		<td bgcolor="#ff9999" valign="middle" align="right" width="50%">
			<form action="index.php" target="_self" name="selection" method="get" encoding="">
			<input type="hidden" name="m" value="dPcim10">
			<input type="hidden" name="tab" value="2">
			<b>code : <input type="text" value="{$master.code}" name="code"></b>
			<input type="submit" value="afficher">
			</form>
		</td>
		<td bgcolor="#ff9999" valign="middle" align="center" width="50%">
			{if $canEdit}
			<form name="addFavoris" action="./index.php?m=dPcim10" method="post">
			<input type="hidden" name="dosql" value="do_favoris_aed">
			<input type="hidden" name="del" value="0">
			<input type="hidden" name="favoris_code" value="{$master.code}">
			<input type="hidden" name="favoris_user" value="{$user}">
			<input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris">
			</form>
			{/if}
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#ffd5d5" height="100%" colspan=2>
			<b>Informations sur ce code :</b>
			<ul compact>
				{if $master.descr != ""}
				<li>Description :
					<ul compact>
						{foreach from=$master.descr item=curr_descr}
						<li>{$curr_descr}</li>
						{/foreach}
					</ul>
				</li>
				{/if}
				{if $master.exclude != ""}
				<li>Exclusions :
					<ul compact>
						{foreach from=$master.exclude item=curr_exclude}
						<li>{$curr_exclude.text} (code : <a href="index.php?m=dPcim10&tab=2&code={$curr_exclude.code}"><b>{$curr_exclude.code}</b></a>)</li>
						{/foreach}
					</ul>
				</li>
				{/if}
				{if $master.glossaire != ""}
				<li>Glossaire :
					<ul compact>
						{foreach from=$master.glossaire item=curr_glossaire}
						<li>{$curr_glossaire}</li>
						{/foreach}
					</ul>
				</li>
				{/if}
				{if $master.include != ""}
				<li>Inclusions :
					<ul compact>
						{foreach from=$master.include item=curr_include}
						<li>{$curr_include}</li>
						{/foreach}
					</ul>
				</li>
				{/if}
				{if $master.indir != ""}
				<li>Exclusions indirectes :
					<ul compact>
						{foreach from=$master.indir item=curr_indir}
						<li>{$curr_indir}</li>
						{/foreach}
					</ul>
				</li>
				{/if}
				{if $master.note != ""}
				<li>Notes :
					<ul compact>
						{foreach from=$master.note item=curr_note}
						<li>{$curr_note}</li>
						{/foreach}
					</ul>
				</li>
				{/if}
			</ul>
		</td>
	</tr>
	<tr>
		{if $master.levelsup.0.sid != 0}
		<td valign="top" bgcolor="#ffd5d5" height="100%" width="50%">
			<b>Codes de niveau superieur :</b>
			<ul>
				{foreach from=$master.levelsup item=curr_level}
				{if $curr_level.sid != 0}
				<li>
					<a href="index.php?m=dPcim10&tab=2&code={$curr_level.code}"><b>{$curr_level.code}</b></a> : {$curr_level.text}
				</li>
				{/if}
				{/foreach}
			</ul>
		</td>
		{/if}
		{if $master.levelinf.0.sid != 0}
		<td valign="top" bgcolor="#ffd5d5" height="100%" width="50%">
			<b>Codes de niveau inferieur :</b>
			<ul>
				{foreach from=$master.levelinf item=curr_level}
				{if $curr_level.sid != 0}
				<li>
					<a href="index.php?m=dPcim10&tab=2&code={$curr_level.code}"><b>{$curr_level.code}</b></a> : {$curr_level.text}
				</li>
				{/if}
				{/foreach}
			</ul>
		</td>
		{/if}
	</tr>
</table>