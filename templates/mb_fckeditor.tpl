// Mediboard Combo configuration
var aMbCombos = new Array();

// Add properties Combo
var oMbCombo = new Object();
oMbCombo.commandName = "MbField";
oMbCombo.spanClass = "field";
oMbCombo.commandLabel = "Champs";

var aOptions = new Array();
oMbCombo.options = aOptions;
aMbCombos.push(oMbCombo);

{foreach from=$templateManager->properties item=property}
  aOptions.push( {ldelim} 
    view: "{$property.field|escape:"htmlall"|escape:"javascript"}" ,
    item: 
      {if $templateManager->valueMode} 
        "{$property.value|escape:"htmlall"|escape:"javascript"}" 
      {else} 
        "[{$property.field|escape:"htmlall"|escape:"javascript"}]" 
      {/if}
    {rdelim});
{/foreach}

{if !$templateManager->valueMode}
// Add name lists Combo
var oMbCombo = new Object();
oMbCombo.commandName = "MbNames";
oMbCombo.spanClass = "name";
oMbCombo.commandLabel = "Liste de choix";

var aOptions = new Array();
oMbCombo.options = aOptions;
aMbCombos.push(oMbCombo);

{foreach from=$templateManager->lists item=list}
  aOptions.push( {ldelim} 
    view: "{$list.name|escape:"htmlall"|escape:"javascript"}" ,
    item: "[List - {$list.name|escape:"htmlall"|escape:"javascript"}]"
    {rdelim});
{/foreach}
{/if}

// Add helpers Combo
var oMbCombo = new Object();
oMbCombo.commandName = "MbHelpers";
oMbCombo.spanClass = "helper";
oMbCombo.commandLabel = "Aides &agrave; la saisie";

var aOptions = new Array();
oMbCombo.options = aOptions;
aMbCombos.push(oMbCombo);

{foreach from=$templateManager->helpers key=helperName item=helperText}
  aOptions.push( {ldelim} 
    view: "{$helperName|escape:"htmlall"|escape:"javascript"}" ,
    item: "{$helperText|escape:"htmlall"|escape:"javascript"}]"
    {rdelim});
{/foreach}

// Tools configuration
aTableToolbar = ['Table','-','TableInsertRow','TableDeleteRows','TableInsertColumn','TableDeleteColumns','TableInsertCell','TableDeleteCells','TableMergeCells','TableSplitCell'];

aMbToolbar = new Array();
for (var i = 0; i < aMbCombos.length; i++) {ldelim}
  aMbToolbar.push(aMbCombos[i].commandName);
{rdelim}


FCKConfig.ToolbarSets['Default'].push(aTableToolbar);
FCKConfig.ToolbarSets['Default'].push(aMbToolbar);

// FCK editor general configuration
sMbPath = "../../../";
sMbComboPath = sMbPath + "modules/dpCompteRendu/fcke_plugins/" ;

FCKConfig.Plugins.Add( 'mbcombo', 'en,fr', sMbComboPath ) ;
FCKConfig.EditorAreaCSS = sMbPath + "style/mediboard/htmlarea.css";
FCKConfig.DefaultLanguage = "fr" ;
FCKConfig.AutoDetectLanguage = false ;
FCKConfig.SkinPath = "./skins/default/";

// If you want to use plugins found on other directories, just use the third parameter.
var sOtherPluginPath = FCKConfig.BasePath.substr(0, FCKConfig.BasePath.length - 7) + 'editor/plugins/' ;
FCKConfig.Plugins.Add( 'tablecommands', null, sOtherPluginPath ) ;
