/**
 * pokemon-api.js - Integración de PokeAPI para promociones diarias
 * Este archivo implementa la funcionalidad para mostrar un "Pokémon del día"
 * como parte de una promoción para el sistema de farmacia.
 */

// Contenedor donde se mostrará la promoción
let pokemonContainer;

// Inicializar el módulo de Pokémon
const initPokemonModule = () => {
    // Crear el contenedor de la promoción si no existe
    if (!document.getElementById('pokemon-promo-container')) {
        pokemonContainer = document.createElement('div');
        pokemonContainer.id = 'pokemon-promo-container';
        pokemonContainer.className = 'container border bg-light shadow rounded p-4 mb-4';
        
        // Titulo de la sección
        const title = document.createElement('h2');
        title.className = 'text-center mb-3';
        title.textContent = '¡Promoción del Día!';
        
        // Contenido de la promoción
        const content = document.createElement('div');
        content.id = 'pokemon-content';
        content.className = 'row justify-content-center';
        
        // Botón para actualizar la promoción
        const refreshButton = document.createElement('button');
        refreshButton.id = 'refresh-pokemon';
        refreshButton.className = 'btn btn-primary mt-3';
        refreshButton.textContent = 'Cambiar Promoción';
        refreshButton.addEventListener('click', fetchRandomPokemon);
        
        // Ensamblar la estructura del contenedor
        pokemonContainer.appendChild(title);
        pokemonContainer.appendChild(content);
        pokemonContainer.appendChild(refreshButton);
        
        // Insertar el contenedor antes del primer formulario
        const firstForm = document.querySelector('form');
        if (firstForm) {
            firstForm.parentNode.insertBefore(pokemonContainer, firstForm);
        } else {
            // Si no hay formulario, agregar al final del contenedor principal
            const mainContainer = document.querySelector('.container');
            if (mainContainer) {
                mainContainer.appendChild(pokemonContainer);
            } else {
                // Si no hay contenedor principal, agregar al body
                document.body.appendChild(pokemonContainer);
            }
        }
    }
    
    // Cargar un Pokémon aleatorio al iniciar
    fetchRandomPokemon();
};

// Obtener un Pokémon aleatorio
const fetchRandomPokemon = async () => {
    try {
        // Mostrar un loader mientras se carga
        document.getElementById('pokemon-content').innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        
        // Generar un ID aleatorio (existen aproximadamente 898 Pokémon)
        const randomId = Math.floor(Math.random() * 898) + 1;
        
        // Realizar la petición a la API
        const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${randomId}`);
        const data = await response.json();
        
        // Calcular un descuento aleatorio basado en el ID del Pokémon (entre 5% y 25%)
        const discount = (randomId % 21) + 5;
        
        // Preparar el HTML con la promoción
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
        
        // Actualizar el contenido
        document.getElementById('pokemon-content').innerHTML = html;
    } catch (error) {
        console.error('Error al cargar el Pokémon:', error);
        document.getElementById('pokemon-content').innerHTML = '<div class="col-12 text-center"><div class="alert alert-danger">No se pudo cargar la promoción. Intente más tarde.</div></div>';
    }
};

// Iniciar el módulo cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initPokemonModule);

// Exponer funciones públicas
window.pokemonAPI = {
    refresh: fetchRandomPokemon
};s