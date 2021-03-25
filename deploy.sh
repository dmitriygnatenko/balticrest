#! /bin/sh
git reset --hard
git pull origin master
php ./bin/console doctrine:migrations:migrate --no-interaction
php ./bin/console cache:clear
gulp
