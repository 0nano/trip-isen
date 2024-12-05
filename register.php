<?php
include 'database.php'; // Inclut la connexion à la base de données

function generateJWT($username) {
    $header = base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64UrlEncode(json_encode(['username' => $username]));
    $signature = '0U92PWHgVpMm8xwEuRReQjt4VrAxLHYqeNnZgZbhLpQ';
    
    return "$header.$payload.$signature";
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

// Vérifie si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère et sécurise les données du formulaire
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation de base
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) ) {
        die("Tous les champs sont obligatoires.");
    }

    // Vérifie si l'username existe déjà dans la base de données
    $sqlCheck = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':username', $username);
    $stmtCheck->execute();
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
        echo "<p>Username already taken. Chose an other one please</p>";
        header("refresh:2;url=register.html");
        exit;
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);



    try {
        // Prépare la requête SQL pour insérer les données
        $sql = "INSERT INTO users (fname, lname, email, username, password, jwt) VALUES (:fname, :lname, :email, :username, :password, :jwt)";
        $stmt = $pdo->prepare($sql);

        // Génération d'un token JWT
        $jwt = generateJWT($username);

        // Lier les paramètres
        $stmt->bindParam(':fname', $firstName);
        $stmt->bindParam(':lname', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':jwt', $jwt);

        // Exécuter la requête
        $stmt->execute();

        // Insérer une image par défaut
        $sql = "INSERT INTO images (name, proprietor) VALUES ('unkonwn.png', :proprietor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':proprietor', $username);
        $stmt->execute();

        // Définir le cookie JWT
        setcookie('auth_token', $jwt, [
            'expires' => time() + 3600, // Expiration dans 1 heure
            'path' => '/',
        ]);

        header("Location: profil.php");

        echo "Utilisateur enregistré avec succès.";
    } catch (PDOException $e) {
        // Gère les erreurs d'insertion
        die("Erreur lors de l'insertion des données : " . $e->getMessage());
    }
}
?>
