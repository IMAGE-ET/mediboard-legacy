<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

require_once("header.php");

if (!@include_once("$mbpath/includes/config.php")) { ?>
  showHeader();

<p>
  Le fichier de configuration n'a pas été validé, merci de revenir à l'étape 
  précédante.
</p>

<?php
  showFooter();
  die();
}
?>