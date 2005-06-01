<?php 
$mbPath = "../../";
$m = "dPcompteRendu";
$dPconfig = array();

class CTemplateManager {
};

// required includes for start-up
require_once( $mbPath . "includes/config.php" );
require_once( $mbPath . "includes/main_functions.php" );
require_once( $mbPath . "classes/ui.class.php" );

// manage the session variable(s)
session_name( 'dotproject' );
if (get_cfg_var( 'session.auto_start' ) > 0) {
  session_write_close();
}
session_start();

$AppUI =& $_SESSION['AppUI'];
$AppUI->setConfig( $dPconfig );

// Get the template manager
$templateManager =& $_SESSION['dPcompteRendu']['templateManager'];

// Création du template
require_once( $AppUI->getSystemClass('smartydp'));
$smarty = new CSmartyDP;
$smarty->debugging = false;
$smarty->assign("templateManager", $templateManager);
$smarty->display('mb_fckeditor.tpl');      

?>
