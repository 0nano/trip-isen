<?php
    include 'database.php';

    function decodeJWT($jwt) {
      // Sépare le header, le payload et la signature
      $parts = explode('.', $jwt);
      if (count($parts) !== 3) {
          return false; // JWT invalide
      }
  
      // Décodage Base64URL du payload
      $payload = json_decode(base64UrlDecode($parts[1]), true);
      if (!$payload) {
          return false; // Payload invalide
      }
  
      return $payload; // Retourne le payload décodé si tout est valide
    }
    
      function base64UrlDecode($data) {
          $base64 = strtr($data, '-_', '+/');
          return base64_decode($base64);
      }

      if (!isset($_COOKIE['auth_token'])) {
        echo "<p>Jeton JWT manquant. Veuillez vous connecter.</p>";
        header("refresh:2;url=login.php");
        exit;
    }

    // Décoder et vérifier le JWT
    $jwt = $_COOKIE['auth_token'];
    $payload = decodeJWT($jwt);

    if (!$payload || !isset($payload['username'])) {
        echo "<p>Jeton invalide. Veuillez vous reconnecter.</p>";
        header("refresh:2;url=login.php");
        exit;
    }

    // Récupérer l'username depuis le payload
    $username = $payload['username'];

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
    $sql = "select name from images where proprietor = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $image = $stmt->fetch();

    // delete the old image
    unlink("uploads/" . $image['name']);

    // update table images
    $sql = "update images set name = :name where proprietor = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', basename($_FILES["passportPhoto"]["name"]));
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // redirect to the profil page after 5 seconds
    header("refresh:2;url=profil.php");
?>