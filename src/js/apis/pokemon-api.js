let pokemonContainer;

const initPokemonModule = () => {
    if (!document.getElementById('pokemon-promo-container')) {
        pokemonContainer = document.createElement('div');
        pokemonContainer.id = 'pokemon-promo-container';
        pokemonContainer.className = 'container border bg-light shadow rounded p-4 mb-4';
        
        const title = document.createElement('h2');
        title.className = 'text-center mb-3';
        title.textContent = '¡Promoción del Día!';
        
        const content = document.createElement('div');
        content.id = 'pokemon-content';
        content.className = 'row justify-content-center';
        
        const refreshButton = document.createElement('button');
        refreshButton.id = 'refresh-pokemon';
        refreshButton.className = 'btn btn-primary mt-3';
        refreshButton.textContent = 'Cambiar Promoción';
        refreshButton.addEventListener('click', fetchRandomPokemon);
        
        pokemonContainer.appendChild(title);
        pokemonContainer.appendChild(content);
        pokemonContainer.appendChild(refreshButton);
        
        const firstForm = document.querySelector('form');
        if (firstForm) {
            firstForm.parentNode.insertBefore(pokemonContainer, firstForm);
        } else {
            const mainContainer = document.querySelector('.container');
            if (mainContainer) {
                mainContainer.appendChild(pokemonContainer);
            } else {
                document.body.appendChild(pokemonContainer);
            }
        }
    }
    
    fetchRandomPokemon();
};

const fetchRandomPokemon = async () => {
    try {
        document.getElementById('pokemon-content').innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        
        const randomId = Math.floor(Math.random() * 898) + 1;
        
        const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${randomId}`);
        const data = await response.json();
        
        const discount = (randomId % 21) + 5;
        
        let html = `
            <div class="col-md-6 text-center">
                <img src="${data.sprites.other['official-artwork'].front_default || data.sprites.front_default}" 
                     alt="${data.name}" class="img-fluid" style="max-height: 200px;">
            </div>
            <div class="col-md-6">
                <h3 class="text-capitalize">${data.name}</h3>
                <p class="lead">¡Llévate un <strong>${discount}% de descuento</strong> en todos los productos relacionados con ${data.types.map(type => type.type.name).join(' y ')}!</p>
                <p>Promoción válida solo por hoy.</p>
                <div class="alert alert-success">
                    Código de promoción: <strong>POKE-${data.name.toUpperCase()}-${randomId}</strong>
                </div>
            </div>
        `;

        document.getElementById('pokemon-content').innerHTML = html;
    } catch (error) {
        console.error('Error al cargar el Pokémon:', error);
        document.getElementById('pokemon-content').innerHTML = '<div class="col-12 text-center"><div class="alert alert-danger">No se pudo cargar la promoción. Intente más tarde.</div></div>';
    }
};

document.addEventListener('DOMContentLoaded', initPokemonModule);

window.pokemonAPI = {
    refresh: fetchRandomPokemon
};
