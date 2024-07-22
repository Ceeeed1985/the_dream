# the_dream - convertisseur de monnaie

## Objectif principal
être capable d'interragir avec une API afind d'afficher à la demande la valeur de ma monnaie en Euro en fonction du pays dans lequel nous nous trouvons.


## Statut
DONE - 22/07/2024


## La conception
Le problème principal était que je disposais d'une API qui pouvait donner :
 - les noms de devises
 - le taux

 Toutefois, la consigne était d'encoder un pays et une somme d'argent, et d'en obtenir une somme d'argent convertie.

 Je suis donc parti sur deux éléments :
  - API
  - database reprenant les pays (FR et EN) ainsi que les devises associées.

  Une fois que l'utilisateur encodait le pays :
   - le pays était conservé dans une variable ;
   - le pays était alors recherché dans la database ;
   - une fois trouvé, le taux de change y associé était également conservé dans une variable

   Ce taux de change était alors recherché dans l'API.

   Et il n'y avait plus qu'à calculer la valeur de la monnaie locale.


## Gros problème rencontré, sans solution pour le moment
Pour y arriver, j'ai créé 3 fichiers :
- style.css
- api.php : le but était de récupérer l'API et la l'intégrer dans un tableau
- index.php : le but était de :
    - faire le formulaire
    - créer le lien avec la database
    - créer le lien avec api.php
    - et faire tourner l'ensemble
Malheureusement, je n'ai jamais réussi à importer convenablement l'api dans index.php. Après de longues séances de débogage, j'ai décidé de tout mettre dans le même fichier !