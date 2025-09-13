DOCKER_COMPOSE_FILE = ./compose.yml

all: up

up:
	docker compose -f ${DOCKER_COMPOSE_FILE} up --build -d
attach:
	docker compose -f ${DOCKER_COMPOSE_FILE} up --build 
down:
	docker compose -f ${DOCKER_COMPOSE_FILE} down
stop:
	docker compose -f ${DOCKER_COMPOSE_FILE} stop
logs:
	docker compose -f ${DOCKER_COMPOSE_FILE} logs
clean: down
	docker container prune --force
fclean: clean
	docker system prune --all --force
ld:
	@if command -v lazydocker >/dev/null 2>&1; then \
		echo "launching lazydocker"; \
	else \
		echo "installation de lazydocker..."; \
		if command -v brew >/dev/null 2>&1; then \
			brew install jesseduffield/lazydocker/lazydocker; \
		else \
			curl -s https://raw.githubusercontent.com/jesseduffield/lazydocker/master/scripts/install_update_linux.sh | bash; \
		fi \
	fi; \
	lazydocker
re: fclean all
