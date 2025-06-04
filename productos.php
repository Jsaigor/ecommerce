<?php
session_start();

require 'menu.php';
require 'footer.php';


    //-- Vista de los productos por Item_Id -->
if (isset($_GET['item_id'])) {
    $item_id = (int) $_GET['item_id'];

    try {
        $db = new SQLite3('TiendaDB.sqlite');
        $db->enableExceptions(true);

        $stmtItem = $db->prepare("
            SELECT 
                categorias.nombre AS categoria,
                subcategorias.nombre AS subcategoria,
                items.nombre AS item
            FROM items
            JOIN subcategorias ON items.subcategory_id = subcategorias.subcategory_id
            JOIN categorias ON subcategorias.category_id = categorias.category_id
            WHERE items.item_id = :item_id
        ");
        $stmtItem->bindValue(':item_id', $item_id, SQLITE3_INTEGER);
        $resultado = $stmtItem->execute();
        $ruta = $resultado->fetchArray(SQLITE3_ASSOC);

        // Consulta 2: Obtener productos con ese item_id
        $stmtProd = $db->prepare("SELECT * FROM productos WHERE item_id = :item_id");
        $stmtProd->bindValue(':item_id', $item_id, SQLITE3_INTEGER);
        $result = $stmtProd->execute();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title><?= isset($ruta['item']) ? htmlspecialchars($ruta['item']) : 'Producto' ?></title>
        <link rel="icon" href="./img/icon4.png">
        <link rel="stylesheet" href="./css/estilo_v3.css">
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel='stylesheet' href="./css/uicons-brands.css">
        <script src="./js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
    <?php menu(); ?>
    <main class="main-main">
        <div class="container mt-5">
            <h4 class="mb-4">:<?php if ($ruta) {
            echo ": " . 
                htmlspecialchars($ruta['categoria']) . "\\" .
                htmlspecialchars($ruta['subcategoria']) . "\\" .
                htmlspecialchars($ruta['item']) . "</strong></h4>";
        } else {
            echo "<p>No se encontró la ruta para este item_id.</p>"; } ?></h4>
            <div class="row">
                <?php while ($prod = $result->fetchArray(SQLITE3_ASSOC)) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php
                            for ($i = 1; $i <= 3; $i++) {
                                if (!empty($prod["imagen$i"])) {
                                    echo '<img src="' . htmlspecialchars($prod["imagen$i"]) . '" class="card-img-top" alt="imagen">';
                                    break;
                                }
                            }
                            $cucarda = !empty($prod['imagen4']) ? htmlspecialchars($prod['imagen4']) : null;
                            ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
                                <p class="card-text"><?= nl2br(htmlspecialchars($prod['descripcion'])) ?></p>
                                <p class="text-primary fw-bold">$<?= number_format($prod['precio'], 2) ?></p>
                                <a href="productos.php?id=<?= $prod['id'] ?>" class="btn btn-primary">Ver detalle</a>
                                <form method="POST" action="carrito.php">
                                <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                                <button type="submit" class="btn btn-outline-primary w-100 mt-2 add-to-cart-btn">🛒 Agregar al carrito</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
    <hr>
    <?php footer(); ?>
    </body>
    </html>
    <?php exit;
    
}

// Vista de los productos por Id
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $db = new SQLite3('TiendaDB.sqlite');
        $db->enableExceptions(true);

        // Consulta 1: Obtener datos de la ruta del producto
        $stmtItem = $db->prepare("
        SELECT 
            categorias.nombre AS categoria,
            subcategorias.nombre AS subcategoria,
            items.nombre AS item
        FROM productos, categorias,subcategorias,items
	    WHERE productos.id = :id
	    AND productos.categoria_id = categorias.category_id
	    AND productos.subcategoria_id = subcategorias.subcategory_id
	    AND productos.item_id = items.item_id");

        $stmtItem->bindValue(':id', $id, SQLITE3_INTEGER);
        $resultado = $stmtItem->execute();
        $ruta = $resultado->fetchArray(SQLITE3_ASSOC);

        // Consulta 2: Obtener datos del producto con el id
        $stmtProd = $db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmtProd->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmtProd->execute();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($ruta['item']) ? htmlspecialchars($ruta['item']) : 'Producto' ?></title>
    <link rel="icon" href="./img/icon4.png">
    <link rel="stylesheet" href="./css/estilo_v3.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel='stylesheet' href="./css/uicons-brands.css">
    <script src="./js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php menu(); ?>
<hr>
<main class="main-main">
    <div class="container mt-5">
    <h4 class="mb-4">:<?php if ($ruta) {
        echo ": " . 
                htmlspecialchars($ruta['categoria']) . "\\" .
                htmlspecialchars($ruta['subcategoria']) . "\\" .
                htmlspecialchars($ruta['item']) . "</strong></h4>";
        } else {
            echo "<p>No se encontró información del producto seleccionado.</p>"; } ?></h4>
    <div class="row">
        <?php while ($prod = $result->fetchArray(SQLITE3_ASSOC)) : ?>
<div class="col-12 mb-4">
    <div class="card h-100 shadow-sm">
        <?php
        // Recolectar imágenes válidas
        $imagenes = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($prod["imagen$i"])) {
                $imagenes[] = htmlspecialchars($prod["imagen$i"]);
            }
        }
        $cucarda = !empty($prod['imagen4']) ? htmlspecialchars($prod['imagen4']) : null;
        // Carrusel con más de una imagen
        if (count($imagenes) > 1): 
            $carouselId = "carousel" . $prod['id'];
        ?>
            <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel">
                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <?php foreach ($imagenes as $index => $img): ?>
                        <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>" aria-label="Imagen <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>

                <!-- Imágenes -->
                <div class="carousel-inner2">
                    <?php foreach ($imagenes as $index => $img): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= $img ?>" class="carousel-img zoom-hover" alt="imagen">
                    <?php if ($cucarda): ?>
                    <img src="<?= $cucarda ?>" class="cucarda" alt="Cucarda">
                    <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        <?php elseif (count($imagenes) === 1): ?>
            <img src="<?= $imagenes[0] ?>" class="carousel-img zoom-hover" alt="imagen">
            <img src="<?= $cucarda ?>" class="cucarda" alt="Cucarda">
        <?php endif; ?>

        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($prod['descripcion'])) ?></p>
            <p class="text-primary fw-bold">$<?= number_format($prod['precio'], 2) ?></p>
            <form method="POST" action="carrito.php">
            <input type="hidden" name="id" value="<?= $prod['id'] ?>">
            <button type="submit" class="btn btn-outline-primary w-100 mt-2 add-to-cart-btn">🛒 Agregar al carrito</button>
            </form>
        </div>
    </div>
</div>

    <?php endwhile; ?>
    </div>
</div>
</main>
<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', () => {
    const icono = document.getElementById('carritoIcono');
    icono.classList.add('cart-animate');
    setTimeout(() => icono.classList.remove('cart-animate'), 500);
    });
});
</script>
<hr>
<?php footer(); ?>
</body>
</html>