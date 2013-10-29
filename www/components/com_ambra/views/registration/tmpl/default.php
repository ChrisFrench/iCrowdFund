<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php

  if(Ambra::getInstance()->get('use_jquery', 0)) {
    $this->setLayout( 'default_jquery' ); echo $this->loadTemplate();
  } else {
    $this->setLayout( 'default_mootools' ); echo $this->loadTemplate(); 
  }
  ?>
    