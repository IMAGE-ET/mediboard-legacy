<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

class Cformulaire
{
  var $tool; // @todo -cprobablement inutile : tool == "add" <=> id != NULL. A virer
  var $id;
  var $chirurgiens;
  var $anesthesistes;
  var $specialites;
  var $salles;
  var $listheures;
  var $listminutes;
  
  function Cformulaire($tool, $id = 0) {
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
    
    foreach($chir as $key => $value) {
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

    foreach($anesth as $key => $value) {
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

    foreach($spe as $key => $value) {
      $this->specialites[$i]['id'] = $value['id'];
      $this->specialites[$i]['nom'] = $value['nom'];
      $i++;
    }

    $sql = "select id, nom from sallesbloc order by nom";
    $this->salles = db_loadlist($sql);

    for($i=0; $i<12; $i++) {
      $this->listheures[$i]['value'] = $i + 8;
      if(strlen($this->listheures[$i]['value']) == 1) {
        $this->listheures[$i]['value'] = "0".$this->listheures[$i]['value'];
      }
    }
    
    for($i=0; $i<4; $i++) {
      $this->listminutes[$i]['value'] = $i*15;
      if(strlen($this->listminutes[$i]['value']) == 1) {
        $this->listminutes[$i]['value'] = "0".$this->listminutes[$i]['value'];
      }
    }
  }
  
  function display() {
    if ($this->tool == "edit") {
      $sql = "select * from plagesop where id = '".$this->id."'";
      $res = db_loadlist($sql);
      $plagesel = $res[0];
    }
?>

<script language="javascript">
function checkPlage() {
  var form = document.editFrm;
    
  if (form.id_chir.value == 0 && form.id_spec.value == 0) {
    alert("Merci de choisir un chirurgien ou une spécialité");
    form.id_chir.focus();
    return false;
  }
  
  if (form.heurefin.value < form.heuredeb.value || (form.heurefin.value == form.heuredeb.value && form.minutefin.value <= form.minutedeb.value)) {
    alert("L'heure de début doit être supérieure à la l'heure de fin");
    form.heurefin.focus();
    return false;
  }
  
 
  return true;
}
</script>


<form name='editFrm' action='./index.php?m=dPbloc' method='post' onsubmit='return checkPlage()'>
<input type='hidden' name='dosql' value='do_plagesop_aed'>
<input type='hidden' name='del' value='0'>
<input type='hidden' name='id' value='<?php echo $this->id; ?>'>

<table class="form">
  <tr>
    <th class="category" colspan="6"><?php echo $plagesel ? "Modifier " : "Ajouter "; ?>une plage opératoire</th>
  </tr>

  <tr>
    <th class="mandatory">Chirurgien:</th>
    <td>
      <select name='id_chir'>";
      <?php foreach($this->chirurgiens as $value) { ?>
        <option value="<?php echo $value['id']; ?>" <?php if ($plagesel['id_chir'] == $value['id']) echo "selected='selected'"; ?> >
          <?php echo $value['nom']; ?> 
        </option>
      <?php }?>
      </select>
    </td>
    
    <th>Salle:</th>
    <td>
      <select name='id_salle'>";
      <?php foreach($this->salles as $value) { ?>
        <option value="<?php echo $value['id']; ?>" <?php if ($plagesel['id_salle'] == $value['id']) echo "selected='selected'"; ?> >
          <?php echo $value['nom']; ?>
        </option>
      <?php }?>
      </select>
    </td>

    <th class="mandatory">début:</th>
    <td>
      <select name='heuredeb'>
      <?php foreach($this->listheures as $value) { ?>
        <option <?php if (substr($plagesel['debut'], 0, 2) == $value['value'])  echo "selected='selected'"; ?> >
          <?php echo $value['value']; ?> 
        </option>
      <?php }?>
      
      </select>
      :
      <select name='minutedeb'>";
      <?php foreach($this->listminutes as $value) { ?>
        <option <?php if (substr($plagesel['debut'], 3, 2) == $value['value'])  echo "selected='selected'"; ?> >
          <?php echo $value['value']; ?> 
        </option>
      <?php }?>
      </select>
    </td>
  </tr>

  <tr>
    <th>Anesthésiste:</th>
    <td>
      <select name='id_anesth'>
      <?php foreach($this->anesthesistes as $value) { ?>
        <option value="<?php echo $value['id']; ?>" <?php if ($plagesel['id_anesth'] == $value['id']) echo "selected='selected'"; ?> >
          <?php echo $value['nom']; ?>
        </option>
      <?php }?>
    </td>

    <th>Date:</th>
    <td class="readonly">
      <input type="text" name="day"   value="<?php echo $_SESSION['day'  ] ; ?>" readonly="readonly" size='1' />-
      <input type="text" name="month" value="<?php echo $_SESSION['month'] ; ?>" readonly="readonly" size='1' />-
      <input type="text" name="year"  value="<?php echo $_SESSION['year' ] ; ?>" readonly="readonly" size='2' />
    </td>

    <th class="mandatory">Fin:</td>
    <td>
      <select name='heurefin'>
      <?php foreach($this->listheures as $value) { ?>
        <option <?php if (substr($plagesel['fin'], 0, 2) == $value['value'])  echo "selected='selected'"; ?> >
          <?php echo $value['value']; ?> 
        </option>
      <?php }?>
      </select>
      :
      <select name='minutefin'>";
      <?php foreach($this->listminutes as $value) { ?>
        <option <?php if (substr($plagesel['fin'], 3, 2) == $value['value'])  echo "selected='selected'"; ?> >
          <?php echo $value['value']; ?> 
        </option>
      <?php }?>
      </select>
    </td>
  </tr>
  
  <tr>
    <th class="mandatory">Spécialité:</th>
    <td colspan="5">
      <select name='id_spec'>";
      <?php foreach($this->specialites as $value) { ?>
        <option value="<?php echo $value['id']; ?>" <?php if ($plagesel['id_spec'] == $value['id']) echo "selected='selected'"; ?> >
          <?php echo $value['nom']; ?>
        </option>
      <?php }?>
      </select>
    </td>
  </tr>
  
  <tr>
    <th>Durée de répétition:</th>
    <td><input type="text" name="repet" size="1" value="1" /> semaine(s)</td>
    <td colspan="4"><input type="checkbox" name="double" />Une semaine sur deux</td>
  </tr>
  
  <tr>
    <td class="button" colspan="6">
    <?php if ($plagesel) { ?>
      <input type='reset' value='Réinitialiser' />
      <input type='submit' value='Modifier' />
    <?php } else { ?>
      <input type='submit' value='Ajouter' >
    <?php } ?>
    </td>
  </tr>

</table>

</form>

<?php if ($this->tool == "edit") { ?>

<form name='removeFrm' action='./index.php?m=dPbloc' method='post'>

<input type='hidden' name='dosql' value='do_plagesop_aed' />
<input type='hidden' name='del' value='1' />
<input type='hidden' name='day'   value='<?php echo $_SESSION['day'  ]; ?>' />
<input type='hidden' name='month' value='<?php echo $_SESSION['month']; ?>' />
<input type='hidden' name='year'  value='<?php echo $_SESSION['year' ]; ?>' />
<input type='hidden' name='heuredeb'  value='<?php echo substr($plagesel['debut'], 0, 2); ?>' />
<input type='hidden' name='minutedeb' value='<?php echo substr($plagesel['debut'], 3, 2); ?>' />
<input type='hidden' name='heurefin'  value='<?php echo substr($plagesel['fin'], 0, 2); ?>' />
<input type='hidden' name='minutefin' value='<?php echo substr($plagesel['fin'], 3, 2); ?>' />
<input type='hidden' name='id_chir' value='<?php echo $plagesel['id_chir']; ?>' />
<input type='hidden' name='id_spec' value='<?php echo $plagesel['id_spec']; ?>' />
<input type='hidden' name='id_salle' value='<?php echo $plagesel['id_salle']; ?>' />

<table class="form">
  <tr>
    <th class="category" colspan="2">Supprimer la plage opératoire</th>
  </tr>
  
  <tr>
    <th>Supprimer cette plage pendant</th> 
    <td><input type='text' name='repet' size=1 value='1' /> semaine(s)</td>
  </tr>
  
  <tr>
    <td class="button" colspan="2">
      <input type='submit' value='Supprimer' />
    </td>
  </tr>
</table>

<?php
    }
  }
}
?>
