// $Id$

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
      // Create id for each element
      element.id = form.name + "_" + element.name;
      if (element.type == "radio") {
        element.id += "_" + element.value;
      }

      // Focus on first non hidden input
      if (giveFocus && element.type == "text") {
        element.focus();
        giveFocus = false;
      }
    }
  }
  
//  alert (msg);
}