<?php
$url = JURI::base();
if(!strpos($url, '.')) {
$url = 'http://staging.icrowdfund.com/';
}
?>
<div><div><img src="<?php echo $url; ?>templates/default/images/logo.png"></div>