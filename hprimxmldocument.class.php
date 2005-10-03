<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPinterop
* @version $Revision$
* @author Thomas Despoix
*/

global $AppUI, $m;

require_once($AppUI->getModuleClass("dPinterop", "mbxmldocument"));

class CHPrimXMLDocument extends CMbXMLDocument {
  function __construct() {
    parent::__construct();
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
    
  function addUniteFonctionnelle($elParent, CFunctions $mbFunction) {
    $this->addCodeLibelle($elParent, "uniteFonctionnelle", "Func$mbFunction->function_id", $mbFunction->_view);
  }
  
  function addProfessionnelSante($elParent, CMediusers $mbMediuser) {
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
  
  function addActeCCAM($elParent, CCodeCCAM $mbCodeCCAM, COperation $mbOp) {
    if (!$mbCodeCCAM->code) {
      return null;
		}
    
    $acteCCAM = $this->addElement($elParent, "acteCCAM");
    $this->addAttribute($acteCCAM, "action", "création");
    $this->addAttribute($acteCCAM, "facturable", "oui");
    $this->addAttribute($acteCCAM, "valide", "oui");
    $this->addAttribute($acteCCAM, "documentaire", "non");
    $this->addAttribute($acteCCAM, "gratuit", "non");

    $identifiant = $this->addElement($acteCCAM, "identifiant");
    $emetteur = $this->addElement($identifiant, "emetteur", "acte{$mbOp->operation_id}-1");
    $this->addElement($acteCCAM, "codeActe", $mbCodeCCAM->code);
    $this->addElement($acteCCAM, "codeActivite", "1");
    $this->addElement($acteCCAM, "codePhase", "0");

    $execute = $this->addElement($acteCCAM, "execute");
    $this->addElement($execute, "date", $mbOp->_ref_plageop->date);

    $mbChir = $mbOp->_ref_chir;
    $executant = $this->addElement($acteCCAM, "executant");
    $medecins = $this->addElement($executant, "medecins");
    $medecinExecutant = $this->addElement($medecins, "medecinExecutant");
    $this->addAttribute($medecinExecutant, "principal", "oui");
    $this->addProfessionnelSante($medecinExecutant, $mbChir);
    $this->addUniteFonctionnelle($executant, $mbChir->_ref_function);
    
    $modificateurs = $this->addElement($acteCCAM, "modificateurs");
    $this->addElement($modificateurs, "modificateur", "B");
    
    $montant = $this->addElement($acteCCAM, "montant");
    $montantDepassement = $this->addElement($montant, "montantDepassement", "150.00");
    
    return $acteCCAM;
  }
  
  function purgeEmptyElements() {
    $this->purgeEmptyElementsNode($this->documentElement);
  }
  
  function purgeEmptyElementsNode(DOMNode $node) {
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
