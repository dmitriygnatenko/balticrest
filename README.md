### Frontend
1. npm i 
2. gulp

### Testing
1. bin/console doctrine:migrations:migrate --env=test
2. bin/console doctrine:fixtures:load  --env=test
3. ./vendor/bin/codecept run

### Commands
1. bin/console balticrest:update-schedule
2. bin/console balticrest:update-search
3. bin/console balticrest:update-sitemap

### Deploy (on prod env)
./deploy.sh
