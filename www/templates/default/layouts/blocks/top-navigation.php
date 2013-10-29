<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php 
$top_navigation = false;
if ($this->countModules('top-navigation')) {
	$top_navigation = true;
}
?>

<?php if ($top_navigation) { ?>
<div id="top-wrapper" class="clear wrap center">
	<div id="top-navigation" class="clear center wrap page">
    	<jdoc:include type="modules" name="top-navigation" style="default" />
	</div>
</div>
<?php } ?>