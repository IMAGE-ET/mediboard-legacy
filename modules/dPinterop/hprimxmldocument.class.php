<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

if (!class_exists("DOMDocument")) {
  trigger_error("sorry, DOMDocument is needed");
  return;
}


global $AppUI, $m;

require_once($AppUI->getModuleClass("dPinterop", "mbxmldocument"));
require_once($AppUI->getModuleClass("dPinterop", "hprimxmlschema"));

class CHPrimXMLDocument extends CMbXMLDocument {
  var $pmsipath = "modules/dPinterop/hprim";
  var $finalpath = "files/hprim";
  var $schemapath = null;
  var $schemafilename = null;
  var $documentfilename = null;
  var $documentfinalprefix = null;
  var $documentfinalfilename = null;
  var $sentFiles = array();
  var $now = null;
   
  function __construct($schemaname) {
    parent::__construct();

    $this->schemapath = "$this->pmsipath/$schemaname";
    $this->schemafilename   = "$this->schemapath/schema.xml"  ;
    $this->documentfilename = "$this->schemapath/document.xml";
    $this->finalpath .= "/$schemaname";

    $this->now = time();
  }
  
  function checkSchema() {
    if (!is_dir($this->schemapath)) {
      trigger_error("ServeurActe schemas are missing. Please extract them from archive in $this->schemapath/ directory");
      return false;
    }
    
    if (!is_file($this->schemafilename)) {
      $schema = new CHPrimXMLSchema();
      $schema->importSchemaPackage($this->schemapath);
      $schema->purgeIncludes();
      $schema->purgeImportedNamespaces();
      $schema->save($this->schemafilename);
    }
    
    return true;
  }
  
  function schemaValidate() {
    return parent::schemaValidate($this->schemafilename);
  }
  
  function addNameSpaces() {
    // Ajout des namespace pour XML Spy
    $this->addAttribute($this->documentElement, "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $this->addAttribute($this->documentElement, "xsi:schemaLocation", "http://www.hprim.org/hprimXML schema.xml");
  }
  
  function saveTempFile() {
    parent::save($this->documentfilename);
  }
  
  function saveFinalFile() {
    $this->documentfinalfilename = "$this->finalpath/$this->documentfinalprefix-$this->now.xml";
    mbForceDirectory(dirname($this->documentfinalfilename));
    parent::save($this->documentfinalfilename);
  }
  
  function getSentFiles() {
    $pattern = "$this->finalpath/$this->documentfinalprefix-*.xml";
    foreach(glob($pattern) as $sentFile) {
      $baseName = basename($sentFile);
      preg_match("`^op[[:digit:]]{6}-([[:digit:]]*)\.xml$`", $baseName, $matches);
      $timeStamp = $matches[1];
      $this->sentFiles[] = array (
        "name" => $baseName,
        "datetime" => strftime("%Y-%m-%d %H:%M:%S", $timeStamp)
      );
    }
  }
  
  function addCodeLibelle($elParent, $nodeName, $code, $libelle) {
    $codeLibelle = $this->addElement($elParent, $nodeName);
    $this->addElement($codeLibelle, "code", substr($code, 0, 10));
    $this->addElement($codeLibelle, "libelle", substr($libelle, 0, 35));
    return $codeLibelle;
  }
  
  function addAgent($elParent, $categorie, $code, $libelle) {
    $agent = $this->addCodeLibelle($elParent, "agent", $code, $libelle);
    $this->addAttribute($agent, "categorie", $categorie);
    return $agent;
    
  }
  
  function addIdentifiantPart($elParent, $partName, $partValue) {
    $part = $this->addElement($elParent, $partName);
    $this->addElement($part, "valeur", $partValue);
    $this->addAttribute($part, "etat", "permanent");
    $this->addAttribute($part, "portee", "local");
    $this->addAttribute($part, "referent", "non");
  }
    
  function addUniteFonctionnelle($elParent, $mbOp) {
    $this->addCodeLibelle($elParent, "uniteFonctionnelle", $mbOp->code_uf, $mbOp->libelle_uf);
  }
  
  
  function addProfessionnelSante($elParent, $mbMediuser) {
    $medecin = $this->addElement($elParent, "medecin");
    $this->addElement($medecin, "numeroAdeli", $mbMediuser->adeli);
    $identification = $this->addElement($medecin, "identification");
    $this->addElement($identification, "code", "prat$mbMediuser->user_id");
    $this->addElement($identification, "libelle", $mbMediuser->_user_username);
    $personne = $this->addElement($medecin, "personne");
    $this->addElement($personne, "nomUsuel", $mbMediuser->_user_last_name);
    $prenoms = $this->addElement($personne, "prenoms");
    $this->addElement($prenoms, "prenom", $mbMediuser->_user_first_name);
    return $medecin;
  }
  
  function addActeCCAM($elParent, $mbActeCCAM, $mbOp) {        
    $acteCCAM = $this->addElement($elParent, "acteCCAM");
    $this->addAttribute($acteCCAM, "action", "cr�ation");
    $this->addAttribute($acteCCAM, "facturable", "oui");
    $this->addAttribute($acteCCAM, "valide", "oui");
    $this->addAttribute($acteCCAM, "documentaire", "non");
    $this->addAttribute($acteCCAM, "gratuit", "non");

    $identifiant = $this->addElement($acteCCAM, "identifiant");
    $emetteur = $this->addElement($identifiant, "emetteur", "acte{$mbOp->operation_id}-1");
    $this->addElement($acteCCAM, "codeActe", $mbActeCCAM->code_acte);
    $this->addElement($acteCCAM, "codeActivite", $mbActeCCAM->code_activite);
    $this->addElement($acteCCAM, "codePhase", $mbActeCCAM->code_phase);

    $execute = $this->addElement($acteCCAM, "execute");
    $this->addElement($execute, "date", $mbOp->_ref_plageop->date);

    $mbExecutant = $mbActeCCAM->_ref_executant;
    $executant = $this->addElement($acteCCAM, "executant");
    $medecins = $this->addElement($executant, "medecins");
    $medecinExecutant = $this->addElement($medecins, "medecinExecutant");
    $this->addAttribute($medecinExecutant, "principal", "oui");
    $this->addProfessionnelSante($medecinExecutant, $mbExecutant);
    $this->addUniteFonctionnelle($executant, $mbOp);
    
    $modificateurs = $this->addElement($acteCCAM, "modificateurs");
    foreach ($mbActeCCAM->_modificateurs as $mbModificateur) {
      $this->addElement($modificateurs, "modificateur", $mbModificateur);
    }
    
    $montant = $this->addElement($acteCCAM, "montant");
    if ($mbActeCCAM->montant_depassement > 0) {
      $montantDepassement = $this->addElement($montant, "montantDepassement", sprintf("%.2f", $mbActeCCAM->montant_depassement));
    }
    
    return $acteCCAM;
  }
  
  function purgeEmptyElements() {
    $this->purgeEmptyElementsNode($this->documentElement);
  }
  
  function purgeEmptyElementsNode($node) {
    // childNodes undefined for non-element nodes (eg text nodes)
    if ($node->childNodes) {
      // Copy childNodes array
      $childNodes = array();
      foreach($node->childNodes as $childNode) {
        $childNodes[] = $childNode;
      }
 
      // Browse with the copy (recursive call)    
      foreach ($childNodes as $childNode) {
        $this->purgeEmptyElementsNode($childNode);      
      }
			
      // Remove if empty
      if (!$node->hasChildNodes() && !$node->hasAttributes()) {
//        trigger_error("Removing child node $node->nodeName in parent node {$node->parentNode->nodeName}", E_USER_NOTICE);
        $node->parentNode->removeChild($node);
      }
		}
		
  }
}

?>
