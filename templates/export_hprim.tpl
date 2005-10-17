<h1>Génération d'un fichier H'XML evenementsServeurActes</h1>

<table class="main">

<tr>

<td>
  <form name="form" method="post" onsubmit="return checkForm(this)">
  
  <table class="form">
  
  <tr>
    <th class="category" colspan="2">Identifiants Mediboard</th>
  </tr>
  
  <tr>
    <th><label for="mb_operation_id" title="Choisir un identifiant d'opération">Identifiant d'opération</label></th>
    <td><input type="text" title="notNull|ref" name="mb_operation_id" value="{$mb_operation_id}" size="5"/></td>
  </tr>
  
  <tr>
    <th class="category" colspan="2">Identifiants S@anté.com</th>
  </tr>
  
  <tr>
   <th><label for="sc_patient_id" title="Choisir un identifiant de patient correspondant à l'opération">Identifiant de patient</label></th>
    <td><input type="text" title="notNull|num|length|8" name="sc_patient_id" value="{$sc_patient_id}" size="8" maxlength="8" /></td>
  </tr>
  
  <tr>
    <th><label for="sc_venue_id" title="Choisir un identifiant pour la venue correspondant à l'opération">Identifiant de venue</label></th>
    <td><input type="text" title="notNull|num|length|8" name="sc_venue_id" value="{$sc_venue_id}" size="8" maxlength="8" /></td>
  </tr>

  <tr>
    <th class="category" colspan="2">Identifiants CMCA</th>
  </tr>

  <tr>
    <th><label for="cmca_uf_code" title="Choisir un code pour l'unité fonctionnelle">Code de l'unité fonctionnelle</label></th>
    <td><input type="text" title="notNull|str|maxLength|10" name="cmca_uf_code" value="{$cmca_uf_code}" size="10" maxlength="10" /></td>
  </tr>

  <tr>
    <th><label for="cmca_uf_libelle" title="Choisir un libellé pour l'unité fonctionnelle">Libellé de l'unité fonctionnelle</label></th>
    <td><input type="text" title="notNull|str|maxLength|35" name="cmca_uf_libelle" value="{$cmca_uf_libelle}" size="35" maxlength="35" /></td>
  </tr>

  <tr>
    <td class="button" colspan="2">
  	  <input type="submit" value="Générer le document"/>
    </td>
  </tr>

  </table>
  </form>
</td>

<td>
  <h3>XML: Schema Validation</h3>

  <ul>
    <li>Consulter <a href="{$schemapath}">le Schema de validation H'XML</a>.</li>
  </ul>

{if $documentpath}
  <h3>XML: Document généré</h3>

  <ul>
    <li>
      Consulter <a href="{$documentpath}">le Document H'XML</a>: 
        Le document <strong>{if $doc_valid}est valide!{else}n'est pas valide...{/if}</strong>
    </li>
    <li>
      Visualiser <a href="?m=dPplanningOp&amp;tab=vw_edit_planning&amp;operation_id={$mb_operation_id}">l'opération correspondante</a>
    </li>
  </ul>
  

{/if}
</td>

</tr>

</table>

