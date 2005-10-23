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

Url.prototype.request = function(fOnReadyStateChange) {
  this.addParam("dialog", "1");

  http_request = null;

  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    http_request = new XMLHttpRequest();
    if (http_request.overrideMimeType) {
      http_request.overrideMimeType('text/xml');
    }
  } else if (window.ActiveXObject) { // IE
    try {
      http_request = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        http_request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
    }
  }

  if (!http_request) {
    alert('Giving up :( Cannot create an XMLHTTP instance');
    return false;
  }
  
  http_request.onreadystatechange = fOnReadyStateChange;
  http_request.open('GET', this.make(), true);
  http_request.send(null);
  
  return http_request;
}