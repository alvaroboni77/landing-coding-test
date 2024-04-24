# Landing coding test
Este proyecto consta de una landing que muestra un listado de productos de Amazon. Dispone de un comando que procesa los datos de un fichero json y los guarda en una base de datos MongoDB. 

Pasos a seguir para probar el proyecto:

#### 1. Levantar contenedores Docker

Ejecutar el siguiente comando en el directorio *`docker/`* de la raíz del proyecto para levantar los contenedores Docker:

```bash
docker-compose up -d --build
```
#### 2. Instalar dependencias de Composer
Ejecutar el siguiente comando para instalar las dependencias:
```bash
docker-compose exec php composer install
```
#### 3. Acceder al proyecto
Una vez que todos los contenedores estén en funcionamiento, podrás acceder al proyecto a través del navegador web utilizando la dirección `localhost:8000`
#### 4. Cargar datos en la base de datos
Si se intenta acceder al proyecto sin haber cargado datos en la base de datos, se redireccionará a una pagina de error donde se indica que no se han encontrado productos.

Para cargar los productos, se debe ejecutar el siguiente comando:
```bash
php bin/console app:import-json-to-db
```
Una vez ejecutado correctamente el comando, se podrá visualizar la landing.
> **NOTA:_** el fichero json se encuentra situado en el directorio *`src/Data/`*.
