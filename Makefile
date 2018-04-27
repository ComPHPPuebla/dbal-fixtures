SHELL = /bin/bash

.PHONY: test fix

test:
	@php bin/phpunit

fix:
	@php-cs-fixer fix src --rules=@PSR2,no_unused_imports
	@php-cs-fixer fix tests --rules=no_unused_imports
