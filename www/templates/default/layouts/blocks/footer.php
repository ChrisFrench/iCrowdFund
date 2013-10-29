<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
$footer = false;
if ($this->countModules('footer')) {
	$footer = true;
}

$Footer1 = false;
if ($this->countModules('footer1')) {
	$Footer1 = true;
}
$Footer2 = false;
if ($this->countModules('footer2')) {
	$Footer2 = true;
}
$Footer3 = false;
if ($this->countModules('footer3')) {
	$Footer3 = true;
}
$Footer4 = false;
if ($this->countModules('footer4')) {
	$Footer4 = true;
}

?>

<?php if ($footer || $Footer1 || $Footer2 || $Footer3 || $Footer4 ) { ?>
<div id="footer-wrapper" class="center full ">
	<div class="outer table center">
    <div id="footer" class="clear wrap row">
    
        <?php if ($Footer1) { ?>
        <div id="footer-1" class="cell vtop">
        	<jdoc:include type="modules" name="footer1" style="default" />
        </div>
        <?php } ?>
        
        
        <?php if ($Footer2) { ?>
        <div id="footer-2" class="cell vtop ">
        	<jdoc:include type="modules" name="footer2" style="default" />
        </div>
        <?php } ?>
        <?php if ($Footer3) { ?>
        <div id="footer-3" class="cell vtop">
        	<jdoc:include type="modules" name="footer3" style="default" />
        </div>
        <?php } ?>
        <?php if ($Footer4) { ?>
        <div id="footer-4" class="cell vtop">
        	<jdoc:include type="modules" name="footer4" style="default" />
        </div>
        <?php } ?>
        
    </div>
    </div>
</div>
<?php } ?>
