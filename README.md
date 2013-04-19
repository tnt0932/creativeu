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

#### `git checkout [name of branch]`
Used to check out the branch that you want to work on. We will use the branch 'develop' for most of our work. 'master' will reflect what is on the live site.

#### `git status`
Check the git status. Shows you which files have been added / modified / deleted since your last commit.

#### `git add .`
This will get all of the files that have changed since your last commit, ready to be commited. 
You can either `git add .` to add everything that has changed,
or you can `git add [name of specific file]` to only add a specific file

#### `git commit -m "[your commit message]"`
Commit all of your changed. Add a message that indicates what has changed.

#### `git pull origin [name of branch]`
Downloads the latest files from a specific branch onto your computer. You'll want to do this every time you start working, and before you push any changes, so that you know that you're working with the latest code.

#### `git push origin [name of branch]`
Made and committed changes? Push them up to the repository so that others can use your code. Do this often.

So the normal flow:

1. `git status` verify which files have changed
2. `git add .` add all of those changes
3. `git commit -m "[Your message]"`
4. `git pull origin [branch]`
5. `git push origin [branch]`
