<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
jimport('joomla.html.parameter');
jimport('joomla.filesystem.file');
$document = JFactory::getDocument();

$main_column_class = htmlspecialchars( substr( JRequest::getVar('option'), strpos( JRequest::getVar('option'), '_' ) + 1 ) . "-" . JRequest::getVar('view') );
if (JRequest::getVar('layout')) {
    $main_column_class .= "-" . htmlspecialchars( JRequest::getVar('layout') );
}

$generator = 'iCrowdFund';
$document->setGenerator( $generator );

$pagetitle_suffix = $this->params->get( 'pagetitle_suffix' );

$page_title =  'iCrowdFund - A New Crowdfunding Platform'. ' - ' .$document->getTitle();
$title = strip_tags( $page_title, '<br>' );
$title = str_replace( array( '<br>', '<br/>' ), ' ', $title );
//$title = ucfirst($title);
$document->setTitle( $title . ' | ' . $pagetitle_suffix );

$noConflict = "jQuery.noConflict();";
$document->addScriptDeclaration( $noConflict );

//JHTML::_( 'script', 'jquery-1.7.2.min.js', 'templates/default/js/' );

if (JDEBUG) {
	JHTML::_( 'script', 'dump.js', 'templates/default/js/' );
}

JHTML::_('behavior.modal', 'a.modal', array('handler'=>'iframe', 'size'=>array('x'=>'800', 'y'=>'700') ));

DSC::loadBootstrap('2.2.2', FALSE);
JHTML::_( 'script', 'default.js', 'templates/default/js/' );

$app    = JFactory::getApplication();
$menu   = $app->getMenu();
$active = $menu->getActive();
$params = new JRegistry();

$name = '';
$pageclass = '';
$tree_count = '';
if (is_object( $active )) :
    $name = !empty($active->name) ? $active->name : $active->title;
    $params->loadString( $active->params );
    $pageclass .= ' ' . $params->get( 'pageclass_sfx' ) . ' ';
    $tree_count = count($active->tree);
endif;

$display_component = $this->params->get( 'display_component' );
$display_pagetitle = $this->params->get( 'display_pagetitle' );

$left = false;
if ($this->countModules('left')) {
	$left = true;
}

$right = false;
if ($this->countModules('right')) {
	$right = true;
}

$above = false;
if ($this->countModules('above')) {
	$above = true;
}
$banner = false;
if ($this->countModules('banner')) {
	$banner = true;
}
$homelogos = false;
if ($this->countModules('homelogos')) {
	$homelogos = true;
}


$featuredProject = false;
if ($this->countModules('featuredProject')) {
	$featuredProject = true;
}
$below = false;
if ($this->countModules('below')) {
	$below = true;
}

$leftComponent = false;
if ($this->countModules('component-left')) { 
	$leftComponent = true; 
}

$rightComponent = false;
if ($this->countModules('component-right')) {
	$rightComponent = true;
}

$rightwComponent = false;
if ($this->countModules('right-navigation')) {
	$rightwComponent = true;
}


$title = false ;
if(@$active->params) {
if(@$active->params->get('page_title') && @$active->params->get('show_page_heading')  ) {
$title = $active->params->get('page_title');
}}



$layout_file = $this->params->get( 'layout', 'default.php' );

if (JFile::exists( JPATH_SITE . '/templates/default/layouts/' . $layout_file )) {
	require 'layouts/' . $layout_file;
}
?>
