<?php
if($_POST['submitted'] == 1): // Form has been upploaded

	$upload_dir = wp_upload_dir();
	$upload_path = $upload_dir['path'] . DIRECTORY_SEPARATOR;
	$num_files = count($_FILES['file']['tmp_name']);

	
	
	if (!empty($_FILES)) {
	
		$tempFile = $_FILES['file']['tmp_name'];
		
		$targetPath = $upload_path;
		
		$targetFile = $targetPath . $_FILES['file']['name'];
		
		//move_uploaded_file($tempFile, $targetFile);
	
	}


endif;