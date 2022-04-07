# INSTALACION DEL PROYECTO DE PRUEBA DOOFINDER
_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y pruebas._
### Pre-requisitos 📋
```
* Prueba desarrollada para ser ejecutada bajo SO Linux o Mac
* Asegurar que el puerto 80 esta libre 
* Asegurar que el puerto 3306 esta libre
* Instalar docker y docker-compose | https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04-es
* Hacer al usuario actual permisos de uso en docker y docker-compose (para no tener que ejecutar los comandos de docker-compose con 'sudo')
* Editar el archivo /etc/hosts y agregar la siguietne linea
    127.0.0.1       doofinderlibrary.com
* Instalar git
```
### Instalación 🔧
_Clonar repositorio_
```
$ git clone https://github.com/Lotykun/doofinder.git
```
_Acceder a carpeta y levantar contenedores_
```
$ cd doofinder/
doofinder (main)$ docker-compose up -d --build
```
_Importar datos a la base de datos_
```
doofinder (main)$ docker cp dump.sql mysql_doof:/dump.sql
doofinder (main)$ docker exec mysql_doof /bin/bash -c 'mysql -uroot -proot < /dump.sql'
```
_Instalar dependencias symfony_
```
doofinder (main)$ docker exec -i -t php_doof /bin/bash
/var/www/symfony# composer install
/var/www/symfony# npm install
/var/www/symfony# npm run dev
/var/www/symfony# exit
```
### Comprobación y Tests 🔧
_Acceder a un navegador y ejecutar la siguiente url_

Deberia devolver un listado de los jugadores totales que hay
```
http://doofinderlibrary.com/book/
```
_Ejecución de Tests PHPUnit_

En la ruta tests/ se encuentran los tests a ejecutar, cada nombre de función, especifica que tipo de test se realiza
```
doofinder (main)$ docker exec -i -t php_doof /bin/bash
/var/www/symfony# bin/phpunit
```
_Ejecucion Requests Colección Postman_

Importar la coleccion Postman del archivo
```
doofinder (main)$ doofinder.postman_collection.json
```
Mejoras:
incluir el buscador de doofinder
agregar tags a los books para una mejor busqueda
agregar campo para ver si viene de api o no
agregar traducciones