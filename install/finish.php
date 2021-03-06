<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

require_once("checkconfig.php");
require_once("checkstructure.php");
require_once("checkauth.php");
?>

<?php showHeader(); ?>

<script type="text/javascript">

function checkForm(oForm) {
  if (oForm.password1.value != oForm.password2.value) {
    alert("Les deux mots de passe ne sont pas identiques");
    oForm.reset();
    oForm.password1.focus();
    return false;
  }
  
  if (oForm.password1.value.length < 5) {
    alert("Le mot de passe est trop court (< 5 caract�res)");
    oForm.reset();
    oForm.password1.focus();
    return false;
  }
  
  return true;
}

</script>
  
<h2>Finalisation de l'installation</h2>

<h3>Changement du mot de passe administrateur</h3>

<p>
  L'assistant d'installation du framework g�n�ral est fonctionnel mais n�cessite d'�tre
  s�curis�. Il est obligatoire de fournir un mot de passe administrateur s�r.
</p>

<div class="big-warning">
  Attention, toute la s�curit� du syst�me se r�sume � la s�curit� de ce mot de passe. 
  Il est recommand� d'utiliser une s�quence de plus de 4 caract�res compos�e de lettres, 
  minuscules et majuscules, de chiffres et d'autres symboles comme @$%^, etc.
</div>

<form name="changePassword" action="finish.php" method="post" onsubmit="return checkForm(this)">

<table class="form">

  <tr>
    <th class="category" colspan="2">Changer le mot de passe administrateur</th>
  </tr>

  <tr>
    <th><label for="password1" title="Saisir un mot de passe fiable">Saisir le mot de passe :</label></th>
    <td><input type="password" size="20" name="password1" value="" /></td>
  </tr>

  <tr>
    <th><label for="password2" title="Re-saisir le mot de passe pour v�rification">Re-saisir le mot de passe :</label></th>
    <td><input type="password" size="20" name="password2" value="" /></td>
  </tr>


  <tr>
    <td class="button" colspan="2"><input type="submit" name="changePassword" value="Valider le mot de passe" /></td>
  </tr>

</table>

</form>

<?php
if (@$_POST["changePassword"]) {
  $password = $_POST["password1"];
  $res = $auth->changePassword("admin", $password);
?>

<table class="tbl">

<tr>
  <th>Action</th>
  <th>Statut</th>
</tr>

<tr>
  <td>Cr�ations des bases et des utilisateurs</td>
  <td>
    <?php if (!Pear::isError($res)) { ?>
    <div class="message">Mot de passe modifi�</div>
    <?php } else { ?>
    <div class="error">
      Erreur lors du changement de mot de passe
      <br />
      <?php echo $res->getMessage(); ?>
    </div>
    <?php } ?>
  </td>
</tr>

</table>

<?php } ?>

<h3>Installer et configurer les modules</h3>

<?php
if (@$_POST["changePassword"] and $password != "admin") {
?>
<div class="big-success">
  F�licitations !
  <br />Le framework de Mediboard est maintenant op�rationnel.
</div>
<?php } ?>


<p>
  A cette �tape le framework g�n�ral de Mediboard est fonctionnel. Il est maintenant 
  n�cessaire d'installer et param�trer un par un les modules que vous souhaitez installer.
  A l'heure actuelle, les couplages inter-modules sont encore assez important, 
  c'est pourquoi il est recommand� sinon obligatoire de tous les installer, quitte � les
  cacher de l'interface.
</p>

<p>  
  A partir de maintenant, il est n�cessaire de s'authentifier aupr�s du syst�me en tant
  qu'administatreur pour pouvoir configurer les modules.
</p>

<div class="wizard-navigation">
  <a href="../index.php?m=system&amp;a=viewmods">
    Me rendre � la page d'administration des modules
  </a>
</div>



<?php showFooter(); ?>
