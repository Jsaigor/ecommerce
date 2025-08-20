
<?php
session_start();

require 'menu.php';
require 'footer.php';
require 'modificar_producto.php';

try {
    $db = new SQLite3('TiendaDB.sqlite');
    $db->enableExceptions(true); // Opcional: ayuda a capturar errores más fácilmente
} catch (Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Stock</title>
    <link rel="icon" href="./img/icon4.png">
    <link rel="stylesheet" href="./css/estilo_v3.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel='stylesheet' href="./css/uicons-brands.css">
</head>
<body>
<?php menu(); ?>
<hr>
<main class=main-main>
<div class="container">
    <h1 class="mb-4">Administración de Stock</h1>
    <div class="card">
        <div class="card-header"><h2>Agregar Producto</h2></div>
        <div class="card-body">      
            <form method="POST" enctype="multipart/form-data" action="agregar_producto.php">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del producto" required>
                </div>
                <div class="col-md-6">
                    <textarea name="descripcion" class="form-control" placeholder="Descripción"></textarea>
                </div>
                <div class="col-md-4">
                    <input type="number" name="cantidad" class="form-control" placeholder="Cantidad">
                </div>
                <div class="col-md-4">
                    <input type="number" name="precio" class="form-control" placeholder="Precio">
                </div>
                <div class="col-md-4">
                <select name="categoria_id" id="categoria" class="form-select" required>
                    <option value="">Seleccionar categoría</option>
                    <?php
                    $categorias = $db->query("SELECT * FROM categorias");
                    while ($c = $categorias->fetchArray(SQLITE3_ASSOC)):
                    ?>
                        <option value="<?= $c['category_id'] ?>"><?= $c['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>
                </div>
                <div class="col-md-6">
                <select name="subcategoria_id" id="subcategoria" class="form-select" required>
                    <option value="">Seleccionar subcategoría</option>
                </select>
                </div>
                <div class="col-md-6">
                <select name="item_id" id="item" class="form-select" required>
                    <option value="">Seleccionar ítem</option>
                </select>
                </div>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="col-md-6">
                        <label for="imagen<?= $i ?>">Imagen <?= $i ?>:</label>
                        <input type="file" id="imagen<?= $i ?>" name="imagen<?= $i ?>" class="form-control">
                    </div>
                <?php endfor; ?>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Agregar producto</button>
                </div>
            </form>
        </div>
        <hr>
<div class="card">
    <div class="card-header"><h2>Modificar Producto</h2></div>
    <div class="card-body">
        <div id="panel-busqueda">
            <p class="form-text">Usa los siguientes menús para encontrar los productos que deseas editar.</p>
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="mod_categoria" class="form-label">Categoría</label>
                    <select id="mod_categoria" class="form-select">
                        <option value="">Seleccionar...</option>
                        <?php
                        // Reutilizamos la consulta de categorías
                        $categorias_mod = $db->query("SELECT * FROM categorias ORDER BY nombre");
                        while ($c = $categorias_mod->fetchArray(SQLITE3_ASSOC)):
                        ?>
                            <option value="<?= htmlspecialchars($c['category_id']) ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="mod_subcategoria" class="form-label">Subcategoría</label>
                    <select id="mod_subcategoria" class="form-select">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="mod_item" class="form-label">Ítem</label>
                    <select id="mod_item" class="form-select">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" id="btn-buscar-productos" class="btn btn-info w-100">Buscar Productos</button>
                </div>
            </div>
        </div>
        <hr>
        <div id="resultados-modificacion">
            <p class="text-center text-muted">Aquí aparecerán los productos encontrados.</p>
        </div>
    </div>
</div>
    <hr>
    <div class="card">
        <div class="card-header"><h2>Eliminar Producto</h2></div>
        <div class="card-body">
            <form action="eliminar_producto.php" method="POST">
                <input type="number" name="producto_id" class="form-control" placeholder="ID del producto a eliminar" required>
                <button type="submit" class="btn btn-danger">Eliminar Producto</button>
            </form>
        </div>
    </div>
    </div>
    </div>
<script>
document.getElementById('categoria').addEventListener('change', function() {
    fetch('get_subcategorias.php?categoria_id=' + this.value)
        .then(res => res.json())
        .then(data => {
            const subcat = document.getElementById('subcategoria');
            subcat.innerHTML = '<option value="">Seleccionar subcategoría</option>';
            data.forEach(sc => {
                subcat.innerHTML += `<option value="${sc.subcategory_id}">${sc.nombre}</option>`;
            });
            document.getElementById('item').innerHTML = '<option value="">Seleccionar ítem</option>';
        });
});


document.getElementById('subcategoria').addEventListener('change', function() {
    fetch('get_items.php?subcategoria_id=' + this.value)
        .then(res => res.json())
        .then(data => {
            const item = document.getElementById('item');
            item.innerHTML = '<option value="">Seleccionar ítem</option>';
            data.forEach(p => {
                item.innerHTML += `<option value="${p.item_id}">${p.nombre}</option>`;
            });
        });
});

// Para formulario de modificar
document.getElementById('mod_categoria').addEventListener('change', function() {
    fetch('get_subcategorias.php?categoria_id=' + this.value)
        .then(res => res.json())
        .then(data => {
            const subcat = document.getElementById('mod_subcategoria');
            subcat.innerHTML = '<option value="">Seleccionar subcategoría</option>';
            data.forEach(sc => {
                subcat.innerHTML += `<option value="${sc.subcategory_id}">${sc.nombre}</option>`;
            });
            document.getElementById('item').innerHTML = '<option value="">Seleccionar ítem</option>';
        });
});
document.getElementById('mod_subcategoria').addEventListener('change', function() {
    fetch('get_items.php?subcategoria_id=' + this.value)
        .then(res => res.json())
        .then(data => {
            const item = document.getElementById('mod_item');
            item.innerHTML = '<option value="">Seleccionar ítem</option>';
            data.forEach(p => {
                item.innerHTML += `<option value="${p.item_id}">${p.nombre}</option>`;
            });
        });
});

// --- AÑADE ESTE CÓDIGO AL FINAL DE TU ETIQUETA <script> ---

// Lógica para el botón de BÚSQUEDA de productos a modificar
document.getElementById('btn-buscar-productos').addEventListener('click', function() {
    const itemId = document.getElementById('mod_item').value;
    const resultadosDiv = document.getElementById('resultados-modificacion');

    if (!itemId) {
        alert('Por favor, seleccione una categoría, subcategoría e ítem para buscar.');
        return;
    }

    // Muestra un mensaje de carga mientras busca
    resultadosDiv.innerHTML = '<p class="text-center">Buscando...</p>';

    // Llama a un nuevo script PHP para obtener los productos
    fetch('buscar_productos.php?item_id=' + itemId)
        .then(response => response.text()) // Esperamos una respuesta HTML
        .then(html => {
            // Inserta el HTML recibido en el div de resultados
            resultadosDiv.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al buscar productos:', error);
            resultadosDiv.innerHTML = '<p class="text-danger text-center">Ocurrió un error al realizar la búsqueda.</p>';
        });
});

</script>
<script src="./js/items.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
</main>
<hr>
<?php footer(); ?>
</body>
</html>
