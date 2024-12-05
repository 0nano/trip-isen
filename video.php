<?php

// Exemple de configuration vulnérable (l'URL cible est fixe mais pourrait être modifiée indirectement)
function getTargetUrl() {
    // Cette fonction retourne une URL "dynamique" qui pourrait être manipulée ou exploitée.
    return isset($_GET['url']) ? $_GET['url'] : 'https://www.youtube.com/watch?v=ih5R_c16bKc';
}

// Fonction pour récupérer et diffuser le contenu d'une URL
function fetchVideo($url) {
    // Envoi d'une requête HTTP pour récupérer la vidéo (aucune validation de l'URL ici)
    $context = stream_context_create([
        'http' => [
            'timeout' => 5, // Timeout pour éviter les requêtes bloquantes
        ]
    ]);

    // Lire le contenu distant
    $videoContent = @file_get_contents($url, false, $context);

    if ($videoContent === false) {
        http_response_code(500);
        echo "Erreur : Impossible de charger la vidéo.";
        exit;
    }

    // Définir les en-têtes HTTP appropriés pour la vidéo
    header("Content-Type: video/mp4");
    echo $videoContent;
}

// Récupérer l'URL cible (source de la vulnérabilité)
$targetUrl = getTargetUrl();

// Appeler la fonction pour diffuser la vidéo
fetchVideo($targetUrl);
