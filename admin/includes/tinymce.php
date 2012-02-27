<?php
/**
 * @package admin
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_includes.php 3061 2009-07-01 21:01 yama $
 */
if (!defined('IS_ADMIN_FLAG')) die('Illegal Access');

switch($_SESSION['language'])
{
	case 'japanese' : $language = 'ja'; break;
	default         : $language = 'en';
}

$plugins = 'imageupload,advlist,save,autolink,lists,fullscreen,advimage,paste,advlink,media,contextmenu,table,advhr,inlinepopups';
$theme_advanced_buttons1  = 'fontselect,fontsizeselect,formatselect,bold,italic,underline,strikethrough,|,sup,sub,|,copy,cut,pastetext,pasteword';
$theme_advanced_buttons2  = 'justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist,outdent,indent,|,forecolor,backcolor,|,advhr,link,imageupload,image,media,table,code,|,fullscreen,|,help';
$site_url = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
$base_dir = DIR_WS_CATALOG;
//<!-- load the main TinyMCE files -->
$str  = <<< EOT
<script type="text/javascript" src="{$site_url}editors/zenc_tinymce/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
theme                             : 'advanced',
mode                              : 'textareas',
language                          : '{$language}',
plugins                           : '{$plugins}',
width                             : '600',
height                            : '350',
accessibility_warnings            : false,
document_base_url                 : '{$site_url}',
relative_urls                     : false,
remove_script_host                : false,
force_br_newlines                 : true,
force_p_newlines                  : false,
forced_root_block                 : '',
convert_fonts_to_spans            : true,
valid_elements                    : '*[*]',
theme_advanced_buttons1           : '{$theme_advanced_buttons1}',
theme_advanced_buttons2           : '{$theme_advanced_buttons2}',
theme_advanced_buttons3           : '',
theme_advanced_toolbar_location   : 'top',
theme_advanced_toolbar_align      : 'left',
theme_advanced_statusbar_location : 'bottom',
theme_advanced_resizing           : true,
theme_advanced_resize_horizontal  : false,
content_css                       : '{$base_dir}editors/zenc_tinymce/add_style.css'
});
</script>
<style type="text/css">
.clearlooks2_modalBlocker {background-color:#333;}
</style>
EOT;
echo $str;
