<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

global $AppUI, $canRead, $canEdit, $m;

require_once( $AppUI->getModuleClass('dPcabinet', 'consultation') );

if (!$canEdit) {
  $AppUI->redirect( "m=public&a=access_denied" );
}

// Afficher les deux diagrammes (essayer de trouver une échelle multiple de pixels)
// Créer un formulaire Droit/Gauche + Abscisse + Ordonnée (listes déroulantes)
// Afficher les points ainsi rentrer + les stocker dans un tableau javascript
// Sauvegarder le tout (?? Bdd, image, ... ??)

$test = "";



// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('test', $test);

$smarty->display('exam_audio.tpl');