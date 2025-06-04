<?php 
session_start();
include 'init.php'; 
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
<main class=main-main>
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" >
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="./img/carousel3.webp" class="d-block w-100" alt="Imagen de inicio">
    </div>
    <div class="carousel-item">
    <div class="d-flex justify-content-center">
      <iframe width="1000" height="250"
        src="https://www.youtube.com/embed/-cjVyp2Gf_w?loop=1&playlist=-cjVyp2Gf_w&autoplay=1&mute=1&controls=1"
        title="YouTube video player"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
      </iframe>
    </div>
    </div>
    <div class="carousel-item">
      <img src="./img/carousel4.webp" class="d-block w-100" alt="imagen de ofertas">
    </div>
    <div class="carousel-item">
      <img src="./img/carousel5.webp" class="d-block w-100" alt="Imagen de Samsung lavado">
    </div>
    <div class="carousel-item">
      <img src="./img/carousel6.webp" class="d-block w-100" alt="imagen de ofertas en 12 cuotas">
    </div>
  </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlidesOnly" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      </button>
</div>
<hr>
<h2>Productos disponibles</h2>
<?php include 'mostrar_productos.php'; ?>
<script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(id) {
  fetch('carrito.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + id
  })
  .then(response => response.json())
  .then(data => {
    if (data.ok) {
      cart.push(id);
      localStorage.setItem('carrito', JSON.stringify(cart));
      updateCartCount();

      const msg = document.getElementById('cart-message');
      msg.classList.add('show');
      setTimeout(() => msg.classList.remove('show'), 2000);

      const cartIcon = document.querySelector('.floating-cart');
      cartIcon.classList.add('bounce');
      setTimeout(() => cartIcon.classList.remove('bounce'), 300);
    }
  });
}
function updateCartCount() {
  document.getElementById('cart-count').innerText = cart.length;
}
document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
<script src="./js/bootstrap.bundle.min.js">
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const categoriaSelect = document.getElementById('categoriaSelect');
  const subcategoriaSelect = document.getElementById('subcategoriaSelect');
  const itemSelect = document.getElementById('itemSelect');

  categoriaSelect.addEventListener('change', () => {
    const categoriaId = categoriaSelect.value;
    subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
    itemSelect.innerHTML = '<option value="">Seleccionar subcategoría primero</option>';
    itemSelect.disabled = true;

    if (categoriaId) {
      fetch(`get_subcategorias.php?categoria_id=${categoriaId}`)
        .then(res => res.json())
        .then(data => {
          subcategoriaSelect.innerHTML = '<option value="">Seleccionar</option>';
          data.forEach(sub => {
            subcategoriaSelect.innerHTML += `<option value="${sub.subcategory_id}">${sub.nombre}</option>`;
          });
          subcategoriaSelect.disabled = false;
        });
    }
  });

  subcategoriaSelect.addEventListener('change', () => {
    const categoriaId = categoriaSelect.value;
    const subcategoriaId = subcategoriaSelect.value;
    itemSelect.innerHTML = '<option value="">Cargando...</option>';

    if (categoriaId && subcategoriaId) {
      fetch(`get_items.php?categoria_id=${categoriaId}&subcategoria_id=${subcategoriaId}`)
        .then(res => res.json())
        .then(data => {
          itemSelect.innerHTML = '<option value="">Seleccionar</option>';
          data.forEach(item => {
            itemSelect.innerHTML += `<option value="${item.item_id}">${item.nombre}</option>`;
          });
          itemSelect.disabled = false;
        });
    }
  });

  itemSelect.addEventListener('change', () => {
    const itemId = itemSelect.value;
    if (itemId) {
      window.location.href = `productos.php?item_id=${itemId}`;
    }
  });
});
</script>
</main>
<hr>
<?php footer(); ?>
</body>
</html>