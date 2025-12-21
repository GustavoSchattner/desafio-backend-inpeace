setup:
	@echo "Construindo containers..."
	docker compose build
	@echo "Iniciando instalação do Symfony..."
	docker compose run --rm app composer create-project symfony/skeleton:"6.4.*" .
	docker compose run --rm app composer require webapp
	@echo "Subindo o ambiente..."
	docker compose up -d
	@echo "Pronto! Acesse http://localhost:8080"

up:
	docker compose up -d

down:
	docker compose down

bash:
	docker compose exec app bash