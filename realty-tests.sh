#!/bin/bash

docker-compose run php php vendor/bin/phpunit -c /var/www/src/RealtyRegistration/phpunit.xml.dist
