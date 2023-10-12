# Переменные
PHP_EXEC = php
COMPOSER = composer
ARTISAN = $(PHP_EXEC) artisan
DOCKER_EXEC = docker exec

# Цели и команды

# Установка зависимостей Composer
install:
	$(COMPOSER) install

# Обновление зависимостей Composer
update:
	$(COMPOSER) update

# Запуск миграций
migrate:
	$(ARTISAN) migrate

# Запуск сидеров
seed:
	$(ARTISAN) db:seed

# Запуск тестов PHPUnit
test:
	$(ARTISAN) test

# Очистка кэша
clear-cache:
	$(ARTISAN) cache:clear

# Очистка файлов конфигурации и кэша
clear-config:
	$(ARTISAN) config:clear

# Очистка и переустановка всей информации
reset:
	$(ARTISAN) migrate:refresh --seed

# Запуск Horizon
horizon:
	$(ARTISAN) horizon

# Вход в контейнер
app-bash:
	$(DOCKER_EXEC) -it aml_app bash

# Вкл
up:
	docker-compose up -d

# Выкл
down:
	docker-compose down

prepare-push:
	$(ARTISAN) ide-helper:generate
	$(ARTISAN) ide-helper:models
	$(ARTISAN) ide-helper:meta
	./vendor/bin/pint

