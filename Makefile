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
re: fclean all
