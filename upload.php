<?php
	$conn = mysqli_connect("localhost", "root", "", "dropZed");
	include ("session.php");
	$target_dir = 'uploads/';
	$subDir = $_SESSION['userId'];
	$newLoc = $target_dir.$subDir."/";
	if(!empty($_FILES)){
		mkdir($newLoc);
		$target_file = $target_dir . basename($_FILES["file"]["name"]);
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $newLoc.$_FILES['file']['name'])) {
			$status = 1;
		}
	}
	
	if(isset($_POST['sendPost'])){
		$caption = $_POST['caption'];
		$saveDir = "posts/".$subDir."/";
		//save the caption
		$saveCaption = mysqli_query($conn, "insert into posts
												(caption)
													VALUES 
														('$caption')");
		//get the Id
		$get = mysqli_query($conn, "select max(poid) as id from posts");
		$arr = mysqli_fetch_array($get);
		$poid = $arr['id'];
		// proceed with the image uploading to global folder while renaming each to the following convention
		// = get the extension
		// = get the session UniqueID
		// = get the looping value,
		// = get the sha1(sessionID.timestamp.loopingValue)
		// = finally concatenate the extension
		// = and move
		
		$dir = $newLoc;
		$loopingValue = 0;
		//$files = scandir($dir);
		if(is_dir($dir)){
			if($dh = opendir($dir)){
				while(($file = readdir($dh)) != false){
					if(!is_dir($file)){
						//echo "filename: " . $file . "<br>";
						$loopingValue++;
						$sessionID = $_SESSION['userId'];
						$extension = strtolower(substr(strrchr($file, '.'), 1));
						$timo = time();
						$preName = $sessionID . $timo . $loopingValue;
						$newFileName = sha1($preName) . '.' . $extension;
						shout($extension);
						$uploadedFiles = 'uploadedFiles/';
						$rename = rename($newLoc.$file, $uploadedFiles.$newFileName);
						if ($rename) {
							$ins = mysqli_query($conn, "insert into images(imagename, post)
												VALUES 
													('$newFileName', $poid)");
							if($ins){
								header("location: index.php");
							}else{
								header("location: index.php");
							}
						}else{
							header("location: index.php");
						}
					}
				}
				closedir($dh);
				header("location: index.php");
			}
		}
	}
	
	if(isset($_POST['fileToDelete'])){
		$theFile = $_POST['fileToDelete'];
		mysqli_query($conn, "insert into testa(addto)values('$theFile')");
		unlink($target_dir.$subDir."/".$theFile);
	}
	
	function shout($param){
		echo "<script>alert('$param')</script>";
	}
?>