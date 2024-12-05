<?php
include 'database.php'; // Inclure la connexion à la base de données

function generateJWT($username) {
    $header = base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64UrlEncode(json_encode(['username' => $username]));
    $signature = '0U92PWHgVpMm8xwEuRReQjt4VrAxLHYqeNnZgZbhLpQ';
    
    return "$header.$payload.$signature";
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // Vérifiez si l'utilisateur existe
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Générer le JWT
            $jwt = generateJWT($username);

            // Définir le cookie JWT
            setcookie('auth_token', $jwt, [
                'expires' => time() + 3600, // Expiration dans 1 heure
                'path' => '/',
            ]);

            // Redirigez ou affichez un message
            header('Location: profil.php'); // Redirection après succès
        } else {
            echo "Invalid username or password.";
            header("refresh:2;url=login.html");
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
