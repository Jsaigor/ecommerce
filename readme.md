Este es un proyecto para la preentrega de un trabajo final de un "Ecommerce" de una cadena ficticia de electrodomésticos (Electro'STORE) para el curso 25015 de Front End con JS de Talento Tech.
Como utilizo PHP no es posible ver la página funcional en GIT ó Netlify.app, si desean ver en FreeHosting en la dirección "http://javiersigot.com.preview.services/" con email/password "javier.sigot@gmail.com/J@vier1970"

Posee una DB en SQLite con estas tablas:
CREATE TABLE categorias ( category_id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT NOT NULL )
CREATE TABLE items ( item_id INTEGER PRIMARY KEY AUTOINCREMENT, subcategory_id INTEGER NOT NULL, nombre TEXT NOT NULL, FOREIGN KEY (subcategory_id) REFERENCES subcategorias(subcategory_id) )
CREATE TABLE productos ( id INTEGER PRIMARY KEY, nombre TEXT, descripcion TEXT, precio REAL, imagen1 TEXT, imagen2 TEXT, imagen3 TEXT, imagen4 TEXT , cantidad INTEGER DEFAULT 1, categoria_id INTEGER, subcategoria_id INTEGER, item_id INTEGER)
CREATE TABLE subcategorias ( subcategory_id INTEGER PRIMARY KEY AUTOINCREMENT, category_id INTEGER NOT NULL, nombre TEXT NOT NULL, FOREIGN KEY (category_id) REFERENCES categorias(category_id) )
CREATE TABLE sucursales ( sucursal_id INTEGER PRIMARY KEY AUTOINCREMENT, direccion TEXT NOT NULL, codigo_postal TEXT, telefono TEXT, ciudad TEXT, provincia TEXT , latitud REAL, longitud REAL)
CREATE TABLE usuarios ( id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT, apellido TEXT, correo TEXT, telefono TEXT, direccion TEXT, cp TEXT, total REAL, fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP )

Cuando se realiza una venta o alguien se suscribe se guardan los datos en la tabla "usuarios" y se envía a "Formspree" los datos ya sea a la "action" Ecommerce ó Suscripción según corresponda

PHP permite utilizar funciones en diferentes archivos, en este caso utilicé un "menu.php", "footer.php", "mostrar_productos.php", "get_categoria.php", "get_subcategoria.php" para llamar desde cualquier página, esto ayuda si es necesario realizar algún cambio hacerlo solo en uno de ellos e impacta en todas las páginas que llamen a estas funciones

Los productos tienen una "categoria", una "subcategoría" y un "item" y por eso utilizo JS para realizar diferentes acciones para poder traer los productos seleccionados en productos.php y pueden filtrarse por "item_id" o directamente por "id" (por ej las notebooks estan en Categoría: Tecnología, Subcategoría: Computación e Item: Notebooks) mostrando todos los items que existan en la DB tabla "productos". Para mostrar_productos.php (que se ven en la página principal) es necesario que en cantidad de stock sea mayor a 0 para poder visualizarlo y al realizar una compra se resta de la cantidad existente en ese "id"

Las sucursales son traidas de la DB de la tabla "sucursales" y las que se agreguen en la tabla van a aparecer en la página (utilizo API de JS de Google gratuita para mostrar el mapa)

En admin.php se pueden agregar mas "productos" al stock, modificar alguno existente o borrar algún producto, actuando directamente en la DB...
