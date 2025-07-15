# Déploiement Back-end

## Api accessible à l'adresse: 35.195.2.21/api

<img width="1918" height="1016" alt="image" src="https://github.com/user-attachments/assets/05859eb9-939e-4439-a7e5-bba72ef62108" />


## Créer le serveur 
1. Création de la machine virtuelle avec cloud en suivant les mêmes étapes. Au début nous avons essayé de créer le projet avec Ubuntu 22 on a eu plusieurs problèmes de compatibilité car Ubuntu ne prend pas en charge les versions de php qu'on avait. Pour palier à  ce problème nous avons décidé de choisir Ubuntu 24.04  car compatible avec les versions des dépendances dans notre projet.
2. Installation composer avec `sudo apt install composer`
3. Puis installer les dépendances comme PHP, MySQL et Apache.
## Création de la BDD
On a transfèré le projet en suivant les même commandes qu'on a fait. On donne bien les autorisations et on crée la database.
```sql
sudo mysql 
<< CREATE DATABASE coliving; 
CREATE USER 'userName'@'localhost' IDENTIFIED BY 'MotDePasseSecure'; 
GRANT ALL PRIVILEGES ON siteName.* TO 'coliving_user'@'localhost'; 
FLUSH PRIVILEGES; 
SQL
```
## Zip du fichier
1. On a zipper et transférer le fichier sur le serveur puis dézipper en mettant `var/www/html` et en faisant la commande suivante:
```bash 
cd /chemin/vers/projet 
zip -r projet.zip . -x "vendor/*" "var/*" "node_modules/*" 
scp projet.zip user@serveur:/home/user/
```
## Déployer sur le serveur
1. On a fait `composer install`
2. Pour gérer le problème avec les fixtures, comme elles sont censées être en dev, on a du installer les fixtures en environnement de prod : `composer require --dev doctrine/doctrine-fixtures-bundle` (un problème s'est présenté ici car dans notre composer.json et composer.lock, le bundle de fixtures n'était requis que en dev et ne s'était donc pas instalé avec la commande composer instal)
<img width="1455" height="679" alt="image" src="https://github.com/user-attachments/assets/f4aa76f5-dff5-47de-8481-114d8107bb01" />

3. On fait `php bin/console d:s:u -f` pour faire la migration et `php bin/console d:f:l -n` pour charger les fixtures 
4. On a configuré apache en créant dans `etc/apache2/sites-available/IP_Externe.conf` puis on y a mis dans ce fichier ce bloc de code: 
```bash
<VirtualHost *:80>
ServerName api.coliving.local 
DocumentRoot /var/www/html/coliving/public 
<Directory /var/www/html/coliving/public> 
AllowOverride All 
Require all granted
FallbackResource /index.php
</Directory>
ErrorLog ${APACHE_LOG_DIR}/coliving_error.log CustomLog ${APACHE_LOG_DIR}/coliving_access.log combined 
</VirtualHost>
```
C'est ce qui permet  à Apache de trouver les éléments nécessaires pour faire le routing.
5. On a activé et recharger Apache 
6. On a modifié le .env pour rajouter les identifiants de connexion à la BDD et mettre l'environnement en prod : `composer dump-env prod`. Il a généré le fichier `.env.local.php` 

### Problèmes Majeures
Nos plus gros problèmes ont été de :
- gérer les problèmes de compatibilité PHP 
<img width="222" height="53" alt="image" src="https://github.com/user-attachments/assets/3246e91f-8179-4bd6-8908-1ac3b641a26e" />
<img width="1473" height="380" alt="image" src="https://github.com/user-attachments/assets/c133b27b-c1fc-421a-a205-1daad1890f60" />

- router au bon endroit (`FallbackResource /index.php` permet de trouver cette ressource physique qui contient tout ce dont le serveur à besoin pour router correctement)
- la gestion des permissions avec les groupes linux
  
#### Fin 
