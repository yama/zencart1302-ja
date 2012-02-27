<?php
$target_dir = date('Y/m');
$filename   = date('md-His');

$base_path = str_replace('\\','/',realpath('../../../../../')) . '/';
include_once("{$base_path}admin/includes/configure.php");

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
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<link href="css/dialog.css" rel="stylesheet" type="text/css" />
<title>uploader</title>
</head>
<body>
<p>アップロード先：<?php echo DIR_WS_IMAGES . $target_dir;?>/</p>
<form name="iform" action="" method="post" enctype="multipart/form-data">
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
