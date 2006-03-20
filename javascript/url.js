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


Url.prototype.requestUpdate = function(ioTarget) {
  this.addParam("suppressHeaders", "1");
  this.addParam("ajax", "1");
  $(ioTarget).innerHTML = "Loading...";
  
  var oOptions = {
    asynchronous : true,
  };
  
  var oUpdater = new Ajax.Updater(ioTarget, this.make(), oOptions);
}