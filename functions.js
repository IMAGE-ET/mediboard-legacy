// $Id$

function popup(width, height, url, name) {
  params = 'left=50, top=50, height=' + height + ', width=' + width
  params += ', resizable=yes, scrollbars=yes, menubar=yes';
  neo = window.open(url, name, params);
  neo.focus();
}

function popunder(width, height, url, name) {
  params = 'left=50, top=50, height=' + height + ', width=' + width
  params += ', resizable=yes, scrollbars=yes, menubar=yes';
  neo = window.open(url, name, params);
  neo.blur();
  window.focus();
}

function main() {
  prepareForms();
  initHTMLArea();
  initFCKEditor();
  pageMain();
}

function pageMain() {}

function initHTMLArea () {}
function initFCKEditor() {}

function prepareForms() {
  var msg = "Rapport: ";
  
  var giveFocus = true;

  // Retrieve labels
  var labels = new Array;
  var labelIt = 0;
  while (label = document.getElementsByTagName("label")[labelIt++]) {
    labels[label.attributes.id] = label;
    msg += "\n\tlabel for: " + label.getAttribute('for');
  }
  
  // For each form
  var formIt = 0;
  while (form = document.forms[formIt++]) {
    var formName = form.getAttribute("name");
    
    // For each element
    var elementIt = 0;
    while (element = form.elements[elementIt++]) {
      // Create id for each element if id is null
      if (!element.id) {
        element.id = formName + "_" + element.name;
        if (element.type == "radio") {
          element.id += "_" + element.value;
        }
      }

      // Focus on first text input
      if (giveFocus && element.type == "text" && !element.getAttribute("readonly")) {
        element.focus();
        giveFocus = false;
      }
    }
  }
  
//  alert (msg);
  
}

function setSelectionRange(textarea, selectionStart, selectionEnd) {
  if (textarea.setSelectionRange) {
    textarea.focus();
    textarea.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (textarea.createTextRange) {
    var range = textarea.createTextRange();
    textarea.collapse(true);
    textarea.moveEnd('character', selectionEnd);
    textarea.moveStart('character', selectionStart);
    textarea.select();
  }
}

function setCaretToPos (textarea, pos) {
  setSelectionRange(textarea, pos, pos);
}

function insertAt(textarea, str) {
  // Inserts given text at selection or cursor position

  if (textarea.setSelectionRange) {
    // Mozilla UserAgent Gecko-1.4
    var scrollTop = textarea.scrollTop;

    var selStart = textarea.selectionStart;
    var selEnd   = textarea.selectionEnd  ;
		
    var strBefore = textarea.value.substring(0, selStart);
    var strAfter  = textarea.value.substring(selEnd);

    textarea.value = strBefore + str + strAfter;
		
    var selNewEnd = selStart + str.length;
    if (selStart == selEnd) { 
      // No selection: move caret
      setCaretToPos(textarea, selNewEnd);
    } else  {
      // Selection: re-select insertion
      setSelectionRange(textarea, selStart, selNewEnd);
    }
		
    textarea.scrollTop = scrollTop;
  } else if (document.selection) {
    // UserAgent IE-6.0
    textarea.focus();
    var range = document.selection.createRange();
    if (range.parentElement() == textarea) {
      var hadSel = range.text.length > 0;
      range.text = str;
      if (hadSel)  {
        range.moveStart('character', -range.text.length);
        range.select();
      }
    }
  } else { 
    // UserAgent Gecko-1.0.1 (NN7.0)
    textarea.value += str;
  }
}

function getElementsByClassName(tagName, className, exactMatch) {
  var els = document.getElementsByTagName(tagName); 
  var elsTag = new Array;

  var elIt = 0;
  while (el = els[elIt++]) {
    // el.getAttribute("class") DOES NOT work in IE
    if (exactMatch ? el.className == className : el.className.indexOf(className) != -1) {
      elsTag.push(el);
    }
  }
  
  return elsTag;
}

function flipElementClass(elementId, firstClass, secondClass, cookieName) {
  var element = document.getElementById(elementId);
  
  if (!element) {
    throwError("The element '" + elementId + "' doesn't exist");
  }

  if (element.className != firstClass && element.className != secondClass) {
    throwError("The element class of '" + elementId + "' is neither '" + firstClass + "' nor '" + secondClass + "'.");
  }
  
  element.className = element.className == firstClass ? secondClass : firstClass;
  
  if (cookieName) {
    var cookie = new CJL_CookieUtil(cookieName);
    cookie.setSubValue(elementId, element.className);
  }
}

function initGroups(groupname) {
  var trs = getElementsByClassName("tr", groupname, false);
  var trsit = 0;
  while(tr = trs[trsit++]) {
    tr.style.display = "none";
  }
  var cookie = new CJL_CookieUtil(groupname);
  groupvalues = cookie.getAllSubValues();
  for (groupid in groupvalues) {
    groupclass = groupvalues[groupid];
    if(groupclass == "groupexpand")
      flipGroup(groupid, "");
  }
}

function flipGroup(id, groupname) {
  flipElementClass(groupname + id, "groupcollapse", "groupexpand", groupname);
  var trs = getElementsByClassName("tr", groupname + id, true);
  var trsit = 0;
  while(tr = trs[trsit++]) {
    tr.style.display = tr.style.display == "none" ? "" : "none";
  }
}
function confirmDeletion(form, typeName, objName, msg) {
  if (!typeName) typeName = "";
  if (!objName) objName = "";
  if (!msg) msg = "Voulez-vous r�ellement supprimer ";
  
  if (objName.length) objName = " '" + objName + "'";
  if (confirm(msg + typeName + " " + objName + " ?" )) {
  	form.del.value = 1; 
  	form.submit();
  }
}

function throwError(msg) {
 var func = throwError.caller.toString();
 var funcName = func.substring(9, func.indexOf("("));
 funcName.replace(/^\s+/,'').replace(/\s+$/,''); //trim
 throw "Error in " + funcName + "(): " + msg;
}