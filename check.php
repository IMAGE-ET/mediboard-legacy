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
$extension->description = "Extension d'acc�s aux bases de donn�es MySQL";
$extension->mandatory = true;
$extension->reasons[] = "Acc�s � la base de donn�e de principale Mediboard";
$extension->reasons[] = "Acc�s aux bases de donn�es de codage CCAM, CIM et GHM";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "MBString";
$extension->description = "Extension de gestion des cha�nes de caract�res multi-octets";
$extension->mandatory = true;
$extension->reasons[] = "Internationalisation de Mediboard";
$extension->reasons[] = "Interop�rabilit� Unicode";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "ZLib";
$extension->description = "Extension de compression au format GNU ZIP (gz)";
$extension->mandatory = true;
$extension->reasons[] = "Installation de Mediboard";
$extension->reasons[] = "Accel�ration substancielle de l'application via une communication web compress�e";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "DOM";
$extension->description = "Extension de manipulation de fichier XML avec l'API DOM";
$extension->mandatory = false;
$extension->reasons[] = "Import de base de donn�es m�decin";
$extension->reasons[] = "Interop�rabilit� HPRIM XML, notamment pour le PMSI";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "FTP";
$extension->description = "Extension d'acc�s aux serveur FTP";
$extension->mandatory = false;
$extension->reasons[] = "Envoi HPRIM vers des serveurs de facturation";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "BCMath";
$extension->description = "Extension de calculs sur des nombres de pr�cision arbitraire";
$extension->mandatory = false;
$extension->reasons[] = "Validation des codes INSEE et ADELI";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "CURL";
$extension->description = "Extension permettant de communiquer avec des serveurs distants, gr�ce � de nombreux protocoles";
$extension->mandatory = false;
$extension->reasons[] = "Connexion au site web du Conseil National l'Ordre des M�decins";
$extensions[] = $extension;

$extension = new CPHPExtension;
$extension->name = "GD";
$extension->description = "Extension de manipulation d'image. \nGD version 2 est recommand�e car elle permet un meilleur rendu, gr�ce � de nombreux protocoles";
$extension->mandatory = false;
$extension->reasons[] = "Module de statistiques graphiques";
$extension->reasons[] = "Fonction d'audiogrammes";
$extensions[] = $extension;

$versions = array();

$version = new CPHPVersion;
$version->name = "4.3";
$version->description = "Version de PHP4 r�cente";
$version->mandatory = true;
$version->reasons[] = "Construction orient�e objet";
$version->reasons[] = "Correction de nombreux bugs, stabilit�";
$versions[] = $version;

$version = new CPHPVersion;
$version->name = "5.1";
$version->description = "Version de PHP5 r�cente";
$version->mandatory = false;
$version->reasons[] = "Int�gration du support XML natif : utilisation pour l'int�rop�rabilit� HPRIM XML'";
$version->reasons[] = "Int�gration de PDO : acc�s universel et s�curis� aux base de donn�es";
$version->reasons[] = "Conception objet plus �volu�e";
$versions[] = $version;

?>

<h1>V�rification des pr�requis � l'installation de Mediboard <?php echo mbVersion(); ?></h1>

<h2>Version de PHP</h2>

<p>
  PHP est le langage d'ex�cution de script c�t� serveur de Mediboard. Il est 
  n�cessaire d'installer une version r�cente de PHP pour assurer le bon 
  fonctionnement du syst�me
</p>

<p>
  N'h�sitez pas � vous rendre sur le site officiel de <a href="http://www.php.net/">http://www.php.net/</a>
  pour obtenir les derni�res versions de PHP.
</p>

<table class="tbl">

<tr>
  <th>Num�ro de version</th>
  <th>Description</th>
  <th>Obligatoire ?</th>
  <th>Utilit�</th>
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
    Recommand�e
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
  PECL est une biblioth�que d'extensions binaires de PHP. 
  <br />
  La plupart des  extensions de base de PHP est fournie avec votre 
  distribution de PHP. Si toutefois certaines extensions sont manquantes,
  v�rifiez que :
</p>
<ul>
  <li>L'extension est install�e sur votre d�ploiement PHP</li>
  <li>L'extension est bien charg�e dans la configuration de PHP (php.ini)</li>
</ul>  
<p>
  N'h�sitez pas � vous rendre sur le site officiel de PHP <a href="http://www.php.net/">http://www.php.net/</a>
  et de PECL <a href="http://pecl.php.net/">http://pecl.php.net/</a>  pour 
  obtenir de plus amples informations. 
</p>

<table class="tbl" >

<tr>
  <th>Nom</th>
  <th>Description</th>
  <th>Obligatoire ?</th>
  <th>Utilit�</th>
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
    Recommand�e
    <?php } ?>
  <td class="text">
    <ul>
      <?php foreach($prereq->reasons as $reason) { ?>
      <li><?php echo $reason; ?></li>
      <?php } ?>
    </ul>
  <td>
    <?php if ($prereq->check()) { ?>
    Extension charg�e
    <?php } else { ?>
    <strong>Extension absente</strong>
    <?php } ?>
  </td>
</tr>
<?php } ?>

</table>

<h2>Packages PEAR</h2>

<p>
  PEAR est un framework de distributions de biblioth�ques �crites en PHP.
  <br />
  Si plusieurs ou tous les packages sont manquants, n'h�sitez pas � vous rendre 
  sur le site officiel <a href="http://pear.php.net/">http://pear.php.net/</a>
  pour les installer sur votre d�ploiement de PHP. 
</p>
  
<table class="tbl" >

<tr>
  <th class="category" colspan="5">Packages PEAR</th>
</tr>

<tr>
  <th>Nom</th>
  <th>Description</th>
  <th>Obligatoire ?</th>
  <th>Utilit�</th>
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
    Recommand�
    <?php } ?>
  <td class="text">
    <ul>
      <?php foreach($prereq->reasons as $reason) { ?>
      <li><?php echo $reason; ?></li>
      <?php } ?>
    </ul>
  <td>
    <?php if ($prereq->check()) { ?>
    Package install�
    <?php } else { ?>
    <strong>Package manquant</strong>
    <?php } ?>
  </td>
</tr>
<?php } ?>

</table>

<?php include("footer.php"); ?>
