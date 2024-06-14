.PHONY: tests run

tests:
	./vendor/phpunit/phpunit/phpunit  ./tests

run:
	php ./app.php input.txt
