<? 
    include("db.php");
    include("auth.php");


    $upload_dir="/home/openecologyadmin/oe/carnivores/image_uploads/";

    $type = $_POST['mimetype']; 
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 


     if( $_FILES["record_photo"]["type"] == "image/jpeg" ) {
            if( $_FILES["record_photo"]["error"] == 0 ) {

	    		  $sql="insert into photo_records(user_id) values('$user_id')";
			  mysql_query($sql);

             
		          $photo_id = mysql_insert_id();

			  $fname = explode('.',$_FILES["record_photo"]["name"]);
			
			  $new_file_name = md5($fname[0]) . "_" . $photo_id . "." . $fname[1];
                          
			  move_uploaded_file($_FILES["record_photo"]["tmp_name"], $upload_dir . $new_file_name);
			  
			  $sql="update photo_records set photo_filename='$new_file_name' where photo_id='$photo_id' and user_id='$user_id'";
			  mysql_query($sql);
			  
?>
			  <script>
			         parent.document.getElementById("photo_image_file").value = '<? echo $photo_id; ?>';
				 //parent.document.getElementById("photo_image_id").value = '<? echo $photo_id; ?>';
				 window.parent.create_imgthumb("<? echo $new_file_name; ?>");
				 
			  </script>


<?

	     } 
     } else {

        print "Only jpeg images allowed";
     }

?> 

					
<form id="photo_import_form" action="photo_import_submit.php" method="post" enctype="multipart/form-data">
        <input type="file" name="record_photo" id="record_photo">
        <input type="submit" name="photo_submit" value="Import" id="photo_submit">
                                         
</form>