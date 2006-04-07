<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage install
* @version $Revision$
* @author Thomas Despoix
*/

include("header.php");

require_once ("Archive/Tar.php");

$mbpath = "..";

class CLibraryPatch {
  var $dirName = "";
  var $sourceName = "";
  var $targetDir = "";
  
  function apply() {
    global $mbpath;
    $libPath = "$mbpath/lib";
    $patchPath = "$libPath/releases/patches";
    $sourcePath = "$patchPath/$this->dirName/$this->sourceName";
    $targetPath = "$libPath/$this->dirName/$this->targetDir/$this->sourceName";
    $oldPath = $targetPath . ".old";
    assert(is_file($targetPath));
    @unlink($oldPath);
    rename($targetPath, $oldPath);
    return copy($sourcePath, $targetPath);
  }
}

class CLibraryRenamer {
  var $sourcePath = "";
  var $targetPath = "";
  
  function apply() {
    global $mbpath;
    $libPath = "$mbpath/lib";
    $sourcePath = "$libPath/$this->sourcePath";
    $targetPath = "$libPath/$this->targetPath";
    assert(is_dir($sourcePath));
    @unlink($targetPath);
    return rename($sourcePath, $targetPath);
  }
}

class CLibrary {
  var $name = "";
  var $url = "";
  var $fileName = "";
  var $description = "";
  var $nbFiles = 0;
  var $renamer = null;
  var $patches = array();
  
  function install() {
    global $mbpath;
    $libPath = "$mbpath/lib";
    $releasePath = "$libPath/releases";
    $filePath = "$releasePath/$this->fileName";
    
    $tarball = new Archive_Tar($filePath);
    $this->nbFiles = count($tarball->listContent());
    return $tarball->extract($libPath);
  }
}

$libraries = array();

$library = new CLibrary;
$library->name = "FCKEditor";
$library->url = "http://www.fckeditor.net/";
$library->fileName = "FCKeditor_2.2.tar.gz";
$library->description = "Composant Javascript d'édition de texte au format HTML";

$patch = new CLibraryPatch;
$patch->dirName = "FCKeditor";
$patch->sourceName = "config.php";
$patch->targetDir = "editor/filemanager/browser/default/connectors/php";

$library->patches[] = $patch;

$libraries[] = $library;

$library = new CLibrary;
$library->name = "JPGraph";
$library->url = "http://www.aditus.nu/jpgraph/";
$library->fileName = "jpgraph-1.20.3.tar.gz";
$library->description = "Composant PHP de génération de graphs aux formats d'image";

$renamer = new CLibraryRenamer;
$renamer->sourcePath = "jpgraph-1.20.3";
$renamer->targetPath = "jpgraph";

$library->renamer = $renamer;

$libraries[] = $library;

?>

<h1>Installation de Mediboard</h1>

<h2>Installation des bibliothèques externes</h2>

<p>
  Mediboard utilise de nombreuses bibliothèques externes non publiées via PEAR.
</p>

<p>
  Celles-ci sont fournies dans leur distributions standards puis extraites. 
  N'hésitez pas à consulter les sites web correspondant pour obtenir de plus amples
  informations.
</p>

<table class="tbl">

<tr>
  <th>Nom</th>
  <th>Description</th>
  <th>Site web</th>
  <th>Distribution</th>
  <th>Installation</th>
</tr>

<?php foreach($libraries as $library) { ?>
<tr>
  <td><strong><?php echo $library->name; ?></strong></td>
  <td class="text"><?php echo nl2br($library->description); ?></td>
  <td>
    <a href="<?php echo $library->url; ?>" title="Site web officiel de <?php echo $library->name; ?>">
    <?php echo $library->url; ?>
    </a>
  <td><?php echo $library->fileName; ?></td>
  <td>
    <?php if ($library->install()) { ?>
    Ok, <?php echo $library->nbFiles; ?> fichiers extraits
    <?php } else { ?>
    Erreur
    <?php } ?>
  </td>
</tr>
<?php foreach($library->patches as $patch) { ?>
<tr>
  <td />
  <td colspan="3">
    Patch <?php echo $patch->sourceName; ?> dans <?php echo $patch->targetDir; ?>
  </td>
  <td>
    <?php if ($patch->apply()) { ?>
    Patch appliqué
    <?php } else { ?>
    Erreur
    <?php } ?>
  </td>
</tr>
<?php } ?>
<?php } ?>

<?php if ($renamer = $library->renamer) { ?>
<tr>
  <td />
  <td colspan="3">
    Renommage de la bibliothèque <?php echo $renamer->sourcePath; ?> 
    en <?php echo $renamer->targetPath; ?>
  </td>
  <td>
    <?php if ($renamer->apply()) { ?>
    Renommage effectué
    <?php } else { ?>
    Erreur
    <?php } ?>
  </td>
</tr>
<?php } ?>

</table>

<h2>Paramétrage des accès aux bases de données</h2>


<?php include("footer.php"); ?>
