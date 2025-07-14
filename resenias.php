<?php 
    require 'menu.php';
    require 'footer.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Electro'STORE</title>
    <link rel="icon" href="./img/icon4.png">
    <link rel="stylesheet" href="./css/estilo_v3.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="css2?family=Afacad+Flux:wght@100..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/aos.css">
    <link rel='stylesheet' href="./css/uicons-brands.css">
</head>

<body>
    <?php menu(); ?>
    <hr>
    <section class="resenia">
        <h1 class="text-center">Clientes satisfechos</h1>
        <div class="d-flex justify-content-center mt-4">
            <div id="carouselClientes" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselContenido">
                    <!-- Slides se cargan acá con JS -->
                </div>

                <!-- Botones -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselClientes" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselClientes" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>
    </section>
    <footer>
        <?php footer(); ?>
    </footer>
        <script src="./js/items.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <script src="./js/resenias.js"> </script>
</body>

</html>