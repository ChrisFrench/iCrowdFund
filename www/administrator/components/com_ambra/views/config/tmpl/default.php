<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

        <div id='onBeforeDisplay_wrapper'>
            <?php 
                $dispatcher = JDispatcher::getInstance();
                $dispatcher->trigger( 'onBeforeDisplayConfigForm', array() );
            ?>
        </div>                

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; min-width: 70%;">

                    <?php
                    // display defaults
                    $pane = '1';
                    echo $this->sliders->startPane( "pane_$pane" );
                    
                    $legend = JText::_( "General Settings" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'general' );
                    ?>
                    
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display UI Submenu' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'display_submenu', 'class="inputbox"', $this->row->get('display_submenu', '1') ); ?>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Title for Required Information' ); ?>
                            </th>
                            <td>
                                <input type="text" name="defaults_title" value="<?php echo $this->row->get('defaults_title', 'Basic Information'); ?>" />
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Javascript Framework' ); ?>
                            </th>
                            <td>
                              <?php echo AmbraSelect::usejQuery( @$this->row->get('use_jquery', '0'), 'use_jquery' ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Profile Menu Item ID' ); ?>
                            </th>
                            <td>
                          <input type="text" name="profile_itemid" value="<?php echo $this->row->get('profile_itemid', '0'); ?>" />

                            </td>
                            <td>
                            </td>
                        </tr>
                       
                        
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Article for Login View' ); ?>
                            </th>
                            <td style="width: 280px;">
                                <?php echo $this->elementArticle_login; ?>
                                <?php echo $this->resetArticle_login; ?>              
                            </td>
                            <td>
                                <?php echo JText::_( 'Article for Login View Desc' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Article for Logout View' ); ?>
                            </th>
                            <td>
                                <?php echo $this->elementArticle_logout; ?>
                                <?php echo $this->resetArticle_logout; ?>                
                            </td>
                            <td>
                                <?php echo JText::_( 'Article for Logout View Desc' ); ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel();
                    
                    $legend = JText::_( "Login Settings" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'login' );
                    ?>
                    
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Redirect on Login' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'login_redirect', 'class="inputbox"', $this->row->get('login_redirect', '0') ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Login Redirect URL' ); ?>
                            </th>
                            <td>
                                <input type="text" name="login_redirect_url" value="<?php echo $this->row->get('login_redirect_url', ''); ?>" />
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Redirect on Registration' ); ?>
                            </th>
                            <td>
                                <?php  echo JHTML::_('select.booleanlist', 'registration_redirect', 'class="inputbox"', $this->row->get('registration_redirect', '0') ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Registration Redirect URL ' ); ?>
                            </th>
                            <td>
                                <input type="text" name="registration_redirect_url" value="<?php echo $this->row->get('registration_redirect_url', ''); ?>" />
                            </td>
                            <td>
                            </td>
                        </tr>
                        
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel();

                    $legend = JText::_( "Points Settings" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'points' );
                    ?>
                    
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Expiration For Points' ); ?>:
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'expiration_of_points', 'class="inputbox"', $this->row->get('expiration_of_points', '1') ); ?>
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Expiration Period For Points' ); ?>:
                            </th>
                            <td>
                              <?php echo AmbraSelect::listmonths( @$this->row->get('expirationperiod', 'PPSP'), 'expirationperiod' ); echo "&nbsp;". JText::_( 'months' ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Minimum Days Before User Can Earn Points' ); ?>:
                            </th>
                            <td>
                                <input type="text" name="days_before_points_can_be_earned" value="<?php echo @$this->row->get('days_before_points_can_be_earned', '0'); ?>" size="10" maxlength="11" />
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Maximum Daily Points' ); ?>:
                            </th>
                            <td>
                                <input type="text" name="max_daily_points" value="<?php echo @$this->row->get('max_daily_points', '100'); ?>" size="10" maxlength="11" />
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Default Maximum Total Points' ); ?>:
                            </th>
                            <td>
                                <input type="text" name="max_total_points" value="<?php echo @$this->row->get('max_total_points', '-1'); ?>" size="10" maxlength="11" />
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Conversion Rate Points to USD' ); ?>:
                            </th>
                            <td>
                                <input name="point_conversion_rate" value="<?php echo @$this->row->get('point_conversion_rate', '10'); ?>" size="10" maxlength="11" type="text" />
                            </td>
                            <td>
                                <?php echo JText::_( 'How many points equal one USD' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Article Describing Points Program' ); ?>
                            </th>
                            <td style="width: 280px;">
                                <?php echo $this->elementArticle_points; ?>
                                <?php echo $this->resetArticle_points; ?>              
                            </td>
                            <td>
                                <?php echo JText::_( 'Select an Article Describing Your Points Program' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Itemid of Points Program Article' ); ?>:
                            </th>
                            <td>
                                <input type="text" name="article_points_itemid" value="<?php echo @$this->row->get('article_points_itemid', ''); ?>" size="10" maxlength="11" />
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display Coupon Form on Point History Page' ); ?>:
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'display_coupon_form', 'class="inputbox"', $this->row->get('display_coupon_form', '1') ); ?>
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display point notification on login ' ); ?>:
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'login_point_notification', 'class="inputbox"', $this->row->get('login_point_notification', '1') ); ?>
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display point notification on Uploading an Avatar  ' ); ?>:
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'avatar_point_notification', 'class="inputbox"', $this->row->get('avatar_point_notification', '1') ); ?>
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Display point notification on Becoming an Affiliate' ); ?>:
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'affiliate_point_notification', 'class="inputbox"', $this->row->get('affiliate_point_notification', '1') ); ?>
                            </td>
                            <td>
                            
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel();
                    
                    $legend = JText::_( "Other Settings" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'others' );
                    ?>
                    
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Show Dioscouri Link in Footer' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'show_linkback', 'class="inputbox"', $this->row->get('show_linkback', '1') ); ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel();
                    
                    $legend = JText::_( "Administrator ToolTips" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'defaults' );
                    ?>
                    
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Hide Dashboard Note' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_dashboard_disabled', 'class="inputbox"', $this->row->get('page_tooltip_dashboard_disabled', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Hide Configuration Note' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_config_disabled', 'class="inputbox"', $this->row->get('page_tooltip_config_disabled', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Hide Tools Note' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_tools_disabled', 'class="inputbox"', $this->row->get('page_tooltip_tools_disabled', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Hide Categories Note' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_categories_disabled', 'class="inputbox"', $this->row->get('page_tooltip_categories_disabled', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Hide Fields Note' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_fields_disabled', 'class="inputbox"', $this->row->get('page_tooltip_fields_disabled', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Hide Profiles Note' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'page_tooltip_profiles_disabled', 'class="inputbox"', $this->row->get('page_tooltip_profiles_disabled', '0') ); ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel();
                    ?>
                    
                    <?php if ( Ambra::getClass( "AmbraHelperAmigos", 'helpers.amigos' )->isInstalled() ) : 
                    $legend = JText::_( "Amigos Integration" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'amigos' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Enable Users to Sign up as Afilliates Upon Registration' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'amigos_registration', 'class="inputbox"', $this->row->get('amigos_registration', '1') ); ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    endif; 
                    ?>
                    
                    <?php if ( Ambra::getClass( "AmbraHelperPhplist", 'helpers.phplist' )->isInstalled() ) : 
                    $legend = JText::_( "Phplist Integration" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'phplist' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Enable Users to Sign up to Newsletters Upon Registration' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'phplist_registration', 'class="inputbox"', $this->row->get('phplist_registration', '1') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Input a CSV of IDs for the Newsletters to Include in the Registration Form' ); ?>
                            </th>
                            <td>
                                <textarea name="phplist_newsletters_csv"><?php echo $this->row->get('phplist_newsletters_csv'); ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    endif; 
                    ?>
                    

                    <?php if ( Ambra::getClass( "AmbraHelperAllchimp", 'helpers.allchimp' )->isInstalled() ) : 
                    $legend = JText::_( "Allchimp Integration" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'allchimp' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Enable Users to Sign up to Newsletters Upon Registration' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'allchimp_registration', 'class="inputbox"', $this->row->get('allchimp_registration', '1') ); ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    endif; 
                    ?>

                    <?php jimport('joomla.filesystem.file');
                         if (JFile::exists(JPATH_ADMINISTRATOR.'/components/com_hybridauth/defines.php')) : 
                    $legend = JText::_( "HybridAuth Integration" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'hybridauth' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'HybridAuth Integration' ); ?>
                            </th>
                            <td>
                                <?php  echo JHTML::_('select.booleanlist', 'hybridauth_int', 'class="inputbox"', $this->row->get('hybridauth_int', '0') ); ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    endif; 
                    ?>

                    <?php 
                    $legend = JText::_( "Campain Monitor Integration" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'campaignmonitor' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Enable Users to Subscribe to Campain Monitor Newsletters Upon Registration' ); ?>
                            </th>
                            <td>
                                <?php echo JHTML::_('select.booleanlist', 'campaignmonitor_registration', 'class="inputbox"', $this->row->get('campaignmonitor_registration', '0') ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Campain Monitor API Key' ); ?>
                            </th>
                            <td>
                                <input type="text" name="campaignmonitor_api_key" value="<?php echo @$this->row->get('campaignmonitor_api_key', ''); ?>" size="50" maxlength="50" />
                            </td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Campain Monitor Subscriber List ID' ); ?>
                            </th>
                            <td>
                                <input type="text" name="campaignmonitor_listid" value="<?php echo @$this->row->get('campaignmonitor_listid', ''); ?>" size="50" maxlength="50" />
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    ?>

                    <?php 
                    $legend = JText::_( "Gravatar Integration" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'gravatar' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                          <tr>
                            <th style="width: 25%;">
                              <?php echo JText::_( 'Gravatar Integration' ); ?>

                            </th>
                            <td>
                                <?php  echo JHTML::_('select.booleanlist', 'gravatar_support', 'class="inputbox"', $this->row->get('gravatar_support', '0') ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>  
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Gravatar Default Image' ); ?>
                            </th>
                            <td>
                                <?php echo AmbraSelect::gratardefaults( @$this->row->get('gravatar_default', '0'), 'gravatar_default' ); ?>
                            </td>
                            <td>
                            </td>
                        </tr> 
                        <tr>
                            <th style="width: 25%;">
                                <?php echo JText::_( 'Gravatar Size' ); ?>
                            </th>
                            <td>
                               <input type="text" name="gravatar_size" value="<?php echo $this->row->get('gravatar_size', '80'); ?>" />

                            </td>
                            <td>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    ?>
                    <?php 
                    $legend = JText::_( "ADVANCED" );
                    echo $this->sliders->startPanel( JText::_( $legend ), 'advanced' );
                    ?>
                    <table class="adminlist">
                    <tbody>
                          <tr>
                            <th style="width: 25%;">
                              <?php echo JText::_( 'Profiles force Registration template' ); ?>

                            </th>
                            <td>
                                <?php  echo JHTML::_('select.booleanlist', 'registration_profiles_templates', 'class="inputbox"', $this->row->get('registration_profiles_templates', '0') ); ?>
                            </td>
                            <td>
                            </td>
                        </tr>  
                    </tbody>
                    </table>
                    <?php
                    echo $this->sliders->endPanel(); 
                    ?>
                    <?php   
                    // if there are plugins, display them accordingly
                    if ($this->items_sliders) 
                    {                   
                        $tab=1;
                        $pane=2;
                        for ($i=0, $count=count($this->items_sliders); $i < $count; $i++) {
                            if ($pane == 1) {
                                // echo $this->sliders->startPane( "pane_$pane" );
                            }
                            $item = $this->items_sliders[$i];
                            echo $this->sliders->startPanel( JText::_( $item->element ), $item->element );
                            
                            // load the plugin
                                $import = JPluginHelper::importPlugin( strtolower( 'Ambra' ), $item->element );
                            // fire plugin
                                $dispatcher = JDispatcher::getInstance();
                                $dispatcher->trigger( 'onDisplayConfigFormSliders', array( $item, $this->row ) );
                                
                            echo $this->sliders->endPanel();
                            if ($i == $count-1) {
                                // echo $this->sliders->endPane();
                            }
                        }
                    }
                    
                    echo $this->sliders->endPane();
                    
                    ?>
                    </td>
                    <td style="vertical-align: top; max-width: 30%;">
                        
                        <?php echo AmbraGrid::pagetooltip( JRequest::getVar('view') ); ?>
                        
                        <div id='onDisplayRightColumn_wrapper'>
                            <?php
                                $dispatcher = JDispatcher::getInstance();
                                $dispatcher->trigger( 'onDisplayConfigFormRightColumn', array() );
                            ?>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>

        <div id='onAfterDisplay_wrapper'>
            <?php 
                $dispatcher = JDispatcher::getInstance();
                $dispatcher->trigger( 'onAfterDisplayConfigForm', array() );
            ?>
        </div>
        
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
    <input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
    
    <?php echo $this->form['validate']; ?>
</form>