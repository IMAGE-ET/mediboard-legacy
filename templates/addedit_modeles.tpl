{literal}
<script language="javascript">
function nouveau() {
  var url = "index.php?m=dPcompteRendu&tab=addedit_modeles&new=1";
  window.location.href = url;
}

function supprimer() {
  var form = document.editFrm;
  form.del.value = 1;
  form.submit();
}

var editor = null;

function initEditor() {
  editor = new HTMLArea("source");
 
  // Get the default configuration
  var cfg = editor.config;
  
  // Loads CSS style mentions
  cfg.pageStyle = "@import url(./style/mediboard/htmlarea.css);";


  // Add dropdown
  var options = {};
  options["&mdash; Ajouter un champ &mdash;"] = "";

{/literal}
  {foreach from=$templateManager->properties item=property}
    options["{$property.field}"] = {if $templateManager->valueMode} "{$property.valueHTML|escape:"javascript"}" {else} "{$property.fieldHTML}" {/if};
  {/foreach}
{literal}

  var obj = {
    id            : "Properties",
    tooltip       : "Ajouter des champs",
    options       : options,
    action        : function(editor) { actionHandler(editor, this); },
    refresh       : function(editor) { refreshHandler(editor, this); },
    context       : ""
  };

  cfg.registerDropdown(obj);

  // add the new button to the toolbar
  cfg.toolbar.push(["Properties"]);

  editor.generate();
  return false;
}

function actionHandler(editor, dropdown) {
  var tbobj = editor._toolbarObjects[dropdown.id].element;
  if (tbobj.value.length) {
    editor.insertHTML(tbobj.value + "&nbsp;");
  }
}

function refreshHandler(editor, dropdown) {
  var tbobj = editor._toolbarObjects[dropdown.id].element;
  tbobj.selectedIndex = 0;
}
</script>
{/literal}

<form name="editFrm" action="?m={$m}" method="POST">
<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_modele_aed" />
<input type="hidden" name="compte_rendu_id" value="{$compte_rendu->compte_rendu_id}" />
<table class="form">
  <tr>
    <th class="category">Informations</th>
    <th>Nom: </th>
    <td><input type="text" name="nom" value="{$compte_rendu->nom}"></td>
    <th>Chirurgien: </th>
    <td>
      <select name="chir_id">
        {foreach from=$listPrat item=curr_prat}
          <option value="{$curr_prat->user_id}" {if $curr_prat->user_id == $prat_id} selected="selected" {/if}>
            {$curr_prat->user_last_name} {$curr_prat->user_first_name}
          </option>
        {/foreach}
      </select>
    </td>
    <th>Type de compte-rendu: </th>
    <td>
      <select name="type">
        {foreach from=$listType item=curr_type}
          <option value="{$curr_type}" {if $curr_type == $compte_rendu->type} selected="selected" {/if}>
            {$curr_type}
          </option>
        {/foreach}
      </select>
    </td>
    {if $compte_rendu->compte_rendu_id}
    <td class="button">
    <input type="submit" value="modifier" />
    <input type="button" value="supprimer" onclick="supprimer()" />
    <input type="button" value="nouveau" onclick="nouveau()" />
    </td>
    {else}
    <td class="button"><input type="submit" value="créer" /></td>
    {/if}
  </tr>
  {if $compte_rendu->compte_rendu_id}
  <tr>
    <th class="category">Modèle</td>
    <td colspan="7" class="compte_rendu">
      <textarea style="width: 99%" id="source" name="source" rows="40">
        {$compte_rendu->source}
      </textarea>
    </td>
  </tr>
  {/if}
</table>
</form>