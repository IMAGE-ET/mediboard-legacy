<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

require_once("checkconfig.php");

require_once("dbconnection.php");
$dbConfigs = $dPconfig["db"];
unset($dbConfigs["ccam"]);

?>

<?php showHeader(); ?>


<h2>Test et construction initiale de la base de données</h2>

<h3>Tests de connexion</h3>

<p>
  Le tableau suivant récapitule les tests de connectivité aux différentes
  bases de données.
<p>

<table class="tbl">
  <tr>
    <th>Configuration</th>
    <th>Test de connectivité</th>
  <tr>
  <?php 
  foreach($dbConfigs as $dbConfigName => $dbConfig) { 
    $dbConnection = new CDBConnection(
      $dbConfig["dbhost"], 
      $dbConfig["dbuser"], 
      $dbConfig["dbpass"], 
      $dbConfig["dbname"]);
    $dbConnection->connect();
  ?>
  <tr>
    <td><?php echo $dbConfigName; ?>
    </td>
    <td>
    
    <?php if (!count($dbConnection->_errors)) { ?>
      <div class="message">Connexion réussie</div>
    <?php } else { ?>
      <div class="error">
        Echec de connexion
        <br />
        <?php echo nl2br(join($dbConnection->_errors, "\n")); ?>
      </div>
    <?php } ?>

    </td>
  </tr>
  <?php } ?>
  
</table>

<h3>Construction de la base principale</h3>

<p>
  Cette opération va créer les structures initiales des tables de la base de 
  données principale. La connexion pour la configuration 'std' doit être 
  opérationnelle pour continuer.
</p>

<?php 

?>

<form action="feed.php" name="feedBase" method="post">  

<table class="form">
  <tr>
    <th class="category">Construction de la base</th>
  </tr>
  <tr>
    <td class="button">
      <input type="submit" name="do" value="Construction de la base" />
    </td>
  </tr>
</table>

</form>

<?php 
if (@$_POST["do"]) {
  $dbConfig = $dbConfigs["std"];
  $dbConnection = new CDBConnection(
    $dbConfig["dbhost"], 
    $dbConfig["dbuser"], 
    $dbConfig["dbpass"], 
    $dbConfig["dbname"]);
  if ($dbConnection->connect()) {
    $dbConnection->queryDump("mediboard.sql");
  }
?>

<table class="tbl">

<tr>
  <th>Action</th>
  <th>Statut</th>
</tr>

<tr>
  <td>Créations des bases et des utilisateurs</td>
  <td>
    <?php if (!count($dbConnection->_errors)) { ?>
    <div class="message">Créations réussies</div>
    <?php } else { ?>
    <div class="error">
      Erreurs lors des créations
      <br />
      <?php echo nl2br(join($dbConnection->_errors, "\n")); ?>
    </div>
    <?php } ?>
  </td>
</tr>

</table>

<?php } ?>

<?php showFooter(); ?>