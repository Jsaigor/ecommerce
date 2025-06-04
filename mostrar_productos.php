<?php
$db = new SQLite3('TiendaDB.sqlite');

$condicion = "WHERE cantidad > 0";
if (isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $item_id = intval($_GET['item_id']);
    $condicion .= " AND id = $item_id";
}

$resultado = $db->query("SELECT * FROM productos $condicion");


echo '<div class="container mt-4">';
echo '<div class="row row-cols-1 row-cols-md-3 g-4">';

while ($producto = $resultado->fetchArray(SQLITE3_ASSOC)) {
    $id = htmlspecialchars($producto['id']);
    $item_id = htmlspecialchars($producto['item_id']);
    $nombre = htmlspecialchars($producto['nombre']);
    $descripcion = htmlspecialchars($producto['descripcion']);
    $precio = htmlspecialchars($producto['precio']);

    $resumen = mb_strlen($descripcion) > 144
        ? mb_substr($descripcion, 0, 144) . '... [ver más]'
        : $descripcion;

    $imagenes = [];
    for ($i = 1; $i <= 3; $i++) {
        $campo = 'imagen' . $i;
        if (!empty($producto[$campo])) {
            $imagenes[] = htmlspecialchars($producto[$campo]);
        }
    }
    $cucarda = !empty($producto['imagen4']) ? htmlspecialchars($producto['imagen4']) : null;
    echo '<div class="col">';
    echo '  <div class="card h-100 shadow-sm">';

    if (!empty($imagenes)) {
        $carouselId = "carousel$item_id";
        echo "<div id='$carouselId' class='carousel slide' data-bs-ride='carousel'>";
        echo '  <div class="carousel-inner1">';
        foreach ($imagenes as $index => $img) {
            $active = $index === 0 ? 'active' : '';
            echo "    <div class='carousel-item $active'>";
            echo "    <div class='img-wrapper'>";
            echo "    <img src='$img' class='d-block w-100 img-carrusel' alt='Imagen del producto'>";
            if ($cucarda):
            echo '<img src="' . $cucarda . '" class="cucarda" alt="Cucarda">';
            endif;
            echo "    </div>";
            echo "    </div>";
        }
        echo '  </div>';
        if (count($imagenes) > 1) {
            echo "  <button class='carousel-control-prev' type='button' data-bs-target='#$carouselId' data-bs-slide='prev'>
                    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                    <span class='visually-hidden'>Anterior</span>
                    </button>";
            echo "  <button class='carousel-control-next' type='button' data-bs-target='#$carouselId' data-bs-slide='next'>
                    <span class='carousel-control-next-icon' aria-hidden='true'></span>
                    <span class='visually-hidden'>Siguiente</span>
                    </button>";
        }
        echo '</div>';
        // if ($cucarda && $index === 0): {// Solo sobre la primera imagen 
        // echo '<img src="' . $cucarda . '" class="cucarda" alt="Cucarda">';
        // }endif;
    }

    echo '    <div class="card-body d-flex flex-column">';
    echo "      <h5 class='card-title'>$nombre</h5>";
    echo "      <p class='card-text'>$resumen</p>";
    echo "      <p class='card-text'><strong>$$precio</strong></p>";
    echo "      <a href='productos.php?id=$id' class='btn btn-outline-primary mt-auto'>Ver más</a>";
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}
echo '</div>';
echo '</div>';
?>