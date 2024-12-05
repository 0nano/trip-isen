<?php

// Exemple de configuration vulnérable (URL cible dynamique)
function getTargetUrl() {
    return isset($_GET['url']) ? $_GET['url'] : 'https://sample-videos.com/video321/mp4/720/big_buck_bunny_720p_1mb.mp4';
}

// Fonction pour récupérer le contenu distant
function fetchVideo($url) {
    // Initialiser une session cURL
    $ch = curl_init();

    // Configurer les options cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Suivre les redirections
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout après 10 secondes

    // Exécuter la requête et récupérer le contenu
    $videoContent = curl_exec($ch);

    // Fermer la session cURL
    curl_close($ch);

    // Renvoyer le contenu
    return $videoContent;
}

// Récupérer l'URL cible
$targetUrl = getTargetUrl();

// Diffuser la vidéo même si l'URL est invalide
$videoContent = fetchVideo($targetUrl);
// Définir les en-têtes HTTP appropriés
header("Content-Type: video/mp4");
echo $videoContent;