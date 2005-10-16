// $Id$

function makeURLParam(field, sParamName) {
  if (!field) return null;

  if (!sParamName) {
    sParamName = field.name;
  }

  return "&" + sParamName + "=" + field.value;
}


function popup(width, height, url, name) {
  params = 'left=50, top=50, height=' + height + ', width=' + width;
  params += ', resizable=yes, scrollbars=yes, menubar=yes';
  neo = window.open(url, name, params);
  neo.focus();
}

function popunder(width, height, url, name) {
  params = 'left=50, top=50, height=' + height + ', width=' + width;
  params += ', resizable=yes, scrollbars=yes, menubar=yes';
  neo = window.open(url, name, params);
  neo.blur();
  window.focus();
}

function view_log(classe, id) {
  url = "index.php?m=system&a=view_history&dialog=1";
  url += "&object_class=" + classe;
  url += "&object_id=" + id;
  url += "&user_id=";
  url += "&type=";
  popup(500, 500, url, 'history');
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

function getLabelFor(oElement) {
  aLabels = document.getElementsByTagName("label");
  iLabel = 0;
  while (oLabel = aLabels[iLabel++]) {
    if (oElement.id == oLabel.getAttribute("for")) {
      return oLabel;
    }  
  } 
  
  return null; 
}

function getBoundingForm(oElement) {
  if (!oElement) {
    return null;
  }
  
  if (oElement.nodeName.match(/^form$/i)) {
    return oElement;
  }
  
  return getBoundingForm(oElement.parentNode);
}

function prepareForms() {
  // Build label targets
  aLabels = document.getElementsByTagName("label");
  iLabel = 0;
  while (oLabel = aLabels[iLabel++]) {
  	oForm = getBoundingForm(oLabel);
  	if (sFor = oLabel.getAttribute("for")) {
      oLabel.setAttribute("for", oForm.name + "_" + sFor);
  	} 
  } 

  var bGiveFocus = true;

  // For each form
  var iForm = 0;
  while (oForm = document.forms[iForm++]) {
    var sFormName = oForm.getAttribute("name");
    
    // For each element
    var iElement = 0;
    while (oElement = oForm.elements[iElement++]) {

      // Create id for each element if id is null
      if (!oElement.id) {
        oElement.id = sFormName + "_" + oElement.name;
        if (oElement.type == "radio") {
          oElement.id += "_" + oElement.value;
        }
      }

      // Label emphasized for notNull elements
      if (sPropSpec = oElement.getAttribute("alt")) {
        aSpecFragments = sPropSpec.split("|");
        if (aSpecFragments.contains("notNull")) {
          if (oLabel = getLabelFor(oElement)) {
            oLabel.className = "notNull";
          }
        }
      }
      
      // Focus on first text input
      if (bGiveFocus && oElement.type == "text" && !oElement.getAttribute("readonly")) {
        oElement.focus();
        bGiveFocus = false;
      }
    }
  }
}

function checkElement(oElement, aSpecFragments) {
  aSpecFragments.removeByValue("confidential");
  bNotNull = aSpecFragments.removeByValue("notNull") > 0;
  if (oElement.value == "") {
    return bNotNull ? "Ne pas peut pas être vide" : null;
  }
  
  switch (aSpecFragments[0]) {
    case "ref":
      if (isNaN(oElement.value)) {
        return "N'est pas une référence (format non numérique)";
      }

      iElementValue = parseInt(oElement.value, 10);
      
      if (iElementValue == 0 && bNotNull) {
        return "ne peut pas être une référence nulle";
      }

      if (iElementValue < 0) {
        return "N'est pas une référence (entier négatif)";
      }
				
      break;
      
    case "str":
      if (sFragment1 = aSpecFragments[1]) {
        switch (sFragment1) {
          case "length":
            iLength = parseInt(aSpecFragments[2], 10);
           
            if (iLength < 1 || iLength > 255) {
              return printf("Spécification de longueur invalide (longueur = %s)", iLength);
            }

            if (oElement.value.length != iLength) {
              return printf("N'a pas la bonne longueur (longueur souhaité : %s)'", iLength);
            }
  
            break;
            
          case "minLength":
            iLength = parseInt(aSpecFragments[2], 10);
           
            if (iLength < 1 || iLength > 255) {
              return printf("Spécification de longueur minimale invalide (longueur = %s)", iLength);
            }

            if (oElement.value.length < iLength) {
              return printf("N'a pas la bonne longueur (longueur minimale souhaité : %s)'", iLength);
            }
  
            break;
            
          case "maxLength":
            iLength = parseInt(aSpecFragments[2], 10);
           
            if (iLength < 1 || iLength > 255) {
              return printf("Spécification de longueur maximale invalide (longueur = %s)", iLength);
            }

            if (oElement.value.length > iLength) {
              return printf("N'a pas la bonne longueur (longueur maximale souhaité : %s)'", iLength);
            }
  
            break;
  
          default:
            return "Spécification de chaîne de caractères invalide";
        }
      };
      
   	  break;

    case "num":
      if (isNaN(oElement.value)) {
        return "N'est pas une chaîne numérique";
      }

      if (sFragment1 = aSpecFragments[1]) {
        switch (sFragment1) {
          case "length":
            iLength = parseInt(aSpecFragments[2], 10);
           
            if (iLength < 1 || iLength > 255) {
              return printf("Spécification de longueur invalide (longueur = %s)", iLength);
            }

            if (oElement.value.length != iLength) {
              return printf("N'a pas la bonne longueur (longueur souhaité : %s)'", iLength);
            }
  
            break;
            
          case "minLength":
            iLength = parseInt(aSpecFragments[2], 10);
           
            if (iLength < 1 || iLength > 255) {
              return printf("Spécification de longueur minimale invalide (longueur = %s)", iLength);
            }

            if (oElement.value.length < iLength) {
              return printf("N'a pas la bonne longueur (longueur minimale souhaité : %s)'", iLength);
            }
  
            break;
            
          case "maxLength":
            iLength = parseInt(aSpecFragments[2], 10);
           
            if (iLength < 1 || iLength > 255) {
              return printf("Spécification de longueur maximale invalide (longueur = %s)", iLength);
            }

            if (oElement.value.length > iLength) {
              return printf("N'a pas la bonne longueur (longueur maximale souhaité : %s)'", iLength);
            }
  
            break;
  
          default:
            return "Spécification de chaîne de caractères invalide";
        }
      };
      
   	  break;
    
    case "enum":
      aSpecFragments.removeByIndex(0);
      if (!aSpecFragments.contains(oElement.value)) {
        return "N'est pas une valeur possible";
      }

      break;

    case "date":
      if(!oElement.value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/)) {
      	debugObject(oElement);
        return "N'as pas un format correct";
      }
      
      break;

    case "time":
      if(!oElement.value.match(/^(\d{1,2}):(\d{1,2}):(\d{1,2})$/)) {
        return "N'as pas un format correct";
      }
      
      break;

    case "dateTime":
      if(!oElement.value.match(/^(\d{4})-(\d{1,2})-(\d{1,2})[ \+](\d{1,2}):(\d{1,2}):(\d{1,2})$/)) {
        return "N'as pas un format correct";
      }
      
      break;
    
    case "currency":
      if (!oElement.value.match(/^(\d+)(\.\d{1,2})?$/)) {
        return "N'est pas une valeur monétaire (utilisez le . pour la virgule)";
      }
      
      break;
    
	case "text":
	  break;
	  
	case "html":
	  break;

    case "code":
      if (sFragment1 = aSpecFragments[1]) {
        switch (sFragment1) {
          case "ccam":
            if (!oElement.value.match(/^([a-z0-9]){0,7}$/i)) {
              return "Code CCAM incorrect, doit contenir 4 lettres et trois chiffres";
            }
          
          break;

          case "cim10":
            if (!oElement.value.match(/^([a-z0-9]){0,5}$/i)) {
              return "Code CCAM incorrect, doit contenir 5 lettres maximum";
            }
            
            break;

          case "adeli":
            if (!oElement.value.match("/^([0-9]){9}$/i")) {
              return "Code Adeli incorrect, doit contenir exactement 9 chiffres";
            }
            
            break;

          case "insee":
            aMatches = oElement.value.match(/^([1-2][0-9]{2}[0-9]{2}[0-9]{2}[0-9]{3}[0-9]{3})([0-9]{2})$/i);
            if (!aMatches) {
              return "Matricule incorrect, doit contenir exactement 15 chiffres (commençant par 1 ou 2)";
            }

            nCode = parseInt(aMatches[1], 10);
            nCle = parseInt(aMatches[2], 10);
            if (97 - (nCode % 97) != nCle) {
              return "Matricule incorrect, la clé n'est pas valide";
            }
          
            break;

          default:
            return "Spécification de code invalide";
        }
      }

      break;
    default:
      return "Spécification invalide";
  }
  
  return null;
}

function checkForm(oForm) {
  oElementFocus = null;
  aMsgFailed = new Array;
  iElement = 0;
  while (oElement = oForm.elements[iElement++]) {
    if (sPropSpec = oElement.getAttribute("alt")) {
      aSpecFragments = sPropSpec.split("|");
      oLabel = getLabelFor(oElement);
      if (sMsg = checkElement(oElement, aSpecFragments)) {
        sLabelTitle = oLabel ? oLabel.getAttribute("title") : null;
        sMsgFailed = sLabelTitle ? sLabelTitle : printf("%s (val:'%s', spec:'%s')", oElement.name, oElement.value, sPropSpec);
        sMsgFailed += "\n => " + sMsg;
        aMsgFailed.push("- " + sMsgFailed);
        
        if (!oElementFocus) {
          oElementFocus = oElement;
        }
      }

      if (oLabel) {
        oLabel.style.color = sMsg ? "#f00" : "#000";
      }
    }
  }

  if (aMsgFailed.length) {
  	sMsg = "Merci de remplir/corriger les champs suivants : \n";
  	sMsg += aMsgFailed.join("\n")
    alert(sMsg);
    if (oElementFocus) {
    oElementFocus.focus();
      if (sDoubleClickAction = oElementFocus.getAttribute("ondoubleclick")) {
        eval(sDoubleClickAction);
      }
    }
    
    return false;
  }
  
  return true;
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
    return;
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

function initElementClass(elementId, cookieName) {
  var element = document.getElementById(elementId);
  var cookie = new CJL_CookieUtil(cookieName);
  value = cookie.getSubValue(elementId);
  if(value)
    element.className = value;
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

function regFieldCalendar(sFormName, sFieldName, bTime) {
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
      showsTime   : bTime
    } 
  );
}

function regRedirectPopupCal(sInitDate, sRedirectBase, sContainerId, bTime) {
  if (sContainerId == null) sContainerId = "changeDate";
  if (bTime == null) bTime = false;
	
  Calendar.setup( {
      button      : sContainerId,
      date        : makeDateFromDATE(sInitDate),
      showsTime   : bTime,
      onUpdate    : function(calendar) { 
        if (calendar.dateClicked) {
          sDate = bTime ? makeDATETIMEFromDate(calendar.date) : makeDATEFromDate(calendar.date)
          window.location = sRedirectBase + sDate;
        }
      }
    } 
  );
}

function regRedirectFlatCal(sInitDate, sRedirectBase, sContainerId, bTime) {
  if (sContainerId == null) sContainerId = "calendar-container";
  if (bTime == null) bTime = false;

  dInit = bTime ? makeDateFromDATETIME(sInitDate) : makeDateFromDATE(sInitDate);
  
  Calendar.setup( {
      date         : dInit,
      showsTime    : bTime,
      flat         : sContainerId,
      flatCallback : function(calendar) { 
        if (calendar.dateClicked) {
          sDate = bTime ? makeDATETIMEFromDate(calendar.date) : makeDATEFromDate(calendar.date)
          window.location = sRedirectBase + sDate;
        }
      }
    } 
  );
}


var idInterval = 0;

function doResize(idElement, iTargetWidth) {
  oElement = document.getElementById(idElement);
  iWidth = parseInt(oElement.style.width);
  iLackingWidth = iTargetWidth - iWidth;
  iLackingWidth *= .85;
  iLackingWidth = parseInt(iLackingWidth);
  iWidth = iTargetWidth - iLackingWidth;
  oElement.style.width = iWidth;

  if (iLackingWidth < 1 && iLackingWidth > -1) {
  	window.clearInterval(idInterval);
  }
}

function smoothToggle(idElement, iExpandedWidth) {
  oElement = document.getElementById(idElement);
  iWidth = parseInt(oElement.style.width);
  iTargetWidth = iWidth == 0 ? iExpandedWidth : 0;
  
  // Close previous interval to prevent collisions
  window.clearInterval(idInterval);
  sFunc = "doResize('" + idElement + "', " +iTargetWidth + ")";
  idInterval = window.setInterval(sFunc, 20);
}
