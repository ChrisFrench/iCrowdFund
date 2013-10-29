<?php defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('stylesheet', 'default.css', 'templates/default/css/');
$pageclass = 'default';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
    <jdoc:include type="head" />
    <?php include JPATH_SITE . '/templates/' . $this->template .  '/layouts/blocks/head.php'; ?>
</head>

<body class="full<?php if (!empty($pageclass)) { echo $pageclass; } ?>">
    <div id="body-wrapper" class="clear wrap center">
		<div id="main">
        <div id="content-wrapper" class="clear wrap center">
            <div id="content" class="clear wrap center page">
            <?php
            $messages = $app->getMessageQueue();
            if (!empty($messages))
            {
                ?>
                <div id="system-messages" class="clear wrap">
                    <jdoc:include type="message" />
                </div>
                <?php
            }
            ?>
            
            <p><?php echo $this->error->getMessage(); ?></p>
            
			</div>
        </div>
		</div>
    </div>
</body>
</html>
