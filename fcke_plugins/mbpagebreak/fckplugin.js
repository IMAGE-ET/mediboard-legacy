/* $Id$
 *
 * @package Mediboard
 * @subpackage dPcompteRendu
 * @version $Revision$
 * @author Romain OLLIVIER
 *
 * Mediboard additional page-break button plugin for FCKeditor
 */
 
// Define the commande name
var sMbPageBreakName = "mbPageBreak";

// Defines command class
var FCKMbPageBreakCommand = function() {
  this.Name = "mbPageBreak";
}
  
FCKMbPageBreakCommand.prototype.Execute = function() {
  FCK.InsertHtml("<hr class='pageBreak' />");
}

FCKMbPageBreakCommand.prototype.GetState = function() {
  return FCK_TRISTATE_OFF ;
}

// Registers command object
var oCommand = new FCKMbPageBreakCommand();
FCKCommands.RegisterCommand("mbPageBreak", oCommand);

// Defines toolbar item class
var FCKToolbarMbPageBreak = function() {
  this.Command = FCKCommands.GetCommand("mbPageBreak");
  this.commandName = "mbPageBreak";
}

// Inherit from FCKToolbarButton.
FCKToolbarMbPageBreak.prototype = new FCKToolbarButton("mbPageBreak", "mbPageBreak") ;

FCKToolbarMbPageBreak.prototype.GetLabel = function() {
  return "mbPageBreak" ;
}

var oMbPageBreakItem = new FCKToolbarMbPageBreak ;
// Impossible de pointer ou on veut ?!?
// oMbPageBreakItem.iconPath = 'images/pageBreak.gif';

// Registers toolbar item object
FCKToolbarItems.RegisterItem("mbPageBreak", oMbPageBreakItem) ;
//oMbPageBreakItem.iconPath = '/dotproject/modules/dPcompteRendu/fcke_plugins/mbpagebreak/images/pageBreak.gif' ;
//oMbPageBreakItem.iconPath = FCKPlugins.Items['mbPageBreak'].Path + 'images/pageBreak.gif';