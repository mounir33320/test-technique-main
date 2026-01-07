# Mes retours
- J'aurais pu ajouter des tests end to end en utilisant le HttpClient mais je ne l'ai pas fait, je suis passé par insomnia
- En temps normal, j'aurais mis tous les montants en centimes pour n'avoir aucun float et ne manipuler que des integer.
- Je n'ai pas utiliser de Domain Service, j'ai préféré mettre un max de logique métier dans les entités pour éviter d'avoir
des entités anémique.
- J'ai ajouté la couche Application pour séparer la logique applicative de la logique métier.
- Test très intéressant permettant de mettre en pratique des éléments DDD et de l'architecture hexagonale dans le but de 
protéger le domain métier du monde extérieur.


# Test technique AuCOFFRE.com

## Prérequis

Installer sur la machine PHP 8.4 & composer.

## Installation

### Installer les paquets avec composer

```bash
composer install
```

### Copier le .env.sample en .env

```bash
cp .env.sample .env
```

### Créer et initialiser la base de données SQLite

```bash
php ./database/init.php
```
### Lancement du serveur php
```bash
composer serve
```

## L'api

Nous avons une mini API qui gère des comptes internes avec un solde en euros.

Les routes existantes :

- **GET /** - health check simple.
- **GET /accounts** – liste les comptes et leurs soldes.

## Objectifs

### Ajout fonctionnalité

Nous aimerions pouvoir effectuer un virement d'un compte à un autre.

A vous de choisir :

- La structure de la route
- Les données attendues en entrée,
- La réponse de l'api.

La règle que nous vous imposons est la suivante :

**Un compte ne doit pas se retrouver avec un solde négatif.**

Vous avez ensuite la liberté de créer les règles qui seraient logiques pour vous.

### Protéger l'api

Nous aimerions que l'api ne soit accessible que si un header portant la bonne clé API est présent.
Protégez-la.

### Notes
Durée conseillée : entre 1h et 1h30 maximum.
Vous êtes plus rapide? Tant mieux !

Surtout n’hésitez pas à :
- Améliorer le code existant, le modifier
- Créer/renommer/déplacer des fichiers
- Inclure quelques lignes expliquant vos choix dans le mail de réponse