{literal}
<script language="JavaScript" type="text/javascript">
var editor = null;

function initEditor() {
  editor = new HTMLArea("test");
//  editor.generate();
//  return false;
 
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




<form name="editFrm" action="./index.php?m={$m}&amp;a=vw_compta&amp;dialog={$dialog}" method="post">

<input type="hidden" name="dosql" value="do_consultation_aed" />
<input type="hidden" name="dialog" value="{$dialog}" />
<input type="hidden" name="del" value="0" />

<input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
<input type="hidden" name="patient_id" value="{$consult->patient_id}" />

<input type="hidden" name="secteur1" value="{$consult->secteur1}" />
<input type="hidden" name="secteur2" value="{$consult->secteur2}" />
<input type="hidden" name="motif" value="{$consult->motif}" />
<input type="hidden" name="rques" value="{$consult->rques}" />

<input type="hidden" name="_hour" value="{$consult->_hour}" />
<input type="hidden" name="_min" value="{$consult->_min}" />
<input type="hidden" name="_duree" value="{$consult->_duree}" />


<textarea style="width: 99%" id="test" name="compte_rendu" rows="{if $dialog}40{else}20{/if}">
{$consult->compte_rendu}
</textarea>
  
<table class="form">
  <tr>
    <td class="button">
      <input type="submit" value="Modifier" />
      <input type="reset" value="Réinitialiser" />
    </td>
  </tr>  
</table>

</form>
  
  