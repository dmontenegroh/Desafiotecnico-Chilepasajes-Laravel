# Proyecto API Nasa con Laravel 11 con Docker y Sail

## Requisitos Previos

- [Docker](https://docs.docker.com/get-docker/) instalado en tu máquina.
- [Git](https://git-scm.com/) instalado.
- (Opcional) [Composer](https://getcomposer.org/) para gestionar dependencias localmente.

## Librerias y Versiones 
"php": "8.2",
"guzzlehttp/guzzle": "7.9",
"laravel/framework": "11.31"

##Paso a paso 
 git clone https://github.com/tu_usuario/tu_repositorio.git

 cd tu_repositorio

composer install
 
cp .env.example .env

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate

./vendor/bin/sail up -d

## Añadir API Key a .env para el servicio de la NASA
NASA_API_KEY=tu_api_key_aqui


