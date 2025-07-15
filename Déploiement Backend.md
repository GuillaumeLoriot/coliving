## Créer le serveur 
1. Création de la machine virtuelle avec cloud en suivant les mêmes étapes. Au début nous avons essayé de créer le projet avec Ubuntu 22 on a eu plusieurs problèmes de compatibilité car Ubuntu ne prend pas en charge les versions de php qu'on avait. Pour palier à  ce problème nous avons décidé de choisissant Ubuntu 24 car c'était compatible avec notre projet.
2. Installation composer avec `sudo apt install composer`
3. Puis installer les dépendances comme PHP, MySQL et Apache.
## Création de la BDD
On a transfère le projet en suivant les même commandes qu'on a fait. On donne bien les autorisations et on crée la database.
```sql
sudo mysql 
<< CREATE DATABASE coliving; 
CREATE USER 'userName'@'localhost' IDENTIFIED BY 'MotDePasseSecure'; 
GRANT ALL PRIVILEGES ON siteName.* TO 'coliving_user'@'localhost'; 
FLUSH PRIVILEGES; 
SQL
```
## Zip du fichier
1. On a zipper et transférer le fichier sur le serveur et dézipper dans en mettant `var/www/html` et en faisant la commande suivante:
```bash 
cd /chemin/vers/projet 
zip -r projet.zip . -x "vendor/*" "var/*" "node_modules/*" 
scp projet.zip user@serveur:/home/user/
```
## Déployer sur le serveur
1. On a fait `composer install`
2. Pour gérer le problème avec les fixtures, comme elles sont censées être en dev, on a du installer les fixtures en environnement de pro : `composer require --dev doctrine/doctrine-fixtures-bundle` 
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
6. On a modifié le .env pour rajouter les identifient de connexion à la BDD et mettre l'environnement en prod : `composer dump-env prod`. Il a généré le fichier `.env.local` 
### Problèmes Majeures
Nos plus gros problèmes ont été de :
- gérer les problèmes de compatibilité PHP
![[Capture d'écran 2025-07-11 124127.png]]
![[Capture d'écran 2025-07-11 102009.png]]
- router au bon endroit
- la gestion des permissions en écriture avec les groupes linux
#### Fin 