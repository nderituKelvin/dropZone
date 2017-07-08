<html>
	<head>
		<link href="css/dropzone.css" type="text/css" rel="stylesheet"/>
		<link href="css/basic.css" type="text/css" rel="stylesheet"/>
		<link href="css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
		<script src="dropzone.js" type="text/javascript"></script>
		<script src="jquery-3.1.0.min.js" type="text/javascript"></script>
		<script src="bootstrap.js" type="text/javascript"></script>
		
		
	</head>
	<body>
	<?php
		include ("session.php");
	?>
		<div class="container" >
			<div class='content'>
				<form action="upload.php" method="post">
					<div class="form-group">
						<label for="caption">Caption</label>
						<textarea name="caption" class="form-control" placeholder="Write in something" cols="4" rows="4" maxlength="300" required ></textarea>
						<br>
						<input name="sendPost" type="submit" class="btn btn-info" value="Post">
					</div>
				</form>
				<form action="upload.php" class="dropzone" id="myDropzone">
					<div class="dz-message" data-dz-message><span><img src="images/imgIcon.png" class="img img-rounded" width="120" /> Drop Images Here Add Images<small><br>Max 3</small></span></div>
				</form>
			</div>
		</div>
	<style type="text/css">
		.dz-max-files-reached {
			background-color: red;
		}
	</style>
	<script type="text/javascript">
        Dropzone.options.myDropzone = {
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFiles: 3,
            init: function () {
                this.on("maxfilesexceeded", function (data) {
                    var res = eval('(' + data.xhr.responseText + ')');
                });
                this.on("addedfile", function (file) {
                    var removeButton = Dropzone.createElement("<button class='btn btn-link btn-xs'>x</button>");
                    var _this = this;
                    removeButton.addEventListener("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        _this.removeFile(file);
                        $.ajax({
                            url: "upload.php",
                            type: "POST",
                            data: { 'fileToDelete': file.name }
                        });
                    });
                    file.previewElement.appendChild(removeButton);
                });
            }
        };
	</script>
	</body>
</html>