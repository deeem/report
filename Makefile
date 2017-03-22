# If the first argument is "run"...
ifeq (run,$(firstword $(MAKECMDGOALS)))
# use the rest as arguments for "run"
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
# ...and turn them into do-nothing targets
$(eval $(RUN_ARGS):;@:)
endif

install:
	docker run --rm -v `pwd`:/app composer/composer install
autoload:
	docker run --rm -v `pwd`:/app composer/composer dump-autoload
lint:
	docker run --rm -v `pwd`:/app php php /app/vendor/bin/phpcs --standard=PSR2 /app/src /app/tests
test:
	docker run --rm -v `pwd`:/app phpunit/phpunit tests
repl:
	docker run --rm -it -v `pwd`:/app -w="/app/src" php php /app/vendor/bin/psysh
run:
	docker run --rm -it -v `pwd`:"/app" -w="/app/src" php php -f $(RUN_ARGS)
