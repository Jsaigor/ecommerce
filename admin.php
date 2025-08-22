
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
        <p class="form-text">Usa los menús para encontrar los productos que deseas eliminar.</p>
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="elim_categoria" class="form-label">Categoría</label>
                <select id="elim_categoria" class="form-select">
                    <option value="">Seleccionar...</option>
                    <?php
                    // Reutilizamos la consulta de categorías
                    $categorias_elim = $db->query("SELECT * FROM categorias ORDER BY nombre");
                    while ($c = $categorias_elim->fetchArray(SQLITE3_ASSOC)):
                    ?>
                        <option value="<?= htmlspecialchars($c['category_id']) ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="elim_subcategoria" class="form-label">Subcategoría</label>
                <select id="elim_subcategoria" class="form-select">
                    <option value="">Seleccionar...</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="elim_item" class="form-label">Ítem</label>
                <select id="elim_item" class="form-select">
                    <option value="">Seleccionar...</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" id="btn-buscar-para-eliminar" class="btn btn-info w-100">Buscar Productos</button>
            </div>
        </div>
        <hr>
        <div id="mensaje-eliminacion"></div>
        <div id="resultados-eliminacion">
            <p class="text-center text-muted">Aquí aparecerá la lista de productos a eliminar.</p>
        </div>
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

// --- CÓDIGO NUEVO PARA LA SECCIÓN DE ELIMINAR PRODUCTO ---

const elimCategoriaSelect = document.getElementById('elim_categoria');
const elimSubcategoriaSelect = document.getElementById('elim_subcategoria');
const elimItemSelect = document.getElementById('elim_item');
const resultadosEliminacionDiv = document.getElementById('resultados-eliminacion');
const mensajeEliminacionDiv = document.getElementById('mensaje-eliminacion');

// Lógica para los menús desplegables de la sección Eliminar
elimCategoriaSelect.addEventListener('change', function() {
    const categoriaId = this.value;
    elimSubcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
    elimItemSelect.innerHTML = '<option value="">Seleccionar ítem</option>';
    if (categoriaId) {
        fetch(`get_subcategorias.php?categoria_id=${categoriaId}`)
            .then(res => res.json()).then(data => {
                let options = '<option value="">Seleccionar subcategoría</option>';
                data.forEach(sc => { options += `<option value="${sc.subcategory_id}">${sc.nombre}</option>`; });
                elimSubcategoriaSelect.innerHTML = options;
            });
    } else {
        elimSubcategoriaSelect.innerHTML = '<option value="">Seleccionar subcategoría</option>';
    }
});

elimSubcategoriaSelect.addEventListener('change', function() {
    const subcategoriaId = this.value;
    elimItemSelect.innerHTML = '<option value="">Cargando...</option>';
    if (subcategoriaId) {
        fetch(`get_items.php?subcategoria_id=${subcategoriaId}`)
            .then(res => res.json()).then(data => {
                let options = '<option value="">Seleccionar ítem</option>';
                data.forEach(p => { options += `<option value="${p.item_id}">${p.nombre}</option>`; });
                elimItemSelect.innerHTML = options;
            });
    } else {
        elimItemSelect.innerHTML = '<option value="">Seleccionar ítem</option>';
    }
});

// Lógica para el botón de BÚSQUEDA de productos a eliminar
document.getElementById('btn-buscar-para-eliminar').addEventListener('click', function() {
    const itemId = elimItemSelect.value;
    if (!itemId) {
        alert('Por favor, seleccione un ítem para buscar.');
        return;
    }
    resultadosEliminacionDiv.innerHTML = '<p class="text-center">Buscando...</p>';
    fetch(`buscar_productos_para_eliminar.php?item_id=${itemId}`)
        .then(response => response.text())
        .then(html => {
            resultadosEliminacionDiv.innerHTML = html;
        });
});

// Lógica para la ELIMINACIÓN con AJAX (sin recargar página)
resultadosEliminacionDiv.addEventListener('click', function(event) {
    // Se activa solo si se hace clic en un botón con la clase 'btn-eliminar'
    if (event.target.classList.contains('btn-eliminar')) {
        const productoId = event.target.dataset.id;
        const productoNombre = event.target.dataset.nombre;
        
        if (confirm(`¿Estás seguro de que deseas eliminar el producto "${productoNombre}" (ID: ${productoId})?`)) {
            // Usamos POST para la eliminación
            fetch('eliminar_producto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${productoId}`
            })
            .then(response => response.json()) // Esperamos una respuesta JSON del servidor
            // --- CÓDIGO NUEVO ---
            .then(data => {
                const modalEl = document.getElementById('notificacionModal');
                const modal = new bootstrap.Modal(modalEl);
                const modalMensaje = document.getElementById('modalMensaje');

                if (data.status === 'success') {
                // Eliminar el elemento de la lista visualmente
                document.getElementById(`producto-item-${productoId}`).remove();
        
                // Preparar y mostrar el modal de éxito
                modalMensaje.textContent = "Producto eliminado correctamente.";
                modal.show();
            } else {
            // Si hay un error, lo mostramos en un modal también (opcional pero consistente)
            modalMensaje.textContent = data.message || "Ocurrió un error al eliminar el producto.";
            // podrías cambiar el título a "Error" si quisieras
            modal.show();
            }
        });
        }
    }
});

// --- CÓDIGO NUEVO PARA MOSTRAR EL MODAL AL CARGAR LA PÁGINA ---
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const action = urlParams.get('action');

    if (status === 'success') {
        const modalEl = document.getElementById('notificacionModal');
        const modal = new bootstrap.Modal(modalEl);
        const modalMensaje = document.getElementById('modalMensaje');
        
        let mensaje = '';
        if (action === 'add') {
            mensaje = 'Producto agregado correctamente.';
        } else if (action === 'modify') {
            mensaje = 'Producto modificado correctamente.';
        }
        
        if (mensaje) {
            modalMensaje.textContent = mensaje;
            modal.show();
        }
        
        // Limpia la URL para que el modal no reaparezca si se recarga la página
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

</script>
<script src="./js/items.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
</main>
<hr>
<?php footer(); ?>

<div class="modal fade" id="notificacionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalTitulo"><i class="fas fa-check-circle text-success me-2"></i>Éxito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <p id="modalMensaje">La operación se realizó correctamente.</p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
        </div>
    </div>
    </div>
</div>

</body>
</html>
