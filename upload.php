<?php 

if (isset($_POST['submit']) && isset($_FILES['my_video'])) {
	include "db_conn.php";
    $video_name = $_FILES['my_video']['name'];
    $tmp_name = $_FILES['my_video']['tmp_name'];
    $error = $_FILES['my_video']['error'];

	$name = $_POST['video_name'];
	$desc = $_POST['description'];

    if ($error === 0) {
    	$video_ex = pathinfo($video_name, PATHINFO_EXTENSION);

    	$video_ex_lc = strtolower($video_ex);

    	$allowed_exs = array("mp4", 'webm', 'avi', 'flv');

    	if (in_array($video_ex_lc, $allowed_exs)) {
    		
    		$new_video_name = uniqid("video-", true). '.'.$video_ex_lc;
    		$video_upload_path = 'uploads/'.$new_video_name;
    		move_uploaded_file($tmp_name, $video_upload_path);

    		// Now let's Insert the video path into database
            $sql = "INSERT INTO videos(v_name, v_desc, video_url) 
                   VALUES('$name', '$desc', '$new_video_name')";
            mysqli_query($conn, $sql);
            header("Location: manage.php");
    	}else {
    		$em = "You can't upload files of this type";
    		header("Location: upload_videos.php?error=$em");
    	}
    }


}else{
	header("Location: upload_videos.php");
}