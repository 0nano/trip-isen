<?php
include 'db_connection.php'; // Inclut la connexion à la base de données

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

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Génération d'un token JWT (optionnel, nécessite une bibliothèque pour le faire)
    $jwt = bin2hex(random_bytes(16)); // Exemple simple de génération de token

    try {
        // Prépare la requête SQL pour insérer les données
        $sql = "INSERT INTO users (fname, lname, email, username, password, jwt) VALUES (:fname, :lname, :email, :username, :password, :jwt)";
        $stmt = $pdo->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':fname', $firstName);
        $stmt->bindParam(':lname', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':jwt', $jwt);

        // Exécuter la requête
        $stmt->execute();

        echo "Utilisateur enregistré avec succès.";
    } catch (PDOException $e) {
        // Gère les erreurs d'insertion
        die("Erreur lors de l'insertion des données : " . $e->getMessage());
    }
}
?>
