let cart = JSON.parse(localStorage.getItem("cart")) || [];

function addToCart(id) {
    fetch("carrito.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "id=" + id,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.ok) {
        cart.push(id);
        localStorage.setItem("carrito", JSON.stringify(cart));
        updateCartCount();

        const msg = document.getElementById("cart-message");
        msg.classList.add("show");
        setTimeout(() => msg.classList.remove("show"), 2000);

        const cartIcon = document.querySelector(".floating-cart");
        cartIcon.classList.add("bounce");
        setTimeout(() => cartIcon.classList.remove("bounce"), 300);
    }
    });
}
function updateCartCount() {
    document.getElementById("cart-count").innerText = cart.length;
}
document.addEventListener("DOMContentLoaded", updateCartCount);
