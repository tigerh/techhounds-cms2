<?php
require("util.php");

$path = isset( $_GET['path'] ) ? $_GET['path'] : "/";

//Permissions checking
$allowed = true;
foreach($sub_perms as $permission) {
	if(patternMatch($permission["action"],$path,true)) {
		$allowed = $permission["allowed"];
	}
}
if(!$allowed) {
	redirect("permissions.php?action=denied");
}

$uploads_dir = ROOT_DIR . $path;
for($i = 0;$i < count($_FILES["files"]["error"]);$i++) {
	if($_FILES["files"]["error"][$i] == UPLOAD_ERR_OK) {
		$tmp_name = $_FILES["files"]["tmp_name"][$i];
		$name = $_FILES["files"]["name"][$i];
		move_uploaded_file($tmp_name, "$uploads_dir/$name");
		chmod("$uploads_dir/$name",FILE_PERM);
	}
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Upload Files</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<div class="body-container">
			<h1>File Upload: <?php echo $path ?></h1>
			<a href="files.php?path=<?php echo urlencode($path) ?>">Back</a><br>
			<form action="upload.php?path=<?php echo urlencode($path) ?>" method="POST" enctype="multipart/form-data">
				<input name="files[]" type="file" multiple="true"/><br />
				<input type="submit" value="Send files" />
			</form>
		</div>
	</body>
</html>
