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
	@echo "$(STRING_COLOR)eb-load-positions .............: Load product segmentations positions from earlybird$(NO_COLOR)"
	@echo "$(STRING_COLOR)lengow-generate-csv ...........: Generate lengow marketplace products csv and save it on gcs$(NO_COLOR)"
	@echo "$(STRING_COLOR)process-import ................: Execute full import process (all import_logs)(NO_COLOR)"
	@echo "$(STRING_COLOR)open-facet-generate-template ..: Generate open facet template file and send it to gcs /feeds/open-facet/....xlsx$(NO_COLOR)"
	@echo "$(STRING_COLOR)pubsub-init ...................: Init topic and bind to a subscription$(NO_COLOR)"
	@echo "$(STRING_COLOR)load-fixtures .................: Load fixtures$(NO_COLOR)"
	@echo "$(STRING_COLOR)yaml-linter ...................: Check yaml coding standard$(NO_COLOR)"

start:
	docker-compose up -d

stop:
	docker-compose stop

restart: stop start

ssh-php:
	docker-compose exec php bash

ssh-db:
	docker-compose exec db bash

ssh-api:
	docker-compose exec api bash

prune:
	docker system prune -af

clean-vendor: cc-hard
	docker-compose exec php rm -Rf vendor
	docker-compose exec php rm composer.lock
	docker-compose exec php composer install

cc:
	docker-compose exec php bin/console c:c

cc-test:
	docker-compose exec php bin/console c:c --env=test

cc-hard:
	docker-compose exec php rm -fR var/cache/*

clean-db:
	docker-compose exec php bin/console doctrine:database:drop --force
	docker-compose exec php bin/console doctrine:database:create
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php bin/console hautelook:fixtures:load --no-interaction
	docker-compose exec php bin/console naiades:update-attributes

clean-db-test: cc-hard cc-test
	- docker-compose exec php bin/console doctrine:database:drop --force --env=test
	docker-compose exec php bin/console doctrine:database:create --env=test
	docker-compose exec php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker-compose exec php php -d memory_limit=-1 bin/console hautelook:fixtures:load --no-interaction --env=test

composer-install:
	docker-compose exec php composer install

func-test: clean-db-test
	docker-compose exec php ./vendor/bin/phpunit --verbose tests/Func

unit-test:
	docker-compose exec php ./vendor/bin/phpunit --log-junit build/junit/phpunit.xml -dxdebug.max_nesting_level=500 -dmemory_limit=512M --coverage-html ./coverage

all-test: func-test unit-test

linter:
	docker-compose exec php vendor/bin/phpcs --standard=phpcs.xml -n

linter-fix:
	docker-compose exec php vendor/bin/phpcbf --standard=phpcs.xml

eb-load-positions:
	docker-compose exec php bin/console eb:l:p

eb-publish-category-positions-config:
	docker-compose exec php bin/console eb:publish:categories-positions -vvv

eb-save-category-positions-config:
	docker-compose exec php bin/console eb:save:products-categories-country-positions -vvv

lengow-generate-csv:
	docker-compose exec php bin/console lengow:product:generate-csv

process-import:
	docker-compose exec php bin/console naiades:process:import

open-facet-generate-template:
	docker-compose exec php bin/console naiades:open-facet:generate-template

load-fixtures:
	docker-compose exec php php -d memory_limit=-1 bin/console hautelook:fixtures:load --no-interaction

yaml-linter:
	docker-compose run yamllint .

mirakl-product-import:
	docker-compose exec php php -d memory_limit=-1 bin/console mirakl:import-products -f
mirakl-product-consumer:
	docker-compose exec php php -d memory_limit=-1 bin/console mirakl:consume:products
mirakl-offer-import:
	docker-compose exec php php -d memory_limit=-1 bin/console mirakl:import-offers-async -f
mirakl-offer-consumer:
	docker-compose exec php php -d memory_limit=-1 bin/console mirakl:consume:offers
mirakl-shop-import:
	docker-compose exec php php -d memory_limit=-1 bin/console mirakl:import-shops

pubsub-init:
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/import-mirakl-product-requests projects/digital-staging/subscriptions/naiades_import-mirakl-product-requests
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/import-mirakl-offer-requests projects/digital-staging/subscriptions/naiades_import-mirakl-offer-requests
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/categories-associations-db-save-events projects/digital-staging/subscriptions/naiades_categories-associations-db-save-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/products-sku-build-events projects/digital-staging/subscriptions/naiades_products-sku-build-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/cloudinary-upload-image-events projects/digital-staging/subscriptions/naiades_upload-mirakl-images-to-cloudinary-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/zod-translations-events projects/digital-staging/subscriptions/naiades_zod-translations-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/switch-alias-events projects/digital-staging/subscriptions/naiades_switch-alias-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/naiades-product-notready projects/digital-staging/subscriptions/naiades-product-notready-subscription
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/naiades-offer-ready projects/digital-staging/subscriptions/naiades-offer-ready-subscription
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/naiades-product-ready projects/digital-staging/subscriptions/naiades-product-ready-subscription
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/categories-indexation-events projects/digital-staging/subscriptions/elastic-indexer_categories-indexation-events projects/digital-staging/subscriptions/algolia-stream-puller_categories-indexation-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/categories-associations-events projects/digital-staging/subscriptions/elastic-indexer_categories-associations-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/shops-payload-build-events projects/digital-staging/subscriptions/elastic-indexer_shops-payload-build-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/filters-payload-build-events projects/digital-staging/subscriptions/elastic-indexer_filters-payload-build-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/eb-products-positions-events projects/digital-staging/subscriptions/eb-products-positions-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/alerting-events projects/digital-staging/subscriptions/alerting-service_alerting-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/eb-categories-position-events projects/digital-staging/subscriptions/naiades_eb-categories-position-events
	docker-compose exec php bin/console pubsub:create projects/digital-staging/topics/product-naiades-feed projects/digital-staging/subscriptions/product-naiades-feed-subscription

gitlab-test-unit:
	php -dmemory_limit=512M -dpcov.enabled=1 ./vendor/bin/phpunit --log-junit tests/_output/coverage-test-unit/junit.xml --coverage-clover tests/_output/coverage-test-unit/clover.xml

gitlab-test-func:
	bin/console doctrine:database:drop --force
	bin/console doctrine:database:create
	bin/console doctrine:migrations:migrate --no-interaction
	APP_ENV=test php -d memory_limit=2048M bin/console hautelook:fixtures:load --no-interaction
	APP_ENV=test ./vendor/bin/phpunit --verbose tests/Func/ --log-junit tests/_output/coverage-test-func/junit.xml
