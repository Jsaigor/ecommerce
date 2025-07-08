<?php require 'menu.php'; 
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
        <h1>Clientes satisfechos</h1>
        <div class="container my-4 text-center"></div>
        <div class="mt-2 d-flex justify-content-center" id="contenido">
        <!--Contenido recuperado con Fetch -->
        </div>
        <button class="btn-danger" onclick="traer()">Siguiente</button>
    </section>
<footer>
<?php footer(); ?>
<script src="./js/bootstrap.bundle.min.js"></script>
<script src="./js/resenias.js"> </script>
</footer>
</body>
</html>