# PREAMBULO
_Para la realizacion de esta prueba se ha optado por un sistema distribuido y dockerizado compuesto por un docker nginx como servidor web, un docker de base de datos, y el docker de la aplicacion; para este ultimo docker, se ha optado por un framework symfony simple con servicios de frontend y backend_

_El acceso al frontend es bajo la ruta http://doofinderlibrary.com, se trata de un sistema de plantillas twich y que utiliza npm como gestor de paquetes, se renderiza gracias a los controladores, utiliza el servicio de webpack para pre-processar los ficheros desde los assets_

![Alt text](readme_pics/screenshot1.png)

_El acceso al backend es bajo la ruta http://doofinderlibrary.com/api/, se trata de una API REST, simple que permite las tipicas operaciones CRUD sobre las entidades; en el fichero de postman existe una coleccion un requests a modo de ejemplo_

![Alt text](readme_pics/screenshot2.png)
# INFRAESTRUCTURA DE PROYECTO
### Estructura General
![Alt text](readme_pics/folder1.png)

_Build_
```
Configuracion especifica para los servicios de docker instalados 
```
_Mysql_
```
Mapeo del docker de Mysql para tener el acceso dsiponible desde el host 
```
_Symfony_
```
Directorio donde se almacena toda la logica de aplicación  
```
_docker-compose.yml_
```
Fichero para la creacion y despligue de dockers  
```
_Ficheros DockerFile_
```
recetas para la creacion del docker especifico  
```
_Postman Collection_
```
Se trata de un fichero .json que se ha de importar en postman. Es una coleccion de postman con requests ya preparadas para probar la parte del backend  
```
### Estructura Symfony
![Alt text](readme_pics/folder2.png)

_Estructura Básica de un proyecto Symfony destacar lo siguiente_

_Config_
```
Necesario para toda la configuracion de la aplicacion  
```
_src_
```
Se encuentra toda la lógica de la aplicación esta compuesto por:
  * Los controladores
  * Las Entidades
  * Las Entidades Tipo Formualrios
  * Servicios de Repositorio, estos servicios son los encargados de realizar las funciones especifas de base de datos
  * Otros Servicios (validador)    
```
_public_
```
Directorio publico que se muestra en el front, se trata de un directorio dinámico que se genera gracias a la compilacion de webpack      
```
_templates_
```
Este directorio almacena las plantillas twig para el renderizado del front      
```
_tests_
```
Este directorio almacena todos los tests que se ejecutan gracias a PhpUnit      
```
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
# POSIBLES MEJORAS A REALIZAR
_* Incluir el buscador de doofinder_

_* Agregar tags a los books para una mejor busqueda_

_* Gestion de Usuarios para securizar la insercion y/o borrado de items_

_* Agregar Traducciones_
