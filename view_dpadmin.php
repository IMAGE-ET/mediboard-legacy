<?php /* $Id$ */

/*
 * @package Mediboard
 * @subpackage system
 * @version $Revision$
 * @author Romain Ollivier
 */

// Old dP administration put in a tab

global $AppUI, $canRead, $canEdit, $m;

?>
<table width="50%" border="0" cellpadding="0" cellspacing="5" align="left">
<tr>
  <td width="42">
    <?php echo dPshowImage( dPfindImage( 'rdf2.png', $m ), 42, 42, '' ); ?>
  </td>
  <td>
    <h2><?php echo $AppUI->_( 'Language Support' );?></h2>
  </td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td align="left">
    <a href="?m=system&amp;a=translate"><?php echo $AppUI->_( 'Translation Management' );?></a>
  </td>
</tr>

<tr>
  <td>
    <?php echo dPshowImage( dPfindImage( 'myevo-weather.png', $m ), 42, 42, '' ); ?>
  </td>
  <td>
    <h2><?php echo $AppUI->_( 'Preferences' );?></h2>
  </td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td align="left">
    <a href="?m=system&amp;a=addeditpref"><?php echo $AppUI->_('Default User Preferences');?></a>
    <br /><a href="?m=system&amp;u=syskeys&amp;a=keys"><?php echo $AppUI->_( 'System Lookup Keys' );?></a>
    <br /><a href="?m=system&amp;u=syskeys"><?php echo $AppUI->_( 'System Lookup Values' );?></a>
  </td>
</tr>

<tr>
  <td>
    <?php echo dPshowImage( dPfindImage( 'power-management.png', $m ), 42, 42, '' ); ?>
  </td>
  <td>
    <h2><?php echo $AppUI->_( 'Modules' );?></h2>
  </td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td align="left">
    <a href="?m=system&amp;a=viewmods"><?php echo $AppUI->_('View Modules');?></a>
  </td>
</tr>

<tr>
  <td>
    <?php echo dPshowImage( dPfindImage( 'main-settings.png', $m ), 42, 42, '' ); ?>
  </td>
  <td>
    <h2><?php echo $AppUI->_( 'Administration' );?></h2>
  </td>
</tr>

<tr>
  <td>&nbsp;</td>
  <td align="left">
    <a href="?m=system&amp;u=roles"><?php echo $AppUI->_('User Roles');?></a>
  </td>
</tr>

</table>