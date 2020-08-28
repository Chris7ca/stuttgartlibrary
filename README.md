<p align="center"><img src="https://stuttgart.christianmtz.com/images/stuttgart-logo.png" alt="Stuttgart Logo"></p>

# Stuttgart Library

Este repositorio es una prueba de desarrollo para aplicar como desarrollador web en [DIT](WWW.DIT.MX).

## Ver el proyecto en línea

Para ver el proyecto en línea, vaya a [https://stuttgart.christianmtz.com](https://stuttgart.christianmtz.com). Para acceder o iniciar sesión puede acerlo con las credenciales porporcionadas por correo.

## Instalar el proyecto de forma local

Para instalar el proyecto de forma local deberá clonar el repositorio:

```git clone https://github.com/Chris7ca/stuttgartlibrary.git```

```cd stuttgartlibrary```

```composer install```

```npm install && npm run production```

```cp .env.example . .env```
 
*Configure las variables de entorno correspondientes a su base de datos [DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD] que previamente deberá crear en MySQL o MariaDB con el nombre que desee, solo asegúrese de establecer este nombre en la configuración.*

Posteriormente corra las migraciones y seeders

```php artisan migrate --seed```

Opcionalmente puede ejecutar:

```php artisan config:cache```

```php artisan view:cache```

```php artisan route:cache```

Se recomienda el uso de [Laragon](https://laragon.org/) si se encuentra en Windows o [Valet](https://laravel.com/docs/7.x/valet) si es usuario de macOS. 

## Notas

**Las imágenes están libres de derechos de autor.**

**El logotipo es de autoria propia y se diseñó únicamente para representar el propóstio del proyecto.**

**Las interfaces de usuario son diseñadas pensando en los requerimienos del proyecto, con apoyo del Framework CSS [UIkit](https://getuikit.com)**

**La información visualizada en el sitio web es información generada de forma aleatoria y por tanto ficticia.**

**Para una mayor comprensión de las funcionalidades consulte al desarrollador: christian.mb7ca@gmail.com**