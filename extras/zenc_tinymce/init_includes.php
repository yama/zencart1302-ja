<?php
/**
 * @package admin
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_includes.php 3061 2009-07-01 21:01 yama $
 */
if (!defined('IS_ADMIN_FLAG'))
{
	die('Illegal Access');
}
$storeValue = $_SESSION['html_editor_preference_status'];
$_SESSION['html_editor_preference_status'] = 'TINYMCE';
if ($_SESSION['html_editor_preference_status']=='TINYMCE')
{
	$languages = zen_get_languages(); // todo
	$plugins = 'safari,style,fullscreen,advimage,paste,advlink,media,contextmenu,table,advhr,inlinepopups';
	$theme_advanced_buttons1  = 'undo,redo,|,bold,forecolor,backcolor,formatselect,styleselect,fontsizeselect,code,|,fullscreen,help';
	$theme_advanced_buttons2  = 'image,media,link,unlink,anchor,|,bullist,numlist,|,blockquote,outdent,indent,|,';
	$theme_advanced_buttons2 .= 'justifyleft,justifycenter,justifyright,|,table,|,advhr,|,xstyleprops,removeformat,|,pastetext,pasteword';
	//<!-- load the main TinyMCE files -->
	$str  = '';
	$str .= '<script type="text/javascript" src="' . DIR_WS_CATALOG . 'extras/zenc_tinymce/jscripts/tiny_mce/tiny_mce.js"></script>' . PHP_EOL;
	$str .= '<script language="javascript" type="text/javascript">' . PHP_EOL;
	$str .= 'tinyMCE.init({' . PHP_EOL;
	$str .= '	theme : "advanced",' . PHP_EOL;
	$str .= '	mode : "textareas",' . PHP_EOL;
	$str .= '	plugins : "' . $plugins . '",'                                 . PHP_EOL;
	$str .= '	theme_advanced_buttons1 : "' . $theme_advanced_buttons1 . '",' . PHP_EOL;
	$str .= '	theme_advanced_buttons2 : "' . $theme_advanced_buttons2 . '",' . PHP_EOL;
	$str .= '	theme_advanced_buttons3 : "",'                                 . PHP_EOL;
	$str .= '	theme_advanced_toolbar_location : "top",'      . PHP_EOL;
	$str .= '	theme_advanced_toolbar_align : "left",'        . PHP_EOL;
	$str .= '	theme_advanced_statusbar_location : "bottom",' . PHP_EOL;
	$str .= '	theme_advanced_resizing : true,'               . PHP_EOL;
	$str .= '	theme_advanced_resize_horizontal : false,'     . PHP_EOL;
	$str .= '	content_css : "' . DIR_WS_CATALOG . 'extras/zenc_tinymce/add_style.css"' . PHP_EOL;
	$str .= '});' . PHP_EOL;
	$str .= '</script>' . PHP_EOL;
	echo $str;
}
$_SESSION['html_editor_preference_status'] = $storeValue;
?>