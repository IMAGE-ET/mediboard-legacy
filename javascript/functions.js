// $Id$

function main() {
  prepareForms();
  initHTMLArea();
  initFCKEditor();
  pageMain();
}

function pageMain() {}

function initHTMLArea () {}
function initFCKEditor() {}

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
  var oElement = document.getElementById(elementId);
  var cookie = new CJL_CookieUtil(cookieName);
  value = cookie.getSubValue(elementId);
  if(value)
    oElement.className = value;
}

function flipEffectElement(id, shownEffect, hiddenEffect) {
  var oElement = document.getElementById(id);
  if(oElement.className == "effectShown") {
    eval('new Effect.' + shownEffect + '(oElement)');
  } else {
    eval('new Effect.' + hiddenEffect + '(oElement)');
  }
  flipElementClass(id, "effectShown", "effectHidden", id);
}

function initEffectClass(elementId, cookieName) {
  var oElement = document.getElementById(elementId);
  initElementClass(elementId, cookieName);
  if(oElement.className == "effectHidden")
    oElement.style.display = "none";
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
    tr.style.display = tr.style.display == "table-row" ? "none" : "table-row";
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

function throwError(sMsg) {
 var sFunction = throwError.caller.toString();
 var sFuncName = sFunction.substring(9, sFunction.indexOf("("));
 sFuncName.replace(/^\s+/,'').replace(/\s+$/,''); //trim
 debug("Error in " + sFuncName + "(): " + sMsg);
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
