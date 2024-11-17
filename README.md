# API de Ecommerce

Esta API gestiona los productos de un Ecommerce

## ENDPOINTS

* localhost/API-ECOMMERCE/api/productos/ (GET)
* localhost/API-ECOMMERCE/api/productos/ (POST)
* localhost/API-ECOMMERCE/api/productos/:ID (GET ID)
* localhost/API-ECOMMERCE/api/productos/:ID (PUT)
* localhost/API-ECOMMERCE/api/productos/:ID (DELETE)
* localhost/API-ECOMMERCE/api/auth/token (GET AUTORIZACION)

## Servicios GET
### OBTENER TODOS LOS PRODUCTOS
 _Para poder acceder a todos los registros utilizamos el metod GET._
```
localhost/API-ECOMMERCE/api/productos/
```
### OBTENER UN PRODUCTO POR ID
_Para poder acceder a un registro por ID tambien utilizamos el metodo GET._
* localhost/API-ECOMMERCE/api/productos/:ID
```
localhost/API-ECOMMERCE/api/productos/25
```
### ordenPor & orden
_utilizando los query params ordenPor & orden podemos establecer un orden ascendente 'ASC' o descentendete 'DESC' a una clasificacion ingresada en 'ordenPor'_
ordenPor:
* id_producto
* id_vendedor
* id_categoria
* nombre
* descripcion
* precio
* stock
* fecha_creacion
* imagen (ruta en la cual esta localizada esta misma)
* disponible

orden:
* ASC
* DESC
```
localhost/API-ECOMMERCE/api/productos?ordenPor=nombre&orden=ASC
localhost/API-ECOMMERCE/api/productos?ordenPor=precio&orden=DESC
```

### FILTRO
_utilizando los query params CAMPO & VALOR podemos establecer el valor de una celda para poder filtrar. campo(columna) y valor(valor de la celda)_ - ej: campo=nombre & valor=Sillon cama
       
* nombre
```
localhost/API-ECOMMERCE/api/productos?campo=nombre&valor=Sillon+cama
```
* descripcion
```
localhost/API-ECOMMERCE/api/productos?campo=descripcion&valor=Nuevo+y+muy+comodo
```
* precio
```
localhost/API-ECOMMERCE/api/productos?campo=precio&valor=2500
```
* stock 
```
localhost/API-ECOMMERCE/api/productos?campo=stock&valor=2
```
* id_categoria
```
localhost/API-ECOMMERCE/api/productos?campo=id_categoria&valor=7
```

* fecha_creacion
```
localhost/API-ECOMMERCE/api/productos?campo=fecha_creacion&valor=2024-10-20 18:21:17
```

Etc. Puede hacerlo con cualquiera de los atributos del Producto

### PAGINACIÓN
_Para utilizar la paginacion debemos ingresar dos valroes para nuestras keys pagina y limite (registros que queremos mostrar). Podemos utilizar solo la key PAGINA y por defecto tendra un limite de 3 registros_   
```
localhost/API-ECOMMERCE/api/productos?pagina=1
localhost/API-ECOMMERCE/api/productos?pagina=1&limite=5    
```
### Conclusion filtro/orden/paginacion
* _Podemos utilizar solo campo&valor (en caso de que solo queramos buscar ese dato de esa columna)_
* _Podemos utilizar solo ordenPor&orden (en caso de q solo queramos cambiar el orden de lo q vemos en base a la columna q decidamos -ordenPor-)_
* _Podemos utilizar solo pagina o pagina&limite (en caso de que queramos paginar lo que ya estamos viendo en el get all)_
* _Podemos combinar estos query params como sea siempre y cuando esten los pares juntos salvo pagina que puede ir solo (campo&valor | ordenPor&orden | pagina&limite)_
```
localhost/API-ECOMMERCE/api/productos?ordenPor=precio&orden=DESC&pagina=1&limite=5
(de esta forma, veremos todos los registros organizados por precio descendente con un limite de 5 registros por pagina)
```
## Servicio POST (requiere autorizacion)
_Para insertar un registro en la BBDD necesitamos poner nuestro endpoint con el metodo POST (localhost/API-ECOMMERCE/api/productos/)_
``` 
{
  "id_vendedor": 26,
  "id_categoria": 2,
  "nombre": "Sillon cama",
  "descripcion": "Nuevo y muy comodo",
  "precio": "34.00",
  "imagen": "rutaimagen",
  "stock": 2147483647,
  "fecha_creacion": "0000-00-00 00:00:00",
  "disponible": 1,
}
```
_En caso de querer incluir una imagen,la misma debe estar subida en la web,agregando su correspondiente URL en el "body" del request, resultando de la siguiente manera_
```

## Servicio PUT (requiere autorizacion)
_Para editar un registro en la BBDD necesitamos poner nuestro endpoint con el metodo PUT y saber el ID que vamos a editar (localhost/API-ECOMMERCE/api/productos/:ID)_
  ```
localhost/API-ECOMMERCE/api/productos/25
  ```  
_y luego debemos completar el siguiente json:_
```   
{
  "id_vendedor": 26,
  "id_categoria": 2,
  "nombre": "Juego de mesa y sillas",
  "descripcion": "Moderno y de calidad",
  "precio": "1400000.00",
  "imagen": "juegomesa.png",
  "stock": 2,
  "fecha_creacion": "2024-10-20 18:09:30",
  "disponible": 1,
  "categoria_nombre": "Muebles"
}
```
## Servicio DELETE (requiere autorizacion)
_para elimintar un registro en la BBDD debemos conocer el ID, utilizamos el endpoint con metodo DELETE (localhost/API-ECOMMERCE/api/productos/:ID)_
```
localhost/API-ECOMMERCE/api/productos/25 (delete)
```
# AUTORIZACION:
_para poder identificarnos en la api debemos utilizar el metodo GET y cambiar nuestro endpoint a:_

localhost/API-ECOMMERCE/api/auth/token 

_Luego con nuestro usuario y contraseña (Basic Auth) accedemos para poder recibir un token._
_Este token es el que nos da la autorizacion para poder insertar,editar o eliminar (Bearer Token)._
