<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPplanningOp
* @version $Revision$
* @author Romain Ollivier
*/

GLOBAL $AppUI, $canRead, $canEdit, $m;

if (!$canRead) {			// lock out users that do not have at least readPermission on this module
	$AppUI->redirect( "m=public&a=access_denied" );
}

$type = dPgetParam( $_GET, 'type', 0 );
$chir = dPgetParam( $_GET, 'chir', 0 );

switch($type) {
	case 'ccam' : {
		$sql = "select favoris_code as code
				from ccamfavoris
				where favoris_user = '$chir' or favoris_user = $AppUI->user_id
				group by favoris_code
				order by favoris_code";
		$codes = db_loadlist($sql);
		$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
			or die("Could not connect");
		mysql_select_db("ccam")
			or die("Could not select database");
		$i = 0;
		foreach($codes as $key => $value) {
			$query = "select CODE, LIBELLELONG from ACTES where CODE = '".$value['code']."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			$list[$i]["id"] = $value['favoris_id'];
			$list[$i]["code"] = $row['CODE'];
			$list[$i]["texte"] = $row['LIBELLELONG'];
			$i++;
		}
		mysql_close();
		break;
	}
	case 'ccam2' : {
		$sql = "select favoris_code as code
				from ccamfavoris
				where favoris_user = '$chir' or favoris_user = $AppUI->user_id
				group by favoris_code
				order by favoris_code";
		$codes = db_loadlist($sql);
		$mysql = mysql_connect("localhost", "CCAMAdmin", "AdminCCAM")
			or die("Could not connect");
		mysql_select_db("ccam")
			or die("Could not select database");
		$i = 0;
		foreach($codes as $key => $value) {
			$query = "select CODE, LIBELLELONG from ACTES where CODE = '".$value['code']."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			$list[$i]["id"] = $value['favoris_id'];
			$list[$i]["code"] = $row['CODE'];
			$list[$i]["texte"] = $row['LIBELLELONG'];
			$i++;
		}
		mysql_close();
		break;
	}
	default : {
		$sql = "select favoris_code as code
				from cim10favoris
				where favoris_user = '$chir' or favoris_user = $AppUI->user_id
				order by favoris_code";
		$codes = db_loadlist($sql);
		$mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
			or die("Could not connect");
		mysql_select_db("cim10")
			or die("Could not select database");
		$i = 0;
		foreach($codes as $key => $value)
		{
			$query = "select * from master where abbrev = '".$value['code']."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			$list[$i]["code"] = $row['abbrev'];
			$query = "select * from libelle where SID = '".$row['SID']."' and source = 'S'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			$list[$i]["texte"] = $row['FR_OMS'];
			$i++;
		}
		mysql_close();
	}
}

// Création du template
require_once( $AppUI->getSystemClass ('smartydp' ) );
$smarty = new CSmartyDP;

$smarty->assign('type', $type);
$smarty->assign('list', $list);

$smarty->display('code_selector.tpl');

?>