<?php
function menu() {
    try {
        $db = new SQLite3('TiendaDB.sqlite');
        $db->enableExceptions(true);
    } catch (Exception $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
?>
<header class="main-header">
    <nav class="navbar navbar-expand-lg navbar-dark container-fluid">
        <!-- Logo + Marca -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="img/icon2.jpg" alt="Logo" class="logo me-2"></a>

        <!-- Botón hamburguesa -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="carrito.php" class="nav-link position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= count($_SESSION['carrito'] ?? []) ?>
                        </span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Productos
                    </a>
                    <ul class="dropdown-menu p-3" style="min-width: 300px;">
                        <li>
                            <label for="categoriaSelect">Categoría:</label>
                            <select id="categoriaSelect" class="form-select">
                                <option value="">Seleccionar</option>
                                <?php
                                $categorias = $db->query("SELECT * FROM categorias");
                                while ($cat = $categorias->fetchArray(SQLITE3_ASSOC)) {
                                    echo '<option value="' . $cat['category_id'] . '">' . htmlspecialchars($cat['nombre']) . '</option>';
                                }
                                ?>
                            </select>
                        </li>
                        <li class="mt-2">
                            <label for="subcategoriaSelect">Subcategoría:</label>
                            <select id="subcategoriaSelect" class="form-select" disabled>
                                <option value="">Seleccionar categoría primero</option>
                            </select>
                        </li>
                        <li class="mt-2">
                            <label for="itemSelect">Ítem:</label>
                            <select id="itemSelect" class="form-select" disabled>
                                <option value="">Seleccionar subcategoría primero</option>
                            </select>
                        </li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#footer">Contacto</a></li>
                <li class="nav-item"><a class="nav-link" href="sucursales.php">Sucursales</a></li>
                <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
            </ul>

            <!-- Buscador -->
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Búsqueda</button>
            </form>
        </div>
    </nav>
</header>
<?php } ?>
