<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php // include style sheet
$document->addStyleSheet( JURI::root(true).'/modules/mod_ambra_top_points_earners/tmpl/style.css');
$article_url = Ambra::getClass( "AmbraHelperPoint", 'helpers.point' )->getArticle();
?>
<?php if ( $article_url && $params->get( 'display_article', '0' ) ) : ?>
    <div class="points_article">
        <a href="<?php echo JRoute::_( $article_url ); ?>">
            <?php echo JText::_( "Learn About Our Points Program" ); ?>
        </a>
    </div>
<?php endif;?>
        
<?php foreach ($items as $item) : ?>
<div class="top_earner_item">
    <div class="top_earner_avatar">
        <a href="<?php echo JRoute::_( $url."&id=".$item->id ); ?>">
        <img src="<?php echo Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $item->id ); ?>" />
        </a>
    </div>
    
    <div class="top_earner_data">
        <div class="top_earner_name">
        <a href="<?php echo JRoute::_( $url."&id=".$item->id ); ?>">
            <?php echo ($params->get('username_length')) ? substr( $item->username, 0, $params->get('username_length') ) : $item->username; ?>
        </a>
        </div>
        <div class="top_earner_points"><?php echo (int) $item->points_total." ".JText::_( "Points" ); ?></div>
    </div>
</div>
<?php endforeach; ?>