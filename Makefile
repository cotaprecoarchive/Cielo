.PHONY: tests

tests:
	@./vendor/bin/phpunit -c phpunit.xml.dist

coverage:
	@./vendor/bin/phpunit -c phpunit.xml.dist --coverage-html ./tmp/coverage

cs:
	@./vendor/bin/phpcs --standard=PSR2 src tests

phpmd:
	@./vendor/bin/phpmd src text phpmd.xml.dist
