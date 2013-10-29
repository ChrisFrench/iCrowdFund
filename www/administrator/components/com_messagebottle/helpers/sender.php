<?php
/**
 * @package Messagebottle
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// Require the base controller
Messagebottle::load( 'Mandrill', 'library.Mandrill' );


class Sender { 

var $Mandrill = null;

function __construct() { 
	FB::log('constructing sender');
	$apiKey = Messagebottle::getInstance()->get('md_smtp_api_key','eysm51A1VGiYEYLkTwgs2g');
	$apiKey = 'eysm51A1VGiYEYLkTwgs2g';
	FB::log($apiKey);
	$this->Mandrill = new Mandrill($apiKey);
	FB::log($this->Mandrill);

	$params = array(
   
    'message' => array(
        "html" => "<p>\r\n\tHi Adam,</p>\r\n<p>\r\n\tThanks for <a href=\"http://mandrill.com\">registering</a>.</p>\r\n<p>etc etc</p>",
        "text" => null,
        "from_email" => "chris@ammonitenetworks.com",
        "from_name" => "chris french",
        "subject" => "Your recent registration",
        "to" => array(array("email" => "contact@chrisfrench.me")),
        "track_opens" => true,
        "track_clicks" => true,
        "auto_text" => true )
    );
    FB::log($params);
    FB::log($this->Mandrill->users->ping($params, true));

$this->Mandrill->messages->send($params, true);
}


function getEmails() {}

function perpareEmail() {}

function doSend() {


$params = array(
   

        "html" => "<p>\r\n\tHi Adam,</p>\r\n<p>\r\n\tThanks for <a href=\"http://mandrill.com\">registering</a>.</p>\r\n<p>etc etc</p>",
        "text" => null,
        "from_email" => "chris@ammonitenetworks.com",
        "from_name" => "chris french",
        "subject" => "Your recent registration",
        "to" => array(array("email" => "contact@chrisfrench.me")),
        "track_opens" => true,
        "track_clicks" => true,
        "auto_text" => true
    
);

//$this->Mandrill->messages->send($params, false);
FB::log($this->Mandrill->messages->send($params, true));

/*"message": {
        "html": "example html",
        "text": "example text",
        "subject": "example subject",
        "from_email": "message.from_email@example.com",
        "from_name": "example from_name",
        "to": [
            {
                "email": "example email",
                "name": "example name"
            }
        ],
        "headers": {
            "...": "..."
        },
        "track_opens": true,
        "track_clicks": true,
        "auto_text": true,
        "url_strip_qs": true,
        "preserve_recipients": true,
        "bcc_address": "message.bcc_address@example.com",
        "merge": true,
        "global_merge_vars": [
            {
                "name": "example name",
                "content": "example content"
            }
        ],
        "merge_vars": [
            {
                "rcpt": "example rcpt",
                "vars": [
                    {
                        "name": "example name",
                        "content": "example content"
                    }
                ]
            }
        ],
        "tags": [
            "example tags[]"
        ],
        "google_analytics_domains": [
            "..."
        ],
        "google_analytics_campaign": "...",
        "metadata": [
            "..."
        ],
        "recipient_metadata": [
            {
                "rcpt": "example rcpt",
                "values": [
                    "..."
                ]
            }
        ],
        "attachments": [
            {
                "type": "example type",
                "name": "example name",
                "content": "example content"
            }
        ]
    },
    "async": true
} */







}

function updateRecord() {}



}

?>