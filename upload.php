<?php
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["passportPhoto"]["name"]);
    $uploadOk = 1;

    // Check file size
    if ($_FILES["passportPhoto"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["passportPhoto"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["passportPhoto"]["name"])). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }

    // redirect to the profil page after 5 seconds
    header("refresh:2;url=profil.html");
?>