# mintymint-tt

Requirements
========================
 * Web: Apache, NGINX
 * PHP: v5.6+ (preferable 7+) + Composer ([**https:\/\/getcomposer.org**][1])
 * MySQL: v5.7+
 * NodeJS: v8+
 * NPM: v5.5+
 * Angular CLI: v1.4+

Installation
========================

Change variable 'host' in src/AppBundle/Resources/public/mURL/src/app/app.configuration.ts to hostname that will be used for application (before running `npm run-script build`).
 
Console commands
------------------------
 * `git clone https://github.com/valeryvkrukov/mintymint-tt.git`
 * `cd mintymint-tt/src/AppBundle/Resources/public/mURL`
 * `npm install`
 * `npm run-script build`
 * `cd ../../../../../`
 * `composer install`
 * `bin/console doctrine:database:create`
 * `bin/console doctrine:schema:update --force`
 * `chmod -R 0777 var/logs var/cache var/sessions`

Backend CRUD test
------------------------
 * `phpunit`

Load fake data
------------------------
 * `bin/console doctrine:fixtures:load`

[1]:  https://getcomposer.org/
