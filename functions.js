// $Id$

function makeURLParam(field, sParamName) {
  if (!field) return null;

  if (!sParamName) {
    sParamName = field.name;
  }

  return "&" + sParamName + "=" + field.value;
}

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
  if (!msg) msg = "Voulez-vous réellement supprimer ";
  
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

function makeDateFromDATE(sDate) {
  // sDate must be: YYYY-MM-DD
  var aParts = sDate.split("-");
  if (aParts.length != 3) throwError("'" + sDate + "' :Bad DATE format");

  var year  = parseInt(aParts[0], 10);
  var month = parseInt(aParts[1], 10);
  var day   = parseInt(aParts[2], 10);
  
  return new Date(year, month - 1, day); // Js months are 0-11!!
}

function makeDateFromDATETIME(sDateTime) {
  // sDateTime must be: YYYY-MM-DD HH:MM:SS
  var aHalves = sDateTime.split(" ");
  if (aHalves.length != 2) throwError("'" + sDateTime + "' :Bad DATETIME format");

  var sDate = aHalves[0];
  var date = makeDateFromDATE(sDate);

  var sTime = aHalves[1];
  var aParts = sTime.split(":");
  if (aParts.length != 3) throwError("'" + sTime + "' :Bad TIME format");

  date.setHours  (parseInt(aParts[0], 10));
  date.setMinutes(parseInt(aParts[1], 10));
  date.setSeconds(parseInt(aParts[2], 10));
  
  return date;
}

function makeDateFromLocaleDate(sDate) {
  // sDate must be: dd/mm/yyyy
  var aParts = sDate.split("/");
  if (aParts.length != 3) throwError("Bad Display date format");

  var year  = parseInt(aParts[2], 10);
  var month = parseInt(aParts[1], 10);
  var day   = parseInt(aParts[0], 10);
  
  return new Date(year, month - 1, day); // Js months are 0-11!!
}

function makeDATEFromDate(date) {
  var y = date.getFullYear();
  var m = date.getMonth()+1; // Js months are 0-11!!
  var d = date.getDate();
  
  return printf("%04d-%02d-%02d", y, m, d);
}

function makeLocaleDateFromDate(date) {
  var y = date.getFullYear();
  var m = date.getMonth()+1; // Js months are 0-11!!
  var d = date.getDate();
  
  return printf("%02d/%02d/%04d", d, m, y);
}

function makeDATETIMEFromDate(date) {
  var h = date.getHours();
  var m = date.getMinutes();
  var s = date.getSeconds();
  
  return makeDATEFromDate(date) + printf("+%02d:%02d:%02d", h, m, s);
}

function regFlatCalendar(sContainerId, sInitDATE, sRedirectBase, bTime) {
  if (bTime == null) bTime = false;

  Calendar.setup( {
      date         : bTime ? 
      	makeDateFromDATETIME(sInitDATE) : 
      	makeDateFromDATE(sInitDATE) ,
      showsTime   : bTime,
      flat         : sContainerId,
      flatCallback : function(calendar) { 
        if (calendar.dateClicked && sRedirectBase) {
          var sdate = bTime ? 
            makeDATETIMEFromDate(calendar.date) : 
            makeDATEFromDate(calendar.date)
          window.location = sRedirectBase + sdate;
        }
      }
    } 
  );
}

function regPopupCalendar(sFormName, sFieldName, sRedirectBase, bTime) {
  if (bTime == null) bTime = false;
  
  var sInputId = sFormName + "_" + sFieldName;
  
  if (!document.getElementById(sInputId)) {
    return;
  }

  Calendar.setup( {
      inputField  : sInputId,
      displayArea : sInputId + "_da",
      ifFormat    : "%Y-%m-%d" + (bTime ? " %H:%M:%S" : ""),
      daFormat    : "%d/%m/%Y" + (bTime ? " %H:%M:%S" : ""),
      button      : sInputId + "_trigger",
      showsTime   : bTime,
      onUpdate    : function(calendar) { 
        if (calendar.dateClicked && sRedirectBase) {
          window.location = sRedirectBase + bTime ? 
            makeDATETIMEFromDate(calendar.date) : 
            makeDATEFromDate(calendar.date);
        }
      }
    } 
  );
}
