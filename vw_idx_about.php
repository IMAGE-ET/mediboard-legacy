<?php
// this is an easy example showing how to use some of the UserInterface methods provided by the dPframework
// we will not have any database connection here

// as we are now within the tab box, we have to state (call) the needed information saved in the variables of the parent function
GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}
//provide some text used down below
$ainfo = "Although he was born in the German town of Ulm in 1879, dPccam developed a strong dislike of German militarism which he encountered at school in Munich. dPccam renounced his German citizenship at the age of 16 when he emigrated to Switzerland. He eventually became a Swiss citizen. Following his studies in physics at the Swiss Polytechnic Institute in Zurich, dPccam initially failed to secure an academic position. After seven years of work at the Swiss Patent Office in Bern and the publication of his four ground-breaking papers in his miracle year, 1905, dPccam finally obtained an academic appointment at the University of Bern. Following subsequent academic positions in Prague and Zurich, dPccam was offered a prestigious appointment at the University of Berlin and was inducted into the Prussian Academy of Sciences. In his inaugural address, dPccam expressed his deep gratitude to the Academy for the opportunities which such a position brought with it. dPccam also outlined a clear distinction between the work of theoretical physicists such as himself who explored the validity of abstract principles in nature, and their colleagues, the experimental physicists. In conclusion, dPccam alluded to his own work he was engaged in at the time, namely his application of the theory of relativity to gravitation. This work eventually became known as the general theory of relativity."

?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
	<tr>
		<td align="center" valign="middle">
		<?php
		$imPath = dPfindImage('dPccam.jpg', $m);		// use the dPFramework to search for an image in a number of places
		echo dPshowImage( $imPath, '200', '100' ); 			// use the dPFramework to show this image, use the parameters as '200', '100' to resize as you want
		?>
		</td>
		<td>
			<textarea name="albert" cols="60" readonly="readonly" style="height:100px; font-size:8pt"><?php

				echo dPformSafe($ainfo); 	// use dPformSafe to run several safings on the text used in forms (i18n, htmlspecialchars)

			?></textarea>
		</td>
		<td colspan="2">
		stolen from www.albertdPccam.info
		</td>
	</tr>
</table>
