<?php
/**
 * @package Messagebottle
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

Messagebottle::load('EmailHelper','helpers.emailhelper');
 

class Bottle extends EmailHelper {
    var $recipients = null;
    var $attachments = null;
    var $bccHolder = null;
    var $ccHolder = null;
    var $event = null;
    //Table Vars
    var $email_id = null; //int
    var $sender_id = null; //int
    var $sender_name = null; //int
    var $sender_email = null; //int
    var $receiver_id = null; //int
    var $receiver_name = null; //varchar
    var $receiver_email = null; //varchar
    var $bcc = null; //text Serialize data
    var $cc = null; //text Serialize data
    var $title = null; //varchar
    var $Body = null; //longtext
    var $body = null; //longtext
    var $altbody = null; //longtext
    var $scope_id = null; //int
    var $template = null; //int
    var $parent_object_id = null; //int
    var $object_id = null; //int
    var $sent = null; //int
    var $senddate = null; //datetime
    var $sentdate = null; //datetime
    var $datecreated = null; //datetime
    var $datemodified = null; //datetime
    var $enabled = null; //int
    var $isHtml = null; //int
    var $hasattachments = null;
    var $sendmethod = null; //int
    var $option = null;
    var $view = null;
    /**
      * lets start  by setting up the variables we need to store the  infomation for this specific email
      * @todo 
      * @example $this->setSubject($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function __construct() {

    
     
        $this->recipients = array();
        $this->attachments = array();
        $this->bccHolder  = array();
        $this->ccHolder   = array();
        $this->event = new stdClass;
        $this->sender_id = 0;
        $this->option = JRequest::getCmd('option');
        $this->view = JRequest::getCmd('view');
        
    }
    
    /**
      * sets the email view, the view that generated the email
      * @todo 
      * @example $this->setView($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function setView($view) {

      $this->view = $view;

      if(empty($view) && empty($this->view) ) {
       $this->view = JRequest::getCmd('view');
      }
      return $this;
    }

    /**
      * sets the email option, the option that genated the email
      * @todo 
      * @example $this->setOption($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function setOption($option) {
      $this->option = $option;

      if(empty($option) && empty($this->option) ) {
       $this->option = JRequest::getCmd('option');
      }
      return $this;
    }    

    /**
      * sets the email title
      * @todo 
      * @example $this->setSubject($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setSubject($title) {
        $this->title = $title;
        return $this;
    }
     /**
      * sets the email body title
      * @todo Should we do html checks? or clean the body?
      * @example $this->setBody($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setBody($body) {
        $this->Body = $body;
        return $this;
    }
      /**
      * sets the email replyto email
      * @todo 
      * @example $this->setReplyTo($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setReplyTo($email) {
        $this->replyto = $email;
        return $this;
    }
      /**
      * sets the email isHTML which means we should do something  to the email before send set encoding or something
      * @todo 
      * @example $this->setHTML($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setHTML() {
        $this->isHtml = 1;
        return $this;
    }



     /**
      * sets the sender as a Joomla User, if sender is set to 0 emails will sent from config settings
      * @todo 
      * @example $this->setHTML($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setSender($from) {

        if (is_array($from))
        { 
         
            // If $from is an array we assume it has an address and a name
            if (isset($from[1]))
            {   
               // $this->setSenderEmail(EmailHelper::cleanLine($from[0]));
               // $this->setSenderName(EmailHelper::cleanLine($from[1]));
                
                $this->setSenderEmail($from[0]);
                $this->setSenderName($from[1]);
            }
            else
            {
                $this->setSenderEmail($from[0]);
            }
        }
        elseif ($this->isEmailAddress($from)) 
        { 
      
            // If it is a string we assume it is just the address
            $this->setSenderEmail($from);
        } 
        elseif (is_numeric($from)) {
       
            $user  = JFactory::getUser($from);

            $this->setSenderid($user->id);

            $this->setSenderName($user->name);
            $this->setSenderEmail($user->email);

        } 
        return $this;
    }
     /**
      * sets the sender id
      * @todo checks and clean up
      * @example $this->setSenderid($title);
      * @param int
      * @since 0.1
      * @return 
      * 
      */
    function setSenderid($id) {
        $this->sender_id = $id;
        return $this;
    }
    /**
      * sets the sender name
      * @todo  checks and clean up
      * @example $this->setSenderName($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setSenderName($name) {
        $this->sender_name = $name;
        return $this;
    }
    /**
      * sets the sender email
      * @todo  checks and clean up
      * @example $this->setSenderEmail($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setSenderEmail($email) {
        $this->sender_email = $email;
        return $this;
    }
    
     /**
      * scopes are available for advanced integration only
      * @todo  checks and clean up
      * @example $this->setSenderEmail($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    
    function setScope($id) {
        $this->scope_id = $id;
        return $this;
    }
    /**
      * Templates are not yet implemented as they are different depending on the mailing class, but for Mandrill templates are stored on mandrill and require replacement vars to be posted
      * @todo  checks and clean up
      * @example $this->setSenderEmail($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function setTemplate($t) {
        $this->template = $t;
        return $this;
    }
    
 
    /**
      * Objects and Parent Objects will be limited to custom integrations, for example, if you are to send an email about an update  a Campaign, than you could store  the campaign as parent and object_id as update you are talking about,
      *   Or a tienda vendor and product or something like that
      * @todo  checks and clean up
      * @example $this->setParentObject($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function setParentObject($id) {
        $this->parent_object_id = $id;
        return $this;
    }
    
     /**
      * Objects and Parent Objects will be limited to custom integrations, for example, if you are to send an email about an update  a Campaign, than you could store  the campaign as parent and object_id as update you are talking about,
      *   Or a tienda vendor and product or something like that
      * @todo  checks and clean up
      * @example $this->setObject($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setObject($id) {
        $this->object_id = $id;
        return $this;
    }
    
    /**
      * Sets a date of when the email should be sent, it is up to the mail queue processes to use the senddates in their getEmails methods 
      *
      * @todo   should we support DateTime objects here?
      * @example $this->setSendDate($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function setSendDate($date) {
        //TODO formating checking
        $this->senddate = $date;
        return $this;
    }
    
    /**
      * should the email be able to decide the sending Service? 
      * the idea here is that we could make a select of sending services some, like 0 would be default and 1, 2, 3, could be mandrill, joomla smtp, sendgrid,  or maybe even different mandrill accounts or configs
      *
      * @todo   implement this
      * @example $this->setSendMethod($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function setSendMethod() {
      return $this;
    }
    
    /**
      * events are part of advanced custom integration features 
      *
      * @todo   implement this farther
      * @example $this->setEvent($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function setEvent($id = null, $title = null) {
      $this->event->event_id = $id;
      $this->event->title = $title;
      return $this;
    }

    /**
      * events are part of advanced custom ingegration features, create the event, and return the ID so we can  assign emails to specific events
      *
      * @todo   implement this farther
      * @example $this->createEvent($title);
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */
    function createEvent() {


      if(@$this->event->event_id || @$this->event->title) {
      $this->event->scope_id = $this->scope_id; 
      $this->event->object_id = $this->object_id; 
      $this->event->parent_object_id = $this->parent_object_id; 
      $this->event->sender_id = $this->sender_id; 
      JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_messagebottle/tables');
        $table = JTable::getInstance('Events', 'MessagebottleTable');
        $table->bind($this->event) ;
        $table->save();
        return $table->event_id; 

       }
    }

    /**
      *  adds the lists of users to the email being created, however it stores a row for each  email added here as there own row.
      *
      * @todo   implement this farther
      * @example $this->createEvent($title);
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */
    
    function addRecipient($array) {
        if (is_array($array)) {
            //they passed an array, lets check if it is keyed, and if it has an email or an ID
            if (array_key_exists('receiver_email', $array) || array_key_exists('receiver_id', $array)) {
                $this->recipients[] = $array;
                

            }
            // if they sent an array, but not keyed example $array = array('chris@dioscouri.com','Chris French');
            //make it an arrayed key and store it and return count
            elseif (count($array)) {
                $array              = $this->makeKeyedArray($array);
                $this->recipients[] = $array;

            }
        }
        
        //if $array is actually just en email address example if something calls $bottle->addRecipient('chris@dioscouri.com');
        elseif (is_string($array)) {
            if ($this->check_email_address($array)) {
                //they added a valid email lets make it match the others and return the account
                $array              = array(
                    'receiver_email' => $array,
                    'receiver_name' => '',
                    'receiver_id' => '0'
                );
                $this->recipients[] = $array;

            }
        }
        
        elseif (is_numeric($array)) {

            //TODO NOT sure what to do if the user ID is not valid  
            $user               = JFactory::getUser($array);
            $array              = array(
                'receiver_email' => $user->email,
                'receiver_name' => $user->name,
                'receiver_id' => $user->id
            );
            $this->recipients[] = $array;
            
        }
        
        return $this;
    }
    
     /**
      * adds email to BCC holder, the BCCHolder  is an array that will be serialized and stored with the email
      *
      * @todo   
      * @example $this->addBCC($title);
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */
    
    function addBCC($email) {
        $this->bccHolder[] = $this->addEmail($email);
        return $this;
    }
    
    /**
      * adds email to CC holder, the BCCHolder  is an array that will be serialized and stored with the email
      *
      * @todo   
      * @example $this->addCC($title);
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */

    function addCC($email) {
        $this->ccHolder[] = $this->addEmail($email);
        return $this;
    }

    
    function addEmail($email) {
        if ($this->check_email_address($email)) {
            return $email;
        }
        return $this;
    }

    /**
      * attachments will need to be stored, probably in another  table of one to many relationships
      *
      * @todo  build in support for attachments
      * @example $this->addAttachment($title);
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */

    function addAttachment($path) {

      $this->attachments[] = array('path' => $path);
      $this->hasattachments = 1;
      return $this;
    }

    /**
      * attachments will need to be stored, probably in another  table of one to many relationships
      *
      * @todo  build in support for attachments
      * @example $this->addAttachment($title);
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */

    function saveAttachments($email_id) {
       JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_messagebottle/tables');
       $table = JTable::getInstance('Attachments', 'MessagebottleTable');
      foreach ($this->attachments as $attachment) {
        $keys = array('email_id' => $email_id, 'path' => $attachment['path'] );
        $table->load($keys);
        $table->email_id = $email_id;
        $table->path = $attachment['path'];
        $table->store();
      }
    }
    
     /**
      * html test, tests if  a variable contains html already so not to process it is as html
      *
      * @todo  add checks for everything
      * @example $this->prepareData();
      * @param string
      * @since 0.1
      * @return (int) true/false
      * 
      */
   
    function htmlTest($var) {
      //TODO make this alot smarter
      $return = true;
      if(strpos($var, '<br')) { 
        $return = false;
      }
      if(strpos($var, '<div')) { 
        $return = false;
      }
        if(strpos($var, '<td')) { 
        $return = false;
      }
      return $return;
    }

    function formatHtml($var) {
      //TODO maybe this is better.
      return nl2br( $var );
      // return  $var ;
    }

     /**
      * prepareData  checks and sets up the data before being stored
      *
      * @todo  add checks for everything
      * @example $this->prepareData();
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */


    function prepareData() {
        $this->bcc = serialize($this->bccHolder);
     
        $this->cc = serialize($this->ccHolder);

        $this->setView('');
        $this->setOption('');
        
        //fix for bad naming conventions
        if(empty($this->Body) && !empty($this->body)) {
          $this->Body = $this->body;
        }

        if ($this->isHtml) {
                if($this->htmlTest($this->Body)) {
                  $this->Body = $this->formatHtml( $this->Body );
                } 
        }
        
        return $this;
    }
    
     /**
      * This is the  method that  stores the data in the database for sending later
      *
      * @todo  add checks for everything
      * @example $this->prepareData();
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */

    
     function queue() {
        $this->prepareData();
        
        $event_id = $this->createEvent();

        //each person gets their own email.
        foreach ($this->recipients as $receiver) {
          
            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_messagebottle/tables');
            $table = JTable::getInstance('Emails', 'MessagebottleTable');
            $table->bind($this);
            $table->body = $this->Body;
              if(@$event_id) {
                 $table->event_id    = $event_id;  
              }
              $table->receiver_id    = $receiver['receiver_id'];
              $table->receiver_name  = $receiver['receiver_name'];
              $table->receiver_email = $receiver['receiver_email'];
               if ($table->store())  { 

                 JPluginHelper::importPlugin( 'messagebottle', null, true, null);
                 $dispatcher = JDispatcher::getInstance();
                 $dispatcher->trigger( 'AfterMessageBottleQueueEmail', array( $table ) );
                  
               }
              
              
        }

        $this->saveAttachments($table->email_id);


        return true;   
    }

     /**
      * This is just a for fun method, you could just call $this->queue() but it is more fun to bottleit
      *
      * @todo  
      * @example $this->bottleit();
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */
    function bottleit() {
      return $this->queue();
     
    }
     /**
      * Wrapper to  grab JMailer emails
      *
      * @todo  
      * @example $this->Send();
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */
    function Send() {
      return  $this->queue();
     
    }
    
     /**
      * Wrapper to  grab JMailer emails, the reason for this it support Jmailer and also keep with our naming scheme,  for this class AddSomething() is for having more than one, and setSomething is for single values
      *
      * @todo  
      * @example $this->addReplyTo();
      * @param string
      * @since 0.1
      * @return (int) event_id
      * 
      */
    function addReplyTo($email) {
      $this->setReplyTo($email);
      return $this;
    }
     /**
      * Wrapper to  grab JMailer emails
      *
      * @todo  
      * @example $this->IsHTML();
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    function IsHTML() {
        $this->setHTML();
        return $this;
    }
    
     /**
      * in Joomla Jmailer they have this SendMail Function which sends the email right than, we should probably do the same here. We could  make this function send the email, and store the result of the already sent mail in the queue
      * @todo Make the function work
      * @example $this->sendMail($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */

    function sendMail($from, $fromname, $recipient, $subject, $body, $mode = 0, $cc = null, $bcc = null, $attachment = null, $replyto = null, $replytoname = null) {
      // Deprecation warning.
       JLog::add('JUtility::sendmail() is deprecated.', JLog::WARNING, 'deprecated');

       $this->setSenderEmail($from);
       $this->setSenderName($fromname);
       $this->addRecipient($recipient);
       $this->setSubject($subject);
       $this->setBody($body);
       $this->setHTML($mode);
       $this->addCC($cc);
       $this->addBCC($bcc);
       $this->addAttachment($attachment);
       $this->setReplyTo($replyto);
       $this->queue();

      return true;
    }
     /**
      * do we need to do anything with this? This is a Jmailer wrapper
      * @todo Make the function work
      * @example $this->useSendmail($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    
    function useSendmail() {
      return $this;
    }

    /**
      * do we need to do anything with this? This is a Jmailer wrapper
      * @todo Make the function work
      * @example $this->useSMTP($title);
      * @param string
      * @since 0.1
      * @return 
      * 
      */
    
    function useSMTP() {
      return $this;
    }
    
    /*HELPER Validator Functions */
    
    /*taken from http://stackoverflow.com/a/6232878 */
    
    function check_email_address($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    
    
    function makeKeyedArray($array) {
        $keyedArray                   = array();
        $keyedArray['receiver_email'] = $array[0];
        $keyedArray['receiver_name']  = $array[1];
        $keyedArray['receiver_id']    = $array[2];
        
        return $keyedArray;
    }
    
    public function bind($src, $ignore = array())
  {
        // If the source value is not an array or object return false.
        if (!is_object($src) && !is_array($src)) {
                $this->setError(get_class($this).'::bind failed. Invalid source argument');
                return false;
        }
 
        // If the source value is an object, get its accessible properties.
        if (is_object($src)) {
                $src = get_object_vars($src);
        }
 
        // If the ignore value is a string, explode it over spaces.
        if (!is_array($ignore)) {
                $ignore = explode(' ', $ignore);
        }
 
        // Bind the source value, excluding the ignored fields.
        foreach ($this->getProperties() as $k => $v) {
                // Only process fields not in the ignore array.
                if (!in_array($k, $ignore)) {
                        if (isset($src[$k])) {
                                $this->$k = $src[$k];
                        }
                }
        }
 
        return true;
  } 
    
    public function ClearReplyTos() {
        $this->ReplyTo = array();
    }
    
    
}
?>