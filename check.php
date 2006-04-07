<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

include("header.php");

class CPrerequisite {
  var $name = "";
  var $description = "";
  var $mandatory = false;
  var $reason = array();

  function check() {
    return false;
  }
}

class CPearPackage extends CPrerequisite {
  function check() {
    return @include("$this->name.php");
  }
}

class CPHPExtension  extends CPrerequisite {
  function check() {
    return extension_loaded(strtolower($this->name));
  }
}

class CPHPVersion extends CPrerequisite {
  function check() {
    return phpversion() >= $this->name;
  }
}
$packages = array();

$package = new CPearPackage;
$package->name = "Archive/Tar";
$package->description = "Package de manipulation d'archives au format GNU TAR";
$package->mandatory = true;
$package->reasons[] = "Installation de Mediboard";
$package->reasons[] = "Import des fonctions de GHM";
$packages[] = $package;

$package = new CPearPackage;
$package->name = "Archive/Zip";
$package->description = "Package de manipulation d'archives au format ZIP";
$package->mandatory = true;
$package->reasons[] = "Installation de Mediboard";
$packages[] = $package;

$package = new CPearPackage;
$package->name = "Date";
$package->description = "Package de manipulation de dates";
$package->mandatory = true;
$package->reasons[] = "Relicats du framework dotProject";
$packages[] = $package;

$extensions = array();

$extension = new CPHPExtension;
$extension->name = "MySQL";
$extension->description = "Extension d'accès aux bases de données MySQL";
$extension->mandatory = true;
$extension->reasons[] = "Accès à la base de donnée de principale Mediboard";
$extension->reasons[] = "Accès aux bases de données de codage CCAM, CIM et GHM";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "MBString";
$extension->description = "Extension de gestion des chaînes de caractères multi-octets";
$extension->mandatory = true;
$extension->reasons[] = "Internationalisation de Mediboard";
$extension->reasons[] = "Interopérabilité Unicode";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "ZLib";
$extension->description = "Extension de compression au format GNU ZIP (gz)";
$extension->mandatory = true;
$extension->reasons[] = "Installation de Mediboard";
$extension->reasons[] = "Accelération substancielle de l'application via une communication web compressée";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "DOM";
$extension->description = "Extension de manipulation de fichier XML avec l'API DOM";
$extension->mandatory = false;
$extension->reasons[] = "Import de base de données médecin";
$extension->reasons[] = "Interopérabilité HPRIM XML, notamment pour le PMSI";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "FTP";
$extension->description = "Extension d'accès aux serveur FTP";
$extension->mandatory = false;
$extension->reasons[] = "Envoi HPRIM vers des serveurs de facturation";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "BCMath";
$extension->description = "Extension de calculs sur des nombres de précision arbitraire";
$extension->mandatory = false;
$extension->reasons[] = "Validation des codes INSEE et ADELI";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "CURL";
$extension->description = "Extension permettant de communiquer avec des serveurs distants, grâce à de nombreux protocoles";
$extension->mandatory = false;
$extension->reasons[] = "Connexion au site web du Conseil National l'Ordre des Médecins";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "GD";
$extension->description = "Extension de manipulation d'image. \nGD version 2 est recommandée car elle permet un meilleur rendu, grâce à de nombreux protocoles";
$extension->mandatory = false;
$extension->reasons[] = "Module de statistiques graphiques";
$extension->reasons[] = "Fonction d'audiogrammes";
$extensions[] = $extension;

$versions = array();

$version = new CPHPVersion;
$version->name = "4.3";
$version->description = "Version de PHP4 récente";
$version->mandatory = true;
$version->reasons[] = "Construction orientée objet";
$version->reasons[] = "Correction de nombreux bugs, stabilité";
$versions[] = $version;

$version = new CPHPVersion;
$version->name = "5.1";
$version->description = "Version de PHP5 récente";
$version->mandatory = false;
$version->reasons[] = "Intégration du support XML natif : utilisation pour l'intéropérabilité HPRIM XML'";
$version->reasons[] = "Intégration de PDO : accès universel et sécurisé aux base de données";
$version->reasons[] = "Conception objet plus évoluée";
$versions[] = $version;

?>

<h1>Vérification des prérequis à l'installation de Mediboard <?php echo mbVersion(); ?></h1>

<h2>Version de PHP</h2>

<p>
  PHP est le langage d'exécution de script côté serveur de Mediboard. Il est 
  nécessaire d'installer une version récente de PHP pour assurer le bon 
  fonctionnement du système
</p>

<p>
  N'hésitez pas à vous rendre sur le site officiel de <a href="http://www.php.net/">http://www.php.net/</a>
  pour obtenir les dernières versions de PHP.
</p>

<table class="tbl">

<tr>
  <th>Numéro de version</th>
  <th>Description</th>
  <th>Obligatoire ?</th>
  <th>Utilité</th>
  <th>Installation ?</th>
</tr>
  
<?php foreach($versions as $prereq) { ?>
<tr>
  <td><strong><?php echo $prereq->name; ?></strong></td>
  <td class="text"><?php echo nl2br($prereq->description); ?></td>
  <td>
    <?php if ($prereq->mandatory) { ?>
    Oui
    <?php } else { ?>
    Recommandée
    <?php } ?>
  <td class="text">
    <ul>
      <?php foreach($prereq->reasons as $reason) { ?>
      <li><?php echo $reason; ?></li>
      <?php } ?>
    </ul>
  <td>
    <?php if ($prereq->check()) { ?>
    Oui, Version <?php echo phpVersion(); ?>
    <?php } else { ?>
    Non, Version <?php echo phpVersion(); ?>
    <?php } ?>
  </td>
</tr>
<?php } ?>
  
</table>

<h2>Extensions PECL</h2>
<p>
  PECL est une bibliothèque d'extensions binaires de PHP. 
  <br />
  La plupart des  extensions de base de PHP est fournie avec votre 
  distribution de PHP. Si toutefois certaines extensions sont manquantes,
  vérifiez que :
</p>
<ul>
  <li>L'extension est installée sur votre déploiement PHP</li>
  <li>L'extension est bien chargée dans la configuration de PHP (php.ini)</li>
</ul>  
<p>
  N'hésitez pas à vous rendre sur le site officiel de PHP <a href="http://www.php.net/">http://www.php.net/</a>
  et de PECL <a href="http://pecl.php.net/">http://pecl.php.net/</a>  pour 
  obtenir de plus amples informations. 
</p>

<table class="tbl" >

<tr>
  <th>Nom</th>
  <th>Description</th>
  <th>Obligatoire ?</th>
  <th>Utilité</th>
  <th>Installation ?</th>
</tr>

<?php foreach($extensions as $prereq) { ?>
<tr>
  <td><strong><?php echo $prereq->name; ?></strong></td>
  <td class="text"><?php echo nl2br($prereq->description); ?></td>
  <td>
    <?php if ($prereq->mandatory) { ?>
    Oui
    <?php } else { ?>
    Recommandée
    <?php } ?>
  <td class="text">
    <ul>
      <?php foreach($prereq->reasons as $reason) { ?>
      <li><?php echo $reason; ?></li>
      <?php } ?>
    </ul>
  <td>
    <?php if ($prereq->check()) { ?>
    Extension chargée
    <?php } else { ?>
    <strong>Extension absente</strong>
    <?php } ?>
  </td>
</tr>
<?php } ?>

</table>

<h2>Packages PEAR</h2>

<p>
  PEAR est un framework de distributions de bibliothèques écrites en PHP.
  <br />
  Si plusieurs ou tous les packages sont manquants, n'hésitez pas à vous rendre 
  sur le site officiel <a href="http://pear.php.net/">http://pear.php.net/</a>
  pour les installer sur votre déploiement de PHP. 
</p>
  
<table class="tbl" >

<tr>
  <th class="category" colspan="5">Packages PEAR</th>
</tr>

<tr>
  <th>Nom</th>
  <th>Description</th>
  <th>Obligatoire ?</th>
  <th>Utilité</th>
  <th>Installation ?</th>
</tr>

<?php foreach($packages as $prereq) { ?>
<tr>
  <td><strong><?php echo $prereq->name; ?></strong></td>
  <td class="text"><?php echo nl2br($prereq->description); ?></td>
  <td>
    <?php if ($prereq->mandatory) { ?>
    Oui
    <?php } else { ?>
    Recommandé
    <?php } ?>
  <td class="text">
    <ul>
      <?php foreach($prereq->reasons as $reason) { ?>
      <li><?php echo $reason; ?></li>
      <?php } ?>
    </ul>
  <td>
    <?php if ($prereq->check()) { ?>
    Package installé
    <?php } else { ?>
    <strong>Package manquant</strong>
    <?php } ?>
  </td>
</tr>
<?php } ?>

</table>

<?php include("footer.php"); ?>
