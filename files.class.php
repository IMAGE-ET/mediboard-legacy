<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPcabinet
* @version $Revision$
* @author Romain Ollivier
*/

class CFile extends CDpObject {
	// DB Table key
	var $file_id = NULL;
	
	// DB Fields
	var $file_consultation = NULL;
	var $file_consultation_anesth = NULL;
	var $file_operation = NULL;
	var $file_real_filename = NULL;
	var $file_task = NULL;
	var $file_name = NULL;
	var $file_parent = NULL;
	var $file_description = NULL;
	var $file_type = NULL;
	var $file_owner = NULL;
	var $file_date = NULL;
	var $file_size = NULL;
	var $file_version = NULL;

	// Form fields
  var $_file_size;

	function CFile() {
		$this->CDpObject( 'files_mediboard', 'file_id' );
	}

	function check() {
	// ensure the integrity of some variables
		$this->file_id = intval( $this->file_id );
		$this->file_consultation = intval( $this->file_consultation );
		$this->file_consultation_anesth = intval( $this->file_consultation );
		$this->file_operation = intval( $this->file_operation );

		return NULL; // object is ok
	}

  function updateFormFields() {
    $bytes = $this->file_size;
    $value = $bytes;
    $unit = "o";

    $kbytes = $bytes / 1024;
    if ($kbytes >= 1) {
			$value = $kbytes;
      $unit = "Ko";
		}

    $mbytes = $kbytes / 1024;
    if ($mbytes >= 1) {
      $value = $mbytes;
      $unit = "Mo";
    }

    $gbytes = $mbytes / 1024;
    if ($gbytes >= 1) {
      $value = $gbytes;
      $unit = "Go";
    }
    
    // Value with 3 significant digits, thent the unit
    $value = round($value, $value > 99 ? 0 : $value >  9 ? 1 : 2);
    $this->_file_size = "$value $unit";
  }

	function delete() {
		global $AppUI;
	// remove the file from the file system
	    if($this->file_consultation) {
		  @unlink( "{$AppUI->cfg['root_dir']}/files/consultations/$this->file_consultation/$this->file_real_filename" );
	    }
	    elseif($this->file_consultation_anesth) {
		  @unlink( "{$AppUI->cfg['root_dir']}/files/consultations_anesth/$this->file_consultation_anesth/$this->file_real_filename" );
	    }
	    else {
		  @unlink( "{$AppUI->cfg['root_dir']}/files/operations/$this->file_operation/$this->file_real_filename" );
	    }
	// delete any index entries
		$sql = "DELETE FROM files_index_mediboard WHERE file_id = $this->file_id";
		if (!db_exec( $sql )) {
			return db_error();
		}
	// delete the main table reference
		$sql = "DELETE FROM files_mediboard WHERE file_id = $this->file_id";
		if (!db_exec( $sql )) {
			return db_error();
		}
		return NULL;
	}

// move a file from a temporary (uploaded) location to the file system
	function moveTemp( $upload ) {
		global $AppUI;
	// check that directories are created
		if (!is_dir("{$AppUI->cfg['root_dir']}/files")) {
		    $res = mkdir( "{$AppUI->cfg['root_dir']}/files", 0777 );
		    if (!$res) {
			     return false;
			 }
		}
		if($this->file_consultation) {
		  if (!is_dir("{$AppUI->cfg['root_dir']}/files/consultations/$this->file_consultation")) {
		      $res = mkdir( "{$AppUI->cfg['root_dir']}/files/consultations/$this->file_consultation", 0777 );
			   if (!$res) {
			       return false;
			   }
		  }
		  $this->_filepath = "{$AppUI->cfg['root_dir']}/files/consultations/$this->file_consultation/$this->file_real_filename";
		}
		elseif($this->file_consultation_anesth) {
		  if (!is_dir("{$AppUI->cfg['root_dir']}/files/consultations_anesth/$this->file_consultation_anesth")) {
		      $res = mkdir( "{$AppUI->cfg['root_dir']}/files/consultations_anesth/$this->file_consultation_anesth", 0777 );
			   if (!$res) {
			       return false;
			   }
		  }
		  $this->_filepath = "{$AppUI->cfg['root_dir']}/files/consultations_anesth/$this->file_consultation_anesth/$this->file_real_filename";
		}
		else {
		  if (!is_dir("{$AppUI->cfg['root_dir']}/files/operations/$this->file_operation")) {
		      $res = mkdir( "{$AppUI->cfg['root_dir']}/files/operations/$this->file_operation", 0777 );
			   if (!$res) {
			       return false;
			   }
		  }
		  $this->_filepath = "{$AppUI->cfg['root_dir']}/files/operations/$this->file_operations/$this->file_real_filename";
		}
	// move it
		$res = move_uploaded_file( $upload['tmp_name'], $this->_filepath );
		if (!$res) {
		    return false;
		}
		return true;
	}

// parse file for indexing
	function indexStrings() {
		global $ft, $AppUI;
	// get the parser application
		$parser = @$ft[$this->file_type];
		if (!$parser) {
			return false;
		}
	// buffer the file
		$fp = fopen( $this->_filepath, "rb" );
		$x = fread( $fp, $this->file_size );
		fclose( $fp );
	// parse it
		$parser = $parser . " " . $this->_filepath;
		$pos = strpos( $parser, '/pdf' );
		if (false !== $pos) {
			$x = `$parser -`;
		} else {
			$x = `$parser`;
		}
	// if nothing, return
		if (strlen( $x ) < 1) {
			return 0;
		}
	// remove punctuation and parse the strings
		$x = str_replace( array( ".", ",", "!", "@", "(", ")" ), " ", $x );
		$warr = split( "[[:space:]]", $x );

		$wordarr = array();
		$nwords = count( $warr );
		for ($x=0; $x < $nwords; $x++) {
			$newword = $warr[$x];
			if (!ereg( "[[:punct:]]", $newword )
				&& strlen( trim( $newword ) ) > 2
				&& !ereg( "[[:digit:]]", $newword )) {
				$wordarr[] = array( "word" => $newword, "wordplace" => $x );
			}
		}
		db_exec( "LOCK TABLES files_index WRITE" );
	// filter out common strings
		$ignore = array();
		include "{$AppUI->cfg['root_dir']}/modules/files/file_index_ignore.php";
		foreach ($ignore as $w) {
			unset( $wordarr[$w] );
		}
	// insert the strings into the table
		while (list( $key, $val ) = each( $wordarr )) {
			$sql = "INSERT INTO files_index VALUES ('" . $this->file_id . "', '" . $wordarr[$key]['word'] . "', '" . $wordarr[$key]['wordplace'] . "')";
			db_exec( $sql );
		}

		db_exec( "UNLOCK TABLES;" );
		return nwords;
	}
}
?>
