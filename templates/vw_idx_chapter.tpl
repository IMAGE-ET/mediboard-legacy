<table width="100%" bgcolor="#cccccc">
	<th align="center">
		<h1>Liste des chapitres de la CIM10</h1>
	</th>
	<tr>
		<td valign="top" align="center">
			<table width="750" bgcolor="#dddddd">
				{foreach from=$chapter item=curr_chapter}
				<tr>
					<td valign="top" align="right">
						<b>{$curr_chapter.rom}</b>
					</td>
					<td valign="top" align="left">
						<a href="index.php?m={$m}&amp;tab=vw_full_code&amp;code={$curr_chapter.code}"><b>{$curr_chapter.text}</b></a>
					</td>
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
</table>