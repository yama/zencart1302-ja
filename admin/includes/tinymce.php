<?php
/**
 * @package admin
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_includes.php 3061 2009-07-01 21:01 yama $
 */
if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

$languages = zen_get_languages(); // todo
foreach($languages as $a)
{
	$lang_code[] = $a['code'];
}
if(array_search('ja',$lang_code)!==false) $language = 'ja';
else                                      $language = 'en';

$plugins = 'advlist,save,autolink,lists,style,fullscreen,advimage,paste,advlink,media,contextmenu,table,advhr,inlinepopups';
$theme_advanced_buttons1  = 'fontselect,fontsizeselect,formatselect,bold,italic,underline,strikethrough,|,sup,sub,|,copy,cut,pastetext,pasteword';
$theme_advanced_buttons2  = 'justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist,outdent,indent,|,forecolor,backcolor,|,advhr,link,image,media,table,code,|,fullscreen,|,help';
$base_dir = DIR_WS_CATALOG;
//<!-- load the main TinyMCE files -->
$str  = '';
$str .= '<script type="text/javascript" src="' . DIR_WS_CATALOG . 'extras/zenc_tinymce/jscripts/tiny_mce/tiny_mce.js"></script>' . "\n";
$str .= '<script language="javascript" type="text/javascript">' . "\n";
$str .= 'tinyMCE.init({' . "\n";
$str .= "theme : 'advanced',\n";
$str .= "mode : 'textareas',\n";
$str .= "language : '{$language}',\n";
$str .= "plugins : '{$plugins}',\n";
$str .= "theme_advanced_buttons1 : '{$theme_advanced_buttons1}',\n";
$str .= "theme_advanced_buttons2 : '{$theme_advanced_buttons2}',\n";
$str .= "theme_advanced_buttons3 : '',\n";
$str .= "theme_advanced_toolbar_location : 'top',\n";
$str .= "theme_advanced_toolbar_align : 'left',\n";
$str .= "theme_advanced_statusbar_location : 'bottom',\n";
$str .= "theme_advanced_resizing : true,\n";
$str .= "theme_advanced_resize_horizontal : false,\n";
$str .= "content_css : '{$base_dir}extras/zenc_tinymce/add_style.css'\n";
$str .= "});\n";
$str .= "</script>\n";
echo $str;
