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

$plugins = 'advlist,save,autolink,lists,fullscreen,advimage,paste,advlink,media,contextmenu,table,advhr,inlinepopups';
$theme_advanced_buttons1  = 'fontselect,fontsizeselect,formatselect,bold,italic,underline,strikethrough,|,sup,sub,|,copy,cut,pastetext,pasteword';
$theme_advanced_buttons2  = 'justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist,outdent,indent,|,forecolor,backcolor,|,advhr,link,image,media,table,code,|,fullscreen,|,help';
$site_url = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
$base_dir = DIR_WS_CATALOG;
//<!-- load the main TinyMCE files -->
$str  = '';
$str .= '<script type="text/javascript" src="' . DIR_WS_CATALOG . 'editors/zenc_tinymce/tiny_mce/tiny_mce.js"></script>' . "\n";
$str .= '<script language="javascript" type="text/javascript">' . "\n";
$str .= 'tinyMCE.init({' . "\n";
$str .= "theme : 'advanced',\n";
$str .= "mode : 'textareas',\n";
$str .= "language : '{$language}',\n";
$str .= "plugins : '{$plugins}',\n";
$str .= "width  : '600',\n";
$str .= "height : '350',\n";
$str .= "accessibility_warnings            : false,\n";
$str .= "document_base_url                 : '{$site_url}',\n";
$str .= "relative_urls                     : false,\n";
$str .= "remove_script_host                : false,\n";
$str .= "force_br_newlines                 : true,\n";
$str .= "force_p_newlines                  : false,\n";
$str .= "forced_root_block                 : '',\n";
$str .= "convert_fonts_to_spans            : true,\n";
$str .= "valid_elements : '*[*]',\n";
$str .= "theme_advanced_buttons1 : '{$theme_advanced_buttons1}',\n";
$str .= "theme_advanced_buttons2 : '{$theme_advanced_buttons2}',\n";
$str .= "theme_advanced_buttons3 : '',\n";
$str .= "theme_advanced_toolbar_location : 'top',\n";
$str .= "theme_advanced_toolbar_align : 'left',\n";
$str .= "theme_advanced_statusbar_location : 'bottom',\n";
$str .= "theme_advanced_resizing : true,\n";
$str .= "theme_advanced_resize_horizontal : false,\n";
$str .= "content_css : '{$base_dir}editors/zenc_tinymce/add_style.css'\n";
$str .= "});\n";
$str .= "</script>\n";
echo $str;
