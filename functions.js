// $Id$

function popup(width, height, url, name) {
  params = 'left=50, top=50, height=' + height + ', width=' + width + ', resizable=yes, scrollbars=yes';
  neo = window.open(url, name, params);
  neo.window.focus();
}

function prepareForms() {
  var msg = "Rapport: ";
  
  var giveFocus = true;

  // Retrieve labels
  var labels = new Array;
  var labelIt = 0;
  while (label = document.getElementsByTagName("label")[labelIt++]) {
    labels[label.attributes.id] = label;
    msg += "\nlabel for: " + label.getAttribute('for');
  }
  
  
  // For each form
  var formIt = 0;
  while (form = document.forms[formIt++]) {
    var formName = "Formulaire: " + form.name;
    
    // For each element
    var elementIt = 0;
    while (element = form.elements[elementIt++]) {
      // Create id for each element if id is null
      if (!element.id) {
        element.id = form.name + "_" + element.name;
        if (element.type == "radio") {
          element.id += "_" + element.value;
        }
      }

      // Focus on first non hidden input
      if (giveFocus && element.type == "text") {
        element.focus();
        giveFocus = false;
      }
    }
  }
  
//  alert (msg);
  
  // HTMLArea initialisation
//  HTMLArea.loadPlugin("TableOperations");
//  HTMLArea.loadPlugin("SpellChecker");
//  HTMLArea.loadPlugin("FullPage");
//  HTMLArea.loadPlugin("CSS");
//  HTMLArea.loadPlugin("ContextMenu");
//  HTMLArea.loadPlugin("HtmlTidy");
//  HTMLArea.loadPlugin("ListType");
//  HTMLArea.loadPlugin("CharacterMap");
//  HTMLArea.loadPlugin("DynamicCSS");
  
  HTMLArea.init(); 
  HTMLArea.onload = initEditor;
}

function initEditor() {
}
