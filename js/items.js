document.addEventListener("DOMContentLoaded", function () {
    const categoriaSelect = document.getElementById("categoriaSelect");
    const subcategoriaSelect = document.getElementById("subcategoriaSelect");
    const itemSelect = document.getElementById("itemSelect");

    categoriaSelect.addEventListener("change", () => {
    const categoriaId = categoriaSelect.value;
    subcategoriaSelect.innerHTML = '<option value="">Cargando...</option>';
    itemSelect.innerHTML =
        '<option value="">Seleccionar subcategoría primero</option>';
    itemSelect.disabled = true;

    if (categoriaId) {
        fetch(`get_subcategorias.php?categoria_id=${categoriaId}`)
        .then((res) => res.json())
        .then((data) => {
            subcategoriaSelect.innerHTML =
            '<option value="">Seleccionar</option>';
            data.forEach((sub) => {
            subcategoriaSelect.innerHTML += `<option value="${sub.subcategory_id}">${sub.nombre}</option>`;
            });
        subcategoriaSelect.disabled = false;
        });
    }
    });

    subcategoriaSelect.addEventListener("change", () => {
    const categoriaId = categoriaSelect.value;
    const subcategoriaId = subcategoriaSelect.value;
    itemSelect.innerHTML = '<option value="">Cargando...</option>';

    if (categoriaId && subcategoriaId) {
        fetch(
        `get_items.php?categoria_id=${categoriaId}&subcategoria_id=${subcategoriaId}`
        )
        .then((res) => res.json())
        .then((data) => {
        itemSelect.innerHTML = '<option value="">Seleccionar</option>';
        data.forEach((item) => {
            itemSelect.innerHTML += `<option value="${item.item_id}">${item.nombre}</option>`;
        });
        itemSelect.disabled = false;
        });
    }
    });

    itemSelect.addEventListener("change", () => {
    const itemId = itemSelect.value;
    if (itemId) {
        window.location.href = `productos.php?item_id=${itemId}`;
    }
    });
});
