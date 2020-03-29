.PHONY: ${TARGETS}

DIR := ${CURDIR}
QA_IMAGE := coverd/phpqa:latest

define say_red =
    echo "\033[31m$1\033[0m"
endef

define say_green =
    echo "\033[32m$1\033[0m"
endef

define say_yellow =
    echo "\033[33m$1\033[0m"
endef

cs-lint: ## Verify check styles
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff-format udiff --allow-risky=yes --dry-run -vvv

cs-fix: ## Apply Check styles
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff-format udiff --allow-risky=yes -vvv

phpstan: ## Run PHPStan
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) phpstan analyse

phpunit: ## Run phpunit
	-./vendor/bin/phpunit

test: phpunit cs-lint phpstan ## Run tests
