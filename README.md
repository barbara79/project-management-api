to create the project I run to have everything in the right place since I have the docker files:
`docker-compose run --rm php bash -c "composer create-project symfony/skeleton /tmp/symfony && cp -R /tmp/symfony/. /var/www/html && rm -rf /tmp/symfony"`


