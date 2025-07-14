<?php
session_start();

require "menu.php";
require 'footer.php';
// Conectar a la base de datos
$db = new SQLite3('TiendaDB.sqlite');

// Consultar las sucursales
$resultado = $db->query("SELECT * FROM sucursales");

// Crear un array con los datos
$sucursales = [];
while ($fila = $resultado->fetchArray(SQLITE3_ASSOC)) {
  $sucursales[] = $fila;
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sucursales</title>
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
      <div class="listado">
        <h3>Listado de Sucursales</h3>
        <ul>
          <?php foreach ($sucursales as $suc): ?>
            <li>
              <strong><?php echo htmlspecialchars($suc['direccion']); ?></strong>
              <?php echo htmlspecialchars($suc['ciudad'] . ', ' . $suc['provincia']); ?><br>
              CP: <?php echo htmlspecialchars($suc['codigo_postal']); ?><br>
              Tel: <?php echo htmlspecialchars($suc['telefono']); ?>

            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div id="map"></div>
    </div>
    <script>
      function initMap() {
        // Crear el mapa centrado en Buenos Aires
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: {
            lat: -34.6037,
            lng: -58.3816
          }
        });

        // Sucursales desde PHP
        var sucursales = <?php echo json_encode($sucursales); ?>;

        // Agregar marcadores
        sucursales.forEach(function(s) {
          if (s.latitud && s.longitud) {
            var marker = new google.maps.Marker({
              position: {
                lat: parseFloat(s.latitud),
                lng: parseFloat(s.longitud)
              },
              map: map
            });

            var infoWindow = new google.maps.InfoWindow({
              content: "<b>Dirección:</b> " + s.direccion + "<br>" +
                "<b>Teléfono:</b> " + s.telefono + "<br>" +
                "<b>Código Postal:</b> " + s.codigo_postal
            });

            marker.addListener('click', function() {
              infoWindow.open(map, marker);
            });
          }
        });
      }
    </script>

    <!-- Cargamos el script de Google Maps con tu API Key -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbI7X0U7EINMmkc-pvdgYNdao1557EUaQ&callback=initMap&loading=async&libraries=marker" async defer></script>
    <script src="./js/items.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
  </main>
  <hr>
  <?php footer(); ?>
</body>

</html>