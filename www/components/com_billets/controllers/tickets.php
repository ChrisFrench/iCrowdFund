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

class BilletsControllerTickets extends BilletsController 
{
	/**
	 * constructor
	 */
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'tickets');
		$this->registerTask( 'flag_read', 'flag' );
		$this->registerTask( 'flag_unread', 'flag' );
		$this->registerTask( 'flag_archived', 'flag' );
		$this->registerTask( 'flag_unarchived', 'flag' );
		$this->registerTask( 'flag_deleted', 'flag' );
		$this->registerTask( 'addlabel', 'setlabel' );
		$this->registerTask( 'removelabel', 'setlabel' );
		$this->registerTask( 'selected_enable', 'selected_switch' );
	}

	/**
	 * 
	 * @return unknown_type
	 */
    function _setModelState()
    {
		$state = parent::_setModelState();   	
		$app = JFactory::getApplication();
		$model = $this->getModel( $this->get('suffix') );
    	$ns = $this->getNamespace();
		
    	$config = Billets::getInstance();
		// adjust offset for when filter has changed
    	if (
    		$app->getUserState( $ns.'categoryid' ) != $app->getUserStateFromRequest($ns.'categoryid', 'filter_categoryid', '', '') ||
    		$app->getUserState( $ns.'stateid' ) != $app->getUserStateFromRequest($ns.'stateid', 'filter_stateid', $config->get( 'state_new', '1' ), 'int') 
    	)
    	{
    		$state['limitstart'] = '0';
    	}
    	
      	$state['filter_categoryid']	= $app->getUserStateFromRequest($ns.'categoryid', 'filter_categoryid', '', '');
      	$state['filter_stateid'] 	= $app->getUserStateFromRequest($ns.'stateid', 'filter_stateid', '' );
		$state['filter_userid']		= JFactory::getUser()->id;
		
    	$state['filter_id_from'] 	= $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', '');
    	$state['filter_id_to'] 		= $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', '');
		$state['filter_title']	= $app->getUserStateFromRequest($ns.'title', 'filter_title', '', '');
		
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
    	
    	$config = Billets::getInstance();
    	
    	$require_login = $config->get( 'require_login', '1' );
    	$redirect_menu_id = $config->get( 'redirect_menu_id', '1' );
    	
		if ($require_login && empty(JFactory::getUser()->id)) 
		{
			Billets::load('BilletsUrl', 'library.url');
			
			$this->message = JText::_('COM_BILLETS_PLEASE_LOGIN_FIRST');
			$this->messagetype = 'notice';
			$redirect = BilletsUrl::getMenuLink($redirect_menu_id);
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		$redirect = "index.php?option=com_billets&view=tickets";
    	$redirect = JRoute::_( $redirect, false );
	    	
		$user = JFactory::getUser();
		$this->_setModelState();
		
		$view	= $this->getView( 'tickets', 'html' );
		$view->set( '_controller', 'tickets' );
		$view->set( '_view', 'tickets' );
		$view->set( '_action', "index.php?option=com_billets&view=tickets" );
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
		$model 	= $this->getModel( $this->get('suffix') );
    	$config = Billets::getInstance();
    	
    	$require_login = $config->get( 'require_login', '1' );
    	$redirect_menu_id = $config->get( 'redirect_menu_id', '1' );
    	
		if ($require_login && empty(JFactory::getUser()->id)) 
		{
			Billets::load('BilletsUrl', 'library.url');
			
			$redirect = BilletsUrl::getMenuLink($redirect_menu_id);
			$this->message = JText::_('COM_BILLETS_PLEASE_LOGIN_FIRST');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		JRequest::setVar( 'view', $this->get('suffix') );
		JRequest::setVar( 'layout', 'view');
		$redirect = "index.php?option=com_billets&view=tickets";
    	$redirect = JRoute::_( $redirect, false );
	    	
		$user = JFactory::getUser();
		$row = $model->getTable();
		$row->load( $model->getId() );
		if (empty($row->id)) 
		{
			$this->message = JText::_('COM_BILLETS_INVALID_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		// message not assoc with user
		if (!BilletsHelperTicket::canView( $row->id, $user->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}

        $view = $this->getView( 'tickets', 'html' );
        
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
        $model = JModel::getInstance( 'Files', 'BilletsModel' );
        $model->setState( 'filter_ticketid', $model->getId() );
        $model->setState( 'filter_messageid', '0');
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
	function edit()
	{
		$model 	= $this->getModel( $this->get('suffix') );
    	$config = Billets::getInstance();
    	
		$require_login = $config->get( 'require_login', '1' );
    	$redirect_menu_id = $config->get( 'redirect_menu_id', '1' );
		
		if ($require_login && empty(JFactory::getUser()->id)) 
		{
			Billets::load('BilletsUrl', 'library.url');
			
			$redirect = BilletsUrl::getMenuLink($redirect_menu_id);
			$this->message = JText::_('COM_BILLETS_PLEASE_LOGIN_FIRST');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		if ($model->getId())
		{
		    // then this is not a new ticket
			Billets::load('BilletsHelperTicket', 'helpers.ticket');
			if (!BilletsHelperTicket::canView( $model->getId(), JFactory::getUser()->id )) 
			{
				$redirect = "index.php?option=com_billets&view=tickets";
		    	$redirect = JRoute::_( $redirect, false );
				$this->message = JText::_('You Cannot Edit that Ticket' );
				$this->messagetype = 'notice';
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			}
			
			if (Billets::getInstance()->get( 'enable_frontend_editing', '0' ) != '1')
			{
                $redirect = "index.php?option=com_billets&view=tickets";
                $redirect = JRoute::_( $redirect, false );
                $this->message = JText::_('You Cannot Edit the Properties of that Ticket ' );
                $this->messagetype = 'notice';
                $this->setRedirect( $redirect, $this->message, $this->messagetype );
                return;			    
			}
		}
		
		JRequest::setVar( 'hidemainmenu', '1' );
		JRequest::setVar( 'view', $this->get('suffix') );
		JRequest::setVar( 'layout', 'form');
		parent::display();
		
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
			
		// get elements from post json_encode			
			$elements = json_decode( preg_replace('/[\n\r]+/', '\n', JRequest::getVar( 'elements', '', 'post', 'string' ) ) );
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
						JText::_('COM_BILLETS_COULD_NOT_PROCESS_FORM').": ".Billets::dump( $elements )						
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

		// get table object
			$table = $this->getModel( $this->get('suffix') )->getTable();
		
		// bind to values
			$table->bind( $values );
			
		// then check if email field is needed
			$config = Billets::getInstance();
			$require_login = $config->get( 'require_login', '1' );
			$user = JFactory::getUser();
			$table->sender_userid = $user->id;
			if (!$require_login && !$user->id) 
			{
				if (empty($values['user_name']))
				{
					$response['error'] = '1';
					$response['msg'] = '
						<dl id="system-message">
						<dt class="notice">notice</dt>
						<dd class="notice message fade">
							<ul style="padding: 10px;">'.
							JText::_('COM_BILLETS_USERNAME_REQUIRED')						
							.'</ul>
						</dd>
						</dl>
						';
						echo ( json_encode( $response ) );
				
						return;
					
				} 
				else
				{
					Billets::load( 'BilletsHelperUser', 'helpers.user' );
					if (BilletsHelperUser::usernameExists($values['user_name']))
					{ 
						$credentials = array();
						$credentials['username'] = $values['user_name'];
						$credentials['password'] = $values['user_password'];
						$options = array();					
						//Get the global JAuthentication object
						jimport( 'joomla.user.authentication');
						$authenticate = JAuthentication::getInstance();
        				$rsp = $authenticate->authenticate($credentials,$options);
        				if($rsp->status === JAUTHENTICATE_STATUS_FAILURE)
        				{
        					if( isset( $values['_checked']['newuser_register'] ) )
        					{
		        				if(empty($values['newuser_email']))
								{
									$response['error'] = '1';
									$response['msg'] = '
									<dl id="system-message">
									<dt class="notice">notice</dt>
									<dd class="notice message fade">
										<ul style="padding: 10px;">'.
										JText::_('COM_BILLETS_YOU_ARE_A_NEW_USER_AND_YOU_MUST_PROVIDE_EMAIL_ADDRESS')						
										.'</ul>
									</dd>
									</dl>
									';
									echo ( json_encode( $response ) );					
									return;	
								}
								
								if(BilletsHelperUser::emailExists($values['newuser_email']))
								{
									$response['error'] = '1';
									$response['msg'] = '
									<dl id="system-message">
									<dt class="notice">notice</dt>
									<dd class="notice message fade">
										<ul style="padding: 10px;">'.
										JText::_('COM_BILLETS_THIS_EMAIL_ADDRESS_IS_ALREADY_REGISTRED')						
										.'</ul>
									</dd>
									</dl>
									';
									echo ( json_encode( $response ) );					
									return;	
								}
								
								if(!BilletsHelperUser::validateEmailAddress($values['newuser_email']))
								{
									$response['error'] = '1';
									$response['msg'] = '
									<dl id="system-message">
									<dt class="notice">notice</dt>
									<dd class="notice message fade">
										<ul style="padding: 10px;">'.
										JText::_('COM_BILLETS_EMAIL_ADDRESS_IS_INVALID')						
										.'</ul>
									</dd>
									</dl>
									';
									echo ( json_encode( $response ) );					
									return;
								}
        					}
        					else 
        					{
	        					$response['error'] = '1';
	                        	$response['msg'] = '
	                            <dl id="system-message">
	                            <dt class="notice">notice</dt>
	                            <dd class="notice message fade">
	                                <ul style="padding: 10px;">'.
	                                JText::sprintf('COM_BILLETS_WRONG_PASSWORD_ENTERED', $credentials['username'])                      
	                                .'</ul>
	                            </dd>
	                            </dl>
	                            ';
	                        	echo ( json_encode( $response ) );
	                        	return;
        					}
        				}
        				else 
        				{
        					BilletsHelperUser::login($credentials);	        					
        				}       			
					}
					else
					{
						if(empty($values['newuser_email']))
						{
							$response['error'] = '1';
							$response['msg'] = '
							<dl id="system-message">
							<dt class="notice">notice</dt>
							<dd class="notice message fade">
								<ul style="padding: 10px;">'.
								JText::_('COM_BILLETS_YOU_ARE_A_NEW_USER_AND_YOU_MUST_PROVIDE_EMAIL_ADDRESS')						
								.'</ul>
							</dd>
							</dl>
							';
							echo ( json_encode( $response ) );					
							return;	
						}
						
						if(BilletsHelperUser::emailExists($values['newuser_email']))
						{
							$response['error'] = '1';
							$response['msg'] = '
							<dl id="system-message">
							<dt class="notice">notice</dt>
							<dd class="notice message fade">
								<ul style="padding: 10px;">'.
								JText::_('COM_BILLETS_THIS_EMAIL_ADDRESS_IS_ALREADY_REGISTRED')						
								.'</ul>
							</dd>
							</dl>
							';
							echo ( json_encode( $response ) );					
							return;	
						}
						
						if(!BilletsHelperUser::validateEmailAddress($values['newuser_email']))
						{
							$response['error'] = '1';
							$response['msg'] = '
							<dl id="system-message">
							<dt class="notice">notice</dt>
							<dd class="notice message fade">
								<ul style="padding: 10px;">'.
								JText::_('COM_BILLETS_EMAIL_ADDRESS_IS_INVALID')						
								.'</ul>
							</dd>
							</dl>
							';
							echo ( json_encode( $response ) );					
							return;
						}
						
					} 
					
					$table->sender_userid = '1';	
				}
				
			}
			
		
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
		
		$require_login = Billets::getInstance()->get( 'require_login', '1' );
		if ($require_login && empty(JFactory::getUser()->id)) 
		{
			$redirect = "index.php";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_PLEASE_LOGIN_FIRST');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		$model 	= $this->getModel( $this->get('suffix') );
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if ($model->getId() && !BilletsHelperTicket::canView( $model->getId(), JFactory::getUser()->id )) 
		{
			$redirect = "index.php?option=com_billets&view=tickets";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
	    $row = $model->getTable();
	    $row->load( $model->getId() );
		$row->bind( JRequest::get( 'post', 2 ) );
		$row->sender_userid = JFactory::getUser()->id;
		$row->_isNew = empty($row->id);
				
		$require_login = Billets::getInstance()->get( 'require_login', '1' );
		if (!$require_login && !$row->sender_userid) 
		{
		    Billets::load('BilletsHelperUsers', 'helpers.users');
		    $newuser_username = JRequest::getVar( 'user_name' );		    
			$newuser_password = JRequest::getVar( 'user_password' );
			$newuser_email = JRequest::getVar( 'newuser_email' );
						
			jimport('joomla.user.helper');			
			$details['name'] 		= BilletsHelperUser::createValidUsername($newuser_username);
			$details['username'] 	= BilletsHelperUser::createValidUsername($newuser_username);
			$details['email'] 		= $newuser_email;
			if(empty($newuser_password))
			{
				$details['password'] 	= JUserHelper::genRandomPassword();
				$details['password2'] 	= $details['password'];
			}
			else 
			{
				$details['password'] 	= $newuser_password;
				$details['password2'] 	= $details['password'];
			}
			
			$msg = new JObject();
			if ($user = BilletsHelperUser::createNewUser( $details, $msg )) 
			{
				// login the new user
				$login = BilletsHelperUser::login( $details, '1' );
			}
			if ( !$user || !isset($user->id) ) 
			{
				$redirect = "index.php?option=com_billets&view=tickets";
		    	$redirect = JRoute::_( $redirect, false );
				$this->message = $msg->message;
				$this->messagetype = 'notice';
				$this->setRedirect( $redirect, $this->message, $this->messagetype );
				return;
			}
			$row->sender_userid = $user->id;
		}
		
		$config = Billets::getInstance();
		
		// get userdata.  if not present, create record using defaults from config
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$userdata = JTable::getInstance('Userdata', 'BilletsTable');
		$userdata->load( array('user_id'=>$row->sender_userid) );
		if (empty($userdata->user_id))
		{
		    $userdata->user_id = $row->sender_userid;
		    $userdata->limit_tickets = $config->get('limit_tickets_globally');
		    $userdata->ticket_max = $config->get('default_max_tickets');
		    $userdata->limit_hours = $config->get('limit_hours_globally');
		    $userdata->hour_max = $config->get('default_max_hours');
		    
		    $model->emptyState();
		    $model->setState( 'select', 'COUNT(tbl.id)' );
		    $model->setState( 'filter_userid', $row->sender_userid );
		    $userdata->ticket_count = $model->getResult();
		    
		    $model->emptyState();
		    $model->setState( 'select', 'SUM(tbl.hours_spent)' );
		    $model->setState( 'filter_userid', $row->sender_userid );
		    $userdata->hour_count = $model->getResult();
		    
		    $userdata->store();
		}
		
		// if the ticket is new and this user is not excluded from ticket limiting
		if ($row->_isNew && !$userdata->limit_tickets_exclusion)
		{
		    $limit_tickets_globally = $config->get('limit_tickets_globally');
		    // if there is a global limit OR if the user has a limit
		    if ($limit_tickets_globally || $userdata->limit_tickets)
		    {
                // check if the user has crossed their ticket limit
                if ($userdata->ticket_count >= $userdata->ticket_max)
                {
                    $redirect = "index.php?option=com_billets&view=tickets";
                    $redirect = JRoute::_( $redirect, false );
                    $this->message = JText::_('COM_BILLETS_YOU_HAVE_EXCEEDED_YOUR_MAXIMUM_NUMBER_OF_NEW_TICKETS');
                    $this->messagetype = 'notice';
                    $this->setRedirect( $redirect, $this->message, $this->messagetype );
                    return;
                }
		    }  
		}
		
		// if the ticket is new and this user is not excluded from hour limiting
		if ($row->_isNew && !$userdata->limit_hours_exclusion)
		{
		    $limit_hours_globally = $config->get('limit_hours_globally');
		    // if there is a global limit OR if the user has a limit
		    if ($limit_hours_globally || $userdata->limit_hours)
		    {
                // check if the user has crossed their ticket limit
                if ($userdata->hour_count >= $userdata->hour_max)
                {
                    $redirect = "index.php?option=com_billets&view=tickets";
                    $redirect = JRoute::_( $redirect, false );
                    $this->message = JText::_('COM_BILLETS_YOU_HAVE_EXCEEDED_YOUR_MAXIMUM_HOUR_OF_SUPPORT');
                    $this->messagetype = 'notice';
                    $this->setRedirect( $redirect, $this->message, $this->messagetype );
                    return;
                }
		    }  
		}
		
		if ( $row->save() ) 
		{
		    // increase ticket and hour count
		    $userdata->ticket_count = $userdata->ticket_count + 1; 
		    $userdata->hour_count = $userdata->hour_count + $row->hours_spent;
		    $userdata->store();  
		    		    
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

			// Send ticket submission confirmation email
			$this->_sendEmailConfirmation ( $row );

			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger( 'onAfterSave'.$this->get('suffix'), array( $row ) );
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
	 * Adds a comment to a message
	 * @return unknown_type
	 */
	function addcomment()
	{

		$model 	= $this->getModel( $this->get('suffix') );
		$redirect = "index.php?option=com_billets&view=tickets&task=view&id=".$model->getId();
    	$redirect = JRoute::_( $redirect, false );
    	
    	Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $model->getId(), JFactory::getUser()->id )) 
		{
			$redirect = "index.php?option=com_billets&view=tickets";
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
			
			$ticket->load( $row->ticketid );
			$ticket->last_modified_datetime = $date->toMysql();
			$config = Billets::getInstance();
			$ticket->stateid = $config->get( 'state_new' );
			
			if ($ticket->save())
			{
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
				$this->message 		= JText::_('COM_BILLETS_NEW_COMMENT_ADDED_BUT_TICKET_SAVE_FAILED')." :: ".$ticket->getError();				
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
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		$row->bind( JRequest::get( 'post' ) );
		$redirect = "index.php?option=com_billets&view=tickets&task=view&id=".$model->getId();
    	$redirect = JRoute::_( $redirect, false );
    	
    	Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if (!BilletsHelperTicket::canView( $row->id, JFactory::getUser()->id )) 
		{
			$redirect = "index.php?option=com_billets&view=tickets";
	    	$redirect = JRoute::_( $redirect, false );
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		
		if ( !$row->save() ) 
		{
			$this->messagetype 	= 'notice';			
			$this->message 		= JText::_('COM_BILLETS_SAVE_FAILED')." :: ".$row->getError();
		} 
			else
		{
			$this->messagetype 	= 'message';
			$this->message  	= JText::_('COM_BILLETS_TICKET_MOVED');
		}
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Rate and close a ticket
	 * 
	 * @return unknown_type
	 */
	function closeticket()
	{
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		$user = JFactory::getUser();
		
		$model = $this->getModel($this->get('suffix'));
		$row = $model->getTable();
		
		$redirect = "index.php?option=com_billets&view=tickets&task=view&id=".$model->getId();
    	$redirect = JRoute::_( $redirect, false );

		$row->load($model->getId());
			
		if (!BilletsHelperTicket::canView( $row->id, $user->id )) 
		{
			$this->message = JText::_('COM_BILLETS_YOU_CANNOT_VIEW_THAT_TICKET');
			$this->messagetype = 'notice';
			$this->setRedirect( $redirect, $this->message, $this->messagetype );
			return;
		}
		$row->feedback_by = $user->id;
		$row->feedback_rating = JRequest::getVar( 'apply_rating', '0', 'post', 'int' );
		$row->stateid = Billets::getInstance()->get('state_closed');
		$date = JFactory::getDate();
		$row->last_modified_datetime = $date->toMysql();
		$row->last_modified_by = $user->id;
		$row->closed_datetime = $date->toMysql();
		$row->closed_by = $user->id;
			
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
		
		if ($error)
		{
			$this->message = JText::_('COM_BILLETS_UNABLE_TO_CLOSE').": ".implode(", ", $errors);
		}
			else
		{
			$this->message = JText::_('COM_BILLETS_TICKET_CLOSED');
		}

		$task = JRequest::getVar('task');
		$redirect = 'index.php?option=com_billets&view='.$this->get('suffix');
		if ($model->getId())
		{
			$redirect .= '&task=view&id='.$model->getId();
		}
		$redirect = JRoute::_( $redirect, false );
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
		return;
	}
	
	/**
	 * Changes a tickets status
	 * @return unknown_type
	 */
	function changestatus()
	{
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		$row->bind( JRequest::get( 'post' ) );
		$redirect = "index.php?option=com_billets&view=tickets&task=view&id=".$model->getId();
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
			
			$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterChangeStatus', array( $row ) );
		}
		
		$this->setRedirect( $redirect, $this->message, $this->messagetype );
	}
	
	/**
	 * Download an attachment
	 * @return unknown_type
	 */
	function downloadfile() 
	{
		$model 	= $this->getModel( $this->get('suffix') );
		$row = $model->getTable();
		$row->load( $model->getId() );
		$user 	= &JFactory::getUser();
		$fileid = intval( JRequest::getVar( 'fileid' ) );
		
		Billets::load('BilletsHelperTicket', 'helpers.ticket');
		if ( !$canView = BilletsHelperTicket::canViewAttachment( $row->id, $user->id, $fileid ) ) 
		{
			$redirect = "index.php?option=com_billets&view=tickets";
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
			$redirect = "index.php?option=com_billets&view=tickets&task=view&id=".$model->getId();
	    	$redirect = JRoute::_( $redirect, false );
			$this->setRedirect( $redirect );
		}
	}
	
	/**
	 * Gets the list of fields for a category (specified by 'id' in the URL) 
	 * and returns HTML, formatted for a ticket form. Intended to be used by Ajax
	 *  
	 * @return json_encoded HTML
	 */
	function getFields()
	{
		//JLoader::import( 'com_billets.library.json', JPATH_ADMINISTRATOR.DS.'components' );
		
		$ticketid = JRequest::getVar( 'ticketid', '', 'request', 'int' );
		$categoryid = JRequest::getVar( 'categoryid', '', 'request', 'int' );
		$html = "";
		$scripts = array();
		
		// load the category object based on the id variable in request
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );       
        $category = JTable::getInstance('Categories', 'BilletsTable');
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
						<th colspan=\"3\" style=\"text-align: left;\">".JText::_('COM_BILLETS_ADDITIONAL_INFORMATION')."</th>
					</tr>
				</thead>
				<tbody>";	
			}
			
			foreach (@$fields as $field)
			{
				// autopopulate fields with values from ticket being viewed
				$name = $field->db_fieldname;
				$default = @$ticket->$name;
				
				$display_code = BilletsField::display( $field, 'ticketdata', $default );
				if (!is_array($display_code)) 
				{
				    $msg = $display_code;
				    $display_code = array();
					$display_code['msg'] = $msg;
					$display_code['script'] = '';
				}
				
				$html .= "
				<tr>
					<td width=\"100\" align=\"right\" class=\"key\">
						<label for=\"$field->db_fieldname\">
						".JText::_( $field->title ).":
						</label>
					</td>
					<td>
						".$display_code['msg']."
					</td>
					<td>
						" . $field->description . "
					</td>
				</tr>
				";
				
				if (!empty($display_code['script']))
				{ 
				    $scripts[] = $display_code['script']; 
				}
			}
			
			if (@$fields)
			{
				$html .= "</tbody></table>";
			}
		}
		
		// set response array
			$response = array();
			$response['msg'] = $html;
			$response['scripts'] = $scripts;
			
		// encode and echo (need to echo to send back to browser)
		
			echo ( json_encode( $response ) );

		return;
	}
	
	function _sendEmailConfirmation( $ticket )
	{
		$user	= JFactory::getUser();
		$message = JFactory::getMailer();
		$message->addRecipient( $user->email );
		
		//Get first parent category
		Billets::load('BilletsHelperCategory', 'helpers.category');
		$parentcategory = BilletsHelperCategory::getFirstParent($ticket->categoryid);

        Billets::load('BilletsHelperEmails', 'helpers.emails');
        $helper = new BilletsHelperEmails();
        $helper = $helper->processTicket( $ticket, 'new' );
        $placeholders = $helper->_placeholders;

        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger( 'onBBCode_RenderText', array(&$placeholders['ticket.description']) );

        $subject = BilletsHelperEmails::replacePlaceholders( JText::_('COM_BILLETS_EMAIL_NEW_TICKET_CONFIRMATION_SUBJECT'), $placeholders );
        $body = BilletsHelperEmails::replacePlaceholders( JText::_('COM_BILLETS_EMAIL_NEW_TICKET_CONFIRMATION_BODY'), $placeholders );
        
        $message->setSubject( $subject );
        $message->setBody( nl2br( $body ) ); // only do this if HTML, otherwise take out the nl2br
        
		$message->IsHTML(true);
		
		$mainframe = JFactory::getApplication();
		$config 	= Billets::getInstance();
		$mailfrom 	= $config->get( 'emails_defaultemail', $mainframe->getCfg('mailfrom') );
		$fromname 	= $config->get( 'emails_defaultname', $mainframe->getCfg('fromname') );
		$sitename 	= $config->get( 'sitename', $mainframe->getCfg('sitename') );
		$siteurl 	= $config->get( 'siteurl', JURI::root() );

		$sender = array( $mailfrom, $fromname );
		$message->setSender($sender);

		// TODO Fix this.  
        //		if ($mainframe->getCfg('mailfrom') != $from)
        //		{
        //			$message->addReplyTo( array($from, $fromname) );		    
        //		}
		return $message->send();
	}
}

?>