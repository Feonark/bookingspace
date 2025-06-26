# Projet Symfony – Site de Gestion des Salles

## Contexte

Ce projet a pour but de créer une application web de gestion de réservations de salles pour une organisation (entreprise, association, etc.). Il vise à faciliter la réservation par les utilisateurs et à simplifier la gestion pour les administrateurs.

## Fonctionnalités

### Pour les utilisateurs :

- Réservation d'une salle selon la date et l’heure choisies.
- Modification ou annulation d'une réservation.
- Consultation des disponibilités des salles.
- Possibilité de poser une pré-réservation (à valider par un administrateur).

### Pour les administrateurs :

- Tableau de bord complet avec gestion des :
  - Salles
  - Réservations (validation/annulation)
  - Utilisateurs
  - Équipements et critères d'ergonomie
- Notifications pour les pré-réservations non traitées (J-5).
- Différenciation visuelle entre pré-réservations et réservations confirmées.

### Recherche avancée :

- Filtres par :
  - Nom
  - Dates voulues
  - Capacité minimale
  - Équipements (logiciels, matériels)
  - Critères ergonomiques (luminosité, accessibilité PMR, etc.)

## Technologies

- **Back-end** : Symfony (PHP), Stimulus, Fixtures, FakerPHP, Maker Bundle, AssetMapper, UX Live Component, Security Bundle
- **Front-end** : Twig, TailwindCSS, UX Icons, FullCalendar
- **Base de données** : Doctrine ORM, SQLite

## Architecture du projet

- **Entités principales :** Booking, Equipment, ErgonomicCriteria, EventRoom, Notification, Software, User
- **Controllers principaux :**
  - Admin :
    - BookingCrudController
    - DashboardCrudController
    - EquipmentCrudController
    - ErgonomicCriteriaCrudController
    - EventRoomCrudController
    - NotificationCrudController
    - SoftwareCrudController
    - UserCrudController
  - **User :**
    - BookingApiController
    - BookingController
    - EventRoomController
    - NotificationsController
    - RegistrationController
- **Forms** : BookingForm, RegistrationForm
- **Templates Twig :**
  - booking : calendar, edit, index, list
  - eventroom : eventrooms, view
  - login : login

## Installation

1. Cloner le projet :

   ```bash
   https://github.com/Feonark/bookingspace.git
   cd bookingspace
   ```
2. Installation des dépendances (via Composer)

   ```
   composer install
   ```
3. Mettre en place la base de données

```
symfony console doctrine:database:drop --force
symfony console doctrine:database:create
symfony console make:migration
symfony console d:m:m
symfony console d:f:l
```

4. Lancer le serveur de développement
   ```
   symfony server:start
   ```
