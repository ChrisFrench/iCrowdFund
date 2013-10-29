<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<h1 class="indent-60 lubefont">Project Details</h1>
<div class="indent-60">
<div class="tabbable">
<ul class="nav nav-pills">
	<li class=" first active"><a>Select Type</a></li>
    <li class="disabled"><a>Project Details</a></li>
    <li class="disabled"><a>Funding Levels</a></li>
    <li class="disabled"><a>WePay Account</a></li>
    <li class="disabled"><a>Checklist</a></li>
</ul>
</div>
    <div class="progress">
    <div class="bar bar-success" style="width: 5%;"></div>
    <div class="bar bar-warning" style="width: 95%;"></div>
    </div>
<br />
<div class="well well-small">
<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns' );  ?>">
  <button id="submit" type="submit"  class="indent-60 btn btn-funding btn-large">Exciting Projects</button>
 
  <input name="type" type="hidden" value="1">
  <input name="task" type="hidden" value="newproject">
  <input name="layout" type="hidden" value="form">
</form>
</div>

<div class="well well-small">
<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns' );  ?>">
  <button id="submit" type="submit"  class="indent-60 btn btn-funding btn-large">501 (c) (3) Charities</button>
 
  <input name="type" type="hidden" value="2">
   <input name="task" type="hidden" value="newproject">
    <input name="layout" type="hidden" value="form">
</form>
</div>

<div class="well well-small">
<form class="form-inline center page" method="post" action="<?php JRoute::_( 'index.php?option=com_tienda&view=campaigns' );  ?>">
  <button id="submit" type="submit"  class="indent-60 btn btn-funding btn-large">Important Causes</button>

  <input name="type" type="hidden" value="3">
  <input name="task" type="hidden" value="newproject">
  <input name="layout" type="hidden" value="form">
</form>
</div>





</div>
