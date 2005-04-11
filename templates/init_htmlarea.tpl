{literal}

<!-- Begin HTML Area Integration -->
<script type="text/javascript">
   _editor_url = "./lib/HTMLArea/";
   _editor_lang = "fr";
</script>
<script src="./lib/HTMLArea/htmlarea.js" type="text/javascript"></script>
<!-- End HTML Area Integration -->

<script language="javascript">

function initHTMLArea () {
  HTMLArea.init(); 
  HTMLArea.onload = initEditor;
}

var editor = null;

function initEditor() {
  editor = new HTMLArea("htmlarea");
 
  // Get the default configuration
  var cfg = editor.config;
  
  // Loads CSS style mentions
  cfg.pageStyle = "@import url(./style/mediboard/htmlarea.css);";


  // Add properties dropdown
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

{/literal}
  {if !$templateManager->valueMode}
{literal}
  // Add user lists dropdown
  var options = {};
  options["&mdash; Ajouter une liste de choix &mdash;"] = "";

{/literal}
  {foreach from=$templateManager->lists item=list}
    options["{$list.name}"] = "{$list.nameHTML|escape:"javascript"}";
  {/foreach}
{literal}

  var obj = {
    id            : "Lists",
    tooltip       : "Ajouter une liste de choix",
    options       : options,
    action        : function(editor) { actionHandler(editor, this); },
    refresh       : function(editor) { refreshHandler(editor, this); },
    context       : ""
  };

  cfg.registerDropdown(obj);
{/literal}
  {/if}
{literal}

  // Add Helpers dropdown
  var options = {};
  options["&mdash; Choisir une aide &mdash;"] = "";

{/literal}
  {foreach from=$templateManager->helpers key=helperName item=helperText}
    options["{$helperName}"] = "{$helperText|escape:"javascript"}";
  {/foreach}
{literal}

  var obj = {
    id            : "Helpers",
    tooltip       : "Utililiser une aide à la saisie",
    options       : options,
    action        : function(editor) { actionHandler(editor, this); },
    refresh       : function(editor) { refreshHandler(editor, this); },
    context       : ""
  };

  cfg.registerDropdown(obj);

  // add the new dropdowns to the toolbar
{/literal}
  {if !$templateManager->valueMode}
  cfg.toolbar.push(["Properties", "space", "Lists", "space", "Helpers"]);
  {else}
  cfg.toolbar.push(["Properties", "space", "Helpers"]);
  {/if}
{literal}
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
