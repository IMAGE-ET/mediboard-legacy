<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

set_time_limit(180);

$mbpath = "..";

require_once("$mbpath/includes/mb_functions.php");
require_once("$mbpath/classes/chrono.class.php");

$steps = array("check", "install", "configure", "initialize", "feed");

$currentStep = basename($_SERVER["PHP_SELF"], ".php");

if (!in_array($currentStep, $steps)) {
   trigger_error("Etape $currentStep inexistante", E_USER_ERROR);
}

$currentStepKey = array_search($currentStep, $steps);

$chrono = new Chronometer();
$chrono->start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Mediboard :: Assistant d'installation &mdash; Etape <?php echo $currentStepKey+1; ?> : <?php echo $currentStep; ?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=ISO 8859-1" />
  <meta name="Description" content="Mediboard : Plateforme Open Source pour les Etablissements de Santé" />
  <meta name="Version" content="<?php echo mbVersion(); ?>" />
  <link rel="stylesheet" type="text/css" href="../style/mediboard/main.css" />
</head>

<body>
<div class="wizard">

<h1>Installation de Mediboard <?php echo mbVersion(); ?> &mdash; Etape <?php echo $currentStepKey+1; ?>/<?php echo count($steps); ?>  </h1>

