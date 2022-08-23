# Présentation

Ce bundle permet de gérer les versions dans les applications du groupe

Il existe deux numéros de version : 

    * L'un pour la base de données
    * L'un pour le code applicatif

## Versions

### Version base de données

Une entité est créée grâce à ce bundle : VersionApp

### Version code applicatif

Le code doit lui aussi être versionné. 

Pour définir le chemin d'accès au fichier de versioning, il faut passer par les config :

    alteis_version:
        file: "%kernel.root_dir%/version.txt"

## Utilisation du bundle

### Commandes 

Il existe trois commandes principales :

    * version:check ; permet de s'assurer que le code applicatif et la base de données sont dans la même version
    * version:new ; permet d'initialiser une nouvelle version pour le code et la base de données
    * version:mep ; permet de renseigner la date de mise en production de la version


#### version:check

Permet de d'assurer que la base de données et le code sont dans la même version. 
La version considérée comme actuelle en base de données est le plus grand ID

option : 
    
    * symfony ; ajoute dans l'output la version utilisée de Symfony
    
#### version:new

Permet de créer une nouvelle version.
Ajoute le numéro de version avec la date d'initialisation en base de données et met à jour le numéro de version applicatif.
Créé un script de migration en utilisant doctrine migration bundle pour mettre à jour la base de prod.

option : 
    
    * name ; permet de spécifier le numéro de version.
    
_Si l'option n'est pas précisée alors la commande deviendra interactive et demandera à l'utilisateur de renseigner un numéro
    
#### version:mep

Permet de définir la date de mise en production
S'intègre dans un processus de déploiement continu. 

option : 
    
    * name ; permet de spécifier le numéro de version pour lequel mettre à jour la date
    
_Si l'option n'est pas précisée alors ce sera la version avec le plus grand ID qui sera mise à jour


### Configurations

Voici les configurations possibles : 

    alteis_version:
        enabled: true
        toolbar: true
        file: "%kernel.root_dir%/version.txt"

## Date mise à jour 

Depuis la version 0.2 il y a un nouveau filtre Twig disponible pour afficher la date de la dernière mise à jour de la version de l'application.

    {{ moisMEP() }}
    
Vous pouvez l'utiliser en définissant des filtres de mises en forme : 

    {{ moisMEP() | capitalize | raw }}
    
## Numéro de version

Un filtre est disponible pour afficher le numéro de version

    {{ currentVersion() }}
    
Ce filtre utilise un data Collector. Selon nos configurations, nous récupérons le contenu du fichier app/version.txt
    