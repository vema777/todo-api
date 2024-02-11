Diese ist das Backend für die Todo-Anwendung "Complete". Um es ausführen zu können
macht man der Terminal auf: 

- docker compose up -d --build
- docker compose exec php /bin/bash

Im Docker-Terminal führt man aus dem Ordner /var/www/symfony_docker folgendes aus:

- ./bin/console doctrine:database:create (wenn notwendig)
- ./bin/console doctrine:migrations:migrate
- ./bin/console doctrine:fixtures:load
- ./bin/console doctrine:query:sql "SELECT * FROM user"

Alle fake-User haben das Passwort "password"  
Es werden auch fake-User mit der E-Mail "owner@example.com" und "admin@example.com" erstellt

Einen neuen User kann man mit Postman erstellen, indem man ein POST-Request auf:  
localhost:8080/api/users/logup  
mit folgendem JSON-Inhalt schickt:  
{
"email": "test_email",
"password": "password",
"firstName": "firstName",
"lastName": "lastName"
}  
  
Man kann sich einloggen, indem man ein POST-Request auf  
localhost:8080/api/login  
mit folgendem JSON-Inhalt schickt:  
{
"email": "admin@example.com",
"password": "password"
}  
  
Nach dem Einloggen und Registrieren wird ein gültiges API-Token zurückgeschickt  

Bei API-Abfragen wie localhost:8080/api/users muss man in Postman im Tab "Authorization" - den Parameter "Type" auf "Bearer Token" setzen und ins Feld "Token" rechts ein gültiges Token einfügen
