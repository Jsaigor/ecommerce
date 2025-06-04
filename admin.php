
<?php
session_start();

require 'menu.php';
require 'footer.php';

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
<style>
</style>
<body>
<?php menu(); ?>
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
            <form action="modificar_producto.php" method="POST">
                <input type="number" name="producto_id" class="form-control" placeholder="ID del producto a modificar" required>
                <select name="categoria_id" id="mod_categoria" class="form-select" required>
                    <option value="">Seleccionar categoría</option>
                    <?php
                    $categorias = $db->query("SELECT * FROM categorias");
                    while ($c = $categorias->fetchArray(SQLITE3_ASSOC)):
                    ?>
                        <option value="<?= $c['category_id'] ?>"><?= $c['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>

                <select name="subcategoria_id" id="mod_subcategoria" class="form-select" required>
                    <option value="">Seleccionar subcategoría</option>
                </select>

                <select name="item_id" id="mod_item" class="form-select" required>
                    <option value="">Seleccionar ítem</option>
                </select>

                <input type="text" name="nombre" class="form-control" placeholder="Nuevo nombre del producto" required>
                <textarea name="descripcion" class="form-control" placeholder="Nueva descripción" required></textarea>
                <button type="submit" class="btn btn-warning">Modificar Producto</button>
            </form>
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
        .then(res => res.text())
        .then(data => {
            document.getElementById('mod_subcategoria').innerHTML = data;
            document.getElementById('mod_item').innerHTML = '<option value="">Seleccionar ítem</option>';
        });
});

document.getElementById('mod_subcategoria').addEventListener('change', function() {
    fetch('get_items.php?subcategoria_id=' + this.value)
        .then(res => res.text())
        .then(data => {
            document.getElementById('mod_item').innerHTML = data;
        });
});
</script>
</main>
<hr>
<?php footer(); ?>
</body>
</html>
