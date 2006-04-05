{if $templateManager->editor == "FCKeditor" }
{literal}
<script type="text/javascript" src="lib/fckeditor/fckeditor.js"></script>
<script language="JavaScript" type="text/javascript">

function initFCKEditor() {
	var field = document.getElementById("htmlarea");
	var oFCKeditor = new FCKeditor(field.name, '100%', '100%') ;
	
	// Be carefull: after that all Js code is executed in lib/FCKeditor/
	oFCKeditor.BasePath	= 'lib/FCKEditor/';
	
	oFCKeditor.Config['CustomConfigurationsPath'] = '../../../modules/dPcompteRendu/mb_fckeditor.php' ;
	
	oFCKeditor.ReplaceTextarea() ;
}

</script>
{/literal}
{/if}