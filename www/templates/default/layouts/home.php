<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('stylesheet', 'default.css', 'templates/default/css/');
$pageclass .= 'default';

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>
    <jdoc:include type="head" />
    <?php include 'blocks/head.php'; ?>
</head>

<body class="full<?php if (!empty($pageclass)) { echo $pageclass; } ?>">
<div class="ribbon-wrapper-green"><div class="ribbon-green">BETA</div></div>
    <div id="body-wrapper" class="center">
     <?php
	               
	                $messages = $app->getMessageQueue();
	                if (!empty($messages))
	                {
	                    ?>
	                   
	                        <jdoc:include type="message" />
	                   
	                    <?php
	                } 
	                ?>
    	<div id="main">
    		<?php include 'blocks/top-navigation.php'; ?>
    		
            <div id="header-wrapper" class="clearfix wrap center table outer">
                <div id="header" class="clearfix center wrap page row">                	
                    <div id="logo" class="cell vtop">
                        <a href="<?php echo $this->baseurl ; ?>">
                        <img src="<?php echo $this->baseurl ; ?>/templates/default/images/logo.png" alt="<?php echo $pagetitle_suffix; ?>" title="<?php echo $pagetitle_suffix; ?>">
                        </a>
                    </div>
                    
					<div id="primary-navigation" class="flat wrap cell vtop">
					    <jdoc:include type="modules" name="primary-navigation" style="default" />
					</div>

                    <div id="search" class="cell vtop">
                        <jdoc:include type="modules" name="search" style="default" />
                        <div class="btn-toolbar">
                        	<jdoc:include type="modules" name="userbar" style="none" />
                        </div>
                    </div>

                </div>
            </div>
           
            	<?php if ($banner) { ?>
            		 <div id="banner-wrapper"  class="clearfix wrap center">
					<div id="banner" class="clearfix center wrap outer">
						<jdoc:include type="modules" name="banner" style="default" />
					</div>
					  </div>
					<?php } ?>
					<?php if ($homelogos) { ?>
            		 <div id="homelogos-wrapper"  class="clearfix wrap center">
					<div id="homelogos" class="clearfix center wrap outer">
						<jdoc:include type="modules" name="homelogos" style="default" />
					</div>
					  </div>
					<?php } ?>
					
          <?php if ($featuredProject) { ?>
            		 <div id="featuredProject-wrapper"  class="clearfix wrap center">
					<div id="featuredProject" class="clearfix center wrap outer">
						<jdoc:include type="modules" name="featuredProject" style="default" />
					</div>
					  </div>
					<?php } ?>

            <div id="content-wrapper" class="clearfix wrap center">
            	
            	
            	<div id="content" class="clearfix wrap center page">
	                <?php /*
	                $messages = $app->getMessageQueue();
	                if (!empty($messages))
	                {
	                    ?>
	                    <div id="system-messages" class="clearfix wrap">
	                        <jdoc:include type="message" />
	                    </div>
	                    <?php
	                } */
	                ?>
	
					<?php if ($above) { ?>
					<div id="above" class="clearfix center wrap page">
						<jdoc:include type="modules" name="above" style="default" />
										</div>
					<?php } ?>
	
	    			<div id="page-title-navigation" class="clearfix center wrap page">
					    <div id="breadcrumb">
					    	<jdoc:include type="modules" name="breadcrumb" style="default" />
					    </div>
                        <?php if ($display_pagetitle) { ?>
					    <h1 id="page-title">
					    	<?php echo $page_title; ?>
					    </h1>
                        <?php } ?>
					    <div id="secondary-navigation">
						    <jdoc:include type="modules" name="secondary-navigation" style="default" />
					    </div>
					</div>
	
					<?php if ($left) { ?>
	                <div id="left" class="left wrap">
	                    <jdoc:include type="modules" name="left" style="default" />
	                </div>
	                <?php } ?>
					
					<?php if ($display_component) { ?>
							
		                <?php if ($leftComponent) { ?>
		                <div id="component-left" class="left wrap">
		                    <jdoc:include type="modules" name="component-left" style="default" />
		                </div>
		                <?php } ?>
		
		                <div id="main-column" class="<?php if (!$leftComponent && !$rightComponent) { echo 'full '; } ?> left wrap <?php if ($leftComponent) { echo "with-left"; } ?> <?php if ($rightComponent) { echo "with-right"; } ?>">
		                    <jdoc:include type="modules" name="component-above" style="default" />
		                    <jdoc:include type="component" />
		                    <jdoc:include type="modules" name="component-below" style="default" />
		                </div>
		
		                <?php if ($rightComponent) { ?>
		                <div id="component-right" class="right wrap">
		                    <jdoc:include type="modules" name="component-right" style="default" />
		                </div>
		                <?php } ?>
	                
	                <?php } ?>
	
					<?php if ($right) { ?>
	                <div id="right" class="right wrap">
	                    <jdoc:include type="modules" name="right" style="default" />
	                </div>
	                <?php } ?>
	
	                <?php if ($below) { ?>
					<div id="below" class="clearfix center wrap page">
						<jdoc:include type="modules" name="below" style="default" />
					</div>
					<?php } ?>
				</div>
            </div>
</div>
</div>
            <?php include 'blocks/footer.php'; ?>
    
</body>
</html>
