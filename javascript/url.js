// Class URL, for easy url parameters writing and poping
function Url() {
  this.aParams = new Array;
}

Url.prototype.setModuleAction = function(sModule, sAction) {
  this.addParam("m", sModule);
  this.addParam("a", sAction);
}

Url.prototype.setModuleTab = function(sModule, sTab) {
  this.addParam("m", sModule);
  this.addParam("tab", sTab);
}

Url.prototype.addParam = function(sName, sValue) {
  this.aParams.push(sName + "=" + sValue);
}

Url.prototype.addElement = function(oElement, sParamName) {
  if (!oElement) {
  	return;
  }

  if (!sParamName) {
    sParamName = oElement.name;
  }

  this.addParam(sParamName, oElement.value);
}

Url.prototype.make = function() {
  return "index.php?" + this.aParams.join("&");
}

Url.prototype.redirect = function() {
  window.location.href = this.make();
}

Url.prototype.pop = function(iWidth, iHeight, sWindowName) {
  this.addParam("dialog", "1");
  params = 'left=50, top=50, height=' + iHeight + ', width=' + iWidth;
  params += ', resizable=yes, scrollbars=yes, menubar=yes';
  return window.open(this.make(), name, params);  
}

Url.prototype.popunder = function(iWidth, iHeight, sWindowName) {
  this.pop(iWidth, iHeight, sWindowName).blur();
  window.focus();
}

Url.prototype.popup = function(iWidth, iHeight, sWindowName) {
  this.pop(iWidth, iHeight, sWindowName).focus();
}

Url.prototype.createRequest = function() {
  var request = null;
  
  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    request = new XMLHttpRequest();
    if (request.overrideMimeType) {
      request.overrideMimeType('text/xml');
    }
  } else if (window.ActiveXObject) { // IE
    try {
      request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
    }
  }
  
  return request;
}

Url.prototype.request = function(fOnReadyStateChange) {
  this.addParam("suppressHeaders", "1");

  var request = this.createRequest();

  if (!request) {
    alert('Giving up :( Cannot create an XMLHTTP instance');
    return false;
  }
  
  request.onreadystatechange = fOnReadyStateChange;
  request.open('GET', this.make(), true);
  request.send(null);
  
  return request;
}

var oRequest = null;

Url.prototype.requestUpdate = function(idTarget) {
  var oTarget = document.getElementById(idTarget);
  oTarget.innerHTML = "Loading...";
  
  oRequest = this.request(function () {updateTarget(oTarget);});
}

Url.prototype.requestUpdate2 = function(idTarget) {
  this.addParam("suppressHeaders", "1");
  $(idTarget).innerHTML = "Loading...";
  new Ajax.Updater(idTarget, this.make(), {asynchronous:true});
}

function updateTarget(oTarget) {
  // oRequest is not affected instantly
  if (!oRequest) {
    return;
  }
  
  if (oRequest.readyState == 4) {
    if (oRequest.status == 200) {
      oTarget.innerHTML = oRequest.responseText;
    } else {
      oTarget.innerHTML = "There was a problem with the request.";
    }
  }
}

function view_log(classe, id) {
  url = new Url();
  url.setModuleAction("system", "view_history");
  url.addParam("object_class", classe);
  url.addParam("object_id", id);
  url.addParam("user_id", "");
  url.addParam("type", "");
  url.popup(600, 500, "history");
}