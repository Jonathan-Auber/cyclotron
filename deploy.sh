#!/bin/sh
rsync -av ./ jonathan-auber@ssh-jonathan-auber.alwaysdata.net:~/www/pedale-joyeuse --include=.htaccess --exclude=deploy --exclude=ECF_back.pdf --exclude=pedale_joyeuse.sql --exclude=README.md --exclude=".*"

#Se connecter en ssh à always data (ssh jonathan-auber@ssh-jonathan-auber.alwaysdata.net) et aller dans le dossier racine (www)
# ./deploy.sh dans le terminer pour lancer l'executable