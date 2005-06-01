/* $Id$
 *
 * @package Mediboard
 * @subpackage dPcompteRendu
 * @version $Revision$
 * @author Thomas Despoix
 *
 * Mediboard additional custom combo plugin for FCKeditor
 */
 
// On Gecko we must do this trick so the user select all the SPAN when clicking on it.
function clickListener(e) {
  if ( e.target.tagName == 'SPAN') {
    for (var i = 0; i < aMbCombos.length; i++) {
      if (e.target.className == aMbCombos[i].spanClass) {
	    FCKSelection.SelectNode( e.target );
      }
    }
  }  
}

for (var i = 0; i < aMbCombos.length; i++) {

  var oMbCombo = aMbCombos[i];
  
  if (!FCKBrowserInfo.IsIE) {
  	FCK.EditorDocument.addEventListener('click', clickListener, true ) ;
  }
  
  // Defines command class
  var FCKMbComboCommand = function() {
  	this.Name = oMbCombo.commandName;
  	this.spanClass = oMbCombo.spanClass;
  }
  
  FCKMbComboCommand.prototype.Execute = function(itemId, item) {
    var oSpan = FCK.CreateElement("span") ;
	oSpan.className = this.spanClass;
	oSpan.innerHTML = itemId;
  }

  FCKMbComboCommand.prototype.GetState = function() {
	return FCK_TRISTATE_OFF ;
  }

  // Registers command object
  var oCommand = new FCKMbComboCommand();
  FCKCommands.RegisterCommand(oCommand.Name, oCommand);

  // Defines toolbar item class
  var FCKToolbarMbCombo = function() {
    this.Command =  FCKCommands.GetCommand(oMbCombo.commandName);
    this.options = oMbCombo.options;
    this.commandLabel = oMbCombo.commandLabel;
  }

  // Inherit from FCKToolbarSpecialCombo.
  FCKToolbarMbCombo.prototype = new FCKToolbarSpecialCombo ;

  FCKToolbarMbCombo.prototype.GetLabel = function() {
    return this.commandLabel;
  }

  FCKToolbarMbCombo.prototype.CreateItems = function( targetSpecialCombo ) {
    for (var i = 0; i < this.options.length; i++) {
      this._Combo.AddItem( this.options[i].item, this.options[i].view);
    }
  }

  // Registers toolbar item object
  FCKToolbarItems.RegisterItem( oMbCombo.commandName, new FCKToolbarMbCombo) ;
  
}
