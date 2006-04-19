<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

include("header.php");

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

  <tr>
    <th class="category" colspan="2">Sans des droits d'aministrateurs</th>
  </tr>

  <tr>
    <td class="button" colspan="2"><input type="submit" value="G�n�rer le code de cr�ation des utilisateurs et des bases" /></td>
  </tr>

</table>

</form>

<h3>Chargement initial des bases</h3>

<p>Il faut d�sormais remplir la base de donn�es principale avec la structures des tables.<p>


<?php include("footer.php"); ?>
