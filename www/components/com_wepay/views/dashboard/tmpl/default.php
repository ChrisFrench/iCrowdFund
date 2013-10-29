<?php

$account = WepayHelperWepay::getUser();
if (empty($account -> access_token)) {
	$this -> setLayout('default_form');
	echo $this -> loadTemplate();
} else {
	$this -> setLayout('default_account');
	echo $this -> loadTemplate();
}
?>