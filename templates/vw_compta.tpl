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

  // Register new buttons
  var imgPrefix = "./style/mediboard/images/";
  cfg.registerButton("my-toc"   , "Ajouter une table des matières", imgPrefix + "plus.png", false, clickHandler);
  cfg.registerButton("my-date"  , "Ajouter l'heure courante"      , imgPrefix + "plus.png", false, clickHandler);
  cfg.registerButton("my-bold"  , "Inverser le gras et l'italique", imgPrefix + "plus.png", false, clickHandler);
  cfg.registerButton("my-hilite", "Mettre en surbrillance"        , imgPrefix + "plus.png", false, clickHandler);

  // Add dropdown
  var options = {};
  options["&mdash; Ajouter un champ &mdash;"] = "";

{/literal}
{if $valueMode}
  options["Date"] = "{$consult->_ref_plageconsult->date}";
  options["Chirurgien"] = "Dr. {$consult->_ref_plageconsult->_ref_chir->user_last_name} {$consult->_ref_plageconsult->_ref_chir->user_first_name}";
  options["Patient"] = "{$consult->_ref_patient->nom} {$consult->_ref_patient->prenom}";
  options["Motif"] = "{$consult->motif|nl2br|escape:"javascript"}";
  options["Remarques"] = "{$consult->rques|nl2br|escape:"javascript"}";
{else}
  options["Date"] = "date";
  options["Chirurgien"] = "chirurgien";
  options["Patient"] = "patient";
  options["Motif"] = "motif";
  options["Remarques"] = "remarques";
{/if}
{literal}

  var obj = {
    id            : "Consultation",
    tooltip       : "Ajouter des champs",
    options       : options,
    action        : function(editor) { actionHandler(editor, this); },
    refresh       : function(editor) { refreshHandler(editor, this); },
    context       : ""
  };

  cfg.registerDropdown(obj);

  // add the new button to the toolbar
  cfg.toolbar.push(["my-toc", "my-date", "my-bold", "my-hilite", "Consultation"]);

  editor.generate();
  return false;
}

function clickHandler(editor, buttonId) {
  switch (buttonId) {
    case "my-toc":
      editor.insertHTML("<h1>Table Of Contents</h1>");
      break;
    case "my-date":
      editor.insertHTML((new Date()).toString());
      break;
    case "my-bold":
      editor.execCommand("bold");
      editor.execCommand("italic");
      break;
    case "my-hilite":
      editor.surroundHTML("<span class='hilite'>", "</span>");
      break;
  }
}

function actionHandler(editor, dropdown) {
  var tbobj = editor._toolbarObjects[dropdown.id].element;
  if (tbobj.value.length) {
{/literal}
{if $valueMode}
    editor.insertHTML("<span class='value'>" + tbobj.value + "</span>&nbsp;");
{else}
    editor.insertHTML("<span class='field'>{ldelim}$" + tbobj.value + "{rdelim}</span>&nbsp;");
{/if}
{literal}

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
  
  