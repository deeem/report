install:
	docker run --rm -v `pwd`:/app composer/composer install
autoload:
	docker run --rm -v `pwd`:/app composer/composer dump-autoload
lint:
	docker run --rm -v `pwd`:/app php php /app/vendor/bin/phpcs --standard=PSR2 /app/src /app/tests
test:
	docker-compose -f docker-compose.test.yml run --rm sut tests
up:
	docker-compose -f docker-compose.yml up -d
down:
	docker-compose -f docker-compose.yml down
