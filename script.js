document.getElementById('buscar').addEventListener('click', () => {
    const nombre = document.getElementById('nombre').value;
    if (!nombre) {
        alert('Por favor ingresa un nombre de Pokémon');
        return;
    }

    // Envía el nombre por POST al PHP
    fetch('pokeapi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'nombre=' + encodeURIComponent(nombre)
    })
    .then(res => res.json())
    .then(data => {
        const resultado = document.getElementById('resultado');
        
        if (data.error) {
            resultado.innerHTML = `<p class="error">${data.mensaje}</p>`;
        } else {
            resultado.innerHTML = `
                <div class="pokemon-card">
                    <h3>${data.nombre}</h3>
                    <img src="${data.imagen}" alt="${data.nombre}">
                    <p>Altura: ${data.altura} m</p>
                    <p>Peso: ${data.peso} kg</p>
                    <p>Tipos: ${data.tipos.join(', ')}</p>
                </div>
            `;
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById('resultado').innerHTML = '<p class="error">Error al buscar el Pokémon</p>';
    });
});


