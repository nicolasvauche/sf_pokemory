# Pokémory - Jeu de Memory Pokémon

Bienvenue dans Pokémory, un jeu de memory basé sur les Pokémon ! Ce projet est développé avec Symfony 7 et permet aux
utilisateurs de jouer à un jeu de memory avec différents niveaux de difficulté tout en utilisant des Pokémon comme
cartes. Les joueurs peuvent suivre leur progression et voir leur classement en fonction de leurs performances.

## Fonctionnalités

- **Trois niveaux de difficulté** : Débutant (4 cartes), Avancé (16 cartes), Expert (36 cartes).
- **Jeu de memory solo** : Les joueurs peuvent jouer en solo, mais doivent être connectés pour enregistrer leur
  progression.
- **Cartes Pokémon** : Les cartes affichent des Pokémon avec leur image et leur nom.
- **Authentification** : Système d'authentification pour que seuls les utilisateurs connectés puissent jouer.
- **Gestion des Pokémon** : Récupération des informations des Pokémon via l'API Pokémon.
- **Classement** : Classement global des joueurs par niveau de jeu.
- **Administration** : Les administrateurs peuvent gérer les données des Pokémon.

## Prérequis

- PHP 8.2.x
- Composer 2.5.x
- Symfony CLI 5.x
- MySQL 8.x ou MariaDB 11.x

## Installation

1. Clonez le dépôt :
    ```bash
    git clone https://github.com/nicolasvauche/sf_pokemory.git
    cd pokemory
    ```

2. Installez les dépendances :
    ```bash
    composer install
    ```

3. Configurez les variables d'environnement :
    ```bash
    cp .env .env.local
    ```
   Modifiez le fichier `.env.local` avec vos paramètres de base de données et autres configurations nécessaires.

4. Créez la base de données et exécutez les migrations et les fixtures :
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
    ```

5. Lancez le serveur Symfony :
    ```bash
    symfony server:start
    ```

6. Accédez à l'application dans votre navigateur à l'adresse [http://localhost:8000](http://localhost:8000).

## Utilisation

### Administration

1. Connectez-vous en tant qu'administrateur :

    - admin@pokemory.com | admin,

2. Accédez à la page d'administration pour charger les Pokémon dans la base de données.

### Jouer

1. Inscrivez-vous ou connectez-vous en tant que joueur :

    - player@pokemory.com | player,
    - player2@pokemory.com | player

2. Choisissez un mode de jeu (Débutant, Avancé, Expert).
3. Jouez au jeu de memory en trouvant les paires de cartes Pokémon.
4. Consultez votre progression et votre classement sur la page de classement.

## Commandes Utiles

- Lancer les tests :
    ```bash
    php bin/phpunit
    ```

- Nettoyer le cache :
    ```bash
    php bin/console cache:clear
    ```

## Contributions

Les contributions sont les bienvenues ! Si vous souhaitez contribuer, veuillez créer une branche à partir de `main`,
apporter vos modifications et soumettre une pull request.

1. Fork le projet
2. Créez votre branche de fonctionnalité (`git checkout -b feature/ma-fonctionnalité`)
3. Commitez vos modifications (`git commit -am 'Ajout de ma fonctionnalité'`)
4. Poussez votre branche (`git push origin feature/ma-fonctionnalité`)
5. Ouvrez une pull request

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](licence.txt) pour plus d'informations.

## Remerciements

Un grand merci à l'équipe de Pokémon pour l'API fantastique et à tous les contributeurs de Symfony et des autres
bibliothèques utilisées dans ce projet.

---

Amusez-vous bien avec Pokémory et attrapez-les tous !
