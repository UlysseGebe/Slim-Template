<?php
    $target_dir = "../assets/video/";
    $target_video = $target_dir . basename($_FILES["videoToUpload"]["name"]);
    $uploadOk = 1;
    $videoResult = 0;
    $videoFileType = strtolower(pathinfo($target_video,PATHINFO_EXTENSION));
    
    // Check if file already exists
    if (file_exists($target_video)) {
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["videoToUpload"]["size"] > 1000000000) {
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($videoFileType != "mp4" && $videoFileType != "mov" && $videoFileType != "mkv" && $videoFileType != "avi") {
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $videoResult = -1;
    } else {
        if (move_uploaded_file($_FILES["videoToUpload"]["tmp_name"], $target_video)) {
            $videoResult = 0;
        } else {
            $videoResult = -1;
        }
    }
