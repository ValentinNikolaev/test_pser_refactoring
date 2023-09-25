# Run make help by default
.DEFAULT_GOAL = help
.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.NOTPARALLEL: ;          # wait for this target to finish
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
Makefile: ;              # skip prerequisite discovery

DOCKER_COMPOSE=$(shell command -v docker-compose 2> /dev/null)

.PHONY: build
build:
	docker build \
        --no-cache \
        --force-rm .

.PHONY: up
up:
	$(DOCKER_COMPOSE) -f docker-compose.yml up -d  --remove-orphans

.PHONY: down
down:
	$(DOCKER_COMPOSE) down --remove-orphans

reset: down up
start:
	$(DOCKER_COMPOSE) start

stop:
	$(DOCKER_COMPOSE) stop

bash:
	docker exec -it test-app-pser sh

.PHONY: test
test:
	docker exec test-app-pser php vendor/bin/codecept run tests/


.PHONY: calculation
calculation:
	docker exec test-app-pser php app.php input.txt


.PHONY: help
help: .title
	@echo ''
	@echo 'Usage: make [target] [ENV_VARIABLE=ENV_VALUE ...]'
	@echo ''
	@echo 'Available targets:'
	@echo ''
	@echo 'App'
	@echo '  test           Full tests run.'
	@echo '  calculation    Run calculation.'
	@echo ''
	@echo '### Docker ###'
	@echo '  build         Build or rebuild services'
	@echo '  down          Stop, kill and purge project containers.'
	@echo '  up            Starts and attaches to containers for a service'
	@echo '  reset         down + up alias'
	@echo '  start         Start containers.'
	@echo '  stop          Stop containers.'
	@echo ''
	@echo '### Bash ###'
	@echo '  bash          Go to the application container (if any)'

%:
	@: help