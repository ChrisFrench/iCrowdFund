<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
JHTML::_('script', 'tienda_inventory_check.js', 'media/com_tienda/js/');

$item = @$this -> row;
$options = array();
$options['num_decimals'] = 0;

if (!class_exists('Favorites')) {JLoader::register("Favorites", JPATH_ADMINISTRATOR . DS . "components" . DS . "com_favorites" . DS . "defines.php");}

Favorites::load( 'FavoritesHelperFavorites', 'helpers.favorites' );

$helper = new FavoritesHelperFavorites();
?>

<div id="campaignWrapper" class="full wrap campaignPage">
	<div id="campaignHeader" class="full greyTopHeader"><h1 class="indent-60"><?php echo @$item -> campaign_name; ?></h1></div>
	<div id="campaignMainColumn" class="pull-left indent-60">
		
	<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-pills">
    <li class="active"><a href="#home" data-toggle="tab">Home</a></li>
    <li ><a class="pull-left" href="#updates"  data-toggle="tab">Updates  <b class="white label label-inverse "><?php echo count(@$item -> updates); ?></b></a></li>
    <li><a class="pull-left" href="#backers" data-toggle="tab">Backers <b class="white label label-inverse "><?php echo count(@$item -> backers); ?></b></a></li>
    <li><a class="pull-left" href="#comments"  data-toggle="tab">Comments <b class="white label label-inverse "><?php
	$shortname = 'ammonitenetworks';
	$article_identifier = 'campaign' . $item -> campaign_id;
	$count_js = @file_get_contents('http://' . $shortname . '.disqus.com/count.js?q=1&0=1,' . $article_identifier);
	//Strip it of spaces, hard to parse later on
	str_replace(' ', '', $count_js);
	if (preg_match('/DISQUSWIDGETS\.displayCount\((.*)\)/si', $count_js, $matches)) {
		$count_json = @json_decode($matches[1], TRUE);
		$count = $count_json['counts'][0]['comments'];
		echo $count;
	}
 ?> </b></a></li>
  </ul>
  
<div class="tab-content">
    <div class="tab-pane active" id="home">
      <div id="campaignImageWrapper" class="pull-left">
      	<?php if($item->video) : ?>
      	<?php echo TiendaHelperCampaign::video(@$item->video); ?>
      	<?php else : ?>
      		<img class="img-rounded" src="<?php echo TiendaHelperCampaign::getImage(@$item, 'full', '', TRUE); ?>"> 	
      	<?php endif; ?>
      
      	
      	</div>
      <div class="options indent-10 pull-right"  style="width:120px;">
      		
      		<div class="follow">
      				<?php echo $helper->favButton($item->campaign_id, '1',$item->campaign_name); ?>
      		</div>
      		<div class="social">
			<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(JUri::current()); ?>&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=136306549842503" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
      			<?php echo '
      		 <ul class="shareicons">
		  						
		  						<li ><script>function fbs_click() {u=location.href;t=document.title;window.open(\'http://www.facebook.com/sharer.php?u=\'+encodeURIComponent(u)+\'&t=\'+encodeURIComponent(t),\'sharer\',\'toolbar=0,status=0,width=626,height=436\');return false;}</script>
        <a href="http://www.facebook.com/share.php?u='.JURI::current().'" onClick="return fbs_click()" target="_blank" title="Share This on Facebook"><img src="'.JURI::base().'images/common/logo_fb.png" alt="facebook" width="24" height="25" border="0" /></a></li>
        						<li ><div id="custom-tweet-button">
   <a href="http://twitter.com/share?url='.JURI::current().'&text='.$item -> campaign_name.'" target="_blank" rel="nofollow"><img src="'.JURI::base().'images/common/logo_tw.png"</a></div></li>
   								
		  					</ul>'; ?>
      		 
      			<?php
            $modules = JModuleHelper::getModules("campaigns_social" );
            $document   = JFactory::getDocument();
            $renderer   = $document->loadRenderer('module');
            $attribs    = array();
            $attribs['style'] = '';
            foreach ( @$modules as $mod ) 
            {
                echo $renderer->render($mod, $attribs);
            }
            ?>
      		</div>
      		</a>
      	</div>
      	<div class="clearfix" ><br></div>
		<div class="clearfix" id="campaignDetails">
			<div id="campaignShortDescription">
			<?php  echo @$item -> campaign_shortdescription; ?>	
			</div>
			<div id-"campaignDetailsOwner">
			<div class="ownername">Project Started By :<?php echo $item->owner->username;?></div>
			<div class="launched">Launched: <?php echo $item->fundingstart->format('M d, Y '); ?></div>
			<div class="finished">Funding Ends:<?php echo $item->fundingend->format('M d, Y '); ?></div>
			
			</div>
		</div>
		<div id="campaignDescription"><?php echo @ $item -> campaign_description; ?></div>
    </div>
    <div class="tab-pane" id="updates">
      <div>
      	<ul>
      		<?php foreach(@$item->updates as $update) : ?>
      		<li>
      			<div>
      				<h3><?php echo $update -> title; ?></h3>
      				<div id=" " class="">
      					<?php echo $update -> introtext; ?>
      				</div>
      			</div>
      		</li>
      		<?php endforeach ; ?>
      	</ul>
      	
      </div>
    </div>
 	<div class="tab-pane" id="backers">
 		<?php foreach (@$item->backers as $backer)  : ?>
 			
      <div class="media">
  		<a class="pull-left" href="#">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::gravatar($backer); ?>">
  </a>
  <div class="media-body">
    <h4 class="media-heading"><?php echo $backer -> name; ?> </h4>
  
    <div class="media">
    </div>
    
  </div>
</div>
<?php endforeach; ?>
    </div>
    <div class="tab-pane" id="comments">
      <p> 
      	<div id="disqus_thread"></div>
        <script type="text/javascript">
			/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
			var disqus_shortname = 'ammonitenetworks';
			// required: replace example with your forum shortname
			var disqus_identifier =  'campaign
<?php  echo $item -> campaign_id; ?>
				';
				var disqus_url = '
<?php echo JURI::current(); ?>
				';

				/* * * DON'T EDIT BELOW THIS LINE * * */
				(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				})();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
        </p>
    </div>
	
 </div><!-- Tab Content -->
</div><!-- Tabbable -->	

	</div>
	
	<div id="campaignLeftColumn" class="pull-left indent-10">
			<?php
            $modules = JModuleHelper::getModules("campaigns_right_top" );
            $document   = JFactory::getDocument();
            $renderer   = $document->loadRenderer('module');
            $attribs    = array();
            $attribs['style'] = '';
            foreach ( @$modules as $mod ) 
            {
                echo $renderer->render($mod, $attribs);
            }
            ?>
		<div class="fundingWrapper">
		<div id="campaignRaised" class="clear right-block well full">
			<div class="amount full"><?php echo TiendaHelperProduct::currency($item -> campaign_raised, '', $options); ?></div>
			<div class="progress progress-striped active"><div class="bar" style="width: <?php echo TiendaHelperCampaign::percent($item -> campaign_raised, $item -> campaign_goal, '100'); ?>%;"></div></div>
		<div class="campiagnGoal"><small>Raised of <?php  echo TiendaHelperProduct::currency($item -> campaign_goal, '', $options); ?> goal</small></div>
		
		
		<div class="campiagnDays pull-left full"><?php echo TiendaHelperCampaign::daysRemaining($item -> fundingstart_date, $item -> fundingend_date, TRUE); ?></div>
		<?php if(count(@$item->levels)) : ?>
		<form action="<?php echo JRoute::_(@$form['action']); ?>" method="POST">
		<div class="fundButton"><button class="full btn btn-large btn-funding">FUND THIS PROJECT</button> </div>
		<input type="hidden" name="layout" value="campaign_fund" >
		</form>
		<div class="pledgedNote">Project won't be funded unless it gets atleast <?php  echo TiendaHelperProduct::currency($item -> campaign_goal, '', $options); ?> amount of dollars</div>
		<?php endif; ?>
		</div>
		</div>
		<div class="clear wrap"></div>
		<div id="campaignLevels" class=" clear right-block well full">
				<?php if(count(@$item->levels)) : ?>
			<ul><?php foreach ($item->levels as $level) : ?> 
				
				<li class="clearfix">
					<div>
						<div class="pull-left">
						<h5><?php  echo TiendaHelperProduct::currency($level -> price, '', $options); ?> Level</h5>
						<?php // <div class="backers">4 Backers</div> ?>
						</div>
						<div class="product_buy pull-right" id="product_buy_<?php echo $level -> product_id; ?>">
							
          				      <?php echo TiendaHelperProduct::getCartButton($level -> product_id, 'campaign_buy');
          				      ?>
           					 </div> 
         				<div class="clearfix"></div>
						<div class="clearfix  level_description_short"><?php echo $level -> product_description_short; ?></div>
           
						
						
					</div>
				</li>
				
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
			<?php
            $modules = JModuleHelper::getModules("campaigns_right_bottom" );
            $document   = JFactory::getDocument();
            $renderer   = $document->loadRenderer('module');
            $attribs    = array();
            $attribs['style'] = '';
            foreach ( @$modules as $mod ) 
            {
                echo $renderer->render($mod, $attribs);
            }
            ?>
	</div>
</div>

<div class="clear wrap center"></div>
<script type="text/javascript">
	/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
	var disqus_shortname = 'ammonitenetworks';
	// required: replace example with your forum shortname

	/* * * DON'T EDIT BELOW THIS LINE * * */
	( function() {
			var s = document.createElement('script');
			s.async = true;
			s.type = 'text/javascript';
			s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
		}());
        </script>
 

