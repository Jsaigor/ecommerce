
function traer(n = 5) {
    fetch(`https://randomuser.me/api/?results=${n}`)
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById("carouselContenido");
            contenedor.innerHTML = ""; // Limpiar carrusel

            data.results.forEach((usuario, index) => {
                const nombre = `${usuario.name.first} ${usuario.name.last}`;
                const email = usuario.email;
                const pais = usuario.location.country;
                const foto = usuario.picture.large;

                const activo = index === 0 ? "active" : "";

                const slide = `
                    <div class="carousel-item ${activo}">
                        <div class="slide-card d-flex flex-column align-items-center">
                            <img src="${foto}" class="rounded-circle mb-3" alt="${nombre}" style="width: 120px; height: 120px; object-fit: cover;">
                            <h5>${nombre}</h5>
                            <p class="mb-1">📧 ${email}</p>
                            <p class="mb-1">🌍 ${pais}</p>
                            <p class="fst-italic">"Excelente atención, ¡muy recomendable!"</p>
                        </div>
                    </div>
                `;
                contenedor.innerHTML += slide;
            });
        })
        .catch(err => {
            document.getElementById("carouselContenido").innerHTML = "<p>Error al cargar clientes.</p>";
            console.error(err);
        });
};

// Llamada al cargar la página
window.onload = () => traer();