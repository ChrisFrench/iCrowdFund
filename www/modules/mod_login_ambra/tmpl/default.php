<?php defined('_JEXEC') or die('Restricted access');

$html = '';
if (JPluginHelper::isEnabled('authentication', 'openid')) {
        $lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
        $langScript =   'var JLanguage = {};'.
                        ' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
                        ' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
                        ' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
                        ' var modlogin = 1;';
        $document = JFactory::getDocument();
        $document->addScriptDeclaration( $langScript );
        JHTML::_('script', 'openid.js');
}
$html .= '<form action="'.JRoute::_( 'index.php', true, $params->get('usesecure')).'" method="post" name="login" id="form-login" >';
    $html .= $params->get('pretext');
    $html .= '<fieldset class="input">';
    $html .= '<div id="form-login-username">';
        $html .= '<label for="modlgn_username">'.JText::_('Username').'</label>';
        $html .= '<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />';
    $html .= '</div>';
    $html .= '<div id="form-login-password">';
        $html .= '<label for="modlgn_passwd">'.JText::_('Password').'</label>';
        $html .= '<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" />';
    $html .= '</div>';
    
    if(JPluginHelper::isEnabled('system', 'remember')) {
        $html .= '<div id="form-login-remember">';
            $html .= '<label for="modlgn_remember" class="checkbox"> '.JText::_('MOD_LOGIN_REMEMBER_ME').'';
            $html .= '<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" /></label>';
        $html .= '</div>';
    }
    
    $html .= '<input type="submit" name="Submit" class="btn btn-primary" value="'.JText::_('LOGIN').'" />';
    $html .= '</fieldset>';
    $html .= '<ul>';
        $html .= '<li>';
            $html .= '<a href="'.DSCRoute::_( 'index.php?option=com_ambra&view=reset' ).'">';
            $html .= JText::_('FORGOT_YOUR_PASSWORD').'</a>';
        $html .= '</li>';
        $html .= '<li>';
            $html .= '<a href="'.DSCRoute::_( 'index.php?option=com_ambra&view=remind' ).'">';
            $html .= JText::_('FORGOT_YOUR_USERNAME').'</a>';
        $html .= '</li>';
        
        $usersConfig = JComponentHelper::getParams( 'com_users' );
        if ($usersConfig->get('allowUserRegistration')) {
        $html .= '<li>';
            $html .= '<a href="'.DSCRoute::_( 'index.php?option=com_ambra&view=registration' ).'">';
            $html .= JText::_('REGISTER').'</a>';
        $html .= '</li>';
        }
    $html .= '</ul>';
    $html .= $params->get('posttext');

    $html .= '<input type="hidden" name="option" value="com_ambra" />';
    $html .= '<input type="hidden" name="view" value="login" />';
    $html .= '<input type="hidden" name="task" value="login" />';
    $html .= '<input type="hidden" name="return" value="'.$return.'" />';
    $html .= JHTML::_( 'form.token' );
$html .= '</form>';

echo $html;
?>