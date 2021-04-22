install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin tests

test:
	composer run-script test

test-coverage:
	composer run-script test-coverage