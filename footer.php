<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

$chrono->stop();

$prevStep = $currentStepKey != 0 ? $steps[$currentStepKey-1] : null;
$nextStep = $currentStepKey+1 != count($steps) ? $steps[$currentStepKey+1] : null;
?>
<hr />
<div class="wizard-navigation">
  <?php if ($prevStep) { ?><a href="<?php echo $prevStep; ?>.php">&lt;&lt; <?php echo $prevStep; ?></a><?php } ?>
  <?php if ($nextStep) { ?><a href="<?php echo $nextStep; ?>.php"><?php echo $nextStep; ?> &gt;&gt;</a><?php } ?>
</div>

<div class="generated">
  Page générée en <?php printf("%.3f", $chrono->total); ?> secondes.
</div>

</div>
</body>

</html>