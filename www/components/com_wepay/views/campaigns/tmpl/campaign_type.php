<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div>
<h1> Project Type</h1>
<ul class="nav nav-pills">
	<li class="active"><a >Select Type</a></li>
    <li class="disabled"><a >Project Details</a></li>
    <li class="disabled"><a >Funding Levels  </a></li>
    <li class="disabled"><a >WePay Account </a></li>
    <li class="disabled"><a >Checks </a></li>
</ul>
    <div class="progress">
    <div class="bar bar-success" style="width: 5%;"></div>
    <div class="bar bar-warning" style="width: 95%;"></div>
    </div>
<br />
<div class="well well-small">
<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns' );  ?>">
  <button id="submit" type="submit"  class="btn btn-primary btn-large">NORMAL PROJECT</button>
  <div> a normal project is something to raise money</div>
  <input name="layout" type="hidden" value="form">
</form>
</div>
<div class="well well-small">
<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns' );  ?>">
  <button id="submit" type="submit" class="btn btn-primary btn-large">CHARITY PROJECT</button>
  <div> a charity project is something to raise money</div>
  <input name="layout" type="hidden" value="campaign_charityform">
</form>
</div>
</div>
