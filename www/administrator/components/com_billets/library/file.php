<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

class BilletsFile extends JObject 
{
	/**
	 * constructor
	 * @return void
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Returns 
	 * @param object
	 * @param object Valid JUser Object
	 * @param object Valid TableFile Object
	 * @param mixed
	 * @param integer Index of userfile array (Array ('name'=>Array (0=>a.txt, 1=>b.txt) ) ) 
	 * @return array
	 */
	function doUpload ( $ticket, $user, &$file, $fieldname='userfile', $nUserfileIndex=0 ) 
	{
		$date = JFactory::getDate();
		$database = JFactory::getDBO();
		$success = false;
		$config = Billets::getInstance();
		
		// invalid entries
		if ( !$this->handleUpload( $fieldname, $nUserfileIndex ) ) 
		{
        	return $success;
		}	
				
		// invalid ticket
		if ( !$ticket->id ) 
		{
        	$this->setError( JText::_('COM_BILLETS_INVALID_TICKET') );
        	return $success;
		}

		// ticket not assoc with user
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		
		if ( !$canView = BilletsHelperTicket::canView( $ticket->id, $user->id ) ) 
		{
        	$this->setError( JText::_('COM_BILLETS_CANNOT_VIEW_TICKET') );
        	return $success;
		}		

		// if config says to use blob, create blob
		$storeasblob = $config->get( 'files_storagemethod', '1' );
		if ($storeasblob == '2') {
			$this->fileToBlob();			
		} else {
			// otherwise, store as file
			$this->fileisblob = 0;
			$this->setDirectory();
		}
	
		// create random physical name
		$physicalname = $this->createPhysicalName();
						
		// store the file
		$file->physicalname		= $physicalname;
		$file->ticketid			= $ticket->id;
		$file->userid			= $user->id;
		$file->datetime			= $date->toMysql();
		$file->filename			= $this->proper_name;
		$file->fileextension	= $this->extension;
		$file->filesize			= $this->size;
		$file->fileisblob		= $this->fileisblob;

		if ( $this->appendTxtExtension() ) {
			$file->filename			.= ".txt";
			$file->fileextension	.= ".txt";
		}

		if ( $file->save() ) 
		{
			$success = true;
		}
			
		if ($file->fileisblob == '1') 
		{
		    $data = $database->Quote( $this->fileasblob );
			$query = " INSERT INTO #__billets_fileblobs " 
					. " SET `fileid` = '".$file->id."', "
					. " `fileblob` = {$data} "
			;
			$database->setQuery( $query );
			$database->query();
		} 
			else 
		{
			// move the file to the appropriate dir
			$dest = $this->_directory.DS.$file->physicalname;
			JFile::upload($this->file_path, $dest);		
		}
				
		return $success;
	}
	
	/**
	 * Returns 
	 * @param object
	 * @return boolean
	 */
	function removeUploads ($ticketID) 
	{
		$database = JFactory::getDBO();
		$success = false;
		$config = Billets::getInstance();
		
		//I am deliberately not going to check if uploads are enabled, 
		//  because they might have been disabled after files were uploaded.
		//We always want to check for the existence of files and remove any that exist.
		
		//Remove uploads is also not going to be called directly, and should only be called by the delete model
		//  action itself, so we don't need to check permissions either, as they should already have been checked. 
		//In other words : If they have permissions to delete a ticket, then they can remove the attachments associated with it. 
		Billets::load('BilletsHelperTicket', 'BilletsHelperTicket');	
		$files = BilletsHelperTicket::getAttachments( $ticketID );
		
		//only execute our delete statements if we actually have files to remove 
		if (count($files))
		{
			// if config says to use blob, create blob
			$storeasblob = $config->get( 'files_storagemethod', '1' );
			
			$fileIDs = array();
			
			//If the atachments are being stored as blobs, build a list of the id's and delete them all at once.
			if ($storeasblob == '2') {
				
				foreach (@$files as $file) 
				{
					// compile a list of ids, to build our delete sql from
					$fileIDs[] = $file->id;
					
				}
				
				//delete the file blob records
				$query = " DELETE FROM #__billets_fileblobs " 
						. " WHERE `fileid` IN ('".implode("','", $fileIDs)."')"
				;
				$database->setQuery( $query );
				$database->query();
				
				//delete the actual file information records
				//We probably should use the table object, 
				//  but it seems like such a waste to load each one individually to remove it
				$query = " DELETE FROM #__billets_files " 
						. " WHERE `id` IN ('".implode("','", $fileIDs)."')"
				;
				$database->setQuery( $query );
				$database->query();
											
			} else {
				//If the attachments are being stored as physical files, loop through the list and unlink each file
				$dir = $this->getDirectory();
				
				if (!$exists = JFolder::exists( $dir ) || !is_writable($dir)) {
					if (!is_writable($dir)) {
						//If the directory exists but is not writable, then return an error
						$this->setError( JText::_('COM_BILLETS_ATTACHMENT_DIRECTORY_NOT_ACCESSIBLE') );
	        			return $success;
					}
					
					//if the folder doesn't exist then no attachments have been created yet, so just finish here
					$success = true;
					return $success;
				} // end if (!$exists = JFolder::exists( $dir ) || !is_writable($dir)) {
				
				foreach (@$files as $file) 
				{					
					// move the file to the appropriate dir
					$target = $this->_directory.DS.$file->physicalname;
					if (!JFile::delete($target))
					{
						$this->setError( JText::_('COM_BILLETS_ERROR_DELETING_ATTACHMENT_FILES') );
        				return $success;
					}
					
					// compile a list of ids, to build our delete sql from
					$fileIDs[] = $file->id;
				}
				
				//delete the actual file information records
				//We probably should use the table object, 
				//  but it seems like such a waste to load each one individually to remove it
				$query = " DELETE FROM #__billets_files " 
						. " WHERE `id` IN ('".implode("','", $fileIDs)."')"
				;
				$database->setQuery( $query );
				$database->query();
				
			} // end if ($storeasblob == '2')
		}// end if (count($files))
		
		$success = true;		
		return $success;
	}
	
	/**
	 * Returns a list of types
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function setDirectory() 
	{
		$success = false;

		// checks to confirm existence of directory
		// then confirms directory is writeable		
		$dir = $this->getDirectory();
		if (!$exists = JFolder::exists( $dir ) ) {
			$create = JFolder::create( $dir );
			if (!is_writable($dir)) {
				$change = JPath::setPermissions( $dir );
			}
		} else {
			if (!is_writable($dir)) {
				$change = JPath::setPermissions( $dir );
			}
		}

		// then confirms existence of htaccess file
		$htaccess = $dir.DS.'.htaccess';
		if (!$fileexists = JFile::exists( $htaccess ) ) {

			$destination = $htaccess;
			$text = "deny from all";
			
			if ($f = @fopen($destination,'wb')) {
				fwrite ( $f, $text );
				fclose($f);
			}

		}
				
		$success = true;
		return $success;
	}

	/**
	 * Returns a list of types
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function getDirectory () 
	{
		if (!isset($this->_directory)) 
		{
			// checks config for upload store directory?
			$this->_directory = JPATH_SITE.DS.'images'.DS.'attachmentsbillets';
		}
		return $this->_directory;
	}
		
	/**
	 * Returns 
	 * @param mixed Boolean
	 * @param integer Index of userfile array (Array ('name'=>Array (0=>a.txt, 1=>b.txt) ) )
	 * @return object
	 */
	function handleUpload ($fieldname='userfile', $nUserfileIndex=0) 
	{
		$success = false;
		$config = Billets::getInstance();
		
		// Check if file uploads are enabled
		if (!(bool)ini_get('file_uploads')) {
			$this->setError( JText::_('COM_BILLETS_UPLOADS_DISABLED') );
			return $success;
		}
	
		// Check that the zlib is available
		if(!extension_loaded('zlib')) {
			$this->setError( JText::_('COM_BILLETS_ZLIB_UNAVAILABLE') );
			return $success;
		}

		// check that upload exists
		$userfile = JRequest::getVar( $fieldname, '', 'files', 'array' );
		if (!$userfile) 
		{
			$this->setError( JText::_('COM_BILLETS_NO_FILE' ) );
			return $success;
		}
		
		$this->proper_name = basename($userfile['name'][$nUserfileIndex]);
		
		if ($userfile['size'][$nUserfileIndex] == 0) {
			$this->setError( JText::_( 'COM_BILLETS_INVALID_FILE') );
			return $success;
		}
		
		$this->size = $userfile['size'][$nUserfileIndex]/1024;		
		// check size of upload against max set in config
		if($this->size > $config->get( 'files_maxsize', '3000' ) ) 
		{
			$this->setError( JText::_('COM_BILLETS_INVALID_FILE_SIZE') );
			return $success;
	    }
		$this->getExtension();

		// restricted file extension handling
		if ( ( $this->hasRestrictedExtension() ) && ( $config->get( 'auto_convert_file_extension' ) == 0 ) ) {
			$this->setError( JText::_('COM_BILLETS_FILE_EXTENSION_IS_RESTRICTED' ) . ' .' . $this->extension );
			return $success;
		}

	    $this->size = number_format( $this->size, 2 ).' Kb';
		
		if (!is_uploaded_file($userfile['tmp_name'][$nUserfileIndex])) 
		{
			$this->setError( JText::_('COM_BILLETS_INVALID_FILE') );
			return $success;
	    } 
	    else 
	    {
	    	$this->file_path = $userfile['tmp_name'][$nUserfileIndex];
		}
		
		$this->getExtension();
		$this->uploaded = true;
		$success = true;
		
		return $success;
	}

	/**
	 * Returns a list of types
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function getExtension () 
	{
		if (!isset($this->extension)) 
		{
			$namebits = explode('.', $this->proper_name);
			$this->extension = $namebits[count($namebits)-1];
		}
				
		return $this->extension;
	}

	/**
	 * Returns a unique physical name
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function createPhysicalName() 
	{
		$dir = $this->getDirectory();
		$extension = $this->getExtension();
		$name = JUtility::getHash( time() );
		$physicalname = $name.".".$extension;
		
		while ($fileexists = JFile::exists( $dir.DS.$physicalname ) ) 
		{
			$name = JUtility::getHash( time() );
			$physicalname = $name.".".$extension;
		}
				
		return $physicalname;
	}	

	/**
	 * Returns 
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function fileToText () 
	{
		$database = JFactory::getDBO();
		$source = $this->file_path;
		$success = false;
		
		if ($f = @fopen($source,'rb')) 
		{
			$sql = "";
			while($f && !feof($f)) 
			{
				$chunk = fread($f, 65535);
				$sql .= $chunk;
			}
			fclose($f);
			$this->fileastext = $sql;
			$success = true;
			return $success;
		} 
			else 
		{
			return $success;
		}
	}

	/**
	 * Returns 
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function fileToBlob () 
	{
		$database = JFactory::getDBO();
		$source = $this->file_path;
		$success = false;
		
		if ($f = @fopen($source,'rb')) 
		{
			$sql = "";
			while($f && !feof($f)) 
			{
				$chunk = fread($f, 65535);
				$sql .= $chunk;
			}
			fclose($f);
			$this->fileasblob = $sql;
			$this->fileisblob = '1';
			$success = true;
			return $success;
		} else {
			return $success;
		}
	}
		
	/**
	 * Returns
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function textToFile ( $file, $temppath='' ) 
	{
		global $mainframe;
		$result = false;
		if (!$temppath || !is_writable($temppath)) 
		{
			$temppath = $mainframe->getCfg( 'config.tmp_path' );
		}
		
		$destination = $temppath.DS.$file->filename;

		if ( $this->appendTxtExtension() ) {
			$destination	.= ".txt";
		}

		if ($f = @fopen($destination,'wb')) {
			if ($file->filetext AND fwrite ( $f, $file->filetext )) { $result = $destination; }
			fclose($f);
		}
		
		return $result;
	}
	
	/**
	 * Returns
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	function blobToFile ( $file, $temppath='' ) 
	{
		$app = JFactory::getApplication();
		if (!$temppath || !is_writable($temppath)) 
		{
			$temppath = $app->getCfg( 'config.tmp_path' );
		}
		
		$destination = $temppath.DS.$file->filename;
		
		// set $this properties
		$this->proper_name = $file->filename;

		if ( $this->appendTxtExtension() ) {
			$destination	.= ".txt";
		}

		if (empty($file->fileblob))
		{
		    $app->enqueueMessage( 'Fileblob Empty' );
		    return false;
		}
		
		if (!JFile::write( $destination, $file->fileblob ))
		{
		    $error = JError::getError();
		    $app->enqueueMessage( $error->message );
		    return false;
		} 
		
		return $destination;
	}	

	/**
	 * Retrieves the values
	 * @return array Array of objects containing the data from the database
	 */
	public static function getStorageMethods() 
	{
		static $instance;
		
		if (!is_array($instance)) {
			$instance = array();
				$instance_file = new stdClass();
				$instance_file->id = 1;
				$instance_file->title = 'File';
			$instance[0] = $instance_file;
				$instance_blob = new stdClass();
				$instance_blob->id = 2;
				$instance_blob->title = 'Blob';
			$instance[1] = $instance_blob;

		}

		return $instance;
	}
	
	/**
	 * Creates a List
	 * @return array Array of objects containing the data from the database
	 */
	public static function getArrayListStorageMethods() 
	{
		static $instance;
		
		if (!is_array($instance)) {
			$instance = array();
			$data = BilletsFile::getStorageMethods();
			for ($i=0; $i<count($data); $i++) {
				$d = $data[$i];
		  		$instance[] = JHTML::_('select.option', $d->id, JText::_( $d->title ) );
			}
		}

		return $instance;

	}

	/**
	 * Tells if file extension is restricted
	 * @return boolean
	 */
	function hasRestrictedExtension()
	{
		$config = Billets::getInstance();

		$restricted_file_extensions		= trim ( strtolower( $config->get( 'restricted_file_extensions', 'php,html,asp,aspx,jsp,py,htm,shtml,shtm' ) ) );
		if ( $restricted_file_extensions ) {
			$namebits = explode('.', $this->proper_name);
			$extension = $namebits[count($namebits)-1];
			if ( preg_match( '/'.$extension.'/', $restricted_file_extensions ) ) {
				return true;
			}
		}

		return false;
	}

	/*
	* Returns true or false for appending .txt extension to the restricted file
	* @return boolean
	*/
	function appendTxtExtension()
	{
		$config = Billets::getInstance();
		if ( ( $this->hasRestrictedExtension() ) && ( $config->get( 'auto_convert_file_extension' ) == 1 ) ) {
			return true;
		}
		return false;
	}	
}

?>
