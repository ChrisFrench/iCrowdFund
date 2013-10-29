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

Billets::load('BilletsHelperBase','helpers._base' );

if(!class_exists('BilletsHelperTicket')){
class BilletsHelperTicket extends BilletsHelperBase
{
	/**
	 * 
	 * @param $id
	 * @param $userid
	 * @return unknown_type
	 */
	public static function canView( $id, $userid )
	{
		$success = false;
		
		// if superadmin
		
		
		if (DSCAcl::isAdmin($userid)) { return true; }

		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$ticket = JTable::getInstance( 'Tickets', 'BilletsTable' );
		$ticket->load( $id );
		
		// or if ticket created by userid
		if ($ticket->sender_userid == $userid) 
		{
			return true; 
		}
		
		// or if userid is a manager of ticket category/section
		
		
		Billets::load('BilletsHelperManager','helpers.manager' );
		
		if (BilletsHelperManager::isCategory( $userid, $ticket->categoryid ))
		{
			return true; 
		}
		
		// or if ticket associated with userid
		if (BilletsHelperManager::isTicket( $userid, $ticket->id ))
		{
			return true;
		}
	
		return $success;
	}
	
	/**
	 * 
	 * @param $id
	 * @param $userid
	 * @param $fileid
	 * @return unknown_type
	 */
	public static function canViewAttachment( $id, $userid, $fileid )
	{
		$success = false;
		
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$file = JTable::getInstance( 'Files', 'BilletsTable' );
		$file->load( $fileid );
		if (empty($file->id))
		{
			return false;
		}
		
		// if file not assoc with ticket
		if ($id != $file->ticketid) 
		{ 
			return false; 
		}

		if (BilletsHelperTicket::canView( $id, $userid )) 
		{
			return true; 
		}
		
		return $success;
	}
	
	/**
	 * Shows attachment
	 * @param object Valid file object
	 * @param mixed Boolean
	 * @return array
	 */
	public static function viewAttachment( $file ) 
	{
		$success = false;
		
		Billets::load('BilletsFile','library.file' );
		$bfile = new BilletsFile();
		if ($file->fileisblob) {
			$file->filedestination = $bfile->blobToFile( $file );			
		} else {
			$dir = $bfile->getDirectory();
			$file->filedestination = $dir.DS.$file->physicalname;
		}

		//This will set the Content-Type to the appropriate setting for the file
		switch( $file->fileextension ) 
		{
			 case "pdf": $ctype="application/pdf"; break;
			 case "exe": $ctype="application/octet-stream"; break;
			 case "zip": $ctype="application/zip"; break;
			 case "doc": $ctype="application/msword"; break;
			 case "xls": $ctype="application/vnd.ms-excel"; break;
			 case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			 case "gif": $ctype="image/gif"; break;
			 case "png": $ctype="image/png"; break;
			 case "jpeg":
			 case "jpg": $ctype="image/jpg"; break;
			 case "mp3": $ctype="audio/mpeg"; break;
			 case "wav": $ctype="audio/x-wav"; break;
			 case "mpeg":
			 case "mpg":
			 case "mpe": $ctype="video/mpeg"; break;
			 case "mov": $ctype="video/quicktime"; break;
			 case "avi": $ctype="video/x-msvideo"; break;
		
			 //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
			 case "php":
			 case "htm":
			 case "html": if ($file->filedestination) die("<b>Cannot be used for ". $file->fileextension ." files!</b>"); 
		
			 // default: $ctype="application/force-download";
			 default: $ctype="application/octet-stream";	 
		}
		
        // If requested file exists
        if (file_exists($file->filedestination))
        {
            // Fix IE bug [0]
            $header_file = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? preg_replace('/\./', '%2e', $file->filename, substr_count($file->filename, '.') - 1) : $file->filename;
            // Prepare headers
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public", false);
            header("Content-Description: File Transfer");
            header("Content-Type: " . $ctype);
            header("Accept-Ranges: bytes");
            header("Content-Disposition: attachment; filename=\"" . $header_file . "\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($file->filedestination));

			//ob_end_flush();
			ob_clean();
    		flush();
			readfile($file->filedestination);
			$success = true;
			exit;
        }
		
		return $success;
		
	}
	
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public static function getMessages( $id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		//Billets::load('BilletsModelMessages','models.messages');
		$model = JModel::getInstance( 'Messages', 'BilletsModel' );
		$model->setState( 'filter_ticketid', $id );
		$model->setState( 'order', 'tbl.datetime' );
		$model->setState( 'direction', 'DESC' );
		$data = $model->getList();
		return $data;
	}
	
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public static function getComments( $id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( 'Comments', 'BilletsModel' );
		$model->setState( 'filter_ticketid', $id );
		$model->setState( 'order', 'tbl.datetime' );
		$model->setState( 'direction', 'DESC' );
		$data = $model->getList();
		return $data;
	}
	
	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public static function getAttachments( $id )
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( 'Files', 'BilletsModel' );
		$model->setState( 'filter_ticketid', $id );
		$data = $model->getList();
		return $data;
	}
	
	public static function getResponses( $id, $excluding='' )
	{
		$database = JFactory::getDBO();
		$query = "
			SELECT
				COUNT(*) 
			FROM 
				#__billets_messages AS msg
			WHERE 
				msg.ticketid = '{$id}'
				AND msg.userid_from != '{$excluding}'
			ORDER BY 
				msg.datetime ASC
		";
		$database->setQuery($query);
		$result = $database->loadResult();
		return $result;
	}
	
	/**
	 * Returns a ticket status
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getStatus( $id ) 
	{
		
		Billets::load('BilletsHelperTicketstate','helpers.ticketstate');
		
		
		$type = BilletsHelperTicketstate::getType( $id, 'id' );
		$data = JText::_( $type->title );
		return $data;
	}
	
	/**
	 * Returns a Feedback Rating
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getRating( $id ) 
	{
		switch ($id) 
		{
		  case "5": $return = JText::_('COM_BILLETS_GREAT'); break;
		  case "4": $return = JText::_('COM_BILLETS_GOOD'); break;
		  case "3": $return = JText::_('COM_BILLETS_AVERAGE'); break;
		  case "2": $return = JText::_('COM_BILLETS_POOR'); break;
		  case "1": $return = JText::_('COM_BILLETS_UNSATISFACTORY'); break;
		  default:  $return = JText::_('COM_BILLETS_UNRATED'); break;
		} 
		
		return $return;
	}

	/**
	 * Returns a Feedback Rating
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getRatingImage( $num ) 
	{
		if ($num <= '0') 					 { $id = "0"; }
		elseif ($num > '0' && $num < '0.5') { $id = "0"; }
		elseif ($num >= '0.5' && $num <= '1') { $id = "1"; }
		elseif ($num > '1' && $num < '1.5') { $id = "1.5"; }
		elseif ($num >= '1.5' && $num <= '2') { $id = "2"; }
		elseif ($num > '2' && $num < '2.5') { $id = "2.5"; }
		elseif ($num >= '2.5' && $num <= '3') { $id = "3"; }
		elseif ($num > '3' && $num < '3.5') { $id = "3.5"; }
		elseif ($num >= '3.5' && $num <= '4') { $id = "4"; }
		elseif ($num > '4' && $num < '4.5') { $id = "4.5"; }
		elseif ($num >= '4.5' && $num <= '5') { $id = "5"; }
	
		
		switch ($id) 
		{
		  case "5":   $return = "<img src='".JURI::root()."media/com_billets/images/ratings/five.png' alt='".JText::_('COM_BILLETS_GREAT')."' title='".JText::_('COM_BILLETS_GREAT')."' name='".JText::_('COM_BILLETS_GREAT')."' align='center' border='0'>"; break;
		  case "4.5": $return = "<img src='".JURI::root()."media/com_billets/images/ratings/four_half.png' alt='".JText::_('COM_BILLETS_GREAT')."' title='".JText::_('COM_BILLETS_GREAT')."' name='".JText::_('COM_BILLETS_GREAT')."' align='center' border='0'>"; break;
		  case "4":   $return = "<img src='".JURI::root()."media/com_billets/images/ratings/four.png' alt='".JText::_('COM_BILLETS_GOOD')."' title='".JText::_('COM_BILLETS_GOOD')."' name='".JText::_('COM_BILLETS_GOOD')."' align='center' border='0'>"; break;
		  case "3.5": $return = "<img src='".JURI::root()."media/com_billets/images/ratings/three_half.png' alt='".JText::_('COM_BILLETS_GOOD')."' title='".JText::_('COM_BILLETS_GOOD')."' name='".JText::_('COM_BILLETS_GOOD')."' align='center' border='0'>"; break;
		  case "3":   $return = "<img src='".JURI::root()."media/com_billets/images/ratings/three.png' alt='".JText::_('COM_BILLETS_AVERAGE')."' title='".JText::_('COM_BILLETS_AVERAGE')."' name='".JText::_('COM_BILLETS_AVERAGE')."' align='center' border='0'>"; break;
		  case "2.5": $return = "<img src='".JURI::root()."media/com_billets/images/ratings/two_half.png' alt='".JText::_('COM_BILLETS_AVERAGE')."' title='".JText::_('COM_BILLETS_AVERAGE')."' name='".JText::_('COM_BILLETS_AVERAGE')."' align='center' border='0'>"; break;
		  case "2":   $return = "<img src='".JURI::root()."media/com_billets/images/ratings/two.png' alt='".JText::_('COM_BILLETS_POOR')."' title='".JText::_('COM_BILLETS_POOR')."' name='".JText::_('COM_BILLETS_POOR')."' align='center' border='0'>"; break;
		  case "1.5": $return = "<img src='".JURI::root()."media/com_billets/images/ratings/one_half.png' alt='".JText::_('COM_BILLETS_POOR')."' title='".JText::_('COM_BILLETS_POOR')."' name='".JText::_('COM_BILLETS_POOR')."' align='center' border='0'>"; break;
		  case "1":   $return = "<img src='".JURI::root()."media/com_billets/images/ratings/one.png' alt='".JText::_('COM_BILLETS_UNSATISFACTORY')."' title='".JText::_('COM_BILLETS_UNSATISFACTORY')."' name='".JText::_('COM_BILLETS_UNSATISFACTORY')."' align='center' border='0'>"; break;
		  default:    $return = "<img src='".JURI::root()."media/com_billets/images/ratings/zero.png' alt='".JText::_('COM_BILLETS_UNRATED')."' title='".JText::_('COM_BILLETS_UNRATED')."' name='".JText::_('COM_BILLETS_UNRATED')."' align='center' border='0'>"; break;
		}
		
		return $return;
	}
	
	/**
	 * Finds the prev & next ticketids in the list 
	 *  
	 * @param $id	ticket id
	 * @return array( 'prev', 'next' )
	 */
	public static function getSurrounding( $id )
	{
		$return = array();
		
		$prev = intval( JRequest::getVar( "prev" ) );
		$next = intval( JRequest::getVar( "next" ) );
		if ($prev || $next) 
		{
			$return["prev"] = $prev;
			$return["next"] = $next;
			return $return;
		}
		
    	$app = JFactory::getApplication();
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
		$model = JModel::getInstance( 'Tickets', 'BilletsModel' );
		$ns = $app->getName().'::'.'com.billets.model.'.$model->getTable()->get('_suffix');
		$state = array();
		
		$config = Billets::getInstance();
		
        $state['limit']  	= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $state['limitstart'] = $app->getUserStateFromRequest($ns.'limitstart', 'limitstart', 0, 'int');
        $state['filter']    = $app->getUserStateFromRequest($ns.'.filter', 'filter', '', 'string');

        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.last_modified_datetime', 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'DESC', 'word');
            	
    	$state['filter_labelid']	= $app->getUserStateFromRequest($ns.'labelid', 'filter_labelid', '', '');
      	$state['filter_categoryid']	= $app->getUserStateFromRequest($ns.'categoryid', 'filter_categoryid', '', '');
      	$state['filter_stateid'] 	= $app->getUserStateFromRequest($ns.'stateid', 'filter_stateid', $config->get( 'state_new', '1' ) );
		$state['filter_managerid']		= JFactory::getUser()->id;

    	$state['filter_id_from'] 	= $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
    	$state['filter_id_to'] 		= $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
		$state['filter_title']	= $app->getUserStateFromRequest($ns.'title', 'filter_title', '', '');
		$state['filter_user']	= $app->getUserStateFromRequest($ns.'user', 'filter_user', '', '');

    	$state['filter_date_from'] 	= $app->getUserStateFromRequest($ns.'date_from', 'filter_date_from', '', '');
    	$state['filter_date_to'] 		= $app->getUserStateFromRequest($ns.'date_to', 'filter_date_to', '', '');
		$state['filter_datetype']	= $app->getUserStateFromRequest($ns.'datetype', 'filter_datetype', '', '');
		
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}
		$rowset = $model->getList();
			
		$found = false;
		$prev_id = '';
		$next_id = '';

		for ($i=0; $i < count($rowset) && empty($found); $i++) 
		{
			$row = $rowset[$i];		
			if ($row->id == $id) 
			{ 
				$found = true; 
				$prev_num = $i - 1;
				$next_num = $i + 1;
				if (isset($rowset[$prev_num]->id)) { $prev_id = $rowset[$prev_num]->id; }
				if (isset($rowset[$next_num]->id)) { $next_id = $rowset[$next_num]->id; }
	
			}
		}
		
		$return["prev"] = $prev_id;
		$return["next"] = $next_id;	
		return $return;
	}
	
	/**
	 * Returns a an array of objects containing title text, content ids and datestamps for content items created from a ticket
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getArticles($id)
	{
		$database = JFactory::getDBO();
		$query = "
			SELECT
				t2a.articleid AS articleid,
				cnt.title AS title,
				t2a.created_datetime AS created_datetime 
			FROM 
				#__billets_t2a AS t2a
			LEFT JOIN
				#__content cnt ON cnt.id = t2a.articleid
			WHERE 
				t2a.ticketid = '{$id}'
			ORDER BY 
				t2a.created_datetime ASC
		";
		
		$database->setQuery($query);
		$result = $database->loadObjectList();
		return $result;
	}
	
	/**
	 * 
	 * @param $item
	 * @return unknown_type
	 */
	public static function displayLabel( $item )
	{
		$html = "";
		if (empty($item->labelid))
		{
			return $html;
		}
		
		if (empty($item->label_color))
		{
			$item->label_color = "#AFDB80";
		}
		$color = "#000000";
		$colors = array( 'blue', 'gray', 'green', 'maroon', 'navy' ,'olive', 'purple', 'teal' );
		if (in_array($item->label_color, $colors))
		{
			$color = "#FFFFFF";
		}
		$style = "style='background-color: $item->label_color; color: $color; padding: 5px; float: left;'";
		
		$html .= "<span class='label' {$style}>";
			$html .= JText::_( @$item->label_title );
		$html .= "</span>";
		
		return $html;
	}
	
	/**
	 * 
	 * Runs an audit of the T2A table for the passed in Ticket id's and remvoes any orphan records.
	 * 
	 * @param $item
	 * @return none
	 */
	public static function removeOrphanT2A()
	{
		//If MySQL was a real database I could actually do this in one sql, but it won't let you delete
		//from a table that you are also selecting from
		
		//First get a list of all orphaned records from the billets_t2a table.
		//Check everything rather than just the records we looking at this should actually be faster.
		
		$database = JFactory::getDBO();
		$database->setQuery("
			SELECT t2a.id 
			FROM #__billets_t2a t2a
			WHERE t2a.articleid NOT IN (SELECT id FROM #__content)
		");
		$result = $database->loadResultArray();
		
		if (!empty($result))
		{
            $database->setQuery("
                DELETE FROM #__billets_t2a 
                WHERE id IN (".implode(',',$result).")
            ");
            
            if (!$database->query()) 
            {
                $this->setError( $database->stderr() );
                return false;
            }
		}
		
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $list
	 * @return void
	 */
	public static function checkinList( $list )
	{
	    if (empty($list))
	    {
	        return;
	    }
	    
	    $ids = array();
	    foreach ($list as $item)
	    {
	        $ids[] = $item->id;
	    }
	    
	    JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
        $table = JTable::getInstance( 'Config', 'BilletsTable' );
        $table->load( 'maxCheckOutTime' );
        
        $query = "UPDATE `#__billets_tickets` SET ".
        "`checked_out` = '0' , ".
        "`checked_out_time` = '0000-00-00 00:00:00' ".
        " WHERE `id` IN ( " . implode ( ", ", $ids ) . " ) ".
        " AND `checked_out` != '0' ".
        " AND `checked_out_time` < '".gmdate('Y-m-d H:i:s', time()-intval($table->value))."'";
        
        $db = JFactory::getDBO(); 
        $db->setQuery($query);
        if (!$db->query())
        {
            JFactory::getApplication()->enqueueMessage( JText::_('COM_BILLETS_CHECKINLIST_FAILED').": ".$db->getErrorMsg() );
        }
	}
	
	/**
	 * Adds a message_id ref to the files in the files array
	 * @param $files
	 * @param $message_id
	 * @return unknown_type
	 */
	public static function attachFilesToMessage( $files, $message_id )
	{
	    // TODO Add error reporting, duh
	    
	    if (empty($files) || empty($message_id))
	    {
	        return;
	    }
	    
	    JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
	    foreach ($files as $file)
	    {
            $table = JTable::getInstance( 'Files', 'BilletsTable' );
            $table->id = $file->id;
            $table->message_id = $message_id;
            $table->save();
	    }
	    
	    return;
	}
	
	public static function displayMessageFiles( $message_id=null, $files=array() )
	{
	    $html = "";
	    
	    if (empty($files) && !empty($message_id))
	    {
            JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
            $model = JModel::getInstance( 'Files', 'BilletsModel' );
            $model->setState( 'filter_messageid', $message_id );
            $files = $model->getList();	        
	    }

        if (!empty($files))
        {
            $html .= "<ul class='message_files'>";
            foreach ($files as $file)
            {
                $html .= "<li class='message_file'>";
                $html .= "<a href='index.php?option=com_billets&view=tickets&task=downloadfile&id={$file->ticketid}&fileid={$file->id}'>";
                $html .= $file->filename;
                $html .= "</a>";
                $html .= "</li>";
            }
            $html .= "</ul>";
        }
        
        return $html;
	}
}

} // End of if class_exists()
