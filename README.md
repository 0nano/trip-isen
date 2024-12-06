# trip-isen
War Game pour le module de développement sécurisé dans les applications Web

L'application est une application très simple d'agence de voyage. 

## Récupération de l'image Docker
L'image Docker est disponible à la récupération avec la commande suivante :
```bash
docker pull ghcr.io/0nano/trip-isen:main
```

## Installation
L'application est entièrement compilée pour être utilisée avec Docker en un seul container. Pour lancer l'application, il suffit d'effectuer la commande suivante :
```bash
docker run -p 8080:8080 -d ghcr.io/0nano/trip-isen:main
```
La base de données sera automatiquement créée et les données seront insérées. Il n'est pas nécessaire de lancer un script pour initialiser la base de données ou tout autre partie de l'application.

## Utilisation
L'application est accessible à l'adresse suivante : `http://localhost:8080`

## Fonctionnalités
L'application permet de créer un compte utilisateur, de s'y connecter pour réserver un voyage. Il est aussi possible de fournir son passeport pour une vérification de sécurité et d'obtenir les destinations en conséquence.