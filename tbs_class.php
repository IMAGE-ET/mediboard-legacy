<?php
/*
********************************************************
		TinyButStrong 1.95.1
		Template Engine for Pro and Beginners
------------------------
Web site : www.tinybutstrong.com
Author   : skrol29@freesurf.fr
********************************************************

This library is free software.
You can redistribute and modify it even for commercial usage,
but you must accept and respect the LPGL License (v2.1 or later).
*/

//You can change the TinyButStrong markers in you code.
if (!isset($tbs_ChrOpen)) $tbs_ChrOpen = '[' ;
if (!isset($tbs_ChrClose)) $tbs_ChrClose = ']' ;
if (!isset($tbs_CacheMask)) $tbs_CacheMask = 'cache_tbs_*.php' ;

// Render flags.
define('TBS_NOTHING', 0) ;
define('TBS_OUTPUT', 1) ;
define('TBS_EXIT', 2) ;
// Special cache actions.
define('TBS_DELETE', -1) ;
define('TBS_CANCEL', -2) ;
define('TBS_CACHENOW', -3) ;

//Check PHP version
if (PHP_VERSION<'4.0.4') tbs_Misc_Alert('PHP Version Check','Your PHP version is '.PHP_VERSION.' while TinyButStrong needs PHP version '.$_tbs_VersionMin.' or higher.' ) ;

//Init common variables
$tbs_CurrVal = '' ;
$tbs_CurrRec = array() ;
$tbs_CurrNav = False ;

$_tbs_FrmMultiLst = array() ;
$_tbs_FrmSimpleLst = array() ;
$_tbs_PhpVarLst = False ;
$_tbs_Timer = tbs_Misc_Timer() ;
$_tbs_False = False ;
tbs_Misc_ActualizeChr() ;


//Classes
class clsTbsLocator {
	var $PosBeg = False ;
	var $PosEnd = False ;
	var $FullName = False ;
	var $SubName = False ;
	var $PrmLst = array() ;
	var $PrmPos = False ;
	var $BlockNbr = 0 ;
	var $BlockLst = False ;
}

class clsTinyButStrong {
	//Public properties
	var $Source = '' ; //Current result of the merged template
	var $Render = 1 ;  //Make the output but don't exit to let dP end the html file
	var $HtmlCharSet = '' ;
	//Private properties
	var $_Version = '1.95.1';
	var $_LastFile = '' ; //The last loaded template file
	var $_StartMerge = 0 ;
	var $_Timer = False ; //True if a system field about time has been found in the template
	var $_CacheFile = False ; // The name of the file to save the content in.
	var $_DebugTxt = '' ;
	//Public methods
	function LoadTemplate($File,$HtmlCharSet='') {
		$this->_StartMerge = tbs_Misc_Timer() ;
		tbs_Misc_ActualizeChr() ;
		//Load the file
		if (tbs_Misc_GetFile($this->Source,$File)===False) {
			tbs_Misc_Alert('LoadTemplate','Unable to read the file \''.$File.'\'.') ;
			return False ;
		}

		$this->_LastFile = $File ;
		//CharSet
		if ($HtmlCharSet==-1) {
			$this->HtmlCharSet = '' ;
			$Pos = 0 ;
			do {
				$Loc = tbs_Html_FindTag($this->Source,'META',True,$Pos,True,1,True) ;
				if ($Loc!==False) {
					$Pos = $Loc->PosEnd + 1 ;
					if (isset($Loc->PrmLst['http-equiv'])) {
						if (strtolower($Loc->PrmLst['http-equiv'])==='content-type') {
							if (isset($Loc->PrmLst['content'])) {
								$x = ';'.strtolower($Loc->PrmLst['content']).';' ;
								$x = str_replace(' ','',$x) ;
								$p = strpos($x,';charset=') ;
								if ($p!==False) {
									$x = substr($x,$p+strlen(';charset=')) ;
									$p = strpos($x,';') ;
									if ($p!==False) $x = substr($x,0,$p) ;
									$this->HtmlCharSet = $x ;
									$Loc = False ;
								}
							}
						}
					}
				}
			} while ($Loc!==False) ;
		} else {
			$this->HtmlCharSet = $HtmlCharSet ;
		}
		//Include files
		tbs_Misc_ClearPhpVarLst() ;
		tbs_Merge_Auto($this->Source,$this->HtmlCharSet,True) ;
		return True ;
	}
	function GetBlockSource($BlockName,$List=False) {
		$BlockLoc = tbs_Locator_FindBlockLst($this->Source,$BlockName,0) ;
		if ($BlockLoc->DefFound===False) {
			if ($List) {
				return False ;
			} else {
				return array() ;
			}
		} else {
			if ($List) {
				return $BlockLoc->BlockLst ;
			} else {
				return $BlockLoc->BlockLst[1] ;
			}
		}
	}
	function MergeBlock($BlockName,$SrcId,$Query='',$PageSize=0,$PageNum=0,$RecKnown=0) {
		return tbs_Merge_Block($this->Source,$this->HtmlCharSet,$BlockName,$SrcId,$Query,$PageSize,$PageNum,$RecKnown) ;
	}
	function MergeField($Name,$Value) {
		tbs_Misc_ClearPhpVarLst() ; //Usefull here because the field can have an file inclusion
		tbs_Merge_Field($this->Source,$this->HtmlCharSet,$Name,$Value,True,True) ;
	}
	function MergeSpecial($Type) {
		$Type = strtolower($Type) ;
		tbs_Misc_ClearPhpVarLst() ;
		tbs_Merge_Special($this,$Type) ;
	}
	function MergeNavigationBar($BlockName,$Options,$PageCurr,$RecCnt=-1,$RecByPage=1) {
		return tbs_Merge_NavigationBar($this->Source,$this->HtmlCharSet,$BlockName,$Options,$PageCurr,$RecCnt,$RecByPage) ;
	}
	function Show($End='',$MergePhpVar=True,$Output='') {
		//Those parameters are only for comaptibility
		//Now you must use the ->Render property
		tbs_Misc_ClearPhpVarLst() ;

		if ($MergePhpVar) {
			tbs_Merge_Special($this,'include,include.onshow,var,sys,check,timer') ;
		} else {
			tbs_Merge_Special($this,'include,include.onshow,sys,check,timer') ;
		}

		if ($this->_DebugTxt!=='') $this->Source = $this->_DebugTxt.$this->Source ;

		if ($this->_CacheFile!==False) {
			tbs_Cache_Save($this->_CacheFile,$this->Source) ;
		}

		if ($Output==='') {
			if (($this->Render & TBS_OUTPUT) == TBS_OUTPUT) echo $this->Source ;
		} elseif ($Output) {
			echo $this->Source ;
		}

		if ($End==='') {
			if (($this->Render & TBS_EXIT) == TBS_EXIT) exit ;
		} elseif ($End) {
			exit ;
		}

	}
	function CacheAction($CacheId,$TimeOut=3600,$Dir='') {

		global $tbs_CacheMask;

		$CacheId = trim($CacheId) ;
		$Res = False ;

		if ($TimeOut === TBS_CANCEL) { //Cancel cache save if any
			$this->_CacheFile = False ;
		} elseif ($CacheId === '*') {
			if ($TimeOut === TBS_DELETE) $Res = tbs_Cache_DeleteAll($Dir,$tbs_CacheMask) ;
		} else {
			$CacheFile = tbs_Cache_File($Dir,$CacheId,$tbs_CacheMask) ;
			if ($TimeOut === TBS_CACHENOW) {
				tbs_Cache_Save($CacheFile,$this->Source) ;
			} elseif ($TimeOut === TBS_DELETE) {
				if (file_exists($CacheFile)) @unlink($CacheFile) ;
			} elseif($TimeOut>=0) {
				$Res = tbs_Cache_IsValide($CacheFile,$TimeOut) ;
				if ($Res) { //Load the cache
					$this->_CacheFile = False ;
					if (tbs_Misc_GetFile($this->Source,$CacheFile)) {
						if (($this->Render & TBS_OUTPUT) == TBS_OUTPUT) echo $this->Source ;
						if (($this->Render & TBS_EXIT) == TBS_EXIT) Exit ;
					} else {
						tbs_Misc_Alert('CacheAction','Unable to read the file \''.$CacheFile.'\'.') ;
						$Res==False ;
					}
				} else {
					//The result will be saved in the cache when the Show() method is called
					$this->_CacheFile = $CacheFile ;
					@touch($CacheFile);
				}
			}
		}

		return $Res ;

	}
	//Hidden functions
	function DebugPrint($Txt) {
		if ($Txt===False) {
			$this->_DebugTxt = '' ;
		} else {
			$this->_DebugTxt .= 'Debug: '.htmlentities($Txt).'<br>' ;
		}
	}
	function DebugLocator($Name) {
		$this->_DebugTxt .= tbs_Misc_DebugLocator($this->Source,$Name) ;
	}
	//Only for compatibility
	function MergePHPVar() {
		tbs_Merge_Special($this->Source,'var',True) ;
	}
}

//*******************************************************

//Find a TBS Field-Merge
function tbs_Locator_FindTbs(&$Txt,$Name,$Pos,$AcceptSub) {

	$PosOpen = $Pos - 1 ;
	$PosEnd = False ;

	do {

		//Search for the opening char
		$PosOpen = strpos($Txt,$GLOBALS['tbs_ChrOpen'],$PosOpen + 1) ;

		//If found => the next char are analyzed
		if ($PosOpen!==False) {
			//Look if what is next the begin char is the name of the locator
			if (strcasecmp(substr($Txt,$PosOpen+1,strlen($Name)),$Name)===0) {

				$Loc = new clsTbsLocator ;

				//Then we check if what is next the name of the merge is an expected char
				$ReadPrm = False ;
				$PosX = $PosOpen + 1 + strlen($Name) ;
				$x = $Txt[$PosX] ;

				if ($x===$GLOBALS['tbs_ChrClose']) {
					$PosEnd = $PosX ;
				} elseif ($AcceptSub and ($x==='.')) {
					$Loc->SubName = '' ; //it is no longer the false value
					$ReadPrm = True ;
					$PosX++ ;
				} elseif (strpos(';',$x)!==False) {
					$ReadPrm = True ;
					$PosX++ ;
				}

				if ($ReadPrm) {
					//Read the Parameters
					tbs_Locator_ReadPrm($Txt,$PosX,';','= ','\'','([{',')]}',$GLOBALS['tbs_ChrClose'],0,$Loc,$PosEnd) ;
					if (isset($Loc->PrmLst['comm'])) { //Enlarge the limits to the comentary bounds.
						tbs_Locator_EnlargeToStr($Txt, $PosOpen, $PosEnd, '<!--' ,'-->') ;
					}
				}

			}
		}

	} while ( ($PosEnd===False) and ($PosOpen!==False) ) ;

	if ($PosEnd===False) {
		return False ;
	} else {
		$Loc->PosBeg = $PosOpen ;
		$Loc->PosEnd = $PosEnd ;
		if ($Loc->SubName===False) {
			$Loc->FullName = $Name ;
		} else {
			$Loc->FullName = $Name.'.'.$Loc->SubName ;
		}
		return $Loc ;
	}

}

//This function reads the parameters that follow the Begin Position and returns the parmeters in an array
function tbs_Locator_ReadPrm(&$Txt,$Pos,$ChrsPrm,$ChrsEqu,$ChrsStr,$ChrsOpen,$ChrsClose,$ChrEnd,$LenMax,&$Loc,&$PosEnd) {
//$Pos       : position in $Txt where the scan begins
//$ChrsPrm   : a string that contains all characters that can be a parameter separator (typically : space and ;)
//$ChrsEqu   : a string that contains all characters that can be a equale symbole (used to get prm value )
//$ChrsStr   : a string that contains all characters that can be a string delimiters (typically : ' and ")
//$ChrsOpen  : a string that contains all characters that can be an opening bracket (typically : ( )
//$ChrsClose : a string that contains all characters that can be an closing bracket (typically : ( )
//$ChrEnd    : the character that marks the end of the parameters list.
//$LenMax    : the maximum of characters to read (enable to not read all the dicument when the parameters list has an unvalide syntaxe).
//$Loc       : the current TBS locatore
//Return values :
//$PosEnd  : the position of the $ChrEnd in the $Txt string

	// variables initalisation
	$PosCur = $Pos ;         // The cursor position
	$PosBuff = True ;           // True if the current char has to be added to the buffer
	$PosEnd = False ;          // True if the end char has been met
	$PosMax = strlen($Txt)-1 ; // The max position that the cursor can go
	if ($LenMax>0) {
		if ($PosMax>$PosDeb+$LenMax) {
			$PosMax = $PosDeb+$LenMax ;
		}
	}

	$PrmNbr = 0 ;
	$PrmName = '' ;
	$PrmBuff = '' ;
	$PrmPosBeg = False ;
	$PrmPosEnd = False ;
	$PrmEnd  = False ;
	$PrmPosEqu  = False ;     //Position of the first equal symbole
	$PrmChrEqu  = '' ;    //Position of the first equal symbole
	$PrmCntOpen = 0 ;     //Number of bracket inclusion. 0 means no bracket encapuslation.
	$PrmIdxOpen = False ; //Index of the current opening bracket in the $ChrsOpen array. False means we are not inside a bracket.
	$PrmCntStr = 0 ;      //Number of string delimiter found.
	$PrmIdxStr = False ;  //Index of the current string delimiter. False means we are not inside a string.
	$PrmIdxStr1 = False ; //Save the first string delimiter found.

	do {

		if ($PosCur>$PosMax) return ;

		if ($PrmIdxStr===False) {

			// we are not inside a string, we check if it's the begining of a new string
			$PrmIdxStr = strpos($ChrsStr,$Txt[$PosCur]) ;

			if ($PrmIdxStr===False) {
				//we are not inside a string, we check if we are not inside brackets
				if ($PrmCntOpen===0) {
					//we are not inside brackets
					if ($Txt[$PosCur]===$ChrEnd) {//we check if it's the end of the parameters list
						$PosEnd = $PosCur ;
						$PrmEnd = True ;
						$PosBuff = False ;
					} elseif (strpos($ChrsEqu,$Txt[$PosCur])!==False) { //we check if it's an equale symbole
							if ($PrmPosEqu===False) {
							if (trim($PrmBuff)!=='') {
								$PrmPosEqu = $PosCur ;
								$PrmChrEqu = $Txt[$PosCur] ;
							}
						} elseif ($PrmChrEqu===' ') {
							if ($PosCur==$PrmPosEqu+1) {
								$PrmPosEqu = $PosCur ;
								$PrmChrEqu = $Txt[$PosCur] ;
							}
						}
					} elseif (strpos($ChrsPrm,$Txt[$PosCur])!==False) { //we check if it's a parameter separator
						$PosBuff = False ;
						if ($Txt[$PosCur]===' ') {//The space char can be a parameter separator only in HTML locators
							if ($PrmBuff!=='') {
								$PrmEnd = True ;
							}
						} else { //-> if ($Txt[$PosCur]===' ') {...
							//We have a ';' separator
							$PrmEnd = True ;
						}
					} else {
						//check if it's an opening bracket
						$PrmIdxOpen = strpos($ChrsOpen,$Txt[$PosCur]) ;
						if ($PrmIdxOpen!==False) {
							$PrmCntOpen++ ;
						}
					}
				} else { //--> if ($PrmCntOpen==0)
					//we are inside brackets, we have to check if there is another opening bracket or a closing bracket
					if ($Txt[$PosCur]===$ChrsOpen[$PrmIdxOpen]) {
						$PrmCntOpen++ ;
					} elseif ($Txt[$PosCur]===$ChrsClose[$PrmIdxOpen]) {
						$PrmCntOpen-- ;
					}
				}
			} else { //--> if ($IdxStr===False)
				//we meet a new string
				$PrmCntStr++ ; //count the number of string delimiter meet for the current parameter
				if ($PrmCntStr===1) $PrmIdxStr1=$PrmIdxStr ; //save the first delimiter for the current parameter
			} //--> if ($IdxStr===False)

		} else { //--> if ($IdxStr===False)

			//we are inside a string,

			if ($Txt[$PosCur]===$ChrsStr[$PrmIdxStr]) {//we check if we are on a char delimiter
				if ($PosCur===$PosMax) {
					$PrmIdxStr = False ;
				} else {
					//we check if the next char is also a string delimiter, is it's so, the string continue
					if ($Txt[$PosCur+1]===$ChrsStr[$PrmIdxStr]) {
						$PosCur++ ; // the string continue
					} else {
						$PrmIdxStr = False ; //the string ends
					}
				}
			}

		} //--> if ($IdxStr===False)

		//Check if it's the end of the scan
		if ($PosEnd===False) {
			if ($PosCur>=$PosMax) {
				$PosEnd = $PosCur ; //end of the scan
				$PrmEnd = True ;
			}
		}

		//Add the current char to the buffer
		if ($PosBuff) {
			$PrmBuff .= $Txt[$PosCur] ;
			if ($PrmPosBeg===False) $PrmPosBeg = $PosCur ;
			$PrmPosEnd = $PosCur ;
		} else {
			$PosBuff = True ;
		}

		//analyze the current parameter
		if ($PrmEnd===True) {
			if (strlen($PrmBuff)>0) {
				if ( ($PrmNbr===0) and ($Loc->SubName!==False) ) {
					//Set the SubName value
					$Loc->SubName = $PrmBuff ;
					$PrmEquMode = 0 ;
				} else {
					if ($PrmPosEqu===False) {
						$PrmName = trim($PrmBuff) ;
						$PrmBuff = True ;
					} else {
						$PrmName = trim(substr($PrmBuff,0,$PrmPosEqu-$PrmPosBeg)) ;
						$PrmBuff = trim(substr($PrmBuff,$PrmPosEqu-$PrmPosBeg+1)) ;
						if ($PrmCntStr===1) tbs_Misc_DelDelimiter($PrmBuff,$ChrsStr[$PrmIdxStr1]) ;
					}
					$Loc->PrmLst[$PrmName] = $PrmBuff ;
				}
				$PrmNbr++ ; // Usefulle for subname identification
				$PrmBuff = '' ;
				$PrmPosBeg = False ;
				$PrmCntStr = 0 ;
				$PrmCntOpen = 0 ;
				$PrmIdxStr = False ;
				$PrmIdxOpen = False ;
				$PrmPosEqu = False ;
			}
			$PrmEnd  = False ;
		}

		// next char
		$PosCur++ ;

	} while ($PosEnd===False) ;

}

//This function enables to enlarge the pos limits of the Locator.
//If the search result is not correct, $PosBeg must not change its value, and $PosEnd must be False.
//This is because of the calling function.
function tbs_Locator_EnlargeToStr(&$Txt,&$PosBeg,&$PosEnd,$StrBeg,$StrEnd) {

	if ($PosEnd===False) {
		return ;
	}

	//Searche for the begining string
	$Pos = $PosBeg ;
	$Ok = False ;
	do {
		$Pos = strrpos(substr($Txt,0,$Pos),$StrBeg[0]) ;
		if ($Pos!==False) {
			if (substr($Txt,$Pos,strlen($StrBeg))===$StrBeg) $Ok = True ;
		}
	} while ( (!$Ok) and ($Pos!==False) );

	if ($Ok===False) {
		$PosEnd = False ;
	} else {
		//Search for the endinf string
		$PosEnd = strpos($Txt,$StrEnd,$PosEnd + 1) ;
		if ($PosEnd!==False) {
			$PosBeg = $Pos ;
			$PosEnd = $PosEnd + strlen($StrEnd) - 1 ;
		}
	}

}

function tbs_Locator_EnlargeToTag(&$Txt,&$Loc,$Tag,$MakeBlock,$Encaps,$Extend,$ReturnSrc) {

	if ($Tag==='') return False ;
	if ($Tag==='row') $Tag = 'tr' ;
	if ($Tag==='opt') $Tag = 'option' ;

	$Ok = False ;

	$TagO = tbs_Html_FindTag($Txt,$Tag,True,$Loc->PosBeg-1,False,$Encaps,False) ;
	if ($TagO!==False) {
		//Search for the closing tag
		$TagC = tbs_Html_FindTag($Txt,$Tag,False,$Loc->PosEnd+1,True,$Encaps,False) ;
		if ($TagC!==False) {
			//It's ok, we get the text string between the locators (including the locators !!)
			$Ok = True ;
			$PosBeg = $TagO->PosBeg ;
			$PosEnd = $TagC->PosEnd ;

			//Extend
			if ($Extend===0) {
				if ($ReturnSrc) {
					$Ok = '' ;
					if ($Loc->PosBeg>$TagO->PosEnd) $Ok .= substr($Txt,$TagO->PosEnd+1,min($Loc->PosBeg,$TagC->PosBeg)-$TagO->PosEnd-1) ;
					if ($Loc->PosEnd<$TagC->PosBeg) $Ok .= substr($Txt,max($Loc->PosEnd,$TagO->PosEnd)+1,$TagC->PosBeg-max($Loc->PosEnd,$TagO->PosEnd)-1) ;
				}
			} else { //Forward
				$TagC = True ;
				for ($i=$Extend;$i>0;$i--) {
					if ($TagC!==False) {
						$TagO = tbs_Html_FindTag($Txt,$Tag,True,$PosEnd+1,True,1,False) ;
						if ($TagO!==False) {
							$TagC = tbs_Html_FindTag($Txt,$Tag,False,$TagO->PosEnd+1,True,0,False) ;
							if ($TagC!==False) {
								$PosEnd = $TagC->PosEnd ;
							}
						}
					}
				}
				$TagO = True ;
				for ($i=$Extend;$i<0;$i++) { //Backward
					if ($TagO!==False) {
						$TagC = tbs_Html_FindTag($Txt,$Tag,False,$PosBeg-1,False,1,False) ;
						if ($TagC!==False) {
							$TagO = tbs_Html_FindTag($Txt,$Tag,True,$TagC->PosBeg-1,False,0,False) ;
							if ($TagO!==False) {
								$PosBeg = $TagO->PosBeg ;
							}
						}
					}
				}
			} //-> if ($Extend!==0) {
			if ($MakeBlock) {
				if ($Loc->SubName===False) {
					//If there is no subname then it's an relative syntax -> we delete the block definition
					$x = substr($Txt,$PosBeg,$Loc->PosBeg - $PosBeg) ;
					$x = $x . substr($Txt,$Loc->PosEnd+1,$PosEnd - $Loc->PosEnd) ;
				} else {
					//If there is a subname then it's a simplified syntax -> we let the field-locator.
					$x = substr($Txt,$PosBeg,$PosEnd - $PosBeg + 1) ;
				}
				$Loc->BlockNbr = 1 ; //Type=Block
				$Loc->BlockLst[1] = $x ;
			}
			$Loc->PosBeg = $PosBeg ;
			$Loc->PosEnd = $PosEnd ;
		}
	}

	return $Ok ;

}

//This function enables to merge a locator with a text and returns the position just after the replaced block
//This position can be usefull because we don't know in advance how $Value will be replaced.
function tbs_Locator_Replace(&$Txt,&$HtmlCharSet,&$Loc,&$Value,$CheckSub) {

	//Found the value if there is a subname
	if ($CheckSub and ($Loc->SubName!==False)) {
		$SubLst = explode('.',$Loc->SubName) ;
		$SubId = 0 ;
		$SubNbr = count($SubLst) ;
		while ($SubId<$SubNbr) {
			if (is_array($Value)) {
				if (isset($Value[$SubLst[$SubId]])) {
					$Value = &$Value[$SubLst[$SubId]] ;
				} else {
					unset($Value) ; $Value = '' ;
					if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('Array value','Can\'t merge ['.$Loc->FullName.'] because there is no key named \''.$SubLst[$SubId].'\'.',True) ;
				}
				$SubId++ ;
			} else {
				if (isset($Loc->PrmLst['selected'])) {
					$SelArray = &$Value ;
				} else {
					if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('Array value expected','Can\'t merge ['.$Loc->FullName.'] because the value before the key \''.$SubLst[$SubId].'\' is not an array.',True) ;
				}
				unset($Value) ; $Value = '' ;
				$SubId = $SubNbr ;
			}
		}
	}

	$CurrValSave = &$GLOBALS['tbs_CurrVal'] ;
	$CurrVal = $Value ;
	$GLOBALS['tbs_CurrVal'] = &$CurrVal ;

	$Select = False ;	

	if ($Loc->BlockNbr==0) { //Type=field

		$HtmlConv = True ;
		$BrConv = True ; //True if we have to convert nl to br with Html conv.
		$WhiteSp = False ; //True if we have to preserve whitespaces
		$EmbedVal = False ; //Value to embed in the current val
		$Script = True ; //False to ignore script execution
		$Protect = True ; //Default value for common field

		//File inclusion
		if (isset($Loc->PrmLst['file'])) {
			$File = $Loc->PrmLst['file'] ;
			tbs_Misc_ReplaceVal($File,$CurrVal) ;
			tbs_Merge_PhpVar($File,$GLOBALS['_tbs_False']) ; //The file definition may contains PHPVar field
			$OnlyBody = True ;
			if (isset($Loc->PrmLst['htmlconv'])) {
				if (strtolower($Loc->PrmLst['htmlconv'])==='no') {
					$OnlyBody = False ; //It's a text file, we don't get the BODY part
				}
			}
			if (tbs_Misc_GetFile($CurrVal,$File)===False) {
				if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('\'file\' Parameter','Can\'t process the \'file\' parameter in the field ['.$Loc->FullName.']. Unable to read the file \''.$CurrVal.'\'.',True) ;
			}
			if ($OnlyBody) $CurrVal = tbs_Html_GetPart($CurrVal,'BODY',False,True) ;
			$HtmlConv = False ;
			$Protect = False ; //Default value for file inclusion
		}

		//OnFormat event
		if (isset($Loc->PrmLst['onformat'])) {
			$OnFormat = $Loc->PrmLst['onformat'] ;
			if (function_exists($OnFormat)) {
				$OnFormat($Loc->FullName,$CurrVal) ;
			} else {
				if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('\'onformat\' Parameter','The function \''.$OnFormat.'\' specified in the field \''.$Loc->FullName.'\' doesn\'t exist.',True) ;
			}
		}

		//Select a value in a HTML option list
		if (isset($Loc->PrmLst['selected'])) {
			$Select = True ;
			if (is_array($CurrVal)) {
				$SelArray = &$CurrVal ;
				unset($CurrVal) ; $CurrVal = ' ' ;
			} else {
				$SelArray = False ;
			}
		}

		//Convert the value to a string, use format if specified
		if (isset($Loc->PrmLst['frm'])) {
			$CurrVal = tbs_Misc_Format($Loc,$CurrVal) ;
			$HtmlConv = False ;
		} else {
			if (!is_string($CurrVal)) $CurrVal = strval($CurrVal) ;
		}

		//case of an 'if' 'then' 'else' options
		if (isset($Loc->PrmLst['if'])) {
			tbs_Misc_ReplaceVal($Loc->PrmLst['if'],$CurrVal) ;
			if (tbs_Misc_CheckCondition($Loc->PrmLst['if'])===True) {
				if (isset($Loc->PrmLst['then'])) {
					$EmbedVal = $CurrVal ;
					$CurrVal = $Loc->PrmLst['then'] ;
				} //else -> it's the given value
			} else {
				$Script = False ;
				if (isset($Loc->PrmLst['else'])) {
					$EmbedVal = $CurrVal ;
					$CurrVal = $Loc->PrmLst['else'] ;
				} else {
					$CurrVal = '' ;
					$Protect = False ; //Only because it is empty
				}
			}
		}

		if ($Script) {//Include external PHP script
			if (isset($Loc->PrmLst['script'])) {
				$File = $Loc->PrmLst['script'] ;
				tbs_Misc_ReplaceVal($File,$CurrVal) ;
				tbs_Merge_PhpVar($File,$GLOBALS['_tbs_False']) ; //The file definition may contains PHPVar field
				if (isset($Loc->PrmLst['getob'])) ob_start() ;
				$tbs_CurrVal = &$CurrVal ; //For compatibility with TBS<1.90. The included script uses local variable and $tbs_CurrVal was told to be available.
				if (isset($Loc->PrmLst['once'])) {
					include_once($File) ;
				} else {
					include($File) ;
				}
				if (isset($Loc->PrmLst['getob'])) {
					$CurrVal = ob_get_contents() ;
					ob_end_clean() ;
				}
				$HtmlConv = False ;
			}
		}

		//Check HtmlConv parameter
		if (isset($Loc->PrmLst['htmlconv'])) {
			$x = strtolower($Loc->PrmLst['htmlconv']) ;
			$x = '+'.str_replace(' ','',$x).'+' ;
			if (strpos($x,'+no+')!==False) $HtmlConv = False ;
			if (strpos($x,'+yes+')!==False) $HtmlConv = True ;
			if (strpos($x,'+nobr+')!==False) { $HtmlConv = True ; $BrConv = False ; }
			if (strpos($x,'+esc+')!==False) { $HtmlConv = False ; $CurrVal = str_replace('\'','\'\'',$CurrVal) ; }
			if (strpos($x,'+wsp+')!==False) $WhiteSp = True ;
			if (strpos($x,'+look+')!==False) {
				if (tbs_Html_IsHtml($CurrVal)) {
					$HtmlConv = False ;
					$CurrVal = tbs_Html_GetPart($CurrVal,'BODY',False,True) ;
				} else {
					$HtmlConv = True ;
				}
			}
		} else {
			if ($HtmlCharSet===False) $HtmlConv = False ; //No HTML
		}

		//MaxLength
		if (isset($Loc->PrmLst['max'])) {
			$x = intval($Loc->PrmLst['max']) ;
			if (strlen($CurrVal)>$x) {
				if ($HtmlConv or ($HtmlCharSet===false)) {
					$CurrVal = substr($CurrVal,0,$x-1).'...' ;
				} else {
					tbs_Html_Max($CurrVal,$x) ;
				}
			}
		}

		//HTML conversion
		if ($HtmlConv) {
			tbs_Html_Conv($CurrVal,$HtmlCharSet,$BrConv,$WhiteSp) ;
			if ($EmbedVal!==False) tbs_Html_Conv($EmbedVal,$HtmlCharSet,$BrConv,$WhiteSp) ;
		}

		//We protect the data that does not come from the source of the template
		//Explicit Protect parameter
		if (isset($Loc->PrmLst['protect'])) {
			$x = strtolower($Loc->PrmLst['protect']) ;
			switch ($x) {
			case 'no' : $Protect = False ; break ;
			case 'yes': $Protect = True  ; break ;
			}
		}
		if ($Protect) {
			if ($EmbedVal===False) {
				$CurrVal = str_replace($GLOBALS['tbs_ChrOpen'],$GLOBALS['tbs_ChrProtect'],$CurrVal) ;
			} else {
				//We must not protec the data wich comes from the source of the template, only the embeded value
				$EmbedVal = str_replace($GLOBALS['tbs_ChrOpen'],$GLOBALS['tbs_ChrProtect'],$EmbedVal) ;
				tbs_Misc_ReplaceVal($CurrVal,$EmbedVal) ;
			}
		}

		//Case when it is an empty string
		if ($CurrVal==='') {
			if (isset($Loc->PrmLst['.'])) {
				$CurrVal = '&nbsp;' ; //Enables to avoid blanks in HTML tables
			} elseif (isset($Loc->PrmLst['ifempty'])) {
				$CurrVal = $Loc->PrmLst['ifempty'] ;
			}
		}

	} //-> if ($Loc->BlockNbr==0)

	//Friend option (for blocks and fields)
	if ($CurrVal==='') {
		if (isset($Loc->PrmLst['friendb'])) {
			$Loc2 = tbs_Html_FindTag($Txt,$Loc->PrmLst['friendb'],True,$Loc->PosBeg,False,1,False) ;
			if ($Loc2!==False) {
				$Loc->PosBeg = $Loc2->PosBeg ;
				if ($Loc->PosEnd<$Loc2->PosEnd) $Loc->PosEnd = $Loc2->PosEnd ;
			}
		}
		if (isset($Loc->PrmLst['frienda'])) {
			$Loc2 = tbs_Html_FindTag($Txt,$Loc->PrmLst['frienda'],True,$Loc->PosBeg,True,1,False) ;
			if ($Loc2!==False) $Loc->PosEnd = $Loc2->PosEnd ;
		}
		if (isset($Loc->PrmLst['friend'])) {
			tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['friend'],False,1,0,False) ;
		}
		if (isset($Loc->PrmLst['friend2'])) {
			$CurrVal = tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['friend2'],False,1,0,True) ;
		}
	}

	$Txt = substr_replace($Txt,$CurrVal,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1) ;
	$NewEnd = $Loc->PosBeg + strlen($CurrVal) ;

	if ($Select) tbs_Html_MergeItems($Txt,$Loc,$CurrVal,$SelArray,$NewEnd) ;

	$GLOBALS['tbs_CurrVal'] = &$CurrValSave ; //Restore saved value. This is uselfull for field inclusion.
	return $NewEnd ; // Returns the new end position of the field

}

//Search the next locator wich is a block definition.
function &tbs_Locator_FindBlockDef(&$Txt,$BlockName,$PosBeg,$OnlyType=False) {

	$Ok = False ;
	$Pos = $PosBeg ;

	do {

		$Loc = tbs_Locator_FindTbs($Txt,$BlockName,$Pos,True) ;

		if ($Loc!==False) {
			$Ok = True ;
			$Pos = $Loc->PosEnd + 1 ;
			//The locator is a field locator but it may contain a block definition
			if (isset($Loc->PrmLst['block'])) {
				$Loc->PrmLst['block'] = trim(strtolower($Loc->PrmLst['block'])) ;
				if ($OnlyType!==False) {
					if ($Loc->PrmLst['block']<>$OnlyType) $Ok = False ;
				}
			} else {
				$Ok = False ;
			}
		}

	} while ( ($Ok===False) and ($Loc!==False) ) ;

	if ($Ok) {
		return $Loc ;
	} else {
		return False ;
	}

}

//Return the first block locator object just after the PosBeg position
function &tbs_Locator_FindBlock1(&$Txt,$BlockName,$PosBeg) {

	$Ok = False ;
	$Loc = tbs_Locator_FindBlockDef($Txt,$BlockName,$PosBeg,False) ;

	if ($Loc!==False) {

		if ($Loc->PrmLst['block']==='begin') {
			//Case of a begin-end
			$Loc2 = tbs_Locator_FindBlockDef($Txt,$BlockName,$Loc->PosEnd,'end') ;
			if ($Loc2!==False) {
				//It's ok, we get the source between the locators (without the bound locators).
				$Ok = True ;
				$Loc->BlockNbr = 1 ;
				$Loc->BlockLst[1] = substr($Txt,$Loc->PosEnd+1,$Loc2->PosBeg - $Loc->PosEnd - 1) ;
				$Loc->PosEnd = $Loc2->PosEnd ;
			}
		} else {
			if (isset($Loc->PrmLst['encaps'])) {
				$Encaps = abs(intval($Loc->PrmLst['encaps'])) ;
			} else {
				$Encaps = 1 ;
				}
			if (isset($Loc->PrmLst['extend'])) {
				$Extend = intval($Loc->PrmLst['extend']) ;
			} else {
				$Extend = 0 ;
			}
			$Ok = tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['block'],True,$Encaps,$Extend,False) ;
			if ($Ok===False) $Loc = False ;
		}

	}

	if ($Ok===False) {
		return False ;
	} else {
		return $Loc ;
	}

}

//Returns a locator object which points on a block wich covers all the block definitions, and contains all the text to merge
//Returns a locator even if there is no block definition found.
function &tbs_Locator_FindBlockLst(&$Txt,$BlockName,$Pos) {

	$LocR = new clsTbsLocator ;
	$LocR->DefFound = false ;
	$LocR->BlockNbr = 0 ;
	$LocR->HeaderNbr = 0 ;
	$LocR->FooterNbr = 0 ;
	$LocR->Serial = array() ;
	$LocR->EmptySrc = false ;
	$LocR->PrmLst = array() ;
	$LocR->NoDataSrc = false ;
	$LocR->SpecialSrc = false ;

	do {
		$Loc = tbs_Locator_FindBlock1($Txt,$BlockName,$Pos) ;
		if ($Loc!==False) {
			if (($LocR->DefFound!==False) and isset($Loc->PrmLst['p1'])) {
				$Loc = False ; //Stop the list feed
			} else {
					$Pos = $Loc->PosEnd + 1 ;
					//Define the block limits
					if ($LocR->DefFound===False) {
						$LocR->DefFound = True ;
						$LocR->PosBeg = $Loc->PosBeg ;
						$LocR->PosEnd = $Loc->PosEnd ;
					} else {
						if ( $LocR->PosBeg > $Loc->PosBeg ) $LocR->PosBeg = $Loc->PosBeg ;
						if ( $LocR->PosEnd < $Loc->PosEnd ) $LocR->PosEnd = $Loc->PosEnd ;
					}
					//Merge block parameters
					if (count($Loc->PrmLst)>0) $LocR->PrmLst = array_merge($LocR->PrmLst,$Loc->PrmLst) ;
					//Add the text int the list of blocks
					if (isset($Loc->PrmLst['nodata'])) {
						$LocR->NoDataSrc = $Loc->BlockLst[1] ;
					} elseif (isset($Loc->PrmLst['currpage'])) {
						$LocR->SpecialSrc = $Loc->BlockLst[1] ;
					} elseif (isset($Loc->PrmLst['headergrp'])) {
						$LocR->HeaderNbr++ ;
						$LocR->HeaderLst[$LocR->HeaderNbr] = array(0=>$Loc->BlockLst[1],1=>strtolower($Loc->PrmLst['headergrp']),2=>False) ;
					} else {
						$LocR->BlockNbr++ ;
						$LocR->BlockLst[$LocR->BlockNbr] = $Loc->BlockLst[1] ;
						$LocR->Serial[$LocR->BlockNbr] = isset($Loc->PrmLst['serial']) ;
						//Look for the empty sub-block definition
						if ($LocR->Serial[$LocR->BlockNbr]) {
							$LocSub = tbs_Locator_FindBlock1($Loc->BlockLst[1],$BlockName.'_0',0) ;
							if ($LocSub!==False) {
								$LocR->EmptySrc = $LocSub->BlockLst[1] ; //Save the empty sub-block source and delete it from its parent block source.
								$LocR->BlockLst[$LocR->BlockNbr] = substr($LocR->BlockLst[$LocR->BlockNbr],0,$LocSub->PosBeg).substr($LocR->BlockLst[$LocR->BlockNbr],$LocSub->PosEnd+1) ;
								$LocSub = tbs_Locator_FindBlock1($LocR->BlockLst[$LocR->BlockNbr],$BlockName.'_1',0) ;
								if ($LocSub===False) $LocR->BlockNbr-- ; //If no other sub-block then we delete the block from the list
							}
						}
					}
			}
		}
	} while ($Loc!==False) ;

	if ($LocR->DefFound===False) {
		$LocR->PosBeg = 0 ;
		$LocR->PosEnd = strlen($Txt) - 1 ;
		$LocR->BlockNbr = 1 ;
		$LocR->BlockLst[1] = $Txt ;
		$LocR->Serial[1] = False ;
	}

	return $LocR ;

}

//Merge all the occurences of a field-locator in the text string
//Returns the number of fields found.
function tbs_Merge_Field(&$Txt,&$HtmlCharSet,$Name,&$Value,$AcceptSub,$CheckSub) {

	$Nbr = 0 ;

	$PosBeg = 0 ;
	do {
		$Loc = tbs_Locator_FindTbs($Txt,$Name,$PosBeg,$AcceptSub) ;
		if ($Loc!==False) {
			$PosBeg = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$Value,$CheckSub) ;
			$Nbr++ ;
		}
	} while ($Loc!==False) ;

	return $Nbr ;

}

function tbs_Merge_Block(&$Txt,&$HtmlCharSet,&$BlockName,&$SrcId,$Query,$PageSize,$PageNum,$RecKnown) {

	$Pos = 0 ;
	$RowTot = 0 ;
	$Query0 = False ; //a not False value means they are parameters

	$CurrRecSave = &$GLOBALS['tbs_CurrRec'] ;
	$CurrRec = array() ;
	$GLOBALS['tbs_CurrRec'] = &$CurrRec ;

	//Get source type and info
	$SrcType = False ;
	$SrcSubType = False ;
	$RecSet = True ; //Must be true for first loop
	$RecInfo = False ;
	tbs_Data_Prepare($SrcId,$SrcType,$SrcSubType,$RecInfo) ;
	if ($SrcType===False) return 0 ;

	do {

		$RowNum = 0 ;  //Number of row merged
		$RowStop = 0 ; //Stop the merge after this row
		$RowSpe = 0 ;  //Row with a special block's definition (used for the navigation bar)
		$Groups = False ; //True if there is Hearder Group definitions
		$OnSection = False ;

		//Search the block
		$BlockLoc = tbs_Locator_FindBlockLst($Txt,$BlockName,$Pos) ;

		if ($BlockLoc->DefFound===False) {
			$RowStop = 1 ; //Merge only the first record
		} else {
			if ($BlockLoc->SpecialSrc!==False) $RowSpe = $RecKnown ;
			//Save the query definition
			if ($Query0===False) {
				if (isset($BlockLoc->PrmLst['p1'])) $Query0 = $Query ;
			}
		}

		//Replace parameters
		if ($Query0!==False) {
			if ($BlockLoc->DefFound===False) {
				$Query0 = False ; //End of the loop
				$RecSet = False ;
			} else {
				$Query = $Query0 ;
				$i = 1 ;
				do {
					$x = 'p'.$i ;
					if (isset($BlockLoc->PrmLst[$x])) {
						$Query = str_replace('%p'.$i.'%',$BlockLoc->PrmLst[$x],$Query) ;
						$i++ ;
					} else {
						$i = False ;
					}
				} while ($i!==False) ;
			}
		}

		//Open the recordset
		if ($RecSet!==False) {
			tbs_Data_Open($SrcId,$Query,$SrcType,$SrcSubType,$RecSet,$RecInfo) ;
			if ($RecSet===False) {
				$GLOBALS['tbs_CurrRec'] = &$CurrRecSave ;
				return $RowTot ;
			}
		}

		if ($RecSet!==False) {

				//Check for OnSection event
			if (isset($BlockLoc->PrmLst['onsection'])) {
				if (function_exists($BlockLoc->PrmLst['onsection'])) {
					$OnSection = $BlockLoc->PrmLst['onsection'] ;
				} else {
					tbs_Misc_Alert('MergeBlock','Unvalide value for the \'onsection\' parameter of the block The block ['.$BlockName.']. The function \''.$BlockLoc->PrmLst['onsection'].'\' is not found.') ;
				}
			}

			if ($SrcType===4) { //Special for Text merge
				if ($BlockLoc->DefFound===False) {
					tbs_Misc_Alert('MergeBlock','Can\'t merge the block \''.$BlockName.'\' with a text value because the block definition is not found.') ;
				} else {
					$RowNum = 1 ;
					$CurrRec = False ;
					if ($OnSection!==False) $OnSection($BlockName,$CurrRec,$RecSet,$RowNum) ;
					tbs_Locator_Replace($Txt,$GLOBALS['_tbs_False'],$BlockLoc,$RecSet,False) ;
				}
			} else { //Other data source type

				$CurrRec = array() ;

				//Manage pages
				if ($PageSize>0) {
					if ($PageNum>0) {
						//We pass all record until the asked page
						$RowStop = ($PageNum-1) * $PageSize ;
						while ($RowNum<$RowStop) {
							tbs_Data_Fetch($SrcType,$RecSet,$RecInfo,$CurrRec,$RowNum) ;
							if ($CurrRec===False) $RowStop=$RowNum ;	
						}
						if ($CurrRec!==False) {
							$RowStop = $PageNum * $PageSize ;
						}
					} else {
						if ($PageNum==-1) { //Goto end of the recordset
							//Read records, saving the last page in $x
							$i = 0 ;
							while ($CurrRec!==False) {
								tbs_Data_Fetch($SrcType,$RecSet,$RecInfo,$CurrRec,$RowNum) ;
								if ($CurrRec!==False) {
									$i++ ;
									if ($i>$PageSize) {
										$x = array() ;
										$i = 1 ;
									}
									$x[] = $CurrRec ;
								}
							}
							//Close the real recordset source
							tbs_Data_Close($SrcType,$RecSet,$RecInfo) ;
							//Open a new recordset on the array
							$SrcType = 0 ; //Array
							$SrcSubType = 0 ;
							tbs_Data_Open($x,$Query,$SrcType,$SrcSubType,$RecSet,$RecInfo) ;
							//Modify info in order to make the tbs_Data_Fetch() function work properly
							$RecInfo['count'] = $RowNum ;
							$RowNum = $RowNum - $i ;
							$CurrRec = array() ;
							$x = '' ;
						} else {
							$RowStop = 1 ;
							$PageCnt = 1 ;
						}
					}
				}

				if ($CurrRec!==False) tbs_Data_Fetch($SrcType,$RecSet,$RecInfo,$CurrRec,$RowNum) ;

				//Initialise
				if ($BlockLoc->HeaderNbr > 0) $Groups = True ;
				$BlockRes = '' ; // The result of the chained merged blocks
				$BlockSrc = '' ; // The current block source
				$i = 1 ;
				$SubId = 0 ;      //The current sub-block id (0 if no serial option)
				$SubDef = '' ;    //The current sub-block definition
				$SubLoc = False ; //The current sub-block locator

				//Main loop
				while($CurrRec!==False) {

					$CurrRec['#'] = $RowNum ;

					//Manage headers
					if ($Groups===True) {
						if ($RowNum===1) {
							$change = True ;
						} else {
							$change = False ;
						}
						for ($j=1 ; $j<=$BlockLoc->HeaderNbr ; $j++) {
							$val = $CurrRec[$BlockLoc->HeaderLst[$j][1]] ;
							if (!$change) {
								$change = !( $BlockLoc->HeaderLst[$j][2] === $val ) ;
							}
							if ($change) {
								$BlockSrc .= $BlockLoc->HeaderLst[$j][0] ;
								$BlockLoc->HeaderLst[$j][2] = $val ;
							}
						}
					}

					//Manage the detail section
					if ($BlockLoc->Serial[$i]===True) {
						$SubId++ ;
						$SubLoc = tbs_Locator_FindBlock1($BlockSrc,$BlockName.'_'.$SubId,0) ;
						if ($SubLoc===False) {
							//Next main-block definition
							$BlockRes .= $BlockSrc ;
							$BlockSrc = '' ;
							$SubId = 0 ;
							$i++ ;
							if ($i>$BlockLoc->BlockNbr) $i = 1 ;
						}
					}

					if ($BlockLoc->Serial[$i]===False) {
						//Classic merge
						$BlockSrc .= $BlockLoc->BlockLst[$i] ;
						if ($RowNum===$RowSpe) $BlockSrc = $BlockLoc->SpecialSrc ;
						if ($OnSection!==False) $OnSection($BlockName,$CurrRec,$BlockSrc,$RowNum) ;
						tbs_Merge_List($BlockSrc,$HtmlCharSet,$BlockName,$CurrRec) ; //we merge the fields
						$BlockRes .= $BlockSrc ; //We add the block to the serial
						$BlockSrc = '' ;
						$i++ ;
						if ($i>$BlockLoc->BlockNbr) $i = 1 ;
					} else {
						//Merge with serial
						if ($SubLoc===False) {//False => it's a new main-block definition
							$BlockSrc = $BlockLoc->BlockLst[$i] ;
							$SubId = 1 ;
							$SubLoc = tbs_Locator_FindBlock1($BlockSrc,$BlockName.'_1',0) ;
						}
						if ($SubLoc!==False) {
							$SubDef = $SubLoc->BlockLst[1] ;
							if ($RowNum===$RowSpe) $SubDef = $BlockLoc->SpecialSrc ;
							if ($OnSection!==False) $OnSection($BlockName,$CurrRec,$SubDef,$RowNum) ;
							tbs_Merge_List($SubDef,$HtmlCharSet,$BlockName.'_'.$SubId,$CurrRec) ; //we merge the fields
							tbs_Locator_Replace($BlockSrc,$GLOBALS['_tbs_False'],$SubLoc,$SubDef,False) ;
						}
					}

					//Next row
					if ($RowNum===$RowStop) {
						$CurrRec = False ;
					} else {
						if ($CurrRec!==False) { //$CurrRec can be set to False by the OnSection event function.
							tbs_Data_Fetch($SrcType,$RecSet,$RecInfo,$CurrRec,$RowNum) ;
						}
					}

				} //--> while($CurrRec!==False) {

				//Serial: merge the extra the sub-blocks
				if (($BlockLoc->Serial[$i]===True) and ($SubId!==0)) {
					$CurrRec = False ;
					$j = $RowNum ; //Enable to have the fictive number of record in a varibale to pass by reference to $OnSection.
					do {
						$SubId++ ;
						$j++ ;
						$SubLoc = tbs_Locator_FindBlock1($BlockSrc,$BlockName.'_'.$SubId,0) ;
						if ($SubLoc!==False) {
							if ($BlockLoc->EmptySrc===False) {
								$SubDef = $SubLoc->BlockLst[1] ;
								if ($OnSection!==False) $OnSection($BlockName,$CurrRec,$SubDef,$j) ;
								$x = '' ;
								tbs_Merge_Field($SubDef,$HtmlCharSet,$BlockName.'_'.$SubId,$x,True,False) ;
							} else {
								$SubDef = $BlockLoc->EmptySrc ;
								if ($OnSection!==False) $OnSection($BlockName,$CurrRec,$SubDef,$j) ;
							}
							tbs_Locator_Replace($BlockSrc,$GLOBALS['_tbs_False'],$SubLoc,$SubDef,False) ;
						}
					} while ($SubLoc!==False) ;
					$BlockRes .= $BlockSrc ;
				}

				//Calculate the value to return
				if ($PageSize>0) {
					if ($RecKnown<0) {
						$CurrRec = True ;
						while($CurrRec!==False) {
							//Pass pages in order to count all records
							tbs_Data_Fetch($SrcType,$RecSet,$RecInfo,$CurrRec,$RowNum) ;
						}
					} else {
						if ($RowNum<$RowStop) {
							//the number of page was surestimated
						} else {
							if ($RecKnown>$RowNum) $RowNum = $RecKnown ; //We know that there is more records
						}
					}
				}

				//Special operation if no data				
				if ($RowNum===0) {
					if ($BlockLoc->DefFound===False) {
						$BlockRes = $BlockLoc->BlockLst[1] ;
					} else {	
						if ($BlockLoc->NoDataSrc!==False) $BlockRes = $BlockLoc->NoDataSrc ;
							if ($OnSection!==False) {
								$CurrRec = False ;
								$OnSection($BlockName,$CurrRec,$BlockRes,$RowNum) ;
							}
					}
				}

				//Merge the result
				tbs_Locator_Replace($Txt,$GLOBALS['_tbs_False'],$BlockLoc,$BlockRes,False) ; //The block must not be converted to HTML !!
				$Pos = $BlockLoc->PosBeg ;

			} //-> if ($SrcType===4) {...} else {...

			//Close the resource
			tbs_Data_Close($SrcType,$RecSet,$RecInfo) ;

		} //-> if ($RecSet!==False) {..

			$RowTot += $RowNum ;

	} while ($Query0!==False) ;

	$CurrRec['#'] = $RowTot ;
	tbs_Merge_Field($Txt,$HtmlCharSet,$BlockName,$CurrRec,True,True) ; //Merge the number of record for the entire template

	//End of the merge
	$GLOBALS['tbs_CurrRec'] = &$CurrRecSave ;
	return $RowTot ;

}

//Type : 0=PHP Array, 1=MySQL, 2=ODBC, 3=SQL-Server,4=Text,5=ADODB, 6=number, 7 or str=custom, 8=PostGreSQL
function tbs_Data_Prepare(&$SrcId,&$SrcType,&$SrcSubType,&$RecInfo) {

	$Src = False ;
	$SrcType = False ;
	$SrcSubType = 0 ;

	if (is_array($SrcId)) {

		$SrcType = 0 ;

	} elseif (is_resource($SrcId)) {

		$Key = get_resource_type($SrcId) ;
		switch ($Key) {
		case 'mysql link'            : $SrcType = 1 ; break ;
		case 'mysql link persistent' : $SrcType = 1 ; break ;
		case 'mysql result'          : $SrcType = 1 ; $SrcSubType = 1 ; break ;
		case 'odbc link'             : $SrcType = 2 ; break ;
		case 'odbc link persistent'  : $SrcType = 2 ; break ;
		case 'odbc result'           : $SrcType = 2 ; $SrcSubType = 1 ; break ;
		case 'mssql link'            : $SrcType = 3 ; break ;
		case 'mssql link persistent' : $SrcType = 3 ; break ;
		case 'mssql result'          : $SrcType = 3 ; $SrcSubType = 1 ; break ;
		case 'pgsql link'            : $SrcType = 8 ; break ;
		case 'pgsql link persistent' : $SrcType = 8 ; break ;
		case 'pgsql result'          : $SrcType = 8 ; $SrcSubType = 1 ; break ;
		default :
			$Src = 'ressource type' ;
			$SrcType = 7 ;
			$x = $Key ;
			$x = str_replace('-','_',$SrcSubType) ;
			$SrcSubType = '' ;
			$i = 0 ;
			$iMax = strlen($SrcSubType) ;
			while ($i<$iMax) {
				if (($x[$i]==='_') or (($x[$i]>='a') and ($x[$i]<='z')) or (($x[$i]>='0') and ($x[$i]<='9'))) {
					$SrcSubType .= $x[$i] ;
					$i++;
				} else {
					$i = $iMax ;
				}
			}
		}

	} elseif (is_string($SrcId)) {

		switch (strtolower($SrcId)) {
		case 'array' : $SrcType = 0 ; $SrcSubType = 1 ; break ;
		case 'clear' : $SrcType = 0 ; $SrcSubType = 2 ; break ;
		case 'mysql' : $SrcType = 1 ; $SrcSubType = 2 ; break ;
		case 'mssql' : $SrcType = 3 ; $SrcSubType = 2 ; break ;
		case 'text'  : $SrcType = 4 ; break ;
		case 'num'   : $SrcType = 6 ; break ;
		default :
			$Key = $SrcId ;
			$Src = 'keyword' ;
			$SrcType = 7 ;
			$SrcSubType = $SrcId ;
		}

	} elseif (is_object($SrcId)) {
		$Key = get_class($SrcId) ;
		if ($Key==='COM') {
			if (strlen(@$SrcId->ConnectionString())>0) { //Look if it's a Connection object
				if ($SrcId->State==1) {
					$SrcType = 5 ; //ADODB
				} else {
					tbs_Misc_Alert('MergeBlock','The specified ADODB Connection is not open or not ready.') ;
				}
			} elseif (strlen(@$SrcId->CursorType())>0) { //Look if it's a RecordSet object
				if ($SrcId->State==1) {
					$SrcType = 5 ; //ADODB
					$SrcSubType = 1 ;
				} else {
					tbs_Misc_Alert('MergeBlock','The specified ADODB Recordset is not open or not ready.') ;
				}
			} else {
				tbs_Misc_Alert('MergeBlock','The specified COM Object is not a Connection or a Recordset.') ;
			}
		} else {
			$Src = 'object type' ;
			$SrcType = 7 ;
			$SrcSubType = $Key ;
		}

	} elseif ($SrcId===false) {
		tbs_Misc_Alert('MergeBlock','The specified source is set to FALSE. Maybe your connection has failed.') ;
	} else {
		tbs_Misc_Alert('MergeBlock','Unsupported variable type : \''.gettype($SrcId).'\'.') ;
	}

	if ($SrcType===7) {
		$SrcOpen  = 'tbsdb_'.$SrcSubType.'_open' ;
		$SrcFetch = 'tbsdb_'.$SrcSubType.'_fetch' ;
		$SrcClose = 'tbsdb_'.$SrcSubType.'_close' ;
		if (function_exists($SrcOpen)) {
			if (function_exists($SrcFetch)) {
					if (function_exists($SrcClose)) {
						$RecInfo = array('o'=>$SrcOpen,'f'=>$SrcFetch,'c'=>$SrcClose) ;
					} else {
					tbs_Misc_Alert('MergeBlock','The expected custom function \''.$SrcClose.'\' is not found.') ;
					$SrcType = False ;
				}
			} else {
				tbs_Misc_Alert('MergeBlock','The expected custom function \''.$SrcFetch.'\' is not found.') ;
				$SrcType = False ;
			}
		} else {
			tbs_Misc_Alert('MergeBlock','The data source Id \''.$Key.'\' is an unsupported '.$Src.'. And the corresponding custom function \''.$SrcOpen.'\' is not found.') ;
			$SrcType = False ;
		}
	}

}

function tbs_Data_Open(&$SrcId,&$Query,&$SrcType,&$SrcSubType,&$RecSet,&$RecInfo) {

	switch ($SrcType) {
	case 0: //Array
		switch ($SrcSubType) {
			case 0: $RecSet = $SrcId ; break ;
			case 1: $RecSet = $Query ; break ;
			case 2: $RecSet = array() ; break ;
		}
		if (is_array($RecSet)) {
			$RecInfo = array('count'=>count($RecSet),'reset'=>True) ;
		} else {
			tbs_Misc_Alert('MergeBlock','The parameters is not an array') ;
			$RecSet = False ;
		}
		break ;
	case 1: //MySQL
		switch ($SrcSubType) {
			case 0: $RecSet = @mysql_query($Query,$SrcId) ; break ;
			case 1: $RecSet = $SrcId ; break ;
			case 2: $RecSet = @mysql_query($Query) ; break ;
		}
		if ($RecSet===False) tbs_Misc_Alert('MergeBlock','MySql: '.mysql_error()) ;
		break ;
	case 2: //ODBC
		switch ($SrcSubType) {
			case 0: $RecSet = @odbc_exec($SrcId,$Query) ; break ;
			case 1: $RecSet = $SrcId ; break ;
		}
		if ($RecSet===False) {
			tbs_Misc_Alert('MergeBlock','ODBC: '.odbc_errormsg()) ;
		} else {
			$RecInfo = array() ;
			$iMax = odbc_num_fields($RecSet) ;
			for ($i=1;$i<=$iMax;$i++) {
				$RecInfo[$i] = ''.odbc_field_name($RecSet,$i) ;
			}
		}
		break ;
	case 3: //MsSQL
		switch ($SrcSubType) {
			case 0: $RecSet = @mssql_query($Query,$SrcId) ; break ;
			case 1: $RecSet = $SrcId ; break ;
			case 2: $RecSet = @mssql_query($Query) ; break ;
		}
		if ($RecSet===False) {
			tbs_Misc_Alert('MergeBlock','SQL-Server: '.mssql_get_last_message()) ;
		}
		break ;
	case 4: //Text
		if (is_string($Query)) {
			$RecSet = $Query ;
		} else {
			$RecSet = ''.$Query ;	
		}
		break ;
	case 5: //ADODB
		switch ($SrcSubType) {
			case 0:
				$RecSet = @$SrcId->Execute($Query) ; //We use the Connection object reather than the Recordset object in order to manage errors
				if ($SrcId->Errors->Count>0) {
					tbs_Misc_Alert('MergeBlock','ADODB: '.$SrcId->Errors[0]->Description) ;
					$RecSet = False ;
				} elseif (@$RecSet->State!=1) {
					tbs_Misc_Alert('MergeBlock','The ADODB query doesn\'t return a RecordSet or the ResordSet is not ready.') ;
					$RecSet = False ;
				}
				break ;
			case 1:
				$RecSet = $SrcId ;
				break ;
		}
		if ($RecSet!==False) {
			$RecInfo = array() ;
			$iMax = $RecSet->Fields->Count ;
			for ($i=0;$i<$iMax;$i++) {
				$RecInfo[$i] = ''.$RecSet->Fields[$i]->Name ;
			}
		}
		break ;
	case 6: //Num
		If (is_array($Query)) {
			$RecSet = $Query ;
			if (!isset($RecSet['min'])) $RecSet['min'] = 1 ;
			if (!isset($RecSet['step'])) $RecSet['step'] = 1 ;
		} else {
			$RecSet = array('min'=>1,'max'=>ceil($Query),'step'=>1) ;
		}
		if (isset($RecSet['max'])) {
			$RecSet['val'] = $RecSet['min'] ;
		} else {
			tbs_Misc_Alert('MergeBlock','The \'num\' source is an array that has no value for the \'max\' key.') ;
			$RecSet = False ;
		}
		break ;
	case 7: //Custom function
		$RecSet = $RecInfo['o']($SrcId,$Query) ;
		break ;
	case 8: //PostgreSQL
		switch ($SrcSubType) {
			case 0: $RecSet = @pg_query($SrcId,$Query) ; break ;
			case 1: $RecSet = $SrcId ; break ;
		}
		if ($RecSet===False) tbs_Misc_Alert('MergeBlock','PostgreeSQL: '.pg_last_error($SrcId)) ;
		break ;
	}	

}

function tbs_Data_Fetch(&$SrcType,&$RecSet,&$RecInfo,&$RowData,&$RowNum) {

	switch ($SrcType) {
	case 0: //Array
		if ($RowNum<$RecInfo['count']) {
			if ($RecInfo['reset']) {
				$RowData = reset($RecSet) ;
				$RecInfo['reset'] = False ;
			} else {
				$RowData = next($RecSet) ;
			}
			if (!is_array($RowData)) $RowData = array('key'=>key($RecSet), 'val'=>$RowData) ;
		} else {
			$RowData = False ;
		}
		break ;
	case 1: //MySQL
			$RowData = mysql_fetch_assoc($RecSet) ;
		break ;
	case 2: //ODBC, odbc_fetch_array -> Error with PHP 4.1.1
		$RowData = odbc_fetch_row($RecSet) ;
		if ($RowData) {
			$RowData = array() ;
			foreach ($RecInfo as $colid=>$colname) {
				$RowData[$colname] = odbc_result($RecSet,$colid) ;
			}
		}
		break ;
	case 3: //MsSQL
		$RowData = mssql_fetch_array($RecSet) ;
		break ;
	case 4: //Text
		if ($RowNum===0) {
			if ($RecSet==='') {
				$RowData = False ;
			} else {
				$RowData = &$RecSet ;
			}
		} else {
			$RowData = False ;
		}
		break ;
	case 5: //ADODB
	if ($RecSet->EOF()) {
			$RowData = False ;
		} else {
			$RowData = array() ;
			foreach ($RecInfo as $colid=>$colname) {
				$RowData[$colname] = $RecSet->Fields[$colid]->Value ;
			}
			$RecSet->MoveNext() ; //brackets () must be there
		}
		break ;
	case 6: //Num
		if ($RecSet['val']<=$RecSet['max']) {
			$RowData = array('val'=>$RecSet['val']) ;
			$RecSet['val'] = $RecSet['val'] + $RecSet['step'] ;
		} else {
			$RowData = False ;
		}
		break ;
	case 7: //Custom function
		$RowData = $RecInfo['f']($RecSet,$RowNum+1) ;
		break ;
	case 8: //PostgreSQL
		$RowData = @pg_fetch_array($RecSet,$RowNum,PGSQL_ASSOC) ; //warning comes when no record left.
		break ;
	}	

	//Set the row count
	if ($RowData!==False) $RowNum++ ;

}

function tbs_Data_Close(&$SrcType,&$RecSet,&$RecInfo) {

		switch ($SrcType) {
		case 1: mysql_free_result($RecSet) ; break ;
		case 2: odbc_free_result($RecSet) ; break ;
		case 3: mssql_free_result($RecSet) ; break ;
		case 5: $RecSet->Close ; break ;
		case 7: $RecInfo['c']($RecSet) ; break ;
		case 8: pg_free_result($RecSet) ; break ;
	}

}

//This function enables to merge a set of 'case' blocks.
//'case' bocks are blocks with the same name and 'when','then', 'else' conditions.
function tbs_Merge_CaseBlock1(&$Txt,$BlockName) {

	$Pos = 0 ;
	$Ok = False ;
	$ElseFound = False ;

	//Scan for each Blocks
	while ($Pos!==False) {
		$Loc = tbs_Locator_FindBlock1($Txt,$BlockName,$Pos) ;
		if ($Loc===False) {
			$Pos = False ;
		} else {

			//Check if it has a 'if' parameter
			if (isset($Loc->PrmLst['if'])) {
				if (tbs_Misc_CheckCondition($Loc->PrmLst['if'])==True) {
					$x = $Loc->BlockLst[1] ;
					$Ok = True ;
				} else {
					$x = '' ;
				}
			} else {
				//If it's a 'else' block, we keep it for the end of the scan
				if (isset($Loc->PrmLst['else'])) {
					$ElseFound = True ;
					$Pos = $Loc->PosEnd ;
					$x = False ;
				} else {
					$x = '' ;
				}
			}

			//Merge the bock
			if ($x!==False) {
				$Pos = tbs_Locator_Replace($Txt,$GLOBALS['_tbs_False'],$Loc,$x,False) ;
			}

		} //--> if ($Loc===False)
	} //--> while ($Pos!==False)

	//Now, we scan for each 'else' blocks.
	if ($ElseFound===True) {
		$Pos = 0 ;
		while ($Pos!==False) {
			$Loc = tbs_Locator_FindBlock1($Txt,$BlockName,$Pos) ;
			if ($Loc===False) {
				$Pos = False ;
			} else {
				if ($Ok===True) {
					$x = '' ;
				} else {
					$x = $Loc->BlockLst[1] ;
				}
				$Pos = tbs_Locator_Replace($Txt,$GLOBALS['_tbs_False'],$Loc,$x,False) ;
			}
		}
	}

}

//Look for each 'check' block and merge them.
function tbs_Merge_CaseBlockAll(&$Txt,$BlockName) {

	$PosBeg = 0 ;
	$CurrName = '' ;
	$LastName = '' ;

	while ($PosBeg!==False) {
		$Loc = tbs_Locator_FindBlock1($Txt,$BlockName,$PosBeg) ;
		if ($Loc===False) {
			$PosBeg = False ;
		} else {
			if ($Loc->SubName===False) {
				//We skip this block because it has no subname.
				$PosBeg = $Loc->PosEnd ;
			} else {
				$LastName = $CurrName ;
				$CurrName = $BlockName.'.'.$Loc->SubName ;
				if (strcasecmp($LastName,$CurrName)==0) {
					//This enable to no go into an nevereding loop
					$PosBeg = $Loc->PosEnd ;
				} else {
					tbs_Merge_CaseBlock1($Txt,$CurrName) ;
				}
			}
		}
	}

}

//Merge the PHP global variables of the main script.
function tbs_Merge_PhpVar(&$Txt,&$HtmlCharSet) {

	$FieldName = 'var' ;

	//Check if the PhpVar list has to be initialized
	if ($GLOBALS['_tbs_PhpVarLst']===False) {
		//Build an array that enables to find any global variable name from its lower case name
		$GLOBALS['_tbs_PhpVarLst'] = array() ;
		$x = array_keys($GLOBALS) ;
		foreach ($x as $Key) {
			$GLOBALS['_tbs_PhpVarLst'][strtolower($Key)] = $Key ;
		}
	}


	//Then we scann all field in the model
	$Pos = 0 ;
	do {
		$Loc = tbs_Locator_FindTbs($Txt,$FieldName,$Pos,True) ;
		if ($Loc!==False) {
			$x = strpos($Loc->SubName,'.') ;
			if ($x===False) {
				$VarName = strtolower($Loc->SubName) ;
				$Loc->SubName = False ;
			} else {
				$VarName = strtolower(substr($Loc->SubName,0,$x)) ;
				$Loc->SubName = substr($Loc->SubName,$x+1) ;
			}
			if (isset($GLOBALS['_tbs_PhpVarLst'][$VarName])) {
				$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$GLOBALS[$GLOBALS['_tbs_PhpVarLst'][$VarName]],True) ;
			} else {
				if (isset($Loc->PrmLst['noerr'])) {
					$x = '' ;
					$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$x,False) ;
				} else {
					tbs_Misc_Alert('Merge Php Var','Can\'t merge ['.$Loc->FullName.'] because there is no corresponding PHP variable.',True) ;
					$Pos = $Loc->PosEnd + 1 ;
				}
			}
		}
	} while ($Loc!==False) ;

}

//This function enables to merge TBS special fields
function tbs_Merge_TbsVar(&$TBS) {

	$Pos = 0 ;

	do {
		$Loc = tbs_Locator_FindTbs($TBS->Source,'sys',$Pos,True) ;
		if ($Loc!==False) {
			$Pos = $Loc->PosEnd ;
			switch (strtolower($Loc->SubName)) {
			case 'now':
				$x = mktime() ;
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,False) ;
				break ;
			case 'version':
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$TBS->_Version,False) ;
				break ;
			case 'script_name':
				if (isset($_SERVER)) { //PHP<4.1.0 compatibilty
					$x = tbs_Misc_GetFilePart($_SERVER['PHP_SELF'],1) ;
				} else {
					global $HTTP_SERVER_VARS ;
					$x = tbs_Misc_GetFilePart($HTTP_SERVER_VARS['PHP_SELF'],1) ;
				}
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,False) ;
				break ;
			case 'template_name':
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$TBS->_LastFile,False) ;
				break ;
			case 'template_date':
				$x = filemtime($TBS->_LastFile) ;
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,False) ;
				break ;
			case 'template_path':
				$x = tbs_Misc_GetFilePart($TBS->_LastFile,0) ;
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,False) ;
				break ;
			case 'name':
				$x = 'TinyButStrong' ;
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,False) ;
				break ;
			case 'logo':
				$x = '**TinyButStrong**' ;
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,False) ;
				break ;
			case 'merge_time' : $TBS->_Timer = True ; break ;
			case 'script_time': $TBS->_Timer = True ; break ;
			case 'charset':
				$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$TBS->HtmlCharSet,False) ;
				break ;
			}
		}
	} while ($Loc!==False) ;

}

//Proceed to one of the special merge
function tbs_Merge_Special(&$TBS,$Type) {

	if ($Type==='*') $Type = 'include,include.onshow,var,sys,check,timer' ;

	$TypeLst = split(',',$Type) ;
	foreach ($TypeLst as $Type) {
		switch ($Type) {
			case 'var':	tbs_Merge_PhpVar($TBS->Source,$TBS->HtmlCharSet) ; break ;
			case 'sys': tbs_Merge_TbsVar($TBS) ; break ;
			case 'check':
				$x = '' ;
				tbs_Merge_Field($TBS->Source,$TBS->HtmlCharSet,'tbs_check',$x,False,False) ;
				tbs_Merge_CaseBlockAll($TBS->Source,'tbs_check') ;
				break ;
			case 'include': tbs_Merge_Auto($TBS->Source,$TBS->HtmlCharSet,True) ; break ;
			case 'include.onshow': tbs_Merge_Auto($TBS->Source,$TBS->HtmlCharSet,False) ; break ;
			case 'timer':
				if ($TBS->_Timer) { //This property is set wihtin the tbs_Merge_PhpVar() function
					global $_tbs_Timer ;
					$Micro = tbs_Misc_Timer() ;
					$x = $Micro - $TBS->_StartMerge ;
					tbs_Merge_Field($TBS->Source,$TBS->HtmlCharSet,'sys.merge_time',$x,False,False) ;
					$x = $Micro - $_tbs_Timer ;
					tbs_Merge_Field($TBS->Source,$TBS->HtmlCharSet,'sys.script_time',$x,False,False) ;
				}
				break ;
			}
	}

}

//Merge a list with named items. Used by tbs_Merge_Block().
function tbs_Merge_List(&$Txt,&$HtmlCharSet,$Name,&$List) {
	foreach ($List as $key => $val) {
		$Pos = 0 ;
		do {
			$Loc = tbs_Locator_FindTbs($Txt,$Name.'.'.$key,$Pos,True) ;
			if ($Loc!==False) $Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$val,True) ;
		} while ($Loc!==False) ;
	}
}

//Include file
function tbs_Merge_Auto(&$Txt,&$HtmlCharSet,$OnLoad) {

	$TmpValue = '' ;
	$Nbr = 0 ;
	$Pos = 0 ;

	do {
		$Loc = tbs_Locator_FindTbs($Txt,'tbs_include',$Pos,True) ;
		if ($Loc!==False) {
			$Ok = False ;
			if ($OnLoad) {
				if (($Loc->SubName===False) or (strtolower($Loc->SubName)==='onload') ) $Ok = True ;
			} else {
				if (strtolower(''.$Loc->SubName)==='onshow') $Ok = True ;
			}
			if ($Ok) {
				$Nbr++ ;
				if ($Nbr>64) {
					tbs_Misc_Alert('Automatic fields','The field ['.$Loc->FullName.'] can\'t be merged because the limit (64) is riched. You maybe have self-included templates.') ;
					$Loc=False ;
				} else {
					$Pos = $Loc->PosBeg ;
					tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$TmpValue,False) ;
				}
			} else {
				$Pos = $Loc->PosEnd ;
			}
		}
	} while ($Loc!==False) ;

}

function tbs_Merge_NavigationBar(&$Txt,&$HtmlCharSet,$BlockName,$Options,$PageCurr,$RecCnt,$RecByPage) {

	//Check values
	if (!is_array($Options)) $Options = array('size'=>intval($Options)) ;
	if (!isset($Options['pos'])) $Options['pos'] = 'step' ;
	if ($Options['size']<=0) $Options['size'] = 10 ;
	if ($PageCurr<=0) $PageCurr = 1 ;
	if ($RecByPage<=0) $RecByPage = 1 ;
	$CurrPos = 0 ;

	$SaveNav = &$GLOBALS['tbs_CurrNav'] ;
	$CurrNav = array('curr'=>$PageCurr,'first'=>1,'last'=>-1,'bound'=>False) ;
	$GLOBALS['tbs_CurrNav'] = &$CurrNav ;

	//Calculate PageMin and PageMax displayed
	if ($Options['pos']=='centred') {
		$PageMin = $PageCurr - intval(floor($Options['size']/2)) ;
	} else {
		//Display by block
		$PageMin = $PageCurr - ( ($PageCurr-1) % $Options['size']) ;
	}
	$PageMin = max($PageMin,1) ;
	$PageMax = $PageMin + $Options['size'] - 1 ;
	
	//Calculate previous and next pages
	$CurrNav['prev'] = $PageCurr - 1 ;
	if ($CurrNav['prev']<1) {
		$CurrNav['prev'] = 1 ;
		$CurrNav['bound'] = 1 ;
	}
	$CurrNav['next'] = $PageCurr + 1 ;
	if ($RecCnt>=0) {
		$PageCnt = intval(ceil($RecCnt/$RecByPage)) ;
		$PageMax = min($PageMax,$PageCnt) ;
		$PageMin = max(1,$PageMax-$Options['size']+1) ;
	} else {
		$PageCnt = -1 ;
	}
	if ($PageCnt>0) {
		if ($PageCurr>=$PageCnt) {
			$CurrNav['next'] = $PageCnt ;
			$CurrNav['last'] = $PageCnt ;
			$CurrNav['bound'] = $PageCnt ;
		} else {
			$CurrNav['last'] = $PageCnt ;
		}
	}	

	//Merge general information
	$Pos = 0 ;
	do {
		$Loc = tbs_Locator_FindTbs($Txt,$BlockName,$Pos,True) ;
		if ($Loc!==False) {
			$Pos = $Loc->PosBeg + 1 ;
			$x = strtolower($Loc->SubName) ;
			if (isset($CurrNav[$x])) {
				$Val = $CurrNav[$x] ;
				if ($CurrNav[$x]==$CurrNav['bound']) {
					if (isset($Loc->PrmLst['endpoint'])) {
						$Val = '' ;
					}
				}
				tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$Val,False) ;
			}
		}
	} while ($Loc!==False) ;

	//Merge pages
	$Data = array() ;
	$RecSpe = 0 ;
	$RecCurr = 0 ;
	for ($PageId=$PageMin;$PageId<=$PageMax;$PageId++) {
		$RecCurr++ ;
		if ($PageId==$PageCurr) $RecSpe = $RecCurr ;
		$Data[] = array('page'=>$PageId) ;
	}
	tbs_Merge_Block($Txt,$HtmlCharSet,$BlockName,$Data,'',0,0,$RecSpe) ;

	$GLOBALS['tbs_CurrNav'] = &$SaveNav ;

}


//This function returns a part of the HTML document (HEAD or BODY)
//The $CancelIfEmpty parameter enables to cancel the extraction when the part is not found.
function tbs_Html_GetPart(&$Txt,$Tag,$WithTags=False,$CancelIfEmpty=False) {

	$x = False ;

	$LocOpen = tbs_Html_FindTag($Txt,$Tag,True,0,True,0,False) ;
	if ($LocOpen!==False) {
		$LocClose = tbs_Html_FindTag($Txt,$Tag,False,$LocOpen->PosEnd+1,True,0,False) ;
		if ($LocClose!==False) {
			if ($WithTags) {
				$x = substr($Txt,$LocOpen->PosBeg,$LocClose->PosEnd - $LocOpen->PosBeg + 1) ;
			} else {
				$x = substr($Txt,$LocOpen->PosEnd+1,$LocClose->PosBeg - $LocOpen->PosEnd - 1) ;
			}
		}
	}

	if ($x===False) {
		if ($CancelIfEmpty) {
			$x = $Txt ;
		} else {
			$x = '' ;
		}
	}

	return $x ;

}

//This function return True if thte text seems to have some HTML tags.
function tbs_Html_IsHtml(&$Txt) {

	$IsHtml = False ;

	//Search for opening and closing tags
	$pos = strpos($Txt,'<') ;
	if ( ($pos!==False) and ($pos<strlen($Txt)-1) ) {
		$pos = strpos($Txt,'>',$pos + 1) ;
		if ( ($pos!==False) and ($pos<strlen($Txt)-1) ) {
			$pos = strpos($Txt,'</',$pos + 1) ;
			if ( ($pos!==False)and ($pos<strlen($Txt)-1) ) {
				$pos = strpos($Txt,'>',$pos + 1) ;
				if ($pos!==False) {
					$IsHtml = True ;
				}
			}
		}
	}

	//Search for special char
	if ($IsHtml===False) {
		$pos = strpos($Txt,'&') ;
		if ( ($pos!==False)  and ($pos<strlen($Txt)-1) ) {
			$pos2 = strpos($Txt,';',$pos+1) ;
			if ($pos2!==False) {
				$x = substr($Txt,$pos+1,$pos2-$pos-1) ; //We extract the found text between the couple of tags
				if (strlen($x)<=10) {
					if (strpos($x,' ')===False) {
						$IsHtml = True ;
					}
				}
			}
		}
	}

	//Look for a simple tag
	if ($IsHtml===False) {
		$Loc1 = tbs_Html_FindTag($Txt,'BR',True,0,True,0,False) ; //line break
		if ($Loc1===False) {
			$Loc1 = tbs_Html_FindTag($Txt,'HR',True,0,True,0,False) ; //horizontal line
			if ($Loc1!==False) {
				$IsHtml = True ;
			}
		} else {
			$IsHtml = True ;
		}
	}

	return $IsHtml ;

}

//Merge items of a list, or radio or check buttons.
//At this point, the Locator is already merged with $SelValue.
function tbs_Html_MergeItems(&$Txt,&$Loc,&$SelValue,&$SelArray,$NewEnd) {

	if ($Loc->PrmLst['selected']===True) {
		$IsList = True ;
		$MainTag = 'SELECT' ;
		$ItemTag = 'OPTION' ;
		$ItemPrm = 'selected' ;
	} else {
		$IsList = False ;
		$MainTag = 'FORM' ;
		$ItemTag = 'INPUT' ;
		$ItemPrm = 'checked' ;
	}
	$ItemPrmZ = ' '.$ItemPrm ;

	$TagO = tbs_Html_FindTag($Txt,$MainTag,True,$Loc->PosBeg-1,False,0,False) ;

	if ($TagO!==False) {

		$TagC = tbs_Html_FindTag($Txt,$MainTag,False,$Loc->PosBeg,True,0,False) ;
		if ($TagC!==False) {

			//We get the main block without the main tags
			$MainSrc = substr($Txt,$TagO->PosEnd+1,$TagC->PosBeg - $TagO->PosEnd -1) ;

			if ($IsList) {
				$Item0Beg = $Loc->PosBeg - ($TagO->PosEnd+1) ;
				$Item0Src = '' ;
			} else {
				//we delete the merged value
				$MainSrc = substr_replace($MainSrc,'',$Loc->PosBeg - ($TagO->PosEnd+1), strlen($SelValue)) ;
			}

			//Now, we going to scann all of the item tags
			$Pos = 0 ;
			$SelNbr = 0 ;
			$Item0Ok = False ;
			while ($Pos!==False) {
				$ItemLoc = tbs_Html_FindTag($MainSrc,$ItemTag,True,$Pos,True,0,True) ;
				if ($ItemLoc===False) {
					$Pos = False ;
				} else {

					//we get the value of the item
					$ItemValue = False ;
					$Select = True ;

					if ($IsList) {
						//Look for the end of the item
						$OptCPos = strpos($MainSrc,'<',$ItemLoc->PosEnd+1) ;
						if ($OptCPos===False) $OptCPos = strlen($MainSrc) ;
						if (($Item0Ok===False) and ($ItemLoc->PosBeg<$Item0Beg) and ($Item0Beg<=$OptCPos)) {
							//If it's the original item, we save it and delete it.
							if (($OptCPos+1<strlen($MainSrc)) and ($MainSrc[$OptCPos+1]==='/')) {
								$OptCPos = strpos($MainSrc,'>',$OptCPos) ;
								if ($OptCPos===False) {
									$OptCPos = strlen($MainSrc) ;
								} else {
									$OptCPos++ ;
								}
							}
							$Item0Src = substr($MainSrc,$ItemLoc->PosBeg,$OptCPos-$ItemLoc->PosBeg) ;
							$MainSrc = substr_replace($MainSrc,'',$ItemLoc->PosBeg,strlen($Item0Src)) ;
							if (!isset($ItemLoc->PrmLst[$ItemPrm])) $Item0Src = substr_replace($Item0Src,$ItemPrmZ,$ItemLoc->PosEnd-$ItemLoc->PosBeg,0) ;
							$OptCPos = min($ItemLoc->PosBeg,strlen($MainSrc)-1) ;
							$Select = False ;
							$Item0Ok = True ;
						} else {
							if (isset($ItemLoc->PrmLst['value'])) {
								$ItemValue = $ItemLoc->PrmLst['value'] ;
							} else { //The value of the option is its caption.
								$ItemValue = substr($MainSrc,$ItemLoc->PosEnd+1,$OptCPos - $ItemLoc->PosEnd - 1) ;
								$ItemValue = str_replace(chr(9),' ',$ItemValue) ;
								$ItemValue = str_replace(chr(10),' ',$ItemValue) ;
								$ItemValue = str_replace(chr(13),' ',$ItemValue) ;
								$ItemValue = trim($ItemValue) ;
							}
						}
						$Pos = $OptCPos ;
					} else {
						if ((isset($ItemLoc->PrmLst['name'])) and (isset($ItemLoc->PrmLst['value']))) {
							if (strcasecmp($Loc->PrmLst['selected'],$ItemLoc->PrmLst['name'])==0) {
								$ItemValue = $ItemLoc->PrmLst['value'] ;
							}
						}
						$Pos = $ItemLoc->PosEnd ;
					}

					if ($Select) {
						//we look if we select the item
						$Select = False ;
						if ($SelArray===False) {
							if (strcasecmp($ItemValue,$SelValue)==0) {
								if ($SelNbr==0) $Select = True ;
							}
						} else {
							if (array_search($ItemValue,$SelArray,False)!==False) $Select = True ;
						}
						//Select the item
						if ($Select) {
							if (!isset($ItemLoc->PrmLst[$ItemPrm])) {
								$MainSrc = substr_replace($MainSrc,$ItemPrmZ,$ItemLoc->PosEnd,0) ;
								$Pos = $Pos + strlen($ItemPrmZ) ;
								if ($IsList and ($ItemLoc->PosBeg<$Item0Beg)) $Item0Beg = $Item0Beg + strlen($ItemPrmZ) ;
							}
							$SelNbr++ ;
						}
					}

				} //--> if ($ItemLoc===False) { ... } else {
			} //--> while ($Pos!==False) {

			if ($IsList) {
				//Add the original item if it's not found
				if (($SelArray===False) and ($SelNbr==0)) $MainSrc = $MainSrc.$Item0Src ;
				$NewEnd = $TagO->PosEnd+1 + strlen($MainSrc) ;
			} else {
				$NewEnd = $Loc->PosBeg ;
			}

			$Txt = substr_replace($Txt,$MainSrc,$TagO->PosEnd+1,$TagC->PosBeg-$TagO->PosEnd-1) ;

		} //--> if ($TagC!==False) {
	} //--> if ($TagO!==False) {

}

//Convert a string to Html with several options
function tbs_Html_Conv(&$Txt,&$HtmlCharSet,&$BrConv,&$WhiteSp) {

	if ($HtmlCharSet==='') {
		$Txt = htmlentities($Txt) ; //Faster
	} else {
		$Txt = htmlentities($Txt,ENT_COMPAT,$HtmlCharSet) ;
	}

	if ($WhiteSp) {
		$check = '  ' ;
		$nbsp = '&nbsp;' ;
		do {
			$pos = strpos($Txt,$check) ;
			if ($pos!==False) $Txt = substr_replace($Txt,$nbsp,$pos,1) ;
		} while ($pos!==False) ;
	}

	if ($BrConv) $Txt = nl2br($Txt) ;

}

//This function is a smarter issue to find an HTML tag.
//It enables to ignore full opening/closing couple of tag that could be inserted before the searched tag.
//It also enable to pass a number of encapsulation.
//To ignore encapsulation and opengin/closing just set $Encaps=0.
function &tbs_Html_FindTag(&$Txt,$Tag,$Opening,$PosBeg,$Forward,$Encaps,$WithPrm) {

	if ($Forward) {
		$Pos = $PosBeg - 1 ;
	} else {
		$Pos = $PosBeg + 1 ;
	}
	$TagIsOpening = False ;
	$TagClosing = '/'.$Tag ;
	if ($Opening) {
		$EncapsEnd = $Encaps ;
	} else {
		$EncapsEnd = - $Encaps ;
	}
	$EncapsCnt = 0 ;
	$TagOk = False ;

	do {

		//Look for the next tag def
		if ($Forward) {
			$Pos = strpos($Txt,'<',$Pos+1) ;
		} else {
			if ($Pos<=0) {
				$Pos = False ;
			} else {
				$Pos = strrpos(substr($Txt,0,$Pos - 1),'<') ;
			}
		}

		if ($Pos!==False) {
			//Check the name of the tag
			if (strcasecmp(substr($Txt,$Pos+1,strlen($Tag)),$Tag)==0) {
				$PosX = $Pos + 1 + strlen($Tag) ; //The next char
				$TagOk = True ;
				$TagIsOpening = True ;
			} elseif (strcasecmp(substr($Txt,$Pos+1,strlen($TagClosing)),$TagClosing)==0) {
				$PosX = $Pos + 1 + strlen($TagClosing) ; //The next char
				$TagOk = True ;
				$TagIsOpening = False ;
			}

			if ($TagOk) {
				//Check the next char
				if (($Txt[$PosX]===' ') or ($Txt[$PosX]==='>')) {
					//Check the encapsulation count
					if ($EncapsEnd==0) {
						//No encaplusation check
						if ($TagIsOpening!==$Opening) $TagOk = False ;
					} else {
						//Count the number of encapsulation
						if ($TagIsOpening) {
							$EncapsCnt++ ;
						} else {
							$EncapsCnt-- ;
						}
						//Check if it's the expected count
						if ($EncapsCnt!=$EncapsEnd) $TagOk = False ;
					}
				} else {
					$TagOk = False ;
				}
			} //--> if ($TagOk)

		}
	} while (($Pos!==False) and ($TagOk===False)) ;

	//Search for the end of the tag
	if ($TagOk) {
		$Loc = new clsTbsLocator ;
		if ($WithPrm) {
			$PosEnd = 0 ;
			tbs_Locator_ReadPrm($Txt,$PosX,' ','=','\'"','','','>',0,$Loc,$PosEnd) ;
		} else {
			$PosEnd = strpos($Txt,'>',$PosX) ;
			if ($PosEnd===False) {
				$TagOk = False ;
			}
		}
	}

	//Result
	if ($TagOk) {
		$Loc->PosBeg = $Pos ;
		$Loc->PosEnd = $PosEnd ;
		return $Loc ;
	} else {
		return False ;
	}

}

//Limits the number of HTML chars
function tbs_Html_Max(&$Txt,&$Nbr) {

	$pMax = strlen($Txt)-1 ;
	$p=0 ;
	$n=0 ;
	$in = False ;
	$ok = true ;

	while ($ok) {
		if ($in) {
			if ($Txt[$p]===';') {
				$in = false;
				$n++;
			}
		} else {
			if ($Txt[$p]==='&') {
				$in = true;
			} else {
				$n++;
			}
		}
		if (($n>=$Nbr) or ($p>=$pMax)) {
			$ok = false ;
		} else {
			$p++ ;
		}
	}
	
	if (($n>=$Nbr) and ($p<$pMax)) $Txt = substr($Txt,0,$p).'...' ;

}

//Return a string that describes all locators with the given name.
function tbs_Misc_DebugLocator(&$Txt,$Name) {
	$x = '' ;
	$Pos = 0 ;
	$Type = 0 ;
	$Nbr = 0 ;
	$Break = '-------------------<br>' ;
	$ColOn = '<font color="#993300">' ;
	$ColOff = '</font>' ;
	do {
		if ($Type===0) {
			$Loc = tbs_Locator_FindTbs($Txt,$Name,$Pos,False) ;
			if ($Loc===False) $Type = 1 ;
		}
		if ($Type===1) {
			$Loc = tbs_Locator_FindTbs($Txt,$Name,$Pos,True) ;
		}
		if ($Loc!==False) {
			$Pos = $Loc->PosEnd + 1 ;
			$Nbr++ ;
			$x .= $Break ;
			$x .= 'Locator = '.$ColOn.htmlentities(substr($Txt,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1)).$ColOff.'<br>' ;
			$x .= 'Name = '.$ColOn.htmlentities($Name).$ColOff.', subname = '.$ColOn.htmlentities($Loc->SubName).$ColOff.'<br>' ;
			$x .= 'Begin = '.$ColOn.$Loc->PosBeg.$ColOff.', end = '.$ColOn.$Loc->PosEnd.$ColOff.'<br>' ;
			foreach ($Loc->PrmLst as $key=>$val) {
				if ($val===True) $val = 'True' ;
				if ($val===False) $val = 'False' ;
				$x .= 'Parameters['.$ColOn.htmlentities($key).$ColOff.'] = '.$ColOn.htmlentities($val).$ColOff.'<br>' ;
			}
		}
	} while ($Loc!==False) ;
	$x .= $Break ;
	$x = 'Locator search = '.$ColOn.htmlentities($Name).$ColOff.', found = '.$ColOn.$Nbr.$ColOff.'<br> Template size = '.$ColOn.strlen($Txt).$ColOff.'<br>'. $x ;
	$x = $Break . $x ;
	return $x ;
}

//Standard alerte message provideed by TinyButStrong, return False is the message is cancelled.
function tbs_Misc_Alert($Source,$Message,$NoErr=False) {
	$x = '<br><b>TinyButStrong Error</b> ('.$Source.'): '.htmlentities($Message) ;
	if ($NoErr) $x = $x.' <em>This message can be cancelled using the \'noerr\' parameter.</em>' ;
	$x = $x.'<br><br>' ;
	$x = str_replace('[',$GLOBALS['tbs_ChrProtect'],$x) ;
	echo $x ;
}

function tbs_Misc_Timer() {
	$x = microtime() ;
	$Pos = strpos($x,' ') ;
	if ($Pos===False) {
		$x = '0.0' ;
	} else {
		$x = substr($x,$Pos+1).substr($x,1,$Pos) ;
	}
	return (float)$x ;
}

//Marks the variable to be initilized
function tbs_Misc_ClearPhpVarLst() {
	$GLOBALS['_tbs_PhpVarLst'] = False ;
}

function tbs_Misc_GetFilePart($File,$Part) {
	$Pos = strrpos($File,'/') ;
	if ($Part===0) { //Path
		if ($Pos===False) {
			return '' ;
		} else {
			return substr($File,0,$Pos+1) ;
		}
	} else { //File
		if ($Pos===False) {
			return $File ;
		} else {
			return substr($File,$Pos+1) ;
		}
	}
}

//Load the content of a file into the text variable.
function tbs_Misc_GetFile(&$Txt,$File,$PrmLst=False) {
	$Txt = '' ;
	$fd = @fopen($File, 'r') ; //'rb' if binary for some OS
	if ($fd===False) return False ;
	$Txt = fread($fd, filesize($File)) ;
	fclose($fd);
	return True ;
}

//This function return the formated representation of a Date/Time or numeric variable using a 'VB like' format syntaxe instead of the PHP syntaxe.
function tbs_Misc_Format(&$Loc,&$Value) {

	global $_tbs_FrmSimpleLst ;

	$FrmStr = $Loc->PrmLst['frm'] ;
	$CheckNumeric = true ;
	if (is_string($Value)) $Value = trim($Value) ;
	
	//Manage Mutli format strings
	if (strpos($FrmStr,'|')!==false) {
		
		global $_tbs_FrmMultiLst ;
		
		//Save the format if it doesn't exist
		if (isset($_tbs_FrmMultiLst[$FrmStr])) {
			$FrmLst = &$_tbs_FrmMultiLst[$FrmStr] ;
		} else {
			$FrmLst = explode('|',$FrmStr) ; //syntax : PostiveFrm|NegativeFrm|ZeroFrm|NullFrm
			$FrmNbr = count($FrmLst) ;
			if (($FrmNbr<=1) or ($FrmLst[1]==='')) {
				$FrmLst[1] = &$FrmLst[0] ; //negativ
				$FrmLst['abs'] = false ;
			} else {
				$FrmLst['abs'] = true ;
			}
			if (($FrmNbr<=2) or ($FrmLst[2]==='')) $FrmLst[2] = &$FrmLst[0] ; //zero
			if (($FrmNbr<=3) or ($FrmLst[3]==='')) $FrmLst[3] = '' ; //null
			$_tbs_FrmMultiLst[$FrmStr] = $FrmLst ;
		}
		
		//Select the format
		if (is_numeric($Value)) {
			if (is_string($Value)) $Value = 0.0 + $Value ;
			if ($Value>0) {
				$FrmStr = &$FrmLst[0] ;
			} elseif ($Value<0) {
				$FrmStr = &$FrmLst[1] ;
				if ($FrmLst['abs']) $Value = abs($Value)  ;
			} else { //zero
				$FrmStr = &$FrmLst[2] ;
				$Minus = '' ;
			}
			$CheckNumeric = false ;
		} else {
			$Value = ''.$Value ;
			if ($Value==='') {
				return $FrmLst[3] ;
			} else {
				return $Value ;
			}
		}
		
	}

	if ($FrmStr==='') return ''.$Value ;

	//Retrieve the correct simple format
	if (!isset($_tbs_FrmSimpleLst[$FrmStr])) tbs_Misc_FormatSave($FrmStr) ;
	
	$Frm = &$_tbs_FrmSimpleLst[$FrmStr] ;

	switch ($Frm['type']) {
	case 'num' :
		//NUMERIC
		if ($CheckNumeric) {
			if (is_numeric($Value)) {
				if (is_string($Value)) $Value = 0.0 + $Value ;
			} else {
				return ''.$Value ;
			}
		}
		if ($Frm['PerCent']) $Value = $Value * 100 ;
		$Value = number_format($Value,$Frm['DecNbr'],$Frm['DecSep'],$Frm['ThsSep']) ;
		return substr_replace($FrmStr,$Value,$Frm['Pos'],$Frm['Len']) ;
		break ;
	case 'date' :
		//DATE
		if (is_string($Value)) {
			if ($Value==='') return '' ;
			$x = strtotime($Value) ;
			if ($x===-1) {
				if (!is_numeric($Value)) $Value = 0 ;
			} else {
				$Value = &$x ;
			}
		} else {
			if (!is_numeric($Value)) return ''.$Value ;
		}
		if (isset($Loc->PrmLst['locale'])) {
			return strftime($Frm['str_loc'],$Value) ;
		} else {
			return date($Frm['str_us'],$Value) ;
		}
		break ;
	default:
		return $Frm['string'] ;
		break ;
	}

}

function tbs_Misc_FormatSave(&$FrmStr) {

	global $_tbs_FrmSimpleLst ;

	$nPosEnd = strrpos($FrmStr,'0') ;
	
	if ($nPosEnd!==False) {
		
		// Numeric format
		$nDecSep = '.' ;
		$nDecNbr = 0 ;
		$nDecOk = True ;
		
		if (substr($FrmStr,$nPosEnd+1,1)==='.') {
			$nPosEnd++;
			$nPosCurr = $nPosEnd ;
		} else {
			$nPosCurr = $nPosEnd - 1 ;
			while (($nPosCurr>=0) and ($FrmStr[$nPosCurr]==='0')) {
				$nPosCurr-- ;
			}
			if (($nPosCurr>=1) and ($FrmStr[$nPosCurr-1]==='0')) {
				$nDecSep = $FrmStr[$nPosCurr] ;
				$nDecNbr = $nPosEnd - $nPosCurr ;
			} else {
				$nDecOk = False ;
			}
		}

		//Thaousand separator
		$nThsSep = '' ;
		if (($nDecOk) and ($nPosCurr>=5)) {
			if ((substr($FrmStr,$nPosCurr-3,3)==='000') and ($FrmStr[$nPosCurr-4]!=='') and ($FrmStr[$nPosCurr-5]==='0')) {
				$nPosCurr = $nPosCurr-4 ;
				$nThsSep = $FrmStr[$nPosCurr] ;
			}
		}

		//Pass next zero
		if ($nDecOk) $nPosCurr-- ;
		while (($nPosCurr>=0) and ($FrmStr[$nPosCurr]==='0')) {
			$nPosCurr-- ;
		}

		//Percent
		$nPerCent = (strpos($FrmStr,'%')===false) ? false : true ;

		$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'num','Pos'=>($nPosCurr+1),'Len'=>($nPosEnd-$nPosCurr),'ThsSep'=>$nThsSep,'DecSep'=>$nDecSep,'DecNbr'=>$nDecNbr,'PerCent'=>$nPerCent) ;

	} else { //if ($nPosEnd!==False)

		// Date format
		$FrmPHP = '' ;
		$FrmLOC = '' ;
		$Local = False ;
		$StrIn = False ;
		$iMax = strlen($FrmStr) ;
		$Cnt = 0 ;

		for ($i=0;$i<$iMax;$i++) {

			if ($StrIn) {
				//We are in a string part
				if ($FrmStr[$i]===$StrChr) {
					if (substr($FrmStr,$i+1,1)===$StrChr) {
						$FrmPHP .= '\\'.$FrmStr[$i] ; //char protected
						$FrmLOC .= $FrmStr[$i] ;
						$i++ ;
					} else {
						$StrIn = False ;
					}
				} else {
					$FrmPHP .= '\\'.$FrmStr[$i] ; //char protected
					$FrmLOC .= $FrmStr[$i] ;
				}
			} else {
				if (($FrmStr[$i]==='"') or ($FrmStr[$i]==='\'')) {
					//Check if we have the opening string char
					$StrIn = True ;
					$StrChr = $FrmStr[$i] ;
				} else {
					$Cnt++ ;
					if     (strcasecmp(substr($FrmStr,$i,4),'yyyy')===0) { $FrmPHP .= 'Y' ; $FrmLOC .= '%Y' ; $i += 3 ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'yy'  )===0) { $FrmPHP .= 'y' ; $FrmLOC .= '%y' ; $i += 1 ; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'mmmm')===0) { $FrmPHP .= 'F' ; $FrmLOC .= '%B' ; $i += 3 ; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'mmm' )===0) { $FrmPHP .= 'M' ; $FrmLOC .= '%b' ; $i += 2 ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'mm'  )===0) { $FrmPHP .= 'm' ; $FrmLOC .= '%m' ; $i += 1 ; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'m'   )===0) { $FrmPHP .= 'n' ; $FrmLOC .= '%m' ; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'wwww')===0) { $FrmPHP .= 'l' ; $FrmLOC .= '%A' ; $i += 3 ; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'www' )===0) { $FrmPHP .= 'D' ; $FrmLOC .= '%a' ; $i += 2 ; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'w'   )===0) { $FrmPHP .= 'w' ; $FrmLOC .= '%u' ; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'dddd')===0) { $FrmPHP .= 'l' ; $FrmLOC .= '%A' ; $i += 3 ; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'ddd' )===0) { $FrmPHP .= 'D' ; $FrmLOC .= '%a' ; $i += 2 ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'dd'  )===0) { $FrmPHP .= 'd' ; $FrmLOC .= '%d' ; $i += 1 ; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'d'   )===0) { $FrmPHP .= 'j' ; $FrmLOC .= '%d' ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'hh'  )===0) { $FrmPHP .= 'H' ; $FrmLOC .= '%H' ; $i += 1 ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'nn'  )===0) { $FrmPHP .= 'i' ; $FrmLOC .= '%M' ; $i += 1 ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'ss'  )===0) { $FrmPHP .= 's' ; $FrmLOC .= '%S' ; $i += 1 ; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'xx'  )===0) { $FrmPHP .= 'S' ; $FrmLOC .= ''   ; $i += 1 ; }
					else {
						$FrmPHP .= '\\'.$FrmStr[$i] ; //char protected
						$FrmLOC .= $FrmStr[$i] ; //char protected
						$Cnt-- ;
					}
				}
			} //-> if ($StrIn) {...} else 

		} //-> for ($i=0;$i<$iMax;$i++) 
		
		if ($Cnt>0) {
			$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'date','str_us'=>$FrmPHP,'str_loc'=>$FrmLOC) ;
		} else {
			$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'else','string'=>$FrmStr) ;
		}

	} // if ($nPosEnd!==False) {...} else
			
}

//Check an expression typed like 'exrp1=expr2' and returns True if 'expr1' and 'expr2' are the same .
function tbs_Misc_CheckCondition($Str) {

	$Symb = '!=' ;
	$Pos = strpos($Str,$Symb) ;
	if ($Pos===False) {
		$Symb = '=' ;
		$Pos = strpos($Str,$Symb) ;
	}

	if ($Pos===False) {
		return False ;
	} else {
		$V1 = trim(substr($Str,0,$Pos)) ;
		tbs_Misc_DelDelimiter($V1,'\'') ;
		$V2 = trim(substr($Str,$Pos+strlen($Symb))) ;
		tbs_Misc_DelDelimiter($V2,'\'') ;
		if (strcasecmp($V1,$V2)==0) {
			if ($Symb==='=') {
				return True ;
			} else {
				return False ;
			}
		} else {
			if ($Symb==='=') {
				return False ;
			} else {
				return True ;
			}
		}
	}

}

//Delete the string delimiters
function tbs_Misc_DelDelimiter(&$Txt,$Delim) {
	$len = strlen($Txt) ;
	if (($len>1) and ($Txt[0]===$Delim)) {
		if ($Txt[$len-1]===$Delim) $Txt = substr($Txt,1,$len-2) ;
	}
}

//Actualize the special TBS char
function tbs_Misc_ActualizeChr() {
	$GLOBALS['tbs_ChrVal'] = $GLOBALS['tbs_ChrOpen'].'val'.$GLOBALS['tbs_ChrClose'] ;
	$GLOBALS['tbs_ChrProtect'] = '&#'.ord($GLOBALS['tbs_ChrOpen']).';' ;
}

function tbs_Misc_GetStrId($Txt) {
	$Txt = strtolower($Txt) ;
	$Txt = str_replace('-','_',$Txt) ;
	$x = '' ;
	$i = 0 ;
	$iMax = strlen($Txt2) ;
	while ($i<$iMax) {
		if (($Txt[$i]==='_') or (($Txt[$i]>='a') and ($Txt[$i]<='z')) or (($Txt[$i]>='0') and ($Txt[$i]<='9'))) {
			$x .= $Txt[$i] ;
			$i++;
		} else {
			$i = $iMax ;
		}
	}
	return $x ;
}

function tbs_Misc_ReplaceVal(&$Txt,&$Val) {
	$Txt =  str_replace($GLOBALS['tbs_ChrVal'],$Val,$Txt) ;
}

//Return the cache file path for a given Id.
function tbs_Cache_File($Dir,$CacheId,$Mask) {
	if (strlen($Dir)>0) {
		if ($Dir[strlen($Dir)-1]<>'/') {
			$Dir .= '/' ;
		}
	}
	return $Dir.str_replace('*',$CacheId,$Mask) ;
}

//Return True if there is a existing valid cache for the given file id.
function tbs_Cache_IsValide($CacheFile,$TimeOut) {
	if (file_exists($CacheFile)) {
		if (time()-filemtime($CacheFile)>$TimeOut) {
			return False ;
		} else {
			return True ;
		}
	} else {
		return False ;
	}
}

function tbs_Cache_Save($CacheFile,&$Txt) {
	$fid = @fopen($CacheFile, 'w') ;
	if ($fid===False) {
		tbs_Misc_Alert('Cache','The cache file \''.$CacheFile.'\' can not be saved.') ;
		return False ;
	} else {
		flock($fid,2) ; //acquire an exlusive lock
		fwrite($fid,$Txt) ;
		flock($fid,3) ; //release the lock
		fclose($fid) ;
		return True ;
	}
}

function tbs_Cache_DeleteAll($Dir,$Mask) {

	if (strlen($Dir)==0) {
		$Dir = '.' ;
	}
	if ($Dir[strlen($Dir)-1]<>'/') {
		$Dir .= '/' ;
	}
	$DirObj = dir($Dir) ;
	$Nbr = 0 ;
	$PosL = strpos($Mask,'*') ;
	$PosR = strlen($Mask) - $PosL - 1 ;

	//Get the list of cache files
	$FileLst = array() ;
	while ($FileName = $DirObj->read()) {
		$FullPath = $Dir.$FileName ;
		if (strtolower(filetype($FullPath))==='file') {
			if (strlen($FileName)>=strlen($Mask)) {
				if ((substr($FileName,0,$PosL)===substr($Mask,0,$PosL)) and (substr($FileName,-$PosR)===substr($Mask,-$PosR))) {
					$FileLst[] = $FullPath ;
				}
			}
		}
	}
	//Delete all listed files
	foreach ($FileLst as $FullPath) {
		@unlink($FullPath) ;
		$Nbr++ ;
	}

	return $Nbr ;

}

?>