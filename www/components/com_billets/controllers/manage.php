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
defined( '_JEXEC' ) or die( 'Restricted access' );

class BilletsControllerManage extends BilletsController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
        // If user is not a manager or superadmin, redirect
        Billets::load('BilletsHelperManager', 'helpers.manager');
        $task = strtolower( JRequest::getVar('task') );
        if (!BilletsHelperManager::isUser( JFactory::getUser()->id ) && $task != 'view' )  
        {
            $this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
            $this->messagetype = 'notice';
            $this->setRedirect( "index.php", $this->message, $this->messagetype );
            return;
        }
				
		$this->set('suffix', 'manage');
		$this->registerTask( 'flag_read', 'flag' );
		$this->registerTask( 'flag_unread', 'flag' );
		$this->registerTask( 'flag_archived', 'flag' );
		$this->registerTask( 'flag_unarchived', 'flag' );
		$this->registerTask( 'flag_deleted', 'flag' );
		$this->registerTask( 'addlabel', 'setlabel' );
		$this->registerTask( 'removelabel', 'setlabel' );
		$this->registerTask( 'selected_enable', 'selected_switch' );
		$this->registerTask( 'add', 'add' );
		
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
    function _setModelState()
    {
    	$this->set('suffix', 'manage');
		$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model 	= $this->getModel( 'manage' );
    	$ns = $this->getNamespace();
		
    	$config = Billets::getInstance();

        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.last_modified_datetime', 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'ASC', 'word');
    	
        $state['filter_labelid']	= $app->getUserStateFromRequest($ns.'labelid', 'filter_labelid', '', '');
      	$state['filter_categoryid']	= $app->getUserStateFromRequest($ns.'categoryid', 'filter_categoryid', '', '');
      	$state['filter_stateid'] 	= $app->getUserStateFromRequest($ns.'stateid', 'filter_stateid', $config->get( 'state_new', '1' ) );
		$state['filter_managerid']	= JFactory::getUser()->id;
		
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
  		return $state;
    }
    
    /**
     * 
     * 
     */
    function display($cachable=false, $urlparams = false)
    {
		$model 	= $this->getModel( $this->get('suffix') );
		$this->_setModelState();
		$list = $model->getList();
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		BilletsHelperTicket::checkinList( $list );
		
		$view	= $this->getView( 'manage', 'html' );
		$view->set( '_controller', 'manage' );
		$view->set( '_view', 'manage' );
		$view->set( '_action', "index.php?option=com_billets&view=manage" );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->setLayout( 'default' );
		$view->setTask(true);
		parent::display($cachable, $urlparams);
    }
    
	/**
	 * @return void
	 */
	function view() 
	{
		$redirect = "index.php?option=com_billets&view=manage";
    	$redirect = JRoute::_( $redirect, false );
	    	
		$user = JFactory::getUser();
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		if (empty($row->id)) 
		{
			$this->message = JText::_('COM_BILLETS_INVALID_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		// message not assoc with user		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $row->id, $user->id )) 
		{
			$redirect = "index.php?option=com_billets&view=manage";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		// Check-in ticket if already checked-out for more than the specified amount of time
		// (by the same or another manager)
		Billets::load('BilletsHelperDiagnostics', 'helpers.diagnostics');
		BilletsHelperDiagnostics::tryCheckInTicket($row->id);
		
		// Checkout the ticket if it isn't already checked out
		if (!JRequest::getVar('donotcheckout', '0'))
		{
			$model 	= $this->getModel( $this->get('suffix') );
		    $row = $model->getTable();
		    $row->load( $model->getId() );
			if (empty($row->checked_out))
			{
				if (!Billets::getInstance()->get('locking_enabled') || $row->checkout( JFactory::getUser()->id ))
				{
					JRequest::setVar('hidemainmenu', '1');
				}
			}
		}
		
		JRequest::setVar( 'view', $this->get('suffix') );
		JRequest::setVar( 'layout', 'view' );
		
		$view	= $this->getView( 'manage', 'html' );
		$view->set( '_controller', 'manage' );
		$view->set( '_view', 'manage' );
		$view->set( '_action', "index.php?option=com_billets&view=manage" );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->setLayout( 'view' );
		
		JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        $model = JModel::getInstance( 'Files', 'BilletsModel' );
        $model->setState( 'filter_ticketid', $model->getId() );
        $model->setState( 'filter_messageid', '0' );
        $files = $model->getList();
        $view->assign( 'files', $files );
        
        //List of tickets by user
        $numberOfTickets = Billets::getInstance()->get( 'number_of_tickets_links' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        $model = JModel::getInstance( 'Tickets', 'BilletsModel' );
        $model->setState( 'filter_user', $row->sender_userid );
        $model->setState('limit', $numberOfTickets);
        $model->setState( 'order', 'tbl.created_datetime' );
        $model->setState( 'direction', 'DESC' );
        $ticket_list = $model->getList();
        $view->assign( 'ticket_list', $ticket_list );
        
		parent::display();
	}
	
    /**
     * Displays a form
     * @see billets/site/BilletsController#edit()
     */
    function add()
    {
        JRequest::setVar( 'hidemainmenu', '1' );
        JRequest::setVar( 'view', $this->get('suffix') );
        JRequest::setVar( 'layout', 'form' );
        parent::display();
    }
	
	/**
	 * @return void
	 */
	function edit() 
	{
		$redirect = "index.php?option=com_billets&view=manage";
    	$redirect = JRoute::_( $redirect, false );
	    	
		$user = JFactory::getUser();
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		if (empty($row->id)) 
		{
			$this->message = JText::_('COM_BILLETS_INVALID_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		// message not assoc with user		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $row->id, $user->id )) 
		{
			$redirect = "index.php?option=com_billets&view=manage";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}

		// Checks if item is checkedout, and if so, redirects to view
		JRequest::setVar( 'view', $this->get('suffix') );
		$model 	= $this->getModel( $this->get('suffix') );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
	    $userid = JFactory::getUser()->id;
	    
		if (empty($row->checked_out) || $userid == $row->checked_out)
		{
			if (!Billets::getInstance()->get('locking_enabled') || $row->checkout( $userid ))
			{
				JRequest::setVar( 'hidemainmenu', '1' );
				JRequest::setVar( 'layout', 'form' );
				parent::display();
			}
		} 
			else
		{
			JRequest::setVar( 'layout', 'view' );
			parent::display();
		}
	}
	
	function release() 
	{
		$model 	= $this->getModel( $this->get('suffix') );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
	    $userid = JFactory::getUser()->id;
		if (!empty($row->checked_out) && $userid == $row->checked_out )
		{
			if ($row->checkin())
			{
				$this->message = JText::_('COM_BILLETS_ITEM_RELEASED');
			}
		}
		
    	$redirect = "index.php?option=com_billets&view=".$this->get('suffix')."&task=view&id=".$model->getId()."&donotcheckout=1";
    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );		
	}
	
	/**
	 * Verifies the fields in a submitted form.  Uses the table's check() method.
	 * Will often be overridden. Is expected to be called via Ajax 
	 * 
	 * @return unknown_type
	 */
	function validate()
	{
		$response = array();
		$response['msg'] = '';
		$response['error'] = '';
			
		// get elements from post
			$patterns = array ('/[\n\r]+/','/[\t]+/');
			$replace = array ('\n', '   ');
			$elements = json_decode( preg_replace($patterns, $replace, JRequest::getVar( 'elements', '', 'post', 'string' ) ) );

			// validate it using table's ->check() method
			if (empty($elements))
			{
				// if it fails check, return message
				$response['error'] = '1';
				$response['msg'] = '
					<dl id="system-message">
					<dt class="notice">notice</dt>
					<dd class="notice message fade">
						<ul style="padding: 10px;">'.
						JText::_('COM_BILLETS_COULD_NOT_PROCESS_FORM').": ".BilletsConfig::dump( $elements )						
						.'</ul>
					</dd>
					</dl>
					';
				echo ( json_encode( $response ) );
				return;
			}
			
		// convert elements to array that can be binded 			
			Billets::load('BilletsHelperBase', 'helpers._base');
			$values = BilletsHelperBase::elementsToArray( $elements );
			$email = $values['user_email'];

		// get table object
			$table = $this->getModel( $this->get('suffix') )->getTable();
		
		// bind to values
			$table->bind( $values );
			
		// then check if email field is needed
			$config = Billets::getInstance();
			$require_login = $config->get( 'require_login', '1' );
			$user = JFactory::getUser();
			if (!$require_login && !$user->id && empty($newuser_email)) 
			{
				// TODO Fix this so it intelligently checks if a valid email is submitted 
				$table->sender_userid = '1';	
			}
			
		// check if user is selected
			if(empty($table->sender_userid))
			{
				// if not, than check if email is entered
				if(empty($email))
				{
					$response['error'] = '1';
					$response['msg'] = '
					<dl id="system-message">
					<dt class="notice">notice</dt>
					<dd class="notice message fade">
						<ul style="padding: 10px;">'.
						JText::_('COM_BILLETS_YOU_MUST_SELECT_USER_OR_ENTER_AN_EMAIL')						
						.'</ul>
					</dd>
					</dl>
					';
					echo ( json_encode( $response ) );					
					return;	
				}
				else 
				{
					Billets::load('BilletsHelperUser', 'helpers.user');
											
					// check if entered email doesn't exsist and if email string is ok				
					if(!BilletsHelperUser::emailExists($email) && !BilletsHelperUser::validateEmailAddress($email))
					{
						$response['error'] = '1';
						$response['msg'] = '
						<dl id="system-message">
						<dt class="notice">notice</dt>
						<dd class="notice message fade">
							<ul style="padding: 10px;">'.	
							JText::sprintf('COM_BILLETS_EMAIL_ADDRESS_SPECIFIED_IS_INVALID', $email)					
							.'</ul>
						</dd>
						</dl>
						';
						echo ( json_encode( $response ) );					
						return;
					}
					
					$table->sender_userid = '999999';
					
					// validate it using table's ->check() method
					if (!$table->check())
					{
						// if it fails check, return message
						$response['error'] = '1';
						$response['msg'] = '
							<dl id="system-message">
							<dt class="notice">notice</dt>
							<dd class="notice message fade">
								<ul style="padding: 10px;">'.
								$table->getError()						
								.'</ul>
							</dd>
							</dl>
							';
						echo ( json_encode( $response ) );					
						return;
					}
				}				
			}
			else 
			{
				// validate it using table's ->check() method
				if (!$table->check())
				{
					// if it fails check, return message
					$response['error'] = '1';
					$response['msg'] = '
						<dl id="system-message">
						<dt class="notice">notice</dt>
						<dd class="notice message fade">
							<ul style="padding: 10px;">'.
							$table->getError()						
							.'</ul>
						</dd>
						</dl>
						';
					echo ( json_encode( $response ) );					
					return;
				}
			}
		
			
		// get table object
			JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
			$ticketdata = JTable::getInstance( 'Ticketdata', 'BilletsTable' );
		
		// bind to values
			$ticketdata->bind( $values );
			$ticketdata->_categoryid = $table->categoryid;
		
		// validate it using table's ->check() method
			if (!$ticketdata->check())
			{
				// if it fails check, return message
				$response['error'] = '1';
				$response['msg'] = '
					<dl id="system-message">
					<dt class="notice">notice</dt>
					<dd class="notice message fade">
						<ul style="padding: 10px;">'.
						$ticketdata->getError()						
						.'</ul>
					</dd>
					</dl>
					';
			}

		echo ( json_encode( $response ) );
		return;
	}
	
	/**
	 * save a record
	 * @return void
	 */
	function save() 
	{
		
		$this->checkToken();
		
		$model 	= $this->getModel( $this->get('suffix') );
		
	    $row = $model->getTable();
	    $row->load( $model->getId() );
	    
	    //Save old hours to keep userdata synced
	    $hours = $row->hours_spent;
	    
		$row->bind( JRequest::get( 'post', 2 ) );
		$row->_isNew = empty($row->id);
		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $row->id, JFactory::getUser()->id ) && !$row->_isNew) 
		{
			$redirect = "index.php?option=com_billets&view=manage";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
				
		if($row->sender_userid == 0)
		{
			$email = JRequest::getVar( 'user_email' );
			Billets::load('BilletsHelperUser', 'helpers.user');
			if(BilletsHelperUser::emailExists($email))
			{
				$row->sender_userid = BilletsHelperUser::getUserIdFromEmail($email);
			}
			else 
			{
				// register new user, if string is valid for email	
				jimport('joomla.user.helper');			
						
				$details['name'] 		= BilletsHelperUser::createUsernameFromEmail($email);
				$details['username'] 	= $details['name'];
				$details['email'] 		= $email;
				$details['password'] 	= JUserHelper::genRandomPassword();
				$details['password2'] 	= $details['password'];
											
				$msg = new JObject();
						
				$user = BilletsHelperUser::createNewUser( $details, $msg ); 
				
				$row->sender_userid = $user->id;
				$row->sender_email	= $user->email;			
			}
		}
		
		if ( $row->save() ) 
		{
			// Check UserData for hours
			if($hours != $row->hours_spent)
			{
				// hours have been modified. Update userdata
				JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
				$userdata = JTable::getInstance('Userdata', 'BilletsTable');
				$userdata->load( array('user_id'=>$row->sender_userid) );
				$userdata->hour_count = $userdata->hour_count - $hours + $row->hours_spent;
				$userdata->store();
			}
			
			$model->setId( $row->id );
			$this->messagetype 	= 'message';
			$this->message  	= JText::_('COM_BILLETS_SAVED');
			
			$attachment = $this->addfile();
			if ($this->getError()) 
			{
				$this->messagetype 	= 'notice';
				$this->message .= " & ".$this->getError();
			}
			
			// convert bbcode if present
			$fulltext = $row->description;
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
			$row->description = $fulltext;
			
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSaveTickets', array( $row ) );
		} 
			else 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." - ".$row->getError();
		}
		
    	$redirect = "index.php?option=com_billets";
    	$task = JRequest::getVar('task');
    	switch ($task)
    	{
    		case "savenew":
    			$redirect .= '&view='.$this->get('suffix').'&task=add';
    		  break;
    		case "apply":
    			$redirect .= '&view='.$this->get('suffix').'&task=edit&id='.$model->getId();
    		  break;
    		case "save":
    		default:
    			$redirect .= '&view='.$this->get('suffix').'&task=view&id='.$model->getId();
    		  break;
    	}

    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Checks in the current ticket and displays the previous one in the list
	 * @return unknown_type
	 */
	function prev() 
	{
		$model 	= $this->getModel( $this->get('suffix') );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
		if (!empty($row->checked_out) && JFactory::getUser()->id == $row->checked_out)
		{
			$row->checkin();
		}
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		$surrounding = BilletsHelperTicket::getSurrounding( $model->getId() );
    	$redirect = "index.php?option=com_billets&view=manage&task=view&id=".$surrounding['prev'];
    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );		
	}

	/**
	 * Checks in the current ticket and displays the next one in the list
	 * @return unknown_type
	 */
	function next() 
	{
		$model 	= $this->getModel( $this->get('suffix') );
	    $row = $model->getTable();
	    $row->load( $model->getId() );
		if (!empty($row->checked_out) && JFactory::getUser()->id == $row->checked_out)
		{
			$row->checkin();
		}
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		$surrounding = BilletsHelperTicket::getSurrounding( $model->getId() );
    	$redirect = "index.php?option=com_billets&view=manage&task=view&id=".$surrounding['next'];
    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );		
	}
	
	/**
	 * Adds a comment to an existing ticket
	 * @return unknown_type
	 */
	function addcomment()
	{
		$model 	= $this->getModel( $this->get('suffix') );
		$redirect = "index.php?option=com_billets&view=manage&task=view&id=".$model->getId();
		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		$surrounding = BilletsHelperTicket::getSurrounding( @$row->id );
		$prev = intval( $surrounding["prev"] );
		$next = intval( $surrounding["next"] );
		$redirect .= "&prev={$prev}&next={$next}";
		$redirect = JRoute::_( $redirect, false );
    	
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $model->getId(), JFactory::getUser()->id )) 
		{
			$redirect = "index.php?option=com_billets&view=manage";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
    	
	    $row = $model->getTable( 'Messages');
		$post = JRequest::get( 'post', 2 );
		$row->message = $post['message'];
		$attachment = $this->addfile();
		if (!$row->check() && !$attachment )
		{
			// no message and no attachment, error
			$this->messagetype 	= 'notice';
			$this->message 		= JText::_('COM_BILLETS_ERROR')." :: ".$row->getError();
			if ($this->getError()) 
			{
				$this->message .= " & ".$this->getError();
			}
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		elseif (!$row->check() && $attachment )
		{
			// no message but yes attachment
		    $row->message = JText::_('COM_BILLETS_FILES_ATTACHED') . ": ";
            $n = 0;
            foreach ($this->files as $file)
            {
                if ($n > 0) { $row->message .= ", "; } 
                $row->message .= $file->filename;
                $n++;
            }
		}
		
		$row->ticketid = $model->getId();
		$row->userid_from = JFactory::getUser()->id;

		if ( $row->save() ) 
		{
            // Attach files to Message
            BilletsHelperTicket::attachFilesToMessage( $this->files, $row->id );
            
			$date = JFactory::getDate();
			$ticket = $model->getTable( 'Tickets' );
			$ticket->load( $model->getId() );
			$ticket->last_modified_datetime = $date->toMysql();
			if ($ticket->firstresponse_by == '0') 
			{
				$date = JFactory::getDate();
				$ticket->firstresponse_by = JFactory::getUser()->id; 
				$ticket->firstresponse_datetime = $date->toMysql();
			}

			$config = Billets::getInstance();
			$ticket->stateid = $config->get( 'state_feedback', '3' );
			$ticket->hours_spent  = $ticket->hours_spent + $post['hours_spent'];
			
			if ($ticket->save())
			{
				// get userdata.
				JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
				$userdata = JTable::getInstance('Userdata', 'BilletsTable');
				$userdata->load( array('user_id'=>$ticket->sender_userid) );
				$userdata->hour_count = $userdata->hour_count + $post['hours_spent'];
				$userdata->store();
				
				$this->messagetype 	= 'message';
				$this->message  	= JText::_('COM_BILLETS_NEW_COMMENT_ADDED');
				if ($attachment)
				{
					$this->message = JText::_('COM_BILLETS_NEW_COMMENT_AND_ATTACHMENT_ADDED');
					
				    // append list of attachments to message
                    $row->message .= "<br/><br/>" . JText::_('COM_BILLETS_FILES_ATTACHED') . ": ";
                    $n = 0;
                    foreach ($this->files as $file)
                    {
                        if ($n > 0) { $row->message .= ", "; } 
                        $row->message .= $file->filename;
                        $n++;
                    }
				}
				
				// convert bbcode if present
				$fulltext = $row->message;
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
				$ticket->description = $fulltext;
				$ticket->_comment_user = JFactory::getUser();
				
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterSaveComment', array( $ticket ) );
			}
				else
			{
				$this->messagetype 	= 'notice';			
				$this->message 		= JText::_('COM_BILLETS_NEW_COMMENT_ADDED_BUT_TICKET_SAVE_FAILED')." :: ".$row->getError();				
			}
		} 
			else 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." :: ".$row->getError();
		}
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Adds a file to an existing ticket
	 * @return unknown_type
	 */
	function addfile()
	{
		$model 	= $this->getModel( $this->get('suffix') );
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $model->getId(), JFactory::getUser()->id )) 
		{
			$redirect = "index.php?option=com_billets&view=manage";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		$user = JFactory::getUser();
		
		$ticket = $model->getTable( 'Tickets' );
		$ticket->load((int) $model->getId() );

		$this->files = array();
		Billets::load('BilletsFile', 'library.file');
		$upload = new BilletsFile();
		$userfiles = JRequest::getVar( 'userfile', '', 'files', 'array' );
		if ( !is_array( $userfiles ) ) {
			$userfiles	= array( $userfiles );
		}
			if ( count( $userfiles['name'] ) && ( !empty( $userfiles['name'][0] ) ) ) {
			$has_error	= false;
			$nIndex	= 0;
			foreach ( $userfiles['name'] as $userfile ) {
				if ( !isset( $userfiles['size'][$nIndex] ) || ( !$userfiles['size'][$nIndex] ) ) {
					$this->setError( sprintf( JText::_('COM_BILLETS_FILE_IS_INVALID_OR_NOT_PROVIDED'), ($nIndex+1) ) );
					$has_error	= true;
				}
				else {
					
					$file = $model->getTable( 'Files');
					$result	= $upload->doUpload( $ticket, $user, $file, 'userfile', $nIndex );
					if (!$result) {
						$this->setError( $upload->getError() );
						$has_error	= true;
					}
				    else
                    {
                        // track the files
                        $this->files[] = $file;
                    }
				}
				$nIndex++;
			}
	
			if ( $has_error ) {
				return false;
			}
		}
	    else
        {
            // no files
            return null;
        }
        
		$date = JFactory::getDate();
		$ticket->last_modified_datetime = $date->toMysql();

   		// Store the entry to the database
    	if (!$ticket->save()) 
    	{
        	$this->setError( $ticket->getError() );
        	return false;
    	}
    	
    	return true;
	}
	
	/**
	 * Moves a ticket to a new category
	 * @return unknown_type
	 */
	function moveticket()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();

		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
		foreach (@$cids as $cid)
		{
			$row->load($cid);
			$row->categoryid = JRequest::getVar( 'apply_categoryid', '0', 'post', 'int' );
			if (!$row->save())
			{
				$errors[] = $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_UNABLE_TO_CHANGE').": ".implode(", ", $errors);
		}
			else
		{
			$this->message = "";
		}

		$task = JRequest::getVar('task');
		$redirect = 'index.php?option=com_billets&view='.$this->get('suffix');
		if ($id = JRequest::getVar('id'))
		{
			$redirect .= '&task=view&id='.$id;
		}
		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
		return;
	}
	
	/**
	 * Changes a manage status
	 * @return unknown_type
	 */
	function changestatus()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();

		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
		foreach (@$cids as $cid)
		{
			$row->load($cid);
			$row->stateid = JRequest::getVar( 'apply_stateid', '0', 'post', 'int' );
			if (!$row->save())
			{
				$errors[] = $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
				else 
			{
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterChangeStatus', array( $row ) );	
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_UNABLE_TO_CHANGE').": ".implode(", ", $errors);
		}
			else
		{
			$this->message = "";
		}

		$task = JRequest::getVar('task');
		$redirect = 'index.php?option=com_billets&view='.$this->get('suffix');
		if ($id = JRequest::getVar('id'))
		{
			$redirect .= '&task=view&id='.$id;
		}
		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
		return;
		
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		$row->bind( JRequest::get( 'post' ) );
		$redirect = "index.php?option=com_billets&view=manage&task=view&id=".$model->getId();
    	$redirect = JRoute::_( $redirect, false );
		
		if ( !$row->save() ) 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." :: ".$row->getError();
		} 
			else
		{
			$this->messagetype 	= 'message';
			$this->message  	= JText::_('COM_BILLETS_STATUS_CHANGED');
		}
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Download an attachment
	 * @return unknown_type
	 */
	function downloadfile() 
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		$user 	= &JFactory::getUser();
		$fileid = intval( JRequest::getVar( 'fileid' ) );
		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if ( !$canView = BilletsHelperTicket::canViewAttachment( $row->id, $user->id, $fileid ) ) 
		{
			$redirect = "index.php?option=com_billets&view=manage";
	    	$redirect = JRoute::_( $redirect, false );
			$this->messagetype 	= 'notice';
			$this->message  	= JText::_('COM_BILLETS_INVALID_TICKET');
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
        	return false;
		}
		
		$file = $model->getTable( 'Files');
		$file->load( $fileid );
	
		if ($viewAttachment = BilletsHelperTicket::viewAttachment( $file )) 
		{
			$redirect = "index.php?option=com_billets&view=manage&task=view&id=".$model->getId();
	    	$redirect = JRoute::_( $redirect, false );
			$this->setRedirect( $redirect );
		}
	}
	
	/**
	 * Displays list of manage to be converted into content items
	 * for confirmation, and also for selecting destination state  
	 * @return void
	 */
	function convert()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		$cids = JRequest::getVar('cid', array(0), 'request', 'array');
		// select only the ids from cid
		$model 	= $this->getModel( $this->get('suffix') );
				
		$query = $model->getQuery();
		$query->where("tbl.id IN ('".implode( "', '", $cids )."') ");
		$model->setQuery( $query );
		// create view, assign model, and display
		$view = $this->getView( 'manage', 'html' );
		$view->setModel( $model, true );
		$view->setLayout( 'convert' );
		$view->setTask( true );
		$view->display();
	}

	/**
	 * Convert manage into content items
	 * Process the confirmed rows and insert them into content items
	 * @return void
	 */	
	function completeconvert()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		$model 	= $this->getModel( $this->get('suffix') );
		
		// Get the list of selected items and their selected params
		$cids 		= JRequest::getVar('cid', array (0), 'post', 'array');
		$create 	= JRequest::getVar('create', array (0), 'post', 'array');
		$categoryid = JRequest::getVar('categoryid', array (0), 'post', 'array');
		$publish 	= JRequest::getVar('publish', array (0), 'post', 'array');
		$username 	= JRequest::getVar('username', array (0), 'post', 'array');
		$readmore 	= JRequest::getVar('readmore', array (0), 'post', 'array');
		
		$errors = array();
		
		// get our config object for determining which name to display below
	    $config = Billets::getInstance();

		// foreach ticket, create article according to params
		foreach (@$cids as $ticketid)
		{
			if (!$create[$ticketid])
			{
				continue;
			}
			$name = "";
			$ticket = $model->getTable();
			$t2a = $model->getTable( 't2a' );
			$article = JTable::getInstance('content');
			
			$ticket->load( $ticketid );
			
			list($section, $category) = explode( ".", $categoryid[$ticketid] );
			
			$article->state = $publish[$ticketid];
			$article->catid = $category;
			$article->sectionid = $section;
			$article->created_by = JFactory::getUser()->id;
			
			$content = "";
			$content .= '<div class="message"><div id="ticket_description" class="message_comment">';
			$content .= '<h3>'.JText::_('COM_BILLETS_ISSUE').'</h3>';
			$content .= nl2br( stripslashes( $ticket->description ) );
			$content .= '</div></div>';
			
			// Add a readmore between theinitial manage and the following messages if requested to
			if ($readmore[$ticketid])
			{ 
				$content .= '<hr id="system-readmore" />';
			}
			
			if($messages = BilletsHelperTicket::getMessages( $ticket->id ))
			{
				$content .= '<hr class="article_separator">';
			}

			foreach (@$messages as $message)
			{
				$content .= '<div class="message">';
				$content .= '<h4>'.JText::_('COM_BILLETS_COMMENT').'</h4>';
				
				//if we want to show the name beside each post
				if ($username[$ticketid])
				{
					// check which name we display in our manage and use that
					$name_display = $config->get( 'display_name', '1');
				  	
					if ($name_display == '3') 
					{ 
						$name = $message->user_email; 
					} elseif($name_display == '2') 
					{ 
						$name = $message->user_username; 
					} else 
					{ 
						$name = $message->user_name; 
					}
					$content  .= '<div class="message_user">'.$name.'</div>';

				}
				$content .= '<div class="message_comment">';
				$content .= nl2br( strip_tags( htmlspecialchars_decode( stripslashes( $message->message ) ) ) );
				$content .= '</div></div>';
				$content .= '<hr class="article_separator">';
			}
			
			// put the ticket content into the article
			$article->introtext = $content;
			$article->title = stripslashes( $ticket->title );
			
			// save article
			if (!$article->store()) 
			{					
	        	$errors[] = $article->getError();
	    	}
	    	
			// add a record to #__billets_t2a with a GMT timestamp
			// get table object for t2a and create a row object
			$t2a->ticketid = $ticketid;
			$t2a->articleid = $article->id;
			$t2a->created_datetime = JFactory::getDate()->toMySQL();
			
			// store cid, new content item id and timestamp
			if (!$t2a->save()) 
			{
				$errors[] = $t2a->getError();
			} 
		}
		
		$this->message = JText::_('COM_BILLETS_ARTICLES_SAVED');
		$this->messagetype = '';
		if (!empty($errors))
		{
			// there were errors, so display them upon redirect
			$this->message = implode( '<br/>', $errors );
			$this->messagetype = 'notice';
		}
				
		// Need to do a redirect because otherwise a refresh causes the articles to be created again
		$url_cids = "&cid[]=".implode('&cid[]=', $cids );
		$redirect = "index.php?option=com_billets&view=manage&task=convert".$url_cids;
    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
		
		return;
	}
	
    function selectusers()
    {
        // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
    	Billets::load('BilletsHelperManager', 'helpers.manager');
    	Billets::load('BilletsHelperCategory', 'helpers.category');
    	$this->set('suffix', 'users');
    	$state = parent::_setModelState();
    	$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();
        
    	foreach (@$state as $key=>$value)
		{
			$model->setState( $key, $value );	
		}

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$row = $model->getTable( 'manage' );
		$row->load( $id );
		
		$view	= $this->getView( 'manage', 'html' );
		$view->set( '_controller', 'manage' );
		$view->set( '_view', 'manage' );
		$view->set( '_action', "index.php?option=com_billets&view=manage&task=selectusers&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setLayout( 'selectusers' );
		$view->setTask( true );
		$view->display();
    }
    
	/*
	 * 
	 */
	function selected_switch()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		Billets::load('BilletsHelperManager', 'helpers.manager');
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();	

		$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
		$task = JRequest::getVar( 'task' );
		$vals = explode('_', $task);
		
		$field = $vals['0'];
		$action = $vals['1'];		
		
		switch (strtolower($action))
		{
			case "switch":
				$switch = '1';
			  break;
			case "disable":
				$enable = '0';
				$switch = '0';
			  break;
			case "enable":
				$enable = '1';
				$switch = '0';
			  break;
			default:
				$this->messagetype 	= 'notice';
				$this->message 		= JText::_('COM_BILLETS_INVALID_TASK');
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			  break;
		}
		
		foreach (@$cids as $cid)
		{
			if ($switch)
			{
				$obj = BilletsHelperManager::isTicket( $cid, $id, '1' );
			
				if (isset($obj->userid)) 
				{
					if (!BilletsHelperManager::removeFromTicket( $cid, $id ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				} 
					else 
				{
					if (!BilletsHelperManager::addToTicket( $cid, $id ))
					{
						$this->message .= $cid.', ';
						$this->messagetype = 'notice';
						$error = true;						
					}
				}
			}
				else
			{
				switch ($enable)
				{
					case "1":
						if (!BilletsHelperManager::addToTicket( $cid, $id ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;
						}
					  break;
					case "0":
					default:
						if (!BilletsHelperManager::removeFromTicket( $cid, $id ))
						{
							$this->message .= $cid.', ';
							$this->messagetype = 'notice';
							$error = true;						
						}
					  break;
				}
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . ": " . $this->message;
		}
			else
		{
			$this->message = "";
		}

		$redirect = "index.php?option=com_billets&view=manage&task=selectusers&tmpl=component&id=".$id;
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * 
	 * @return unknown_type
	 */
    function managercomments()
    {
        // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
    	$id = JRequest::getVar( 'id', JRequest::getVar( 'id', '0', 'post', 'int' ), 'get', 'int' );
    	
    	Billets::load('BilletsHelperManager', 'helpers.manager');
    	Billets::load('BilletsHelperCategory', 'helpers.category');
    	$this->set('suffix', 'comments');
		$model = $this->getModel( $this->get('suffix') );
        
		$model->setState( 'filter_ticketid', $id );
		$model->setState( 'order', 'tbl.datetime' );
		$model->setState( 'direction', 'DESC' );
		
		$row = $model->getTable( 'manage' );
		$row->load( $id );
		
		$view	= $this->getView( 'manage', 'html' );
		$view->set( '_controller', 'manage' );
		$view->set( '_view', 'manage' );
		$view->set( '_action', "index.php?option=com_billets&view=manage&task=managercomments&tmpl=component&id=".$model->getId() );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->assign( 'row', $row );
		$view->setLayout( 'managercomments' );
		$view->setTask( true );
		$view->display();
    }
    
	/**
	 * Adds a manager's comment to an existing ticket
	 * @return unknown_type
	 */
	function addmanagercomment()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
    	$this->set('suffix', 'comments');
		$model = $this->getModel( $this->get('suffix') );
    	
	    $row = $model->getTable();
		$row->message = JRequest::getVar( 'message' );
		$row->ticketid = $model->getId();
		$row->userid = JFactory::getUser()->id;

		if ( $row->save() ) 
		{
			$this->messagetype 	= '';
			$this->message  	= '';
		} 
			else 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." :: ".$row->getError();
		}

		$redirect = "index.php?option=com_billets&view=manage&task=managercomments&tmpl=component&id=".$model->getId();
    	$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Removes label
	 * @return unknown_type
	 */
	function setlabel()
	{
	    // If user is not a manager or superadmin, redirect
    	Billets::load('BilletsHelperManager', 'helpers.manager');
		if (!BilletsHelperManager::isUser( JFactory::getUser()->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_ARE_NOT_AUTHORIZED_TO_MANAGE_TICKETS');
			$this->messagetype = 'notice';
			$this->setRedirect( "index.php", $this->message, $this->messagetype );
			return;
		}
		
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();

		$cids = JRequest::getVar('cid', array (0), 'post', 'array');
		foreach (@$cids as $cid)
		{
			$row->load($cid);
			$labelid = JRequest::getVar( 'apply_labelid', '0', 'post', 'int' );
			if ($labelid < 0)
			{
				$labelid = '0';
			}
			$row->labelid = $labelid;
			if (!$row->save())
			{
				$errors[] = $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_UNABLE_TO_CHANGE').": ".implode(", ", $errors);
		}
			else
		{
			$this->message = "";
		}

		$task = JRequest::getVar('task');
		$redirect = 'index.php?option=com_billets&view='.$this->get('suffix');
		if ($id = JRequest::getVar('id'))
		{
			$redirect .= '&task=view&id='.$id;
		}
		$redirect = JRoute::_( $redirect, false );
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
		return;
	}
	
	/*
	 * Creates a popup where labels can be edited & created
	 */
	/*
	function editlabels()
    {
    	$this->set('suffix', 'labels');
    	$state = parent::_setModelState();
    	$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        $userid = JFactory::getUser()->id;
      	$model->setState('filter_userid', $userid);
		
		$view	= $this->getView( 'labels', 'html' );
		$view->set( '_controller', 'manage' );
		$view->set( '_view', 'manage' );
		$view->set( '_action', "index.php?option=com_billets&view=manage&task=editlabels&tmpl=component" );
		$view->setModel( $model, true );
		$view->assign( 'state', $model->getState() );
		$view->setLayout( 'default' );
		$view->setTask( true );
		$view->display();
    }
    */
    
    /**
     * Deletes a label and redirects
     * @return unknown_type
     */
	/*
    function deletelabel()
    {
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel('labels');
		$row = $model->getTable();
		
		$cids = JRequest::getVar('cid', array(0), 'request', 'array');
		foreach (@$cids as $cid)
		{
			if (!$row->delete($cid))
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . " - " . $this->message;
		}
			else
		{
			$this->message = "";
		}
		
		$redirect = "index.php?option=com_billets&view=manage&task=editlabels&tmpl=component";
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    */
    
    /**
     * Creates a label and redirects
     * 
     * @return unknown_type
     */
	/*
    function createlabel()
    {
    	$this->set('suffix', 'labels');
		$model 	= $this->getModel( $this->get('suffix') );
		
	    $row = $model->getTable();
		$row->title = JRequest::getVar( 'createlabel_title' );
		
		if ( $row->save() ) 
		{
			$model->setId( $row->id );
						
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );
		} 
			else 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." - ".$row->getError();
		}
		
		$redirect = "index.php?option=com_billets&view=manage&task=editlabels&tmpl=component";
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    */
    
	
	/*
    function savelabels()
    {
		$error = false;
		$this->messagetype	= '';
		$this->message 		= '';
				
		$model = $this->getModel('labels');
		$row = $model->getTable();
		
		$cids = JRequest::getVar('cid', array(0), 'request', 'array');
		$colors = JRequest::getVar('color', array(0), 'request', 'array');
		$titles = JRequest::getVar('title', array(0), 'request', 'array');
		foreach (@$cids as $cid)
		{
			$row->load( $cid );
			$row->title = $titles[$cid];
			$row->color = $colors[$cid];
			if (!$row->save())
			{
				$this->message .= $row->getError();
				$this->messagetype = 'notice';
				$error = true;
			}
		}
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_ERROR') . " - " . $this->message;
		}
			else
		{
			$this->message = "";
		}
		
		$redirect = "index.php?option=com_billets&view=manage&task=editlabels&tmpl=component";
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
    }
    */
	
	/**
	 * Gets the list of fields for a category (specified by 'id' in the URL) 
	 * and returns HTML, formatted for a ticket form. Intended to be used by Ajax
	 *  
	 * @return json_encoded HTML
	 */
	/*function getFields()
	{
		

		
		$ticketid = JRequest::getVar( 'ticketid', '', 'request', 'int' );
		$categoryid = JRequest::getVar( 'categoryid', '', 'request', 'int' );
		$html = "";
		
		// load the category object based on the id variable in request
		$category = $this->getModel( $this->get('suffix') )->getTable();
		$category->load( $categoryid );
		
		// if the id is invalid, return nothing
		if (!empty($category->id))
		{
			$model = $this->getModel( 'Tickets' );
			$model->setId( $ticketid );
			$ticket = $model->getItem();
			
			// get the category's fields, format the html, and return
			Billets::load('BilletsHelperCategory', 'helpers.category');
			Billets::load('BilletsField', 'library.field');
			$fields = BilletsHelperCategory::getFields( $category->id );
			if (@$fields)
			{
				$html .= "
				<table class=\"adminlist\">
				<thead>
					<tr>
						<th colspan='2' style=\"text-align: left;\">".JText::_('COM_BILLETS_ADDITIONAL_INFORMATION')."</th>
					</tr>
				</thead>
				<tbody>";	
			}
			
			foreach (@$fields as $field)
			{
				// autopopulate fields with values from ticket being viewed
				$name = $field->db_fieldname;
				$default = @$ticket->$name;
				$html .= "
				<tr>
					<td width=\"100\" align=\"right\" class=\"key\">
						<label for=\"$field->db_fieldname\">
						".JText::_( $field->title ).":
						</label>
					</td>
					<td>
						".BilletsField::display( $field, 'ticketdata', $default )."
					</td>
				</tr>
				";
			}
			
			if (@$fields)
			{
				$html .= "</tbody></table>";
			}
		}
		
		// set response array
			$response = array();
			$response['msg'] = $html;
			
		// encode and echo (need to echo to send back to browser)
			echo ( json_encode( $response ) );

		return;
	}*/
	
    /**
     * This method gets the HTML for the order select list
     * Should be in a layout file...
     * 
     * @return unknown_type
     */
    function getTiendaOrdersHTML()
    {
        $html = '';
        Billets::load('BilletsHelperTienda', 'helpers.tienda');
        if (Billets::getInstance()->get('enable_tienda', '1') && BilletsHelperTienda::isInstalled()) 
        {
            Tienda::load("TiendaSelect", 'library.select');
            $html = '
            <table class="adminlist">
                <tr>
                    <td width="100" align="right" class="key">
                        '. JText::_('COM_BILLETS_ORDER_ID').': 
                    </td>
                    <td>
                        '.TiendaSelect::order( $this->_userid, '', 'tienda_orderid' ).'
                    </td>
                </tr>
            </table>
            ';
        }
        return $html;
    }
    
    /**
     * This method is the target for the js function
     * that updates the list of Tienda orders when a user 
     * has been selected in the ticket creation form
     * 
     * @return unknown_type
     */
    function updateTiendaOrders()
    {
        $elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );

        // convert elements to array that can be binded
        Billets::load( 'BilletsHelperBase', 'helpers._base' );
        $helper = new BilletsHelperBase();
        $values = $helper->elementsToArray( $elements );
        
        $response = array();
        $response['msg'] = '';
        $response['error'] = '';
        
        // now get the summary
        $this->_userid = $values['sender_userid'];
        $html = $this->getTiendaOrdersHTML();

        $response = array();
        $response['msg'] = $html;
        $response['error'] = '';

        // encode and echo (need to echo to send back to browser)
        echo json_encode($response);

        return;
    }	
}

?>