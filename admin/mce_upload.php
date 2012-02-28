<?php
require('includes/application_top.php');
header("Content-type: text/html; charset=utf-8");

$target_dir = date('Y/m');
$filename   = date('md-His');

$base_path = str_replace('\\','/',realpath('../../../../../')) . '/';

$upload_path =  DIR_FS_CATALOG . DIR_WS_IMAGES . $target_dir;
$upload_dir  =  HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $target_dir;

if(!file_exists($upload_path) || !is_dir($upload_path)) mkdir_r($upload_path);

if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']))
{
	$extpos = strrpos($_FILES['image']['name'],'.');
	$filename .= substr($_FILES['image']['name'],$extpos);
	move_uploaded_file($_FILES['image']['tmp_name'], "{$upload_path}/{$filename}");
?>
<input type="text" id="src" name="src" />
<script type="text/javascript" src="../editors/zenc_tinymce/tiny_mce/tiny_mce_popup.js"></script>
<script>
  var ImageDialog = {
    init : function(ed) {
      ed.execCommand('mceInsertContent', false, 
        tinyMCEPopup.editor.dom.createHTML('img', {
          src : '<?php echo "{$upload_dir}/{$filename}"; ?>'
        })
      );
      
      tinyMCEPopup.editor.execCommand('mceRepaint');
      tinyMCEPopup.editor.focus();
      tinyMCEPopup.close();
    }
  };
  tinyMCEPopup.onInit.add(ImageDialog.init, ImageDialog);
</script>
<?php  } else {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quick uploader</title>
<script type="text/javascript" src="../editors/zenc_tinymce/tiny_mce/tiny_mce_popup.js></script>
<script type="text/javascript" src="../editors/zenc_tinymce/tiny_mce/plugins/imageupload/js/dialog.js"></script>
<link href="../editors/zenc_tinymce/tiny_mce/themes/advanced/skins/default/dialog.css" rel="stylesheet" type="text/css" />
</head>
<body>
<p>アップロード先：<?php echo DIR_WS_IMAGES . $target_dir;?>/</p>
<form name="iform" action="mce_upload.php" method="post" enctype="multipart/form-data">
  <input id="file" accept="image/*" type="file" name="image" onchange="this.parentElement.submit()" />
</form>
</body>
</html>
<?php }

function mkdir_r($dir)
{
	if(file_exists($dir)) return false;
	if(strpos($dir, '/')!==false && !file_exists(dirname($dir)))
	{
		if (mkdir_r(dirname($dir)) === false) return false;
	}
	return mkdir($dir);
}
