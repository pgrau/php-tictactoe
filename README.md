[![Build Status](https://secure.travis-ci.org/pgrau/php-tictactoe.svg?branch=master)](http://travis-ci.org/pgrau/tictactoe)

Tic Tac Toe is a PHP 7.1 application written following Domain-Driven Design approach.

You can see output app with the follow command line:

php app/console t:g:s

<!--You can see all domain events sent in the logs folder.-->

## Mandatory requirements
 
* PHP 7.1

## Use Docker 

    You can use Docker with the follow the command line:
    
    (Mac users) docker-machine start default
    
    docker-compose up
    docker exec -it tictactoe_php_1 /bin/bash

## Use Composer 

    composer install
    
## Unit Test

    You can execute the unit test with the follow command
    
    php bin/phpunit
    
    You can execute code coverage with the follow command
    
    php bin/phpunit  -c phpunit.xml.dist  --coverage-html=metrics
    
## Symfony Commands
    
    You can execute the symfony commands with the follow command
    
    php app/console
    