# =====================
# Code Quality & Tests
# =====================

APP_SERVICE=app

test:
	docker compose exec $(APP_SERVICE) phpunit tests

phpstan:
	docker compose exec $(APP_SERVICE) phpstan analyse src --level=max

psalm:
	docker compose exec $(APP_SERVICE) psalm

phpmd:
	docker compose exec $(APP_SERVICE) phpmd src text cleancode,codesize,design,naming,unusedcode --exclude UseSuperGlobals

phpcs:
	docker compose exec $(APP_SERVICE) phpcs --standard=PSR12 src

quality: phpstan phpmd phpcs
