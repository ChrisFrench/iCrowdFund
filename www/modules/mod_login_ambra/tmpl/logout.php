<?php defined('_JEXEC') or die('Restricted access');
$html = '';
$html .= '<form action="index.php" method="post" name="login" id="form-login">';
if ($params->get('greeting')) {
    $html .= '<div>';
    if ($params->get('name')) {
        $html .= JText::sprintf( 'HINAME', $user->get('name') );
    } else {
        $html .= JText::sprintf( 'HINAME', $user->get('username') );
    }
    $html .= '</div>';
}
    $html .= '<div align="center">';
        $html .= '<input type="submit" name="Submit" class="button" value="'.JText::_( 'BUTTON_LOGOUT').'" />';
    $html .= '</div>';

    $html .= '<input type="hidden" name="option" value="com_ambra" />';
    $html .= '<input type="hidden" name="view" value="logout" />';
    $html .= '<input type="hidden" name="task" value="logout" />';
    $html .= '<input type="hidden" name="return" value="'.$return.'" />';
    $html .= JHTML::_( 'form.token' );
$html .= '</form>';

echo $html;

?>