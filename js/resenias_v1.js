function traer() {
    fetch('https://randomuser.me/api/')
        .then(res => res.json())
        .then(data => {
            const usuario = data.results[0];
            const nombre = `${usuario.name.first} ${usuario.name.last}`;
            const email = usuario.email;
            const foto = usuario.picture.large;
            const pais = usuario.location.country;

            const html = `
                <div class="card text-center" style="width: 18rem;">
                    <img src="${foto}" class="card-img-top rounded-circle mt-3 mx-auto" alt="${nombre}" style="width: 120px; height: 120px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${nombre}</h5>
                        <p class="card-text">📧 ${email}</p>
                        <p class="card-text">🌍 ${pais}</p>
                        <hr>
                        <p class="card-text fst-italic">"Excelente servicio, ¡volvería a comprar sin dudarlo!"</p>
                    </div>
                </div>
            `;
            document.getElementById('contenido').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('contenido').innerHTML = "<p>Error al cargar usuario.</p>";
            console.error(err);
        });
}
window.onload = traer;