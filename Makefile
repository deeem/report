install:
		docker run --rm -v `pwd`:/app composer/composer install
autoload:
		docker run --rm -v `pwd`:/app composer/composer dump-autoload
lint:
		docker run --rm -v `pwd`:/app php php /app/vendor/bin/phpcs --standard=PSR2 /app/src /app/tests
test:
		docker run --rm -v `pwd`:/app deeem/dbunit tests
repl:
		docker run --rm -it -v `pwd`:/app -w="/app/src" php php /app/vendor/bin/psysh
run:
		docker run --rm -it -v `pwd`:"/app" -w="/app/src" php php /app/src/index.php 
