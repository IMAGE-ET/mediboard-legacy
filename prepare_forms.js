// $Id$

function prepareForms() {
  var bGiveFocus = true;

  var formIt = 0;
  while (form = document.forms[formIt++]) {
    var msg = "Formulaire: " + form.name;

    var elementIt = 0;
    while (element = form.elements[elementIt++]) {
      // create id for each element
      element.id = form.name + "_" + element.name;

      // Focus on first non hidden input
      if (bGiveFocus && element.type == "text") {
        element.focus();
        bGiveFocus = false;
      }
    }
  }
}