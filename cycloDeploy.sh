#!/bin/sh
rsync -av ./ jonathan-auber@ssh-jonathan-auber.alwaysdata.net:~/www/cyclotron --include=.htaccess --exclude=deploy --exclude=ECF_back.pdf --exclude=pedale_joyeuse.sql --exclude=README.md --exclude=".*"

#Se connecter en ssh à always data via un nouveau terminal (ssh jonathan-auber@ssh-jonathan-auber.alwaysdata.net) et aller dans le dossier racine (www)
# ./cycloDeploy.sh dans le terminal pour lancer l'executable (a effectuer en étant dans le dossier de l'executable)