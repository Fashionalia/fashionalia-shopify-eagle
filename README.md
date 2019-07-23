#Fashionalia Eagle
Esta aplicación es un middleware entre Shopify y el consumidor final de la información (Fashionalia) para que la marca pueda gestionar mejor los permisos de letura/escritura en pedidos.

Shopify, cuando da acceso vía API a lectura/escritura, lo da de manera global. Es posible que sólo se quieran dar a Fashionalia permisos de lectura de aquellos pedidos que Fashionalia ha creado.

Este proyecto se ofrece para que el propio cliente lo aloje en su plataforma y tenga la propiedad del código, pudiendo así verificar que nosotros sólo tenemos acceso a la información adecuada.

##Instalación

El proyecto es una aplicación en PHP 7.0 y Laravel 5.5.

Después de clonar el repositorio, crearemos una base de datos MySQL.

Luego, pasamos a configurar el fichero .env con los datos necesarios para que todo funcione.

```
cp .env.example .env
```

El fichero .env debe contener los siguientes valores

```
APP_NAME=Eagle
APP_ENV=production
APP_DEBUG=false
APP_LOG=single
APP_LOG_LEVEL=debug
```

```
APP_URL=https://...
```

Indicar la URL final pública del proyecto. Es importante que vaya con SSL. Notificar a Fashionalia.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eagle
DB_USERNAME=root
DB_PASSWORD=
```

Configurar aquí los parámetros de acceso a la base de datos

```
APP_LOGIN=foo
APP_PASS=faa
```

Login/contraseña con la que Fashionalia llamará a los endpoints. Notificar a Fashionalia.

```
SHOPIFY_URL=url
SHOPIFY_KEY=key
SHOPIFY_PASSWORD=pass
```

Datos para el acceso a la tienda de Shopify

Una vez configurado el .env ejecutamos

```
composer install
php artisan key:generate
php artisan migrate
```

Con esto hemos instalado los paquetes y creado la estructura en base de datos para almacenar los ids de los pedidos.

##Configuración web

No se ofrecen ficheros de configuración para NGINX/Apache.

##Endpoints

Los endpoints están definidos en el fichero api.php, y son

###GET /ping

Simple endpoint para poder monitorizar la salud del proyecto

###GET /order

Obtención de los datos de un pedido en base a su ID

###GET /catalog

Endpoint intermediario para que nos descarguemos el catálogo de la marca

###POST /order

Endpoint para grabación de pedidos

##Tests

El proyecto se ofrece con una suite completa de tests. Para ejecutarlos

```
composer test
```

Además de los tests unitarios "básicos" existe un test end-to-end que NO se debe ejecutar en producción, pero que sí se puede ejecutar el local para comprobar una grabación real de pedido y obtención de datos del mismo. Para que este test funcione necesitamos:

- Haber arrancado un servidor local con "php artisan serve --port 8082 &"
- Conocer el ID de una variación real de Shopify para hacer el pedido de prueba, e indicarlo en el fichero EagleE2ETest.php línea 55 	
    ```
    'variant_id' => 22570750148726,
    ```
- Configurar correspondientemente el .env. No olvidar los valores APP_URL y las api keys del shopify de prueba

Este test se ejecuta con

```
php vendor/bin/phpunit --testsuite Feature
```