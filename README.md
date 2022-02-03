<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# FactuGA App

_FactuGA App es un proyecto opensource hecho en Laravel 8, realizado para los desarrolladores que requieran de un sistema con facturación electronica, con este proyecto se pretende tener lo basico para facturación de compras desde agregar productos, la creación de facturas, facturas pendientes y compras de productos.

## Comenzando 🚀

### Pre-requisitos 📋

_Antes de empezar con la instalación del sistema es necesario tener en cuenta las siguiente extensiones que se requieren en nuestro servidor_
```
• PHP 7+
• Pdo-mysql
```

### Instalación 🔧

_Iniciamos con la instalación de nuestro sistema, damos por entendido que ya se tiene instalado Composer, Git y algun servidor WAMP o XAMPP. En dado caso que no, recomiendo descargar [Laragon](https://laragon.org/) para usuarios Windows_

_Clonamos el proyecto en nuestro directorio de proyectos_
```
git clone https://github.com/GAzocar2021/factuga-app.git
```
_Instalamos los paquetes de laravel_
```
composer install
```
_Creamos nuestra base de datos_
```
mysql> CREATE DATABASE databasename;
```
_Copiamos nuestro archivo .env.example y le cambiamos el nombre a .env y realizamos la configuración de nuestra base de datos_
```
...more

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=databasename
DB_USERNAME=username
DB_PASSWORD=password

...more
```
_Realizamos nuestras migraciones y seeders.
```
dir_project> php artisan migrate --seed
```
_Generamos nuestra key_
```
dir_project> php artisan key:generate
```
_Generamos nuestros links_
```
dir_project> php artisan storage:link
```
_Levantamos nuestro servidor_
```
dir_project> php artisan serve
```
_Iniciamos sesión_
```
Admin: admin@factugaapp.com
pass: 12345678
```
Cliente 1: cliente1@factugaapp.com
pass: 90123456
```
Cliente 2: cliente2@factugaapp.com
pass: 78901234
```
Cliente 3: cliente3@factugaapp.com
pass: 56789012
```
## Autores ✒️

_Menciona a todos aquellos que ayudaron a levantar el proyecto desde sus inicios_

* **Guifrank Azócar** - *Trabajo Inicial y Documentación* - [GAzocar2021](https://github.com/GAzocar2021/)

## Licencia 📄

Este proyecto está bajo la Licencia (MIT)


---
⌨️ con ❤️ por [GAzocar2021](https://github.com/GAzocar2021/)😊
