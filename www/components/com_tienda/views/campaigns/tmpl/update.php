<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('script', 'jquery.limit.js', 'media/com_tienda/js/');
JHTML::_('script', 'add_campaign.js', 'media/com_tienda/js/');

$form = @$this -> form;
$row = @$this -> row;

?>

<form action="/crowdfunding/create-article?a_id=0" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
		<fieldset>
			<legend>Editor</legend>
			<div class="formelm control-group"> <label id="jform_title-lbl" for="jform_title" class="hasTip required control-label invalid" title="" aria-invalid="true">Title<span class="star">&nbsp;*</span></label>				<div class="controls"> <input type="text" name="jform[title]" id="jform_title" value="" class="inputbox required invalid" size="30" aria-required="true" required="required" aria-invalid="true"> </div>
			</div>
						<div class="formelm control-group"> <label id="jform_alias-lbl" for="jform_alias" class="hasTip control-label" title="" aria-invalid="false">Alias</label>				<div class="controls"><input type="text" name="jform[alias]" id="jform_alias" value="" class="inputbox" size="45" aria-invalid="false"> </div>
			</div>
						<div class="formelm-buttons form-actions">
				<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('article.save')"> Save </button>
				<button class="btn" type="button" onclick="Joomla.submitbutton('article.cancel')"> Cancel </button>
			</div>
			<div class="control-group"> 
<fieldset>
<legend>Tags</legend>

<div style="background-color: #FFFFFF;">
		<table class="table table-striped table-bordered">
		<tbody>
		<tr>
		    <td class="dsc-key hasTip" style="width: 100px; padding-right: 5px;" title="">
		        Add a new tag		    </td>
		    <td>
                <input type="text" name="tag_name" id="tag_name" value="" onkeypress="Tags.handleKeyPress(event,this.form)">
                <input class="btn btn-large" id="add_tags_button" type="button" value="Add" onclick="Dsc.doTask('index.php?option=com_tags&amp;view=tags&amp;task=addTag&amp;format=raw&amp;scope=com_content.article&amp;identifier=0', 'added_tags', this.form, 'Adding'); document.getElementById( 'tag_name' ).value='';">
		    </td>
		</tr>
		<tr>
		    <td class="dsc-key hasTip" style="width: 100px; padding-right: 5px;" title="">
		        Current tags		    </td>
		    <td>	        
		        <div id="added_tags" class="added_tags">
		        			        		<div class="no_tags">
							None found						</div>
		        			        </div>
		    </td>
		</tr>
		</tbody>
		</table>
</div>
</fieldset><textarea name="jform[articletext]" id="jform_articletext" cols="0" rows="0" style="width: 100%; height: 250px; display: none;" class="mce_editable" aria-hidden="true"></textarea><span role="application" aria-labelledby="jform_articletext_voice" id="jform_articletext_parent" class="mceEditor defaultSkin" style=""><span class="mceVoiceLabel" style="display:none;" id="jform_articletext_voice">Rich Text Area</span><table role="presentation" id="jform_articletext_tbl" class="mceLayout" cellspacing="0" cellpadding="0" style="width: 100%; height: 250px;"><tbody><tr role="presentation" class="mceFirst"><td class="mceToolbar mceLeft mceFirst mceLast" role="presentation"><div id="jform_articletext_toolbargroup" role="group" aria-labelledby="jform_articletext_toolbargroup_voice" tabindex="-1"><span role="application"><span id="jform_articletext_toolbargroup_voice" class="mceVoiceLabel" style="display:none;">Toolbar</span><table id="jform_articletext_toolbar1" class="mceToolbar mceToolbarRow1 Enabled" cellpadding="0" cellspacing="0" align="" role="presentation" tabindex="-1" aria-disabled="false" aria-pressed="false"><tbody><tr><td class="mceToolbarStart mceToolbarStartButton mceFirst"><span><!-- IE --></span></td><td style="position: relative"><a role="button" id="jform_articletext_bold" href="javascript:;" class="mceButton mceButtonEnabled mce_bold" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_bold_voice" title="Bold (Ctrl+B)" tabindex="-1"><span class="mceIcon mce_bold"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_bold_voice">Bold (Ctrl+B)</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_italic" href="javascript:;" class="mceButton mceButtonEnabled mce_italic" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_italic_voice" title="Italic (Ctrl+I)" tabindex="-1"><span class="mceIcon mce_italic"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_italic_voice">Italic (Ctrl+I)</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_underline" href="javascript:;" class="mceButton mceButtonEnabled mce_underline" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_underline_voice" title="Underline (Ctrl+U)" tabindex="-1"><span class="mceIcon mce_underline"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_underline_voice">Underline (Ctrl+U)</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_strikethrough" href="javascript:;" class="mceButton mceButtonEnabled mce_strikethrough" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_strikethrough_voice" title="Strikethrough" tabindex="-1"><span class="mceIcon mce_strikethrough"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_strikethrough_voice">Strikethrough</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><a role="button" id="jform_articletext_justifyleft" href="javascript:;" class="mceButton mceButtonEnabled mce_justifyleft" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_justifyleft_voice" title="Align Left" tabindex="-1"><span class="mceIcon mce_justifyleft"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_justifyleft_voice">Align Left</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_justifycenter" href="javascript:;" class="mceButton mceButtonEnabled mce_justifycenter" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_justifycenter_voice" title="Align Center" tabindex="-1"><span class="mceIcon mce_justifycenter"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_justifycenter_voice">Align Center</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_justifyright" href="javascript:;" class="mceButton mceButtonEnabled mce_justifyright" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_justifyright_voice" title="Align Right" tabindex="-1"><span class="mceIcon mce_justifyright"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_justifyright_voice">Align Right</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_justifyfull" href="javascript:;" class="mceButton mceButtonEnabled mce_justifyfull" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_justifyfull_voice" title="Align Full" tabindex="-1"><span class="mceIcon mce_justifyfull"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_justifyfull_voice">Align Full</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><span role="listbox" aria-haspopup="true" aria-labelledby="jform_articletext_styleselect_voiceDesc" aria-describedby="jform_articletext_styleselect_voiceDesc"><table role="presentation" tabindex="-1" id="jform_articletext_styleselect" cellpadding="0" cellspacing="0" class="mceListBox mceListBoxEnabled mce_styleselect" aria-valuenow="Styles"><tbody><tr><td class="mceFirst"><span id="jform_articletext_styleselect_voiceDesc" class="voiceLabel" style="display:none;">Styles</span><a id="jform_articletext_styleselect_text" tabindex="-1" href="javascript:;" class="mceText mceTitle" onclick="return false;" onmousedown="return false;">Styles</a></td><td class="mceLast"><a id="jform_articletext_styleselect_open" tabindex="-1" href="javascript:;" class="mceOpen" onclick="return false;" onmousedown="return false;"><span><span style="display:none;" class="mceIconOnly" aria-hidden="true">▼</span></span></a></td></tr></tbody></table></span></td><td style="position: relative"><span role="listbox" aria-haspopup="true" aria-labelledby="jform_articletext_formatselect_voiceDesc" aria-describedby="jform_articletext_formatselect_voiceDesc"><table role="presentation" tabindex="-1" id="jform_articletext_formatselect" cellpadding="0" cellspacing="0" class="mceListBox mceListBoxEnabled mce_formatselect" aria-valuenow="Paragraph"><tbody><tr><td class="mceFirst"><span id="jform_articletext_formatselect_voiceDesc" class="voiceLabel" style="display:none;">Format - Paragraph</span><a id="jform_articletext_formatselect_text" tabindex="-1" href="javascript:;" class="mceText" onclick="return false;" onmousedown="return false;">Paragraph</a></td><td class="mceLast"><a id="jform_articletext_formatselect_open" tabindex="-1" href="javascript:;" class="mceOpen" onclick="return false;" onmousedown="return false;"><span><span style="display:none;" class="mceIconOnly" aria-hidden="true">▼</span></span></a></td></tr></tbody></table></span></td><td class="mceToolbarEnd mceToolbarEndListBox mceLast"><span><!-- IE --></span></td></tr></tbody></table><table id="jform_articletext_toolbar2" class="mceToolbar mceToolbarRow2 Enabled" cellpadding="0" cellspacing="0" align="" role="presentation" tabindex="-1" aria-disabled="false" aria-pressed="false"><tbody><tr><td class="mceToolbarStart mceToolbarStartButton mceFirst"><span><!-- IE --></span></td><td style="position: relative"><a role="button" id="jform_articletext_bullist" href="javascript:;" class="mceButton mceButtonEnabled mce_bullist" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_bullist_voice" title="Insert/Remove Bulleted List" tabindex="-1" aria-pressed="false"><span class="mceIcon mce_bullist"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_bullist_voice">Insert/Remove Bulleted List</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_numlist" href="javascript:;" class="mceButton mceButtonEnabled mce_numlist" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_numlist_voice" title="Insert/Remove Numbered List" tabindex="-1" aria-pressed="false"><span class="mceIcon mce_numlist"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_numlist_voice">Insert/Remove Numbered List</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><a role="button" id="jform_articletext_outdent" href="javascript:;" class="mceButton mce_outdent mceButtonDisabled" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_outdent_voice" title="Decrease Indent" tabindex="-1" aria-disabled="true"><span class="mceIcon mce_outdent"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_outdent_voice">Decrease Indent</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_indent" href="javascript:;" class="mceButton mceButtonEnabled mce_indent" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_indent_voice" title="Increase Indent" tabindex="-1"><span class="mceIcon mce_indent"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_indent_voice">Increase Indent</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><a role="button" id="jform_articletext_undo" href="javascript:;" class="mceButton mce_undo mceButtonDisabled" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_undo_voice" title="Undo (Ctrl+Z)" tabindex="-1" aria-disabled="true"><span class="mceIcon mce_undo"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_undo_voice">Undo (Ctrl+Z)</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_redo" href="javascript:;" class="mceButton mce_redo mceButtonDisabled" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_redo_voice" title="Redo (Ctrl+Y)" tabindex="-1" aria-disabled="true"><span class="mceIcon mce_redo"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_redo_voice">Redo (Ctrl+Y)</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><a role="button" id="jform_articletext_link" href="javascript:;" class="mceButton mce_link mceButtonDisabled" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_link_voice" title="Insert/Edit Link" tabindex="-1" aria-disabled="true"><span class="mceIcon mce_link"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_link_voice">Insert/Edit Link</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_unlink" href="javascript:;" class="mceButton mce_unlink mceButtonDisabled" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_unlink_voice" title="Unlink" tabindex="-1" aria-disabled="true"><span class="mceIcon mce_unlink"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_unlink_voice">Unlink</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_anchor" href="javascript:;" class="mceButton mceButtonEnabled mce_anchor" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_anchor_voice" title="Insert/Edit Anchor" tabindex="-1"><span class="mceIcon mce_anchor"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_anchor_voice">Insert/Edit Anchor</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_image" href="javascript:;" class="mceButton mceButtonEnabled mce_image" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_image_voice" title="Insert/Edit Image" tabindex="-1"><span class="mceIcon mce_image"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_image_voice">Insert/Edit Image</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_cleanup" href="javascript:;" class="mceButton mceButtonEnabled mce_cleanup" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_cleanup_voice" title="Cleanup Messy Code" tabindex="-1"><span class="mceIcon mce_cleanup"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_cleanup_voice">Cleanup Messy Code</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_help" href="javascript:;" class="mceButton mceButtonEnabled mce_help" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_help_voice" title="Help" tabindex="-1"><span class="mceIcon mce_help"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_help_voice">Help</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_code" href="javascript:;" class="mceButton mceButtonEnabled mce_code" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_code_voice" title="Edit HTML Source" tabindex="-1"><span class="mceIcon mce_code"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_code_voice">Edit HTML Source</span></a></td><td class="mceToolbarEnd mceToolbarEndButton mceLast"><span><!-- IE --></span></td></tr></tbody></table><table id="jform_articletext_toolbar3" class="mceToolbar mceToolbarRow3 Enabled" cellpadding="0" cellspacing="0" align="" role="presentation" tabindex="-1" aria-disabled="false" aria-pressed="false"><tbody><tr><td class="mceToolbarStart mceToolbarStartButton mceFirst"><span><!-- IE --></span></td><td style="position: relative"><a role="button" id="jform_articletext_hr" href="javascript:;" class="mceButton mceButtonEnabled mce_hr" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_hr_voice" title="Insert Horizontal Line" tabindex="-1"><span class="mceIcon mce_hr"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_hr_voice">Insert Horizontal Line</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_removeformat" href="javascript:;" class="mceButton mceButtonEnabled mce_removeformat" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_removeformat_voice" title="Remove Formatting" tabindex="-1"><span class="mceIcon mce_removeformat"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_removeformat_voice">Remove Formatting</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_visualaid" href="javascript:;" class="mceButton mceButtonEnabled mce_visualaid" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_visualaid_voice" title="show/Hide Guidelines/Invisible Elements" tabindex="-1" aria-pressed="false"><span class="mceIcon mce_visualaid"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_visualaid_voice">show/Hide Guidelines/Invisible Elements</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><a role="button" id="jform_articletext_sub" href="javascript:;" class="mceButton mceButtonEnabled mce_sub" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_sub_voice" title="Subscript" tabindex="-1"><span class="mceIcon mce_sub"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_sub_voice">Subscript</span></a></td><td style="position: relative"><a role="button" id="jform_articletext_sup" href="javascript:;" class="mceButton mceButtonEnabled mce_sup" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_sup_voice" title="Superscript" tabindex="-1"><span class="mceIcon mce_sup"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_sup_voice">Superscript</span></a></td><td style="position: relative"><span class="mceSeparator" role="separator" aria-orientation="vertical" tabindex="-1"></span></td><td style="position: relative"><a role="button" id="jform_articletext_charmap" href="javascript:;" class="mceButton mceButtonEnabled mce_charmap" onmousedown="return false;" onclick="return false;" aria-labelledby="jform_articletext_charmap_voice" title="Insert Special Character" tabindex="-1"><span class="mceIcon mce_charmap"></span><span class="mceVoiceLabel mceIconOnly" style="display: none;" id="jform_articletext_charmap_voice">Insert Special Character</span></a></td><td class="mceToolbarEnd mceToolbarEndButton mceLast"><span><!-- IE --></span></td></tr></tbody></table></span></div><a href="#" accesskey="z" title="Jump to tool buttons - Alt+Q, Jump to editor - Alt-Z, Jump to element path - Alt-X" onfocus="tinyMCE.getInstanceById('jform_articletext').focus();"><!-- IE --></a></td></tr><tr><td class="mceIframeContainer mceFirst mceLast"><iframe id="jform_articletext_ifr" src='javascript:""' frameborder="0" allowtransparency="true" title="Rich Text AreaPress ALT-F10 for toolbar. Press ALT-0 for help" style="width: 100%; height: 160px; display: block;"></iframe></td></tr><tr class="mceLast"><td class="mceStatusbar mceFirst mceLast"><div id="jform_articletext_path_row" role="group" aria-labelledby="jform_articletext_path_voice" tabindex="-1"><span id="jform_articletext_path_voice">Path</span><span>: </span><span id="jform_articletext_path"><a href="javascript:;" role="button" onmousedown="return false;" class="mcePath_0" id="_mce_item_3" tabindex="-1">p</a></span></div><a id="jform_articletext_resize" href="javascript:;" onclick="return false;" class="mceResize" tabindex="-1"></a></td></tr></tbody></table></span>

<div id="editor-xtd-buttons">
<div class="button2-left"><div class="article"><a class="modal-button" title="Article" href="http://crowdfunding.local/crowdfunding/index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;2ed2a6e0ac4d07a5e5da6768ae83f9a6=1" onclick="IeCursorFix(); return false;" rel="{handler: 'iframe', size: {x: 770, y: 400}}">Article</a></div></div>
<div class="button2-left"><div class="image"><a class="modal-button" title="Image" href="http://crowdfunding.local/crowdfunding/index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name=jform_articletext&amp;asset=com_content&amp;author=" onclick="IeCursorFix(); return false;" rel="{handler: 'iframe', size: {x: 800, y: 500}}">Image</a></div></div>
<div class="button2-left"><div class="pagebreak"><a class="modal-button" title="Page Break" href="http://crowdfunding.local/crowdfunding/index.php?option=com_content&amp;view=article&amp;layout=pagebreak&amp;tmpl=component&amp;e_name=jform_articletext" onclick="IeCursorFix(); return false;" rel="{handler: 'iframe', size: {x: 400, y: 100}}">Page Break</a></div></div>
<div class="button2-left"><div class="readmore"><a title="Read More" href="http://crowdfunding.local/crowdfunding/#" onclick="insertReadmore('jform_articletext');return false;" rel="">Read More</a></div></div>
</div>

<div class="toggle-editor">
<div class="button2-left"><div class="blank"><a href="#" onclick="tinyMCE.execCommand('mceToggleEditor', false, 'jform_articletext');return false;" title="Toggle editor">Toggle editor</a></div></div></div>
 </div>
		</fieldset>
				<fieldset>
			<legend>Publishing</legend>
			<div class="formelm control-group"> <label id="jform_catid-lbl" for="jform_catid" class="hasTip required control-label" title="" aria-invalid="false">Category<span class="star">&nbsp;*</span></label>				<div class="controls">
										<select id="jform_catid" name="jform[catid]" class="inputbox required" aria-required="true" required="required" aria-invalid="false">
	<option value="15">- Campaigns</option>
	<option value="24">- Browse</option>
	<option value="29">- Learn</option>
	<option value="53">- - Doc Sharing</option>
	<option value="52">- - Heart Monitor</option>
	<option value="51">- - High Tech Chips</option>
	<option value="50">- - Funding the Crowd Funding Site sadfasdfasdfasdfasdfasdf</option>
	<option value="49">- - Testing a campaign creation</option>
	<option value="48">- - dasdfasdf</option>
	<option value="47">- - 123412354265 sdfgsdfg</option>
	<option value="46">- - 12341234  sdfgsdfgsdfg</option>
	<option value="45">- - Funding the Crowd Funding Site asdfasdfasdfasdfasdfasdfasdf</option>
	<option value="44">- - Creating a Frontend Campaign asdfasdfasdfasd</option>
	<option value="43">- - TESTING RESIZING</option>
	<option value="42">- - Funding the Crowd Funding SiteASDFASDFASDF</option>
	<option value="41">- - The Image Resizing</option>
	<option value="40">- - sdfsdfgsdfgsdfg sdfgsdfgsdfg</option>
	<option value="39">- - fdghdfghdfgh</option>
	<option value="38">- - sdafasdgsgdfsgsdf sdfgsdfgs</option>
	<option value="37">- - Funding the Crowd Funding Site</option>
	<option value="36">- - Testing Frontend Create Campaignsasdfasdf</option>
	<option value="35">- - Testing Frontend Create Campaignssdfgsdfgsdfg</option>
	<option value="34">- - testing campaigns as resized</option>
	<option value="33">- - Creating a Frontend Campaign aasdfasdfasdfasdfa</option>
	<option value="32">- - Another Campaignasdfsadfasdfasdf</option>
	<option value="31">- - Testing Frontend Create Campaigns</option>
	<option value="30">- - testing campaigns</option>
	<option value="28">- - Here is a New Campaign</option>
	<option value="27">- - Creating a Frontend Campaign again</option>
	<option value="26">- - Creating a Frontend Campaign</option>
	<option value="25">- - Front End Campign</option>
	<option value="23">- - Another Campaign</option>
	<option value="22">- - HI Tech Chips</option>
	<option value="21">- - Fake  Campaign</option>
</select>
									</div>
			</div>
			<div class="formelm control-group"> <label id="jform_created_by_alias-lbl" for="jform_created_by_alias" class="hasTip control-label" title="">Author's Alias</label>				<div class="controls"><input type="text" name="jform[created_by_alias]" id="jform_created_by_alias" value="" class="inputbox" size="20"> </div>
			</div>
						<div class="formelm control-group"> <label id="jform_state-lbl" for="jform_state" class="hasTip control-label" title="">Status</label>				<div class="controls"><select id="jform_state" name="jform[state]" class="inputbox" size="1">
	<option value="1" selected="selected">Published</option>
	<option value="0">Unpublished</option>
	<option value="2">Archived</option>
	<option value="-2">Trashed</option>
</select>
 </div>
			</div>
			<div class="formelm control-group"> <label id="jform_featured-lbl" for="jform_featured" class="hasTip control-label" title="">Featured</label>				<div class="controls"><select id="jform_featured" name="jform[featured]" class="inputbox">
	<option value="0" selected="selected">No</option>
	<option value="1">Yes</option>
</select>
 </div>
			</div>
			<div class="formelm control-group"> <label id="jform_publish_up-lbl" for="jform_publish_up" class="hasTip control-label" title="">Start Publishing</label>				<div class="controls"><input type="text" title="" name="jform[publish_up]" id="jform_publish_up" value="" size="22" class="inputbox"><img src="/crowdfunding/media/system/images/calendar.png" alt="Calendar" class="calendar add-on" id="jform_publish_up_img"> </div>
			</div>
			<div class="formelm control-group"> <label id="jform_publish_down-lbl" for="jform_publish_down" class="hasTip control-label" title="">Finish Publishing</label>				<div class="controls"><input type="text" title="" name="jform[publish_down]" id="jform_publish_down" value="" size="22" class="inputbox"><img src="/crowdfunding/media/system/images/calendar.png" alt="Calendar" class="calendar add-on" id="jform_publish_down_img"> </div>
			</div>
						<div class="formelm control-group"> <label id="jform_access-lbl" for="jform_access" class="hasTip control-label" title="">Access</label>				<div class="controls"><select id="jform_access" name="jform[access]" class="inputbox" size="1">
	<option value="5">Guest</option>
	<option value="1" selected="selected">Public</option>
	<option value="2">Registered</option>
	<option value="3">Special</option>
</select>
 </div>
			</div>
						<div class="control-group">
				<div class="form-note controls">
					<p class="help-block">Ordering:<br>New articles default to the first position in the Category. The ordering can be changed in backend.</p>
				</div>
			</div>
					</fieldset>
		<fieldset>
			<legend>Language</legend>
			<div class="formelm-area control-group"> <label id="jform_language-lbl" for="jform_language" class="hasTip control-label" title="">Language</label>				<div class="controls"><select id="jform_language" name="jform[language]" class="inputbox">
	<option value="*">All</option>
	<option value="en-GB">English (UK)</option>
</select>
 </div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Metadata</legend>
			<div class="formelm-area control-group"> <label id="jform_metadesc-lbl" for="jform_metadesc" class="hasTip control-label" title="">Meta Description</label>				<div class="controls"> <textarea name="jform[metadesc]" id="jform_metadesc" cols="50" rows="5" class="inputbox"></textarea> </div>
			</div>
			<div class="formelm-area control-group"> <label id="jform_metakey-lbl" for="jform_metakey" class="hasTip control-label" title="">Keywords</label>				<div class="controls"><textarea name="jform[metakey]" id="jform_metakey" cols="50" rows="5" class="inputbox"></textarea> </div>
			</div>
			<input type="hidden" name="task" value="">
			<input type="hidden" name="return" value="">
						<input type="hidden" name="2ed2a6e0ac4d07a5e5da6768ae83f9a6" value="1">			<div class="formelm-buttons form-actions">
				<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('article.save')"> Save </button>
				<button class="btn" type="button" onclick="Joomla.submitbutton('article.cancel')"> Cancel </button>
			</div>
		</fieldset>
	</form>




<form action="<?php echo  @$form['action'] ;?>" id="addCampiagn" name="addCampiagn"  method="post" enctype="multipart/form-data" >
  <fieldset><legend>Add a Project</legend>
 
  	<div class="row">
  		<div class="span5"><label for="campaign_name" >Name of your Project</label>
    <input type="text" name="campaign_name" id="campaign_name" size="48" maxlength="250" value="<?php echo @$row -> campaign_name; ?>" />
     <label for="category_id" >Category</label>
     <?php  echo TiendaHelperCampaign::getSectorsSelectList(@$row -> category_id, 'category_id'); ?>
      <label for="campaign_goal" >Funding Goal</label>
        <div class="input-prepend input-append">
				<span class="add-on">$</span>
   				  <input name="campaign_goal" id="campaign_goal" value="<?php echo @$row -> campaign_goal; ?>" type="text" size="28" maxlength="250" />
   	   			<span class="add-on">.00</span>
		</div>
	<label>Start Funding Date:</label>
	<div class="input-append">
	<?php echo JHTML::calendar(@$row -> fundingstart_date, "fundingstart_date", "fundingstart_date", '%Y-%m-%d %H:%M:%S', array('size' => 25)); ?>
    </div>
	<label>Stop Funding Date:</label>
	<div class="input-append">
    <?php echo JHTML::calendar(@$row -> fundingend_date, "fundingend_date", "fundingend_date", '%Y-%m-%d %H:%M:%S', array('size' => 25)); ?>
    </div>
    <?php if(@$row -> campaign_full_image) : ?> 
    <label for="campaign_full_image"><?php echo JText::_('Campaign Image'); ?>:</label> 
	<ul class="thumbnails"> <li><?php echo TiendaHelperCampaign::getImage($row, 'full'); ?></li>
    </ul>	
    <input type="text" disabled="disabled" name="campaign_full_image" id="campaign_full_image" size="48" maxlength="250" value="<?php echo @$row -> campaign_full_image; ?>" />
    <?php endif ; ?>
	<label for="campaign_full_image_new">Upload New Image:</label>
        <a href="#" id="imageTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Images will be resized to 560 pixels wide, 420 Pixels tall.  Supported Types are .jpg, .png, .gif" data-original-title="Project Image"><i class="icon-white icon-info-sign"></i></a>
	<input name="campaign_full_image_new" type="file" size="40" />
	<label for="Video" >Video Link</label>
     <a href="#" id="videoTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Youtube or Vimeo, http://www.youtube.com/watch?v=000000000, http://vimeo.com/000000000" data-original-title="Video"><i class="icon-white icon-video"></i></a><input type="text" name="video" id="video" size="48" maxlength="1050" value="<?php echo @$row -> video; ?>" />
    </div>
  	<div class="span5"><label for="campaign_description"><?php echo JText::_('Short Description'); ?>:</label>
    <textarea rows="5" style="width:500px;" id="campaign_shortdescription" name="campaign_shortdescription"><?php echo @$row->campaign_shortdescription ?></textarea>
    <div id="counter"></div>
    <label for="campaign_description"><?php echo JText::_('COM_TIENDA_DESCRIPTION'); ?>:</label>
    <textarea rows="10" style="width:500px;" id="campaign_description" name="campaign_description"><?php echo @$row->campaign_description ?></textarea>
    <div id="longcounter"></div>
    <input type="hidden" name="validate" value="<?php echo @$form['validate'] ;?>" />			
    <input type="hidden" name="id" value="<?php echo @$row->campaign_id?>" />
    <input id="user_id_id" type="hidden" value="<?php echo @$row->user_id?>" name="user_id">
      <input type="hidden" name="task" value="save" />
    <input type="hidden" name="step2" value="addlevels" />
    </div>
  	</div>
    
    
    					
    <button type="submit" class="btn clearfix">Submit</button>
  </fieldset>
</form>

<script>
	jQuery('#imageTIP').popover();
	jQuery('#videoTIP').popover();
</script>
