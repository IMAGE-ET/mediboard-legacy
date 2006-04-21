<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

require_once("header.php");
require_once("dbconnection.php");

?>

<?php if (!@include_once("$mbpath/includes/config.php")) { ?>

<p>
  Le fichier de configuration n'a pas �t� valid�, merci de revenir � l'�tape 
  pr�c�dante.
</p>

<?php
  include("footer.php");
  return;
}

$dbConfigs = $dPconfig["db"];
unset($dbConfigs["ccam"]);
require_once("addusers.sql.php");

?>

<h2>Initialisation des bases de donn�es</h2>

<p>
  Cette �tape permet de cr�er les bases de donn�es et les utilisateurs de base de donn�es
  indispensables pour le fonctionnement de Mediboard. Dans un second temps, il permettra de 
  remplir ces bases avec les structures minimales.
</p>

<h3>Cr�ation des utilisateurs et des bases</h3>

<p>
  Vous �tes sur le point de cr�er les utilisateurs. Si vous avez des droits d'administration
  sur votre serveur de base de donn�es, l'assistant se charge de tout cr�er pour vous.
  Dans le cas contraire, vous devrez fournir le code g�n�r� � un administrateur pour qu'il
  l'ex�cute.
</p>

<form name="createBases" action="initialize.php" method="post">

<table class="form">

  <tr>
    <th class="category" colspan="2">Avec des droits d'aministrateurs</th>
  </tr>

  <tr>
    <th><label for="adminhost" title="Nom de l'h�te">Nom de l'h�te :</label></th>
    <td><input type="text" size="40" name="adminhost" value="localhost" /></td>
  </tr>

  <tr>
    <th><label for="adminuser" title="Nom de l'utilisateur">Nom de l'administrateur :</label></th>
    <td><input type="text" size="40" name="adminuser" value="root" /></td>
  </tr>

  <tr>
    <th><label for="adminpass" title="Mot de passe de l'utililisateur'">Mot de passe de l'administrateur :</label></th>
    <td><input type="password" size="40" name="adminpass" value="" /></td>
  </tr>

  <tr>
    <td class="button" colspan="2"><input type="submit" value="Cr�ation de la base et des utilisateurs" /></td>
  </tr>

</table>

<?php 
if (@$_POST["adminhost"]) { 
  $dbConnection = new CDBConnection(
    $_POST["adminhost"],
    $_POST["adminuser"],
    $_POST["adminpass"]);
    
  $dbConnection->connect();
  foreach($queries as $query) {
    $dbConnection->query($query);
  }
?>

<table class="tbl">

<tr>
  <th>Action</th>
  <th>Statut</th>
</tr>

<tr>
  <td>Cr�ations des bases et des utilisateurs</td>
  <td>
    <?php if (!count($dbConnection->_errors)) { ?>
    <div class="message">Cr�ations r�ussies</div>
    <?php } else { ?>
    <div class="error">
      Erreurs lors des cr�ations
      <br />
      <?php echo nl2br(join($dbConnection->_errors, "\n")); ?>
    </div>
    <?php } ?>
  </td>
</tr>

</table>

<?php } ?>

</form>

<form name="createBases" action="initialize.php" method="post">

<input type="hidden" name="generate" value="true"/>
  
<table class="form">

  <tr>
    <th class="category" colspan="2">Sans des droits d'aministrateurs</th>
  </tr>

  <tr>
    <td class="button" colspan="2"><input type="submit" value="G�n�rer le code de cr�ation des utilisateurs et des bases" /></td>
  </tr>
  
</table>

</form>

<?php if (@$_POST["generate"]) { ?>
<p>
  Merci de fournir le code suivant � un administrateur du serveur de base de
  donn�es pour qu'il puisse l'ex�cuter.
</p>
<p>
  Vous <strong>ne pouvez pas </strong> continuer l'installation de Mediboard 
  tant que cette �tape n'est effectu�e.
</p>

<textarea cols="50" rows="10"><?php echo join($queries, "\n\n"); ?></textarea>
<?php } ?>

<?php require_once("footer.php"); ?>
