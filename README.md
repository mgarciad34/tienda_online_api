<p align="center"><a href="https://laravel.com" target="_blank"><img src="./Logo.jpg" width="400" alt="API REST Tienda Online Logo"></a></p>


#  API REST Tienda Online

Es una API REST la cual contiene el desarrollo backend de una tienda online, utilizando Laravel como framework PHP. Esta API contiene dos roles Administrador y Usuario.

En el rol del Administrador se pueden realizar las siguientes acciones:
    - Gestión de las categorias
    - Gestión de los productos

En el rol del usuario se pueden realizar las siguientes acciones:
    - Visualización global de los productos.
    - Visualización de los productos por categoria.
    - Agregar productos al carrito.
    - Realizar pedidos.
    - Visualizar el historial de pedidos realizados.
    - Actualizar el perfil del usuario.
    - Obtener recomendaciones de productos por las compras realizadas.

Luego tambien tenemos funcionalidades comunes como es el caso del login, y otras mas como el registro de nuevos usuarios, y la recuperación  de contraseña.

## Endpoints Comunes

### Login
- **Ruta:** `http://localhost:8000/api/login`
- **Método:** `POST`
- **Descripción:** Iniciamos sesion con un usuario.
- **JSON de Ejemplo:**

  ```json
  {
    "email": "ramona.cummerata@moen.com",
    "contrasena": "1234"
  }
  ```

### Registro de usuario
- **Ruta:** `http://localhost:8000/api/registro`
- **Método:** `POST`
- **Descripción:** Creamos un nuevo usuario.
- **JSON de Ejemplo:**

  ```json
  {
    "nombre": "Nombre del usuario",
    "email": "correo3@ejemplo.com",
    "contrasena": "contraseña123",
    "rol": "Administrador"
  }
  ```

### Cerrar Sesion
- **Ruta:** `http://localhost:8000/api/logout`
- **Método:** `POST`
- **Descripción:** Cerramos sesion de un usuario.

### Recuperar Usuario

## Rutas del Administrador

## Rutas del Usuario


## Autor

- [Manuel García Díaz](https://github.com/mgarciad34)
