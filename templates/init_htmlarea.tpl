{literal}
<script language="javascript">

// HTMLArea.loadPlugin("TableOperations");
// HTMLArea.loadPlugin("SpellChecker");
// HTMLArea.loadPlugin("FullPage");
// HTMLArea.loadPlugin("CSS");
// HTMLArea.loadPlugin("ContextMenu");
// HTMLArea.loadPlugin("HtmlTidy");
// HTMLArea.loadPlugin("ListType");
// HTMLArea.loadPlugin("CharacterMap");
// HTMLArea.loadPlugin("DynamicCSS");

var editor = null;

function initEditor() {
  editor = new HTMLArea("htmlarea");
 
  // Get the default configuration
  var cfg = editor.config;
  
  // Loads CSS style mentions
  cfg.pageStyle = "@import url(./style/mediboard/htmlarea.css);";


  // Add dropdown
  var options = {};
  options["&mdash; Ajouter un champ &mdash;"] = "";

{/literal}
  {foreach from=$templateManager->properties item=property}
    options["{$property.field}"] = {if $templateManager->valueMode} "{$property.valueHTML|escape:"javascript"}" {else} "{$property.fieldHTML|escape:"javascript"}" {/if};
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
