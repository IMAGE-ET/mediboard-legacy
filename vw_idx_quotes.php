<?php
// this is another example showing how the dPFramework is working
// additionally we will have an easy database connection here

// as we are now within the tab box, we have to state (call) the needed information saved in the variables of the parent function
GLOBAL $AppUI, $canRead, $canEdit;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}
//prepare an html table with a head section
?>
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">
<tr>
	<th nowrap="nowrap">&nbsp;</th>
	<th nowrap="nowrap"><?php echo $AppUI->_( 'Quote' );	// use the method _($param) of the UIclass $AppUI to translate $param automatically
								// please remember this! automatic translation by dP is only possible if all strings
								// are handled like this
	?></th>

</tr>
<?php
// retrieving some dynamic content using an easy database query

$sql = "SELECT * FROM dPccam";	// prepare the sqlQuery command to get all quotes from the dPccam table
// pass the query to the database, please consider always using the (still poor) database abstraction layer
$quotes = db_loadList( $sql );		// retrieve a list (in form of an indexed array) of dPccam quotes via an abstract db method

// add/show now gradually the dPccam quotes

foreach ($quotes as $row) {		//parse the array of dPccam quotes
?>
<tr>
	<td nowrap="nowrap" width="20">


	<?php if ($canEdit) {	// in case of writePermission on the module show an icon providing edit functionality for the given quote item

		// call the edit site with the unique id of the quote item
		echo "\n".'<a href="./index.php?m=dPccam&a=addedit&dPccam_id=' . $row["dPccam_id"] . '">';
		
		// use the dPFrameWork to show the image
		// always use this way via the framework instead of hard code for the advantage
		// of central improvement of code in case of bugs etc. and for other reasons
		echo dPshowImage( './images/icons/stock_edit-16.png', '16', '16' );
		echo "\n</a>";
	}
	?>
	</td>
	<td >
	<?php
	echo $row["dPccam_quote"];		// finally show the dPccam quote stored in the indexed array
	?>
	</td>
</tr>
<?php
}
?>
</table>