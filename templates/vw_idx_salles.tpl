<table class="main">
  <tr>
    <td>
	  <table class="form">
	    <tr>
		  <th class="category" colspan=3>
		    Modifier une salle
		  </th>
		</tr>
		{foreach from=$list item=curr_salle}
		<tr>
          <td>
		    <form name="editFrm" action="./index.php?m=dPbloc" method="post">
            <input type="hidden" name="dosql" value="do_salle_aed" />
			<input type="hidden" name="del" value="0" />
			<input type="hidden" name="id" value="{$curr_salle.id}" />
			<input type="text" name="nom" value="{$curr_salle.nom}" />
		  </td>
		  <td class="button">
		    <input type="submit" value="modifier" />
		    </form>
		  </td>
		  <td class="button">
		    <form name="editFrm" action="./index.php?m=dPbloc" method="post">
            <input type="hidden" name="dosql" value="do_salle_aed" />
			<input type="hidden" name="del" value="1" />
			<input type="hidden" name="id" value="{$curr_salle.id}" />
			<input type="submit" value="supprimer" />
		    </form>
		  </td>
		</tr>
	    {/foreach}
	  </table>
	</td>
	<td>
	  <form name="editFrm" action="./index.php?m=dPbloc" method="post">
      <input type="hidden" name="dosql" value="do_salle_aed" />
	  <input type="hidden" name="del" value="0" />
	  <table class="form">
	    <tr>
		  <th class="category" colspan=2>
		    Ajouter une salle
		  </th>
		</tr>
		<tr>
		  <th>
		    Nom:
		  </th>
		  <td>
		    <input type="text" name="nom" value="" />
		  </td>
		</tr>
		<tr>
		  <td class="button">
		    <input type="reset" value="Réinitialiser" />
		  </td>
		  <td class="button">
		    <input type="submit" value="Valider" />
		  </td>
		</tr>
	  </table>
	  </form>
	</td>
  </tr>
</table>