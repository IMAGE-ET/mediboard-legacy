<?php

class Cformulaire
{
  var $tool;
  var $id;
  var $chirurgiens;
  var $anesthesistes;
  var $specialites;
  var $salles;
  var $listheures;
  var $listminutes;
  
  function Cformulaire($tool, $id = 0)
  {
  	$this->tool = $tool;
	$this->id = $id;
    $sql = "select users.user_username as id, users.user_last_name as nom, users.user_first_name as prenom
			from users, users_mediboard, functions_mediboard, groups_mediboard
			where users.user_id = users_mediboard.user_id
			and users_mediboard.function_id = functions_mediboard.function_id
			and functions_mediboard.group_id = groups_mediboard.group_id
			and (groups_mediboard.text = 'Chirurgie' or groups_mediboard.text = 'Anesthésie')
			order by users.user_last_name";
    $chir = db_loadlist($sql);
    $this->chirurgiens[0]['id'] = 0;
    $this->chirurgiens[0]['nom'] = "Non défini";
    $i = 1;
    foreach($chir as $key => $value)
    {
      $this->chirurgiens[$i]['id'] = $value['id'];
      $this->chirurgiens[$i]['nom'] = "Dr ".$value['prenom']." ".$value['nom'];
	  $i++;
    }
    $sql = "select users.user_username as id, users.user_last_name as nom, users.user_first_name as prenom
			from users, users_mediboard, functions_mediboard, groups_mediboard
			where users.user_id = users_mediboard.user_id
			and users_mediboard.function_id = functions_mediboard.function_id
			and functions_mediboard.group_id = groups_mediboard.group_id
			and groups_mediboard.text = 'Anesthésie'
			order by users.user_last_name";
    $anesth = db_loadlist($sql);
    $this->anesthesistes[0]['id'] = 0;
    $this->anesthesistes[0]['nom'] = "Non défini";
    $i = 1;
    foreach($anesth as $key => $value)
    {
      $this->anesthesistes[$i]['id'] = $value['id'];
      $this->anesthesistes[$i]['nom'] = "Dr ".$value['prenom']." ".$value['nom'];
	  $i++;
    }
    $sql = "select functions_mediboard.function_id as id, functions_mediboard.text as nom
  			from functions_mediboard, groups_mediboard
			where functions_mediboard.group_id = groups_mediboard.group_id
			and (groups_mediboard.text = 'Chirurgie' or groups_mediboard.text = 'Anesthésie')
			order by functions_mediboard.text";
    $spe = db_loadlist($sql);
    $this->specialites[0]['id'] = 0;
    $this->specialites[0]['nom'] = "Non défini";
    $i = 1;
    foreach($spe as $key => $value)
    {
      $this->specialites[$i]['id'] = $value['id'];
      $this->specialites[$i]['nom'] = $value['nom'];
	  $i++;
    }
    $sql = "select id, nom from sallesbloc order by nom";
    $this->salles = db_loadlist($sql);
	for($i=0; $i<12; $i++)
    {
      $this->listheures[$i]['value'] = $i + 8;
      if(strlen($this->listheures[$i]['value']) == 1)
      {
        $this->listheures[$i]['value'] = "0".$this->listheures[$i]['value'];
      }
    }
    for($i=0; $i<4; $i++)
    {
      $this->listminutes[$i]['value'] = $i*15;
      if(strlen($this->listminutes[$i]['value']) == 1)
      {
        $this->listminutes[$i]['value'] = "0".$this->listminutes[$i]['value'];
      }
    }
  }
  
  function display()
  {
    switch($this->tool)
	{
		case "add" :
		{
          echo "<table align='center' bgcolor='#bbccff'>
				<form name='editFrm' action='./index.php?m=dPbloc' method='post'>
				<input type='hidden' name='dosql' value='do_plagesop_aed'>
				<input type='hidden' name='del' value='0'>
					<tr>
						<td bgcolor='#3333ff' colspan=6 align='center'>
							<b>Ajouter une plage opératoire</b>
						</td>
					</tr>
					<tr>
						<td align='right' bgcolor='#bbccff'>
							Chirurgien :
						</td>
						<td bgcolor='#bbccff'>
							<select name='id_chir'>";
          foreach($this->chirurgiens as $value)
		  {
		    echo "<option value='".$value['id']."'>".$value['nom']."</option>";
		  }
		  echo "			</select>
						</td>
						<td align='right' bgcolor='#bbccff'>
							Salle :
						</td>
						<td bgcolor='#bbccff'>
							<select name='id_salle'>";
          foreach($this->salles as $value)
		  {
		    echo "<option value='".$value['id']."'>".$value['nom']."</option>";
		  }
		  echo "			</select>
						</td>
						<td align='right' bgcolor='#bbccff'>
							debut :
						</td>
						<td bgcolor='#bbccff'>
							<select name='heuredeb'>";
          foreach($this->listheures as $value)
		  {
		    echo "<option>".$value['value']."</option>";
		  }
		  echo "			</select>
							:
							<select name='minutedeb'>";
          foreach($this->listminutes as $value)
		  {
		    echo "<option>".$value['value']."</option>";
		  }
		  echo "			</select>
						</td>
					</tr>
					<tr>
					<td align='right' bgcolor='#bbccff'>
						Anesthésiste :
					</td>
					<td bgcolor='#bbccff'>
						<select name='id_anesth'>";
          foreach($this->anesthesistes as $value)
		  {
		    echo "<option value='".$value['id']."'>".$value['nom']."</option>";
		  }
		  echo "			</select>
						</td>
						<td align='right' bgcolor='#bbccff'>
							Date :
						</td>
						<td bgcolor='#bbccff'>
							<input type='text' name='day' value='".$_SESSION['day']."' readonly size='1'>/
							<input type='text' name='month' value='".$_SESSION['month']."' readonly size='1'>/
							<input type='text' name='year' value='".$_SESSION['year']."' readonly size='2'>
						</td>
						<td align='right' bgcolor='#bbccff'>
							Fin :
						</td>
						<td bgcolor='#bbccff'>
							<select name='heurefin'>";
          foreach($this->listheures as $value)
		  {
		    echo "<option>".$value['value']."</option>";
		  }
		  echo "			</select>
							:
							<select name='minutefin'>";
          foreach($this->listminutes as $value)
		  {
		    echo "<option>".$value['value']."</option>";
		  }
		  echo "			</select>
						</td>
					</tr>
					<tr>
						<td align='right' bgcolor='#bbccff'>
							Spécialité :
						</td>
						<td colspan=5 bgcolor='#bbccff'>
							<select name='id_spec'>";
          foreach($this->specialites as $value)
		  {
		    echo "<option value='".$value['id']."'>".$value['nom']."</option>";
		  }
		  echo "			</select>
						</td>
					</tr>
					<tr>
						<td colspan=3 bgcolor='#bbccff'>
							Durée de répétition de la plage : <input type='text' name='repet' size=1 value='1'> semaine
						</td>
						<td colspan=3 bgcolor='#bbccff'>
							Une semaine sur deux ? <input type='checkbox' name='double'>
						</td>
					</tr>
					<tr>
						<td colspan=6 align='center' bgcolor='#bbccff'>
							<input type='submit' value='Ajouter'> <input type='reset' value='Annuler'>
						</td>
					</tr>
					</form>
				</table>";
		  break;
		}
		case "edit" :
		{
		  $sql = "select * from plagesop where id = '".$this->id."'";
		  $res = db_loadlist($sql);
		  $plagesel = $res[0];
          echo "<table align='center' bgcolor='#bbccff'>
				<form name='editFrm' action='./index.php?m=dPbloc' method='post'>
				<input type='hidden' name='dosql' value='do_plagesop_aed'>
				<input type='hidden' name='del' value='0'>
				<input type='hidden' name='id' value='".$this->id."'>
					<tr>
						<td bgcolor='#3333ff' colspan=6 align='center'>
							<b>Editer la plage opératoire</b>
						</td>
					</tr>
					<tr>
						<td align='right' bgcolor='#bbccff'>
							Chirurgien :
						</td>
						<td bgcolor='#bbccff'>
							<select name='id_chir'>";
          foreach($this->chirurgiens as $value)
		  {
		  	if($plagesel['id_chir'] == $value['id'])
			{
		      echo "<option value='".$value['id']."' selected>".$value['nom']."</option>";
			}
			else
			{
		      echo "<option value='".$value['id']."'>".$value['nom']."</option>";
			}
		  }
		  echo "			</select>
						</td>
						<td align='right' bgcolor='#bbccff'>
							Salle :
						</td>
						<td bgcolor='#bbccff'>
							<select name='id_salle'>";
          foreach($this->salles as $value)
		  {
		    if($plagesel['id_salle'] == $value['id'])
			{
		      echo "<option value='".$value['id']."' selected>".$value['nom']."</option>";
			}
			else
			{
		      echo "<option value='".$value['id']."'>".$value['nom']."</option>";
			}
		  }
		  echo "			</select>
						</td>
						<td align='right' bgcolor='#bbccff'>
							debut :
						</td>
						<td bgcolor='#bbccff'>
							<select name='heuredeb'>";
          foreach($this->listheures as $value)
		  {
		    if(substr($plagesel['debut'], 0, 2) == $value['value'])
			{
		      echo "<option selected>".$value['value']."</option>";
			}
			else
			{
		      echo "<option>".$value['value']."</option>";
			}
		  }
		  echo "			</select>
							:
							<select name='minutedeb'>";
          foreach($this->listminutes as $value)
		  {
		    if(substr($plagesel['debut'], 3, 2) == $value['value'])
			{
		      echo "<option selected>".$value['value']."</option>";
			}
			else
			{
		      echo "<option>".$value['value']."</option>";
			}
		  }
		  echo "			</select>
						</td>
					</tr>
					<tr>
					<td align='right' bgcolor='#bbccff'>
						Anesthésiste :
					</td>
					<td bgcolor='#bbccff'>
						<select name='id_anesth'>";
          foreach($this->anesthesistes as $value)
		  {
		    if($plagesel['id_anesth'] == $value['id'])
			{
		      echo "<option value='".$value['id']."' selected>".$value['nom']."</option>";
			}
			else
			{
		      echo "<option value='".$value['id']."'>".$value['nom']."</option>";
			}
		  }
		  echo "			</select>
						</td>
						<td align='right' bgcolor='#bbccff'>
							Date :
						</td>
						<td bgcolor='#bbccff'>
							<input type='text' name='day' value='".$_SESSION['day']."' readonly size='1'>/
							<input type='text' name='month' value='".$_SESSION['month']."' readonly size='1'>/
							<input type='text' name='year' value='".$_SESSION['year']."' readonly size='2'>
						</td>
						<td align='right' bgcolor='#bbccff'>
							Fin :
						</td>
						<td bgcolor='#bbccff'>
							<select name='heurefin'>";
          foreach($this->listheures as $value)
		  {
		    if(substr($plagesel['fin'], 0, 2) == $value['value'])
			{
		      echo "<option selected>".$value['value']."</option>";
			}
			else
			{
		      echo "<option>".$value['value']."</option>";
			}
		  }
		  echo "			</select>
							:
							<select name='minutefin'>";
          foreach($this->listminutes as $value)
		  {
		    if(substr($plagesel['fin'], 3, 2) == $value['value'])
			{
		      echo "<option selected>".$value['value']."</option>";
			}
			else
			{
		      echo "<option>".$value['value']."</option>";
			}
		  }
		  echo "			</select>
						</td>
					</tr>
					<tr>
						<td align='right' bgcolor='#bbccff'>
							Spécialité :
						</td>
						<td colspan=5 bgcolor='#bbccff'>
							<select name='id_spec'>";
          foreach($this->specialites as $value)
		  {
		    if($plagesel['id_spec'] == $value['id'])
			{
		      echo "<option value='".$value['id']."' selected>".$value['nom']."</option>";
			}
			else
			{
		      echo "<option value='".$value['id']."'>".$value['nom']."</option>";
			}
		  }
		  echo "			</select>
						</td>
					</tr>
					<tr>
						<td colspan=3 bgcolor='#bbccff'>
							Durée de répétition de la plage : <input type='text' name='repet' size=1 value='1'> semaine(s)
						</td>
						<td colspan=3 bgcolor='#bbccff'>
							Une semaine sur deux ? <input type='checkbox' name='double'>
						</td>
					</tr>
					<tr>
						<td colspan=6 align='center' bgcolor='#bbccff'>
							<input type='submit' value='Modifier'> <input type='reset' value='Annuler'>
						</td>
					</tr>
					</form>
					<form name='editFrm' action='./index.php?m=dPbloc' method='post'>
					<input type='hidden' name='dosql' value='do_plagesop_aed'>
					<input type='hidden' name='del' value='1'>
					<input type='hidden' name='day' value='".$_SESSION['day']."'>
					<input type='hidden' name='month' value='".$_SESSION['month']."'>
					<input type='hidden' name='year' value='".$_SESSION['year']."'>
					<input type='hidden' name='heuredeb' value='".substr($plagesel['debut'], 0, 2)."'>
					<input type='hidden' name='minutedeb' value='".substr($plagesel['debut'], 3, 2)."'>
					<input type='hidden' name='heurefin' value='".substr($plagesel['fin'], 0, 2)."'>
					<input type='hidden' name='minutefin' value='".substr($plagesel['fin'], 3, 2)."'>
					<input type='hidden' name='id_chir' value='".$plagesel['id_chir']."'>
					<input type='hidden' name='id_spec' value='".$plagesel['id_spec']."'>
					<input type='hidden' name='id_salle' value='".$plagesel['id_salle']."'>
					<tr>
						<td bgcolor='#3333ff' colspan=6 align='center'>
							<b>Editer la plage opératoire</b>
						</td>
					</tr>
					<tr>
						<td colspan=6 align='center' bgcolor='#bbccff'>
							Supprimer cette plage pendant <input type='text' name='repet' size=1 value='1'> semaine(s)
						</td>
					</tr>
					<tr>
						<td colspan=6 align='center' bgcolor='#bbccff'>
							<input type='submit' value='Supprimer'>
						</td>
					</tr>
					</form>
				</table>";
		  break;
		}
	}
  }
}
?>