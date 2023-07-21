
# Projet Challenge Stack EMMA PIERRE PHP JS

## Démarrage

### Docker

Lancer Docker Desktop avant de lancer le projet sur votre IDE préféré

### Composer

Pour récupérer les dépendances déclarées dans `composer.json` et générer l'autoloader PSR-4, exécuter la commande suivante :

```bash
composer install
```

### DB Configuration

La configuration de la base de données doit être inscrite dans un fichier `.env.local`, sur le modèle du fichier `.env`.

### Démarrer l'application

Commande :

```bash
composer start
```

## Fonctionnalités

### Back office

Nous avons implémenté un back office pour les administrateurs. Cette interface lui permet de gérer sa boutique.

Nous avons aussi ajouter un back office pour les clients afin qu'ils puissent voir leurs commandes et modifier leurs informations.

### Routes

Implémentation des routes dynamiques avec une donnée non fixe dans l'url. Nous n'avons pas essayé avec plusieurs paramètre dans la même route mais théoriquement cela devrait marcher.
Nous avons la possiblité de récupérer la valeur du paramètre directement dans la méthode correpondante dans le controllera afin d'itérer le traitement du code selon cette variable.

### Boutique

Implémentation du listing des produits avec les filtres correspondants et une pagination selon le nombre de produits.

Page de détail des produits complétement dynamique en fonction des données dans la base de données.

#### Tunnel d'achat

Nous avons automatisé la validation du panier avec stripe afin d'externaliser vers ce service tier la notion de paiement et d'enregistrement de carte bancaire. De ce fait nous laissons stripe gérer la sécurité lors de cette étape.

## Notre feedback

> Gérer les modifications de structures de la BDD était un peu complexe. Partager ces modifications sans fichier de migration comme dans symfony est créateur facile d'erreur.

> Implémenter les routes avec des varaibles a été un petit challenge, j'ai fais le paris de ne pas regarder comment c'était implémenté par Symfony. J'ai utilisé les regex pour correspondres avec la bonne route. Par la suite j'ai réutilisé le service container pour y injecter en variable le paramètre de la route.

> L'ajout de la foncionnalité de la pagination a été très facile je trouve, il a juste suffit d'intaller PagerFanta et de créer une instance d'un Adapter et de PageFanta.

## Axes d'amélioration

> Instaurer un programme de fidélité pour favoriser les clients réguliers

> Mail de relance pour un panier abandonné

> Système de notifications pour l’ajout de nouveau produit ou autres fonctionnalités

