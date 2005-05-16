<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcim10
* @version $Revision$
* @author Romain Ollivier
*/

function getSommaireCIM10() {
  $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
    or die("Could not connect");
  mysql_select_db("cim10")
    or die("Could not select database");

  $query = "SELECT * FROM chapter ORDER BY chap";
  $result = mysql_query($query);
  $i = 0;
  while($row = mysql_fetch_array($result)) {
    $chapter[$i]["rom"] = $row['rom'];
    $query = "SELECT * FROM master WHERE SID = '".$row['SID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $chapter[$i]["code"] = $row2['abbrev'];
    $query = "SELECT * FROM libelle WHERE SID = '".$row['SID']."' AND source = 'S'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $chapter[$i]["text"] = $row2['FR_OMS'];
    $i++;
  }

  mysql_close();
  return ($chapter);
}

function findCIM10($keys) {
  $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
    or die("Could not connect");
  mysql_select_db("cim10")
    or die("Could not select database");

  $query = "SELECT * FROM libelle WHERE 0";
  $keywords = explode(" ", $keys);
  if($keys != "") {
    $query .= " OR (1";
    foreach($keywords as $key => $value) {
    $query .= " AND FR_OMS LIKE '%".addslashes($value)."%'";
    }
    $query .= ")";
  }
  $query .= " ORDER BY SID LIMIT 0 , 100";
  $result = mysql_query($query);
  $master = array();
  $i = 0;
  while($row = mysql_fetch_array($result)) {
    $master[$i]['text'] = $row['FR_OMS'];
    $query = "SELECT * FROM master WHERE SID = '".$row['SID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $master[$i]['code'] = $row2['abbrev'];
    $i++;
  }

  mysql_close();
  return($master);
}

function getInfoCIM10($sid) {
  $mysql = mysql_connect("localhost", "CIM10Admin", "AdminCIM10")
    or die("Could not connect");
  mysql_select_db("cim10")
    or die("Could not select database");

  //sid
  $info['sid'] = $sid;
  //code
  $query = "select * from master where SID = '$sid'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $info['code'] = $row['abbrev'];
  //level
  $info['level'] = $row['level'];
  //levelsup
  $info['levelsup'][0]['sid'] = $row['id1'];
  if($row['id1'] != 0)
  {
    $query = "select * from master where SID = '".$row['id1']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][0]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id1']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][0]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][0]['code'] = "";
	$info['levelsup'][0]['text'] = "";
  }
  $info['levelsup'][1]['sid'] = $row['id2'];
  if($row['id2'] != 0)
  {
    $query = "select * from master where SID = '".$row['id2']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][1]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id2']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][1]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][1]['code'] = "";
	$info['levelsup'][1]['text'] = "";
  }
  $info['levelsup'][2]['sid'] = $row['id3'];
  if($row['id3'] != 0)
  {
    $query = "select * from master where SID = '".$row['id3']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][2]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id3']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][2]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][2]['code'] = "";
	$info['levelsup'][2]['text'] = "";
  }
  $info['levelsup'][3]['sid'] = $row['id4'];
  if($row['id4'] != 0)
  {
    $query = "select * from master where SID = '".$row['id4']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][3]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id4']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][3]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][3]['code'] = "";
	$info['levelsup'][3]['text'] = "";
  }
  $info['levelsup'][4]['sid'] = $row['id5'];
  if($row['id5'] != 0)
  {
    $query = "select * from master where SID = '".$row['id5']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][4]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id5']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][4]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][4]['code'] = "";
	$info['levelsup'][4]['text'] = "";
  }
  $info['levelsup'][5]['sid'] = $row['id6'];
  if($row['id6'] != 0)
  {
    $query = "select * from master where SID = '".$row['id6']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][5]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id6']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][5]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][5]['code'] = "";
	$info['levelsup'][5]['text'] = "";
  }
  $info['levelsup'][6]['sid'] = $row['id7'];
  if($row['id7'] != 0)
  {
    $query = "select * from master where SID = '".$row['id7']."'";
    $result = mysql_query($query);
    $row2 = mysql_fetch_array($result);
	$info['levelsup'][6]['code'] = $row2['abbrev'];
	$query = "select * from libelle where SID = '".$row['id7']."'";
    $result = mysql_query($query);
    $row3 = mysql_fetch_array($result);
	$info['levelsup'][6]['text'] = $row3['FR_OMS'];
  }
  else
  {
    $info['levelsup'][6]['code'] = "";
	$info['levelsup'][6]['text'] = "";
  }
	
  //levelinf
  $query = "select * from master where id".$info['level']." = '".$info['sid']."' and id".($info['level']+1)." != '0'";
  if($info['level'] < 6)
    $query .= "and id".($info['level']+2)." = '0'";
  $result = mysql_query($query);

  $i = 0;
  $info['levelinf'] = null;
  while($row = mysql_fetch_array($result))
  {
    $info['levelinf'][$i]['sid'] = $row['SID'];
    $info['levelinf'][$i]['code'] = $row['abbrev'];
	$query = "select * from libelle where SID = '".$row['SID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
	$info['levelinf'][$i]['text'] = $row2['FR_OMS'];
	$i++;
  }

  //libelle
  $query = "select * from libelle where SID = '$sid' and source = 'S'";
  $result = mysql_query($query);
  $row = mysql_fetch_array($result);
  $info['libelle'] = $row['FR_OMS'];
  //descr
  $query = "select * from descr where SID = '$sid'";
  $result = mysql_query($query);
  $info['descr'] = "";
  $i = 0;
  while($row = mysql_fetch_array($result))
  {
    $query = "select * from libelle where LID = '".$row['LID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['descr'][$i] = $row2['FR_OMS'];
    $i++;
  }
  //exclude
  $query = "select * from exclude where SID = '".$sid."'";
  $result = mysql_query($query);
  $info['exclude'] = "";
  $i = 0;
  while($row = mysql_fetch_array($result))
  {
    $query = "select * from libelle where LID = '".$row['LID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['exclude'][$i]['text'] = $row2['FR_OMS'];
	$query = "select * from master where SID = '".$row['excl']."'";
	$result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['exclude'][$i]['code'] = $row2['abbrev'];
    $i++;
  }
  //glossaire
  $query = "select * from glossaire where SID = '".$sid."'";
  $result = mysql_query($query);
  $info['glossaire'] = "";
  $i = 0;
  while($row = mysql_fetch_array($result))
  {
    $query = "select * from memo where MID = '".$row['MID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['glossaire'][$i] = $row2['FR_OMS'];
    $i++;
  }
  //include
  $query = "select * from include where SID = '".$sid."'";
  $result = mysql_query($query);
  $info['include'] = "";
  $i = 0;
  while($row = mysql_fetch_array($result))
  {
    $query = "select * from libelle where LID = '".$row['LID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['include'][$i] = $row2['FR_OMS'];
    $i++;
  }
  //indir
  $query = "select * from indir where SID = '".$sid."'";
  $result = mysql_query($query);
  $info['indir'] = "";
  $i = 0;
  while($row = mysql_fetch_array($result))
  {
    $query = "select * from libelle where LID = '".$row['LID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['indir'][$i] = $row2['FR_OMS'];
    $i++;
  }
  //note
  $query = "select * from note where SID = '".$sid."'";
  $result = mysql_query($query);
  $info['note'] = "";
  $i = 0;
  while($row = mysql_fetch_array($result))
  {
    $query = "select * from memo where MID = '".$row['MID']."'";
    $result2 = mysql_query($query);
    $row2 = mysql_fetch_array($result2);
    $info['note'][$i] = $row2['FR_OMS'];
    $i++;
  }
  mysql_close();
  return($info);
}

?>