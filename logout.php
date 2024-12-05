<?php
// Supprimer le cookie auth_token en définissant une date d'expiration dans le passé
setcookie('auth_token', '', time() - 3600, '/');

// Rediriger vers index.html
header('Location: index.html');
exit;
?>