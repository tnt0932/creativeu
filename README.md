creativeu
=========

### Installation:

1. Clone git repo where you want it on your computer:
    * `git clone git@github.com:tnt0932/creativeu.git`

2. Create MySQL database:
    * `CREATE DATABASE creativeu;`

3. Import creativeu.sql into the creativeu database:
    * `mysql -uroot -p creativeu < [path to file]/includes/creativeu.sql` (or use PHPMYADMIN)

4. Copy db-template.php and rename to db-local.php

5. Fill out local database details:
    * `host: localhost`
    * `database: creativeu`
    * `username: root`
    * `password: [your password]`
    
### Useful terminal commands


#### `git status`


git add .

git checkout develop

git pull origin develop
git push origin develop

