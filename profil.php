<?php
include 'database.php'; // Inclut la connexion à la base de données


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
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/elements/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>Trip'ISEN</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
        <!--
        CSS
        ============================================= -->
        <link rel="stylesheet" href="css/linearicons.css">
        <link rel="stylesheet" href="css/owl.carousel.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/nice-select.css">			
        <link rel="stylesheet" href="css/magnific-popup.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <!-- Start banner Area -->
        <section class="generic-banner relative">
        <!-- Start Header Area -->
            <header class="default-header">
                <nav class="navbar navbar-expand-lg  navbar-light">
                    <div class="container">
                          <a class="navbar-brand" href="index.html">
                              <img src="img/logo.png" alt="">
                          </a>
                          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="text-white lnr lnr-menu"></span>
                          </button>

                          <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li><a href="index.html#home">Home</a></li>
                                <li><a href="index.html#contact">Contact</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                          </div>						
                    </div>
                </nav>
            </header>

    
    <!-- About Generic Start -->
    <div class="main-wrapper">




        <!-- Start Generic Area -->
        <section class="about-generic-area pb-100">
            <div class="container">
                <h3 class="profile-title mb-30">Profile Information</h3>
                <div class="row mb-30">
                    <div class="col-md-6">
                        <?php

                            if (!isset($_COOKIE['auth_token'])) {
                                echo "<p>Jeton JWT manquant. Veuillez vous connecter.</p>";
                                header("refresh:2;url=login.html");
                                exit;
                            }

                            // Décoder et vérifier le JWT
                            $jwt = $_COOKIE['auth_token'];
                            $payload = decodeJWT($jwt);

                            if (!$payload || !isset($payload['username'])) {
                                echo "<p>Jeton invalide. Veuillez vous reconnecter.</p>";
                                header("refresh:2;url=login.html");
                                exit;
                            }

                            // Récupérer l'username depuis le payload
                            $username = $payload['username'];

                            $sql = "select fname, lname, email, username from users where username = :username";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':username', $username);
                            $stmt->execute();
                            $user = $stmt->fetch();

                            if ($user) {
                                echo "<div class='profile-info'>";
                                echo "<h4>First Name:</h4>";
                                echo "<p id='fname'>" . $user['fname'] . "</p>";
                                echo "<h4>Last Name:</h4>";
                                echo "<p id='lname'>" . $user['lname'] . "</p>";
                                echo "<h4>Email:</h4>";
                                echo "<p id='email'>" . $user['email'] . "</p>";
                                echo "<h4>Username:</h4>";
                                echo "<p id='username'>" . $user['username'] . "</p>";
                                echo "</div>";
                            } else {
                                echo "<p>Utilisateur introuvable.</p>";
                                header("refresh:2;url=register.php");
                            }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-picture">
                            <h4>Passeport:</h4>
                            <br>
                            <?php
                            $sql = "select name from images where proprietor = :username";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':username', $username);
                            $stmt->execute();
                            $image = $stmt->fetch();
                            echo "<img src='uploads/" .  $image['name'] . "' alt='Profile Picture' class='img-fluid' style='max-width: 150px; border-radius: 0%;'>";
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="passportPhoto">Upload Passport Photo</label>
                                <input type="file" class="form-control-file" name="passportPhoto" id="passportPhoto" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <script src="js/vendor/jquery-2.2.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>