<?php
// one site for both adding and editing dPccam's quote items
// besides the following lines show the possiblities of the dPframework

// retrieve GET-Parameters via dPframework
// please always use this way instead of hard code (e.g. there have been some problems with REGISTER_GLOBALS=OFF with hard code)
$dPccam_id = intval( dPgetParam( $_GET, "dPccam_id", 0 ) );

// check permissions for this record
$canEdit = !getDenyEdit( $m, $dPccam_id );
if (!$canEdit) {
	$AppUI->redirect( "m=public&a=access_denied" );
}

// use the object oriented design of dP for loading the quote that should be edited
// therefore create a new instance of the dPccam Class
$obj = new CdPccam();

// load the record data in case of that this script is used to edit the quote qith dPccam_id (transmitted via GET)
if (!$obj->load( $dPccam_id, false ) && $dPccam_id > 0) {
	// show some error messages using the dPFramework if loadOperation failed
	// these error messages are nicely integrated with the frontend of dP
	// use detailed error messages as often as possible
	$AppUI->setMsg( 'dPccam' );
	$AppUI->setMsg( "invalidID", UI_MSG_ERROR, true );
	$AppUI->redirect();					// go back to the calling location
}

// check if this record has dependancies to prevent deletion
$msg = '';
$canDelete = $obj->canDelete( $msg, $dPccam_id );		// this is not relevant for CdPccam objects
								// this code is shown for demonstration purposes

// setup the title block
// Fill the title block either with 'Edit' or with 'New' depending on if dPccam_id has been transmitted via GET or is empty
$ttl = $dPccam_id > 0 ? "Edit Quote" : "New Quote";
$titleBlock = new CTitleBlock( $ttl, 'dPccam.png', $m, "$m.$a" );
// also have a breadcrumb here
// breadcrumbs facilitate the navigation within dP as they did for haensel and gretel in the identically named fairytale
$titleBlock->addCrumb( "?m=dPccam", "dPccam home" );
if ($canEdit && $dPccam_id > 0) {
	$titleBlock->addCrumbDelete( 'delete quote', $canDelete, $msg );	// please notice that the text 'delete quote' will be automatically
										// prepared for translation by the dPFramework
}
$titleBlock->show();

// some javaScript code to submit the form and set the delete object flag for the form processing
?>
<script language="javascript">
	function submitIt() {
		var f = document.editFrm;
		f.submit();
	}

	function delIt() {
		if (confirm( "<?php echo $AppUI->_('Really delete this object?');?>" )) {	// notice that we prepare for translation here
			var f = document.editFrm;
			f.del.value='1';
			f.submit();
		}
	}
</script>
<?php
// use the css-style 'std' of the UI style theme to format the table
// create a form providing to add/edit a quote
?>
<table cellspacing="0" cellpadding="4" border="0" width="100%" class="std">
<form name="editFrm" action="./index.php?m=dPccam" method="post">
	<?php
	// if set, the value of dosql is automatically executed by the dP core application
	// do_quote_aed.php will be the target of this form
	// it will execute all database relevant commands
	?>
	<input type="hidden" name="dosql" value="do_quote_aed" />
	<?php
	// the del variable contains a bool flag deciding whether to run a delete operation on the given object with dPccam_id
	// the value of del will be zero by default (do not delete)
	// or in case of mouse click on the delete icon it will set to '1' by javaScript (delete object with given dPccam_id)
	?>
	<input type="hidden" name="del" value="0" />

	<?php
	// the value of dPccam_id will be the id of the quote to edit
	// or in case of addition of a new quote it will contain '0' as value
	?>
	<input type="hidden" name="dPccam_id" value="<?php echo $dPccam_id;?>" />
	<?php
	// please notice that html tags that have no </closing tag> should be closed
	// like you find it here (<tag />) for xhtml compliance
	?>

<tr>
	<td width="50%" valign="top">
		<table cellspacing="0" cellpadding="2" border="0">
		<tr>
			<td align="right" nowrap="nowrap"><?php echo $AppUI->_('Quote');?></td>
			<td width="100%">
			<?php
			// the following textarea will either be empty (new quote) or contain the quotes content (edit)

			// edit: we show the property dPccam_quote from the dPccam object we created/loaded some lines above
			// add new: provide a form field for the dPccam_quote of our new CdPccamObject

			// notice that you use a dPformSafe()-method whenever you work with form fields in order to
			// make sure that special characters are handled the right way
			// please always use the way via the dPFrameWork instead of hard code :-)
			?>
				<textarea name="dPccam_quote" cols="60" value="<?php echo dPformSafe( $obj->dPccam_quote );?>" style="height:100px; font-size:8pt"/>
			</td>
		</tr>

		</table>
	</td>
</tr>
<tr>
	<td>
		<input class="button" type="button" name="cancel" value="<?php echo $AppUI->_('cancel');?>" onClick="javascript:if(confirm('Are you sure you want to cancel.')){location.href = './index.php?m=dPccam';}" />
	</td>
	<td align="right">
		<input class="button" type="submit" name="btnFuseAction" value="<?php echo $AppUI->_('submit');?>"/>
	</td>
</tr>
</form>
</table>