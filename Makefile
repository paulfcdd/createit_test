build:
	docker compose up -d --build

start:
	docker compose up -d

down:
	docker compose down

ssh:
	docker compose exec app bash