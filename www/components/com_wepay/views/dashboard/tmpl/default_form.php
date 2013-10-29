<h1>Start Your Project</h1>
<br>
<br>
<div class="alert alert-info fade in span12" style="margin-left:0;">
	<form action="<?php echo JRoute::_('index.php?option=com_wepay&view=oauth&Itemid=' . Wepay::getInstance() -> get('oauth_itemid', '153'), true, -1); ?>">
	<div class="span12">
	<div class="span5">
		
		<h4>
			
<h2>What is WePay?</h2>
</h4>
<p>
WePay is a secure payment processor. Take a minute to register and start accepting payments online instantly.
</p>
</div>
	<div class="span5">
		<br>
		<button class="btn btn-primary btn-large" value="submit" type="submit">Connect My iCrowdFund account to wepay.com</button>
	</div>
	</form>
</div>
</div>
<br><br>

<div class="span10 clearfix">
	<h2>To created a project and receive funds, you need to register with wepay.com</h2>
	When you click the image above you will be directed to wepay.com as you see in the image below,  please fill out wepay  
	information and when your account is created you will be redirected back to iCrowdFund, to create your project. 
	<strong>You only have to follow through this process one time, and it only takes a minute</strong>
<img src="<?php echo DSC::getUrl('images', 'com_wepay');?>wepayOauth.png">
		</div>