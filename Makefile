NO_COLOR=\033[0m
STRING_COLOR=\033[32;01m

help :
	@echo "$(STRING_COLOR)start .........................: Start containers$(NO_COLOR)"
	@echo "$(STRING_COLOR)stop ..........................: Stop Containers$(NO_COLOR)"
	@echo "$(STRING_COLOR)restart .......................: Stop and start containers$(NO_COLOR)"
	@echo "$(STRING_COLOR)cc ............................: Clear cache $(NO_COLOR)"
	@echo "$(STRING_COLOR)cc-hard .......................: Remove var/cache/* $(NO_COLOR)"
	@echo "$(STRING_COLOR)ssh-php .......................: Connect to php container$(NO_COLOR)"
	@echo "$(STRING_COLOR)ssh-db ........................: Connect to bd container$(NO_COLOR)"
	@echo "$(STRING_COLOR)ssh-api .......................: Connect to php container$(NO_COLOR)"
	@echo "$(STRING_COLOR)prune .........................: Clean all that is not actively used$(NO_COLOR)"
	@echo "$(STRING_COLOR)clean-db ......................: Clean DB$(NO_COLOR)"
	@echo "$(STRING_COLOR)clean-vendor ..................: Clean DB$(NO_COLOR)"
	@echo "$(STRING_COLOR)func-test .....................: Launch func tests$(NO_COLOR)"
	@echo "$(STRING_COLOR)unit-test .....................: Launch unit test$(NO_COLOR)"
	@echo "$(STRING_COLOR)all-test ......................: Launch all test$(NO_COLOR)"
	@echo "$(STRING_COLOR)linter ........................: Check php coding standard$(NO_COLOR)"
	@echo "$(STRING_COLOR)linter-fix ....................: Fix coding standard$(NO_COLOR)"
	@echo "$(STRING_COLOR)yaml-linter ...................: Check yaml coding standard$(NO_COLOR)"

start:
	docker-compose up -d

start-watch:
	docker-compose up

stop:
	docker-compose stop

restart: stop start

ssh-php:
	docker-compose exec php bash

ssh-db:
	docker-compose exec db bash

prune:
	docker system prune -af

clean-vendor: cc-hard
	docker-compose exec php rm -Rf vendor
	docker-compose exec php rm composer.lock
	docker-compose exec php composer install

clean-vendor-dev: cc-hard
	docker-compose exec php rm -Rf vendor
	docker-compose exec php rm composer.lock
	docker-compose exec php composer install --dev

cc:
	docker-compose exec php bin/console c:c

cc-test:
	docker-compose exec php bin/console c:c --env=test

cc-hard:
	docker-compose exec php rm -fR var/cache/*

clean-db:
	docker-compose exec php bin/console doctrine:database:drop --force --if-exists
	docker-compose exec php bin/console doctrine:database:create
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction

clean-db-test: cc-hard cc-test composer-install-dev
	docker-compose exec php bin/console doctrine:database:drop --force --env=test --if-exists
	docker-compose exec php bin/console doctrine:database:create --env=test
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker-compose exec php php -d memory_limit=-1 bin/console hautelook:fixtures:load --no-interaction --env=test

composer-install:
	docker-compose exec php composer install

composer-install-dev:
	docker-compose exec php composer install

composer-update:
	docker-compose exec php composer update

accept-test: clean-db-test
	docker-compose exec php ./vendor/bin/codecept run Acceptance --verbose --steps

func-test: clean-db-test
	docker-compose exec php ./vendor/bin/codecept run Functional --verbose

unit-test:
	docker-compose exec php ./vendor/bin/codecept run Unit --verbose

all-test: accept-test func-test unit-test

linter:
	docker-compose exec php vendor/bin/phpcs --standard=phpcs.xml -n

linter-fix:
	docker-compose exec php vendor/bin/phpcbf --standard=phpcs.xml

yaml-linter:
	docker-compose run yamllint .

npm-install:
	docker-compose exec php npm install

npm-dev:
	docker-compose exec php npm run dev

npm-watch:
	docker-compose exec php npm run dev --watch

npm-prod:
	docker-compose exec php npm run production --progress
