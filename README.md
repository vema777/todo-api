Diese ist das Backend für die Todo-Anwendung "Complete". Um es ausführen zu können
macht man der Terminal auf: 

- docker compose up -d --build
- docker exec -it complete-api-php-1 bash.

Im Docker-Terminal führt man aus dem Ordner /var/www/symfony_docker folgendes aus:

- ./bin/console doctrine:database:create (wenn notwendig)
- ./bin/console doctrine:migrations:migrate
- ./bin/console doctrine:fixtures:load (um fake Daten zu laden)
- ./bin/console doctrine:query:sql "SELECT * FROM user"