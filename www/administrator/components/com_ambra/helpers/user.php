<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link  http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
/**
 * @version     $Id: helper.php 14583 2010-02-04 07:16:48Z eddieajau $
 * @package     Joomla.Framework
 * @subpackage  Mail
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.filesystem.file');

class AmbraHelperUser extends DSCHelperUser
{
    /**
     * Gets the total points earned for a user
     *
     * @param $user_id
     * @return unknown_type
     */
    function getTotalPoints( $user_id )
    {
        static $users;

        if (empty($users) || !is_array($users))
        {
            $users = array();
        }

        if (empty($users[$user_id]))
        {
            JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
            $model = JModel::getInstance('PointHistory', 'AmbraModel');
            $model->setState( 'select', "SUM(points) AS pointhistory_sum" );
            $model->setState( 'filter_user', $user_id );
            $model->setState( 'filter_enabled', '1' );
            $query = $model->getQuery();
            $db = JFactory::getDBO();
            $db->setQuery( (string) $query );
            $users[$user_id] = $db->loadResult();
        }

        return $users[$user_id];
    }
    
  /**
     * Gets the current points for a user
     *
     * @param $user_id
     * @return unknown_type
     */
    function getPoints( $user_id )
    {
      //JTable::addIncludePath( JPATH_ADMINISTRATOR."/components/com_ambra/tables");
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
    $table = JTable::getInstance('Userdata', 'AmbraTable');
    $table->load( array( 'user_id'=>$user_id ) );
    $current_points = $table->points_current;
    
    return $current_points;
    }

    /**
     * Gets the maximum number of points a user is allowed
     *
     * @param unknown_type $user_id
     * @return unknown_type
     */
    function getMaxPoints( $user_id )
    {
        static $users;

        if (empty($users) || !is_array($users))
        {
            $users = array();
        }

        if (empty($users[$user_id]))
        {
            // the different limiters are (in order of priority):
            // system-wide, based on age of account
            $account_age = $this->getAccountAge( $user_id );
            if ($account_age < Ambra::getInstance()->get('days_before_points_can_be_earned'))
            {
                $users[$user_id] = '0';
                return $users[$user_id];
            }
            JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
            $model = JModel::getInstance('Users', 'AmbraModel');
            JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
            $model->setId( $user_id );

            if ($user = $model->getItem())
            {
                //as set at user level
                if (is_numeric($user->points_maximum))
                {
                    $users[$user_id] = $user->points_maximum;
                    return $users[$user_id];
                }
            }

            // system-wide, based on max_daily_points
            $users[$user_id] = Ambra::getInstance()->get('max_total_points');
        }

        return $users[$user_id];
    }

    /**
     * Gets the point total for the day for a user
     *
     * @param unknown_type $user_id
     * @return unknown_type
     */
    function getTodayPoints( $user_id )
    {
        static $users;

        if (empty($users) || !is_array($users))
        {
            $users = array();
        }

        if (empty($users[$user_id]))
        {
            JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
            $model = JModel::getInstance('PointHistory', 'AmbraModel');
            $model->setState( 'select', "SUM(tbl.points) AS pointhistory_today" );
            $model->setState( 'filter_user', $user_id );
            $model->setState( 'filter_enabled', '1' );
            $model->setState( 'filter_date_from', Ambra::getClass( "AmbraHelperBase", "helpers._base" )->getToday() );
            $query = $model->getQuery();
            $query->group('tbl.user_id');
            $db = JFactory::getDBO();
            $db->setQuery( (string) $query );
            $result = $db->loadObject();
            if($result) {
              $users[$user_id] = $result->pointhistory_today;
            }
            else{
              $users[$user_id] = 0;
            }
         }

        return $users[$user_id];
    }

    /**
     * Gets the maximum number of points a user is allowed per day
     *
     * @param unknown_type $user_id
     * @return unknown_type
     */
    function getMaxPointsPerDay( $user_id )
    {
        static $users;

        if (empty($users) || !is_array($users))
        {
            $users = array();
        }

        if (empty($users[$user_id]))
        {
            // the different limiters are (in order of priority):
            // system-wide, based on age of account
            $account_age = $this->getAccountAge( $user_id );
            if ($account_age < Ambra::getInstance()->get('days_before_points_can_be_earned'))
            {
                $users[$user_id] = '0';
                return $users[$user_id];
            }

            JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
            $model = JModel::getInstance('Users', 'AmbraModel');
            $model->setId( $user_id );
            if ($user = $model->getItem())
            {
                //as set at user level
                if (is_numeric($user->points_maximum_per_day))
                {
                    $users[$user_id] = $user->points_maximum_per_day;
                    return $users[$user_id];
                }

                // check limit of user's profile
                if (is_numeric($user->profile_max_points_per_day))
                {
                    $users[$user_id] = $user->profile_max_points_per_day;
                    return $users[$user_id];
                }
            }

            // system-wide, based on max_daily_points
            $users[$user_id] = Ambra::getInstance()->get('max_daily_points');
        }

        return $users[$user_id];
    }

    /**
     * Gets the age (in days) of a user account
     *
     * @param unknown_type $user_id
     * @return unknown_type
     */
    function getAccountAge( $user_id )
    {
        static $users;

        if (empty($users) || !is_array($users))
        {
            $users = array();
        }

        if (empty($users[$user_id]))
        {
            // get the age (in days) of the account
            $query = "SELECT DATEDIFF( NOW(), tbl.registerDate) FROM #__users AS tbl WHERE tbl.id = '{$user_id}'; ";
            $db = JFactory::getDBO();
            $db->setQuery( $query );
            $users[$user_id] = $db->loadResult();
        }

        return $users[$user_id];
    }

    /**
     * Gets a users avatar src
     *
     * @param $id
     * @return unknown_type
     */
    public static function getAvatarFilename( $id )
    {
        (int) $id;
      
        $avatar_png = $id.'.png';
        $avatar_jpg = $id.'.jpg';
        $avatar_gif = $id.'.gif';
        $avatar_jpeg = $id.'.jpeg';

        $storage_folder = Ambra::getPath( 'avatars' );
        if ( JFile::exists( $storage_folder.'/'.$avatar_png ) )
        {
            return $avatar_png;
        }

        if ( JFile::exists( $storage_folder.'/'.$avatar_jpg ) )
        {
            return $avatar_jpg;
        }

        if ( JFile::exists( $storage_folder.'/'.$avatar_gif ) )
        {
            return $avatar_gif;
        }

        if ( JFile::exists( $storage_folder.'/'.$avatar_jpeg ) )
        {
            return $avatar_jpeg;
        }
        return false;
    }


    /**
     * Gets a users avatar src
     *
     * @param $id
     * @return unknown_type
     */
    function getAvatar( $id, $type = NULL )
    {   
   
             (int) $id = $id;
        $avatar = 'noprofilepic.png';
        $pic = Ambra::getUrl( 'images' ).$avatar;     
        $object = new JObject();
         $hybridAuth = Ambra::getInstance()->get('hybridauth_int');
          $gravatar = Ambra::getInstance()->get('gravatar_support') ;  
     
        if ($avatar = AmbraHelperUser::getAvatarFilename( $id ))
        {
            $storage_folder = Ambra::getPath( 'avatars' );
            if ( JFile::exists( $storage_folder.'/'.$avatar ) )
            {
                $object->pic = Ambra::getUrl( 'avatars' ).$avatar;
                $object->type = 'ambra';
                 if($type) { return $object; } else { return $object->pic; }
                   
            }
        } 

        if ($hybridAuth) {
            $db = JFactory::getDBO();
            $query = new DSCQuery();
            $query->select('photoURL, provider_type');
            $query->from('#__hybridauth_accounts');
            $query->where('user_id = '. $id);
        
            $db->setQuery( $query , 0 , 1 );

            $pic = $db->loadObject();

            if(@$pic->photoURL) {
                $object->pic = $pic->photoURL;
                $object->type = $pic->provider_type;
                   if($type) { return $object; } else { return $object->pic; }
               
            }
            
        } 

        if($gravatar) {
          
          $user = JFactory::getUser($id);  
    
          if($user->email) {
         
            
             $default =   Ambra::getInstance()->get('gravatar_default');
            if($default == 'default') {
              $default = JURI::base(). $pic;
            }
            $size = Ambra::getInstance()->get('gravatar_size');

            $pic = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?d=" .  $default  . "&s=" . $size;
            
    
            $object->pic = $pic;
            $object->type = 'Gravatar';
                  if($type) { return $object; } else { return $object->pic; }
           
            
            } 
          
        }
         // if nothing is found we use default
        

        $object->pic = $pic;
        $object->type = 'Default';
        if($type) { return $object; } else { return $object->pic; }
      
    }

    /**
     * Can $user->id VIEW $row->id?
     *
     * @return Boolean
     * @param object $user
     * @param object $row
     */
 function canView( $user, $row, $field='profile' )
    {
        $success = false;

        $canEdit = AmbraHelperUser::canEdit( $user, $row );

        switch ($field)
        {
            case "email":
            case "emails":
                // by default, email addresses are not visible
                $success = false;
              break;
            case "profile":
                // by default, profiles are visible
                $success = true;
              break;
            default:
                // by default, extra fields are not visible
                $success = false;
              break;
        }

        // could insert additional facebook-style checks here (i.e. has the user set their profile=restricted?)
        // fire a plugin event to enable that, but make it so that the only thing plugin could do is make success=NO
            // (i.e. plugin shouldn't be able to enable access)

        if ($canEdit) {
            $success = true;
        }

        return $success;
    }

    /**
     * Can $user->id EDIT $row->id?
     *
     * @return Boolean
     * @param object $user
     * @param object $row
     */
  function canEdit( $user, $row )
    {
       


        $success = false;

        // TODO Incorporate Joomla ACL properly here

        // if we're on the front-end and the userid's don't match?
        // or should we enable admins to modify users on the front-end?
        // so, if row->id!=user->id && user isn't an admin/super-admin
        $isAdmin = $user->authorize( 'com_users', 'manage' );
        if ($row->get('id') != $user->get('id') && !$isAdmin)
        {
            return $success;
        }

        // admins can't edit admins
        //if ($row->get('gid') == $user->get('gid') && $row->get('id') != $user->get('id') && $user->get('gid') != '25' )
        //{
        //    return $success;
        //}

        // is the row's gid higher than the user's
        //$isChild = AmbraHelperUser::isChildJoomlaGroup( $row->gid, $user->get('gid') );
        //if ($isChild)
        //{
        //    return $success;
        //}

        // if they made it here, then user should be able to edit the row
        $success = true;
        return $success;
    }

    /**
     * Can $user->id DELETE $row->id?
     *
     * @return
     * @param object $user
     * @param object $row
     */
   function canDelete( $user, $row )
    {
        $success = false;

        $canEdit = AmbraHelperUser::canEdit( $user, $row );
        if (!$canEdit)
        {
            return $success;
        }

        // can't delete yourself
        if ( $row->get('id') == $user->get('id') )
        {
            return $success;
        }

        // if they made it here, then user should be able to delete the row
        $success = true;
        return $success;
    }

    /**
     *
     * @return
     * @param $user Object
     */
  function checkModifications( $row, &$msg ) {
        $success = false;
        $user = JFactory::getUser();

        // TODO Finish checkModifications
            // used when saving a user, to determine if user can make the selected changes

        if ( $row->get('id') == $user->get( 'id' ) && $row->get('block') == 1 )
        {
            $msg = JText::_( 'You cannot block yourself' );
            $mainframe->enqueueMessage($msg, 'message');
            return $this->execute('edit');
        }

        if ( ( $this_group == 'super administrator' ) && $row->get('block') == 1 ) {
            $msg = JText::_( 'You cannot block a Super Administrator' );
            $mainframe->enqueueMessage($msg, 'message');
            return $this->execute('edit');
        }
        else if ( ( $this_group == 'administrator' ) && ( $user->get( 'gid' ) == 24 ) && $row->get('block') == 1 )
        {
            $msg = JText::_( 'WARNBLOCK' );
            $mainframe->enqueueMessage($msg, 'message');
            return $this->execute('edit');
        }
        else if ( ( $this_group == 'super administrator' ) && ( $user->get( 'gid' ) != 25 ) )
        {
            $msg = JText::_( 'You cannot edit a super administrator account' );
            $mainframe->enqueueMessage($msg, 'message');
            return $this->execute('edit');
        }

        $success = true;
        return $success;
    }

    /**
     *
     * @return
     * @param $excludeHigher Object[optional]
     */
   public static function getJoomlaGroups( $excludeHigher='1' ) {
        $success = false;
        $user = JFactory::getUser();
        $acl =& JFactory::getACL();

        // ensure user can't add/edit group higher than themselves in Joomla ACL
        $gtree = $acl->get_group_children_tree( null, 'USERS', false );
        $success = $gtree;

        if ($excludeHigher)
        {
            // remove groups 'above' user's group
            $return = array();
            for ($i=0; $i<count($gtree); $i++)
            {
                $item = $gtree[$i];
                $isChild = AmbraHelperUser::isChildJoomlaGroup( $item->value, $user->get('gid') );

                if (!$isChild)
                {
                    $return[] = $item;
                }

            }

            $success = $return;
        }

        return $success;
    }

    /**
     * Determines if $source is a child of $target
     * using JTableAROGroup (Registered, Administrator, etc)
     *
     * @return
     * @param object $source_gid
     * @param object $target_gid
     */
    function isChildJoomlaGroup( $source_gid, $target_gid )
    {
        /*$isChild = false;

        $acl =& JFactory::getACL();
        $gids = $acl->get_group_children( $target_gid, 'ARO', 'RECURSE' );

        if (@in_array( $source_gid, $gids))
        {
            $isChild = true;
        }

        return $isChild;
        */
    }


  /**
   * fetches user record from user table
   * @param unknown_type $userid
   */
  function getUser( $userid = null)
  { 
    (int) $userid;
    // TODO Make this use ->load()

    $success = false;
    $user = JFactory::getUser($userid);
    JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
    JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
    $model = JModel::getInstance('Users', 'AmbraModel');

    $model->setId( $user->id );
    $user->userdata =  $model->getItem();


    return $user;
  }
  /**
   * for sending email when manually added point
   * @param unknown_type $user
   * @param unknown_type $details
   * @param unknown_type $useractivation
   */
  function sendMailPoints( &$user,$comment=NULL) {    
    $mainframe =  JFactory::getApplication();;
    $db   = JFactory::getDBO();
    $userid   = $user->get('user_id');
    
    if($comment==NULL)
    {
      $comment = $user->get('comment');
    }
        $config = JFactory::getConfig();
        $config->getValue( 'config.sitename' );
    $usertable=AmbraHelperUser::getUser($userid);
    $name=$usertable->name;
    $email=$usertable->email;
    $usersConfig  = &JComponentHelper::getParams( 'com_users' );
    $sitename     = $config->getValue( 'config.sitename' ); 
    $mailfrom     = $config->getValue( 'config.mailfrom' ); 
    $fromname     = $config->getValue( 'config.fromname' ); 
    $siteURL    = JURI::base();
    $subject  = sprintf ( JText::_( 'Points Notification'), $name,$comment,$sitename);
    $subject  = html_entity_decode($subject, ENT_QUOTES);
    
    $message = JText::_('POINTS MESSAGE BODY'). "\r\n\r\n";
    $message .= JText::_('ACTIVITY') . ': ' . $user->pointhistory_name . "\r\n";
    $message .= JText::_('EXPIRES') . ': ' . $user->expire_date . "\r\n";
    $message .= JText::_('DESCRIPTION') . ":\r\n" . $user->pointhistory_description . "\r\n";
    $message = strip_tags ( $message );

    //get all super administrator
    $rows = DSCAcl::getAdminList();

    // Send email to user
    if ( ! $mailfrom  || ! $fromname ) {
      $fromname = $rows[0]->name;
      $mailfrom = $rows[0]->email;
    }

    $success = AmbraHelperUser::doMail($mailfrom, $fromname, $email, $subject, $message);

    return $success;
  }


  /**
   * Returns yes/no
   * @param object
   * @param mixed Boolean
   * @return array
   */
  function _sendMail( &$user, $details, $useractivation ) {
  
    parent::sendMail($user, $details, $useractivation);
  }

    
    /**
     *
     * @return unknown_type
     */
    private static function doMail($from, $fromname, $recipient, $subject, $body, $actions = NULL, $mode = NULL, $cc = NULL, $bcc = NULL, $attachment = NULL, $replyto = NULL, $replytoname = NULL) {
        $success = false;

        $message = JFactory::getMailer();
        $message -> addRecipient($recipient);
        $message -> setSubject($subject);

        // check user mail format type, default html
        $message -> IsHTML(true);
        $body = htmlspecialchars_decode($body);
        $message -> setBody(nl2br($body));

        $sender = array($from, $fromname);
        $message -> setSender($sender);

        $sent = $message -> send();
        if ($sent == '1') {
            $success = true;
        }
        return $success;

    }
  
  /**
   *
   * @param unknown_type $string
   */
   function getManualApproval($userid)
  {
    $success = false;
    $database = JFactory::getDBO();
    $userid = $database->getEscaped($userid);
    $query = "
      SELECT
         `is_manual_approval`
      FROM
        #__ambra_userdata
      WHERE
        `user_id` = '{$userid}'
      LIMIT 1
    ";
    $database->setQuery($query);
    $result = (int) $database->loadResult();
    return $result;
  }
  /**
   *
   * @param unknown_type $datebefore
   */
  function getExpiration($datebefore)
  {

    $database = JFactory::getDBO();
    $query = "
        SELECT * FROM
          #__ambra_pointhistory
        WHERE
          modified_date < '".$database->getEscaped( trim( strtolower($datebefore) ) )."'
        ";
        $database->setQuery( $query );
        $rows = $database->loadObjectList();
        return $rows;

  }

  /**
     * Checks if the specified relationship exists
     *
     * @param $user_from
     * @param $user_to
     * @param $relation_type
     * @return unknown_type
     */
    function relationshipExists( $user_from, $user_to, $relation_type='relates' )
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
        $table = JTable::getInstance('UserRelations', 'AmbraTable');
        $keys = array(
            'user_id_from'=>$user_from,
            'user_id_to'=>$user_to,
            'relation_type'=>$relation_type,
        );
        $table->load( $keys );
        if (!empty($table->user_id_from))
        {
            return true;
        }

        // relates can be inverted
        if ($relation_type == 'relates')
        {
            // so try the inverse
            $table = JTable::getInstance('UserRelations', 'AmbraTable');
            $keys = array(
                'user_id_from'=>$user_to,
                'user_id_to'=>$user_from,
                'relation_type'=>$relation_type,
            );
            $table->load( $keys );
            if (!empty($table->user_id_from))
            {
                return true;
            }
        }

        return false;

    }
}