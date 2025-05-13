
/**
 * covid19-api.js - Integración de la API de COVID-19 
 * Este archivo implementa la funcionalidad para mostrar estadísticas 
 * de COVID-19 relevantes para farmaceuticos.
 */

// Contenedor principal para el dashboard de COVID-19
let covidContainer;

// Inicializar el módulo de COVID-19
const initCovidModule = () => {
    // Crear el contenedor para el dashboard si no existe
    if (!document.getElementById('covid-dashboard-container')) {
        covidContainer = document.createElement('div');
        covidContainer.id = 'covid-dashboard-container';
        covidContainer.className = 'container border bg-light shadow rounded p-4 mb-4';
        
        // Título de la sección
        const title = document.createElement('h2');
        title.className = 'text-center mb-3';
        title.textContent = 'Dashboard COVID-19 para Farmacéuticos';
        
        // Descripción
        const description = document.createElement('p');
        description.className = 'text-center mb-4';
        description.textContent = 'Información actualizada sobre COVID-19 relevante para su farmacia.';
        
        // Selector de país
        const countrySelector = document.createElement('div');
        countrySelector.className = 'row justify-content-center mb-4';
        countrySelector.innerHTML = `
            <div class="col-md-6">
                <label for="covid-country-select" class="form-label">Seleccione un país:</label>
                <div class="input-group">
                    <select id="covid-country-select" class="form-select">
                        <option value="guatemala" selected>Guatemala</option>
                        <option value="mexico">México</option>
                        <option value="colombia">Colombia</option>
                        <option value="argentina">Argentina</option>
                        <option value="spain">España</option>
                        <option value="usa">Estados Unidos</option>
                        <option value="global">Global</option>
                    </select>
                    <button class="btn btn-primary" id="covid-fetch-btn" type="button">
                        <i class="bi bi-arrow-repeat"></i> Actualizar
                    </button>
                </div>
            </div>
        `;
        
        // Panel de estadísticas principales
        const statsPanel = document.createElement('div');
        statsPanel.id = 'covid-stats-panel';
        statsPanel.className = 'row mb-4';
        statsPanel.innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        
        // Gráficos
        const chartsPanel = document.createElement('div');
        chartsPanel.id = 'covid-charts-panel';
        chartsPanel.className = 'row mb-3';
        chartsPanel.innerHTML = `
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title text-center">Casos por Día</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="covid-cases-chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title text-center">Distribución de Productos COVID</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="covid-products-chart"></canvas>
                    </div>
                </div>
            </div>
        `;
        
        // Recomendaciones para farmacéuticos
        const recommendationsPanel = document.createElement('div');
        recommendationsPanel.className = 'card mt-3';
        recommendationsPanel.innerHTML = `
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Recomendaciones para Farmacéuticos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-check-circle-fill text-success"></i> Productos Recomendados</h6>
                        <ul id="covid-products-list">
                            <li>Pruebas rápidas de antígenos</li>
                            <li>Máscarillas N95 y quirúrgicas</li>
                            <li>Gel antibacterial</li>
                            <li>Vitamina C y D</li>
                            <li>Oxímetros de pulso</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-info-circle-fill text-primary"></i> Protocolos</h6>
                        <ul id="covid-protocols-list">
                            <li>Mantener ventilación adecuada en la farmacia</li>
                            <li>Usar barreras físicas en el área de atención</li>
                            <li>Ofrecer servicio a domicilio para pacientes de riesgo</li>
                            <li>Verificar stock de medicamentos para tratamiento sintomático</li>
                            <li>Proporcionar información actualizada sobre vacunación</li>
                        </ul>
                    </div>
                </div>
            </div>
        `;
        
        // Ensamblar la estructura del contenedor
        covidContainer.appendChild(title);
        covidContainer.appendChild(description);
        covidContainer.appendChild(countrySelector);
        covidContainer.appendChild(statsPanel);
        covidContainer.appendChild(chartsPanel);
        covidContainer.appendChild(recommendationsPanel);
        
        // Insertar el contenedor en el DOM
        // Lo colocamos al principio de la página para mayor visibilidad
        const mainContainer = document.querySelector('.container');
        if (mainContainer) {
            mainContainer.parentNode.insertBefore(covidContainer, mainContainer);
        } else {
            document.body.prepend(covidContainer);
        }
        
        // Agregar listener al botón de actualizar
        document.getElementById('covid-fetch-btn').addEventListener('click', fetchCovidData);
        
        // Cargar datos iniciales
        fetchCovidData();
    }
};

// Función para obtener datos de COVID-19
const fetchCovidData = async () => {
    try {
        // Mostrar spinner mientras carga
        document.getElementById('covid-stats-panel').innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        
        // Obtener el país seleccionado
        const countrySelector = document.getElementById('covid-country-select');
        const selectedCountry = countrySelector ? countrySelector.value : 'guatemala';
        
        // Mapeo de los valores del select a los códigos de país de la API
        const countryMap = {
            'guatemala': 'guatemala',
            'mexico': 'mexico',
            'colombia': 'colombia',
            'argentina': 'argentina',
            'spain': 'spain',
            'usa': 'us',
            'global': 'all'
        };
        
        const countryCode = countryMap[selectedCountry] || 'guatemala';
        
        // Como usamos una API simulada, obtenemos datos generales
        // En un caso real, utilizaríamos la API de Covid19
        let responseUrl = 'https://jsonplaceholder.typicode.com/users';
        if (countryCode === 'all') {
            responseUrl = 'https://jsonplaceholder.typicode.com/users';
        } else {
            responseUrl = `https://jsonplaceholder.typicode.com/users?id=${Math.ceil(Math.random() * 10)}`;
        }
        
        const response = await fetch(responseUrl);
        const data = await response.json();
        
        // Transformar los datos para simular estadísticas COVID
        // En un caso real, usaríamos los datos reales de la API
        const transformedData = transformCovidData(data, countryCode);
        
        // Actualizar el panel de estadísticas
        updateStatsPanel(transformedData);
        
        // Actualizar los gráficos
        updateCharts(transformedData);
        
        // Actualizar recomendaciones basadas en los datos
        updateRecommendations(transformedData);
        
    } catch (error) {
        console.error('Error al obtener datos de COVID-19:', error);
        document.getElementById('covid-stats-panel').innerHTML = `
            <div class="col-12 text-center">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Error al conectar con la API de COVID-19. Intente más tarde.
                </div>
            </div>
        `;
    }
};

// Transformar datos para simular estadísticas COVID
const transformCovidData = (data, countryCode) => {
    // Generamos datos aleatorios basados en el tamaño del conjunto de datos
    const baseMultiplier = data.length * (Math.floor(Math.random() * 100) + 1);
    
    // Número aleatorio entre 50 y 5000 para casos confirmados
    const confirmedCases = (baseMultiplier * 1000) + Math.floor(Math.random() * 4950) + 50;
    
    // Número aleatorio entre 1 y 100 para casos activos (porcentaje)
    const activePercentage = Math.floor(Math.random() * 20) + 1;
    const activeCases = Math.floor(confirmedCases * (activePercentage / 100));
    
    // Número aleatorio entre 0.5 y 5 para tasa de letalidad (porcentaje)
    const fatalityRate = (Math.floor(Math.random() * 45) + 5) / 10;
    const deaths = Math.floor(confirmedCases * (fatalityRate / 100));
    
    // Número aleatorio entre 70 y 99 para tasa de recuperación (porcentaje)
    const recoveryRate = Math.floor(Math.random() * 29) + 70;
    const recovered = Math.floor(confirmedCases * (recoveryRate / 100));
    
    // Tasas de vacunación
    const vaccinationRate = Math.floor(Math.random() * 60) + 40;
    
    // Generar datos para el gráfico de casos
    const casesLast14Days = [];
    for (let i = 0; i < 14; i++) {
        casesLast14Days.push({
            day: i + 1,
            cases: Math.floor(Math.random() * 1000) + 100,
            recovered: Math.floor(Math.random() * 800) + 50,
            deaths: Math.floor(Math.random() * 50)
        });
    }
    
    // Generar datos para el gráfico de productos
    const productDistribution = [
        { name: 'Pruebas', percentage: Math.floor(Math.random() * 30) + 20 },
        { name: 'Mascarillas', percentage: Math.floor(Math.random() * 25) + 15 },
        { name: 'Gel', percentage: Math.floor(Math.random() * 20) + 10 },
        { name: 'Vitaminas', percentage: Math.floor(Math.random() * 15) + 10 },
        { name: 'Oxímetros', percentage: Math.floor(Math.random() * 10) + 5 },
        { name: 'Otros', percentage: Math.floor(Math.random() * 10) + 5 }
    ];
    
    // Ajustar para que sumen 100%
    const sum = productDistribution.reduce((acc, product) => acc + product.percentage, 0);
    if (sum !== 100) {
        productDistribution[productDistribution.length - 1].percentage += (100 - sum);
    }
    
    return {
        country: countryCode,
        confirmed: confirmedCases,
        active: activeCases,
        deaths: deaths,
        recovered: recovered,
        fatalityRate: fatalityRate,
        recoveryRate: recoveryRate,
        vaccinationRate: vaccinationRate,
        casesLast14Days: casesLast14Days,
        productDistribution: productDistribution,
        lastUpdated: new Date().toLocaleString()
    };
};

// Actualizar el panel de estadísticas
const updateStatsPanel = (data) => {
    const statsPanel = document.getElementById('covid-stats-panel');
    
    if (!statsPanel) return;
    
    const countryNames = {
        'guatemala': 'Guatemala',
        'mexico': 'México',
        'colombia': 'Colombia',
        'argentina': 'Argentina',
        'spain': 'España',
        'us': 'Estados Unidos',
        'all': 'Global'
    };
    
    const countryName = countryNames[data.country] || 'Guatemala';
    
    statsPanel.innerHTML = `
        <div class="col-12 mb-3">
            <div class="alert alert-info text-center">
                <strong>Datos para ${countryName}</strong> - Última actualización: ${data.lastUpdated}
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Casos Confirmados</h5>
                    <p class="display-6">${data.confirmed.toLocaleString()}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Casos Activos</h5>
                    <p class="display-6">${data.active.toLocaleString()}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Fallecidos</h5>
                    <p class="display-6">${data.deaths.toLocaleString()}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Recuperados</h5>
                    <p class="display-6">${data.recovered.toLocaleString()}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-center">Tasa de Letalidad</h5>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: ${data.fatalityRate}%;" aria-valuenow="${data.fatalityRate}" aria-valuemin="0" aria-valuemax="100">${data.fatalityRate}%</div>
                    </div>
                    <small class="text-muted">Porcentaje de fallecidos del total de casos confirmados.</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-center">Tasa de Recuperación</h5>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ${data.recoveryRate}%;" aria-valuenow="${data.recoveryRate}" aria-valuemin="0" aria-valuemax="100">${data.recoveryRate}%</div>
                    </div>
                    <small class="text-muted">Porcentaje de recuperados del total de casos confirmados.</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-center">Tasa de Vacunación</h5>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: ${data.vaccinationRate}%;" aria-valuenow="${data.vaccinationRate}" aria-valuemin="0" aria-valuemax="100">${data.vaccinationRate}%</div>
                    </div>
                    <small class="text-muted">Porcentaje de población con al menos una dosis de vacuna.</small>
                </div>
            </div>
        </div>
    `;
};

// Actualizar los gráficos
const updateCharts = (data) => {
    // Verificar si Chart.js está disponible
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no está disponible. Los gráficos no se mostrarán.');
        return;
    }
    
    // Gráfico de casos
    const casesCanvas = document.getElementById('covid-cases-chart');
    if (casesCanvas) {
        // Destruir gráfico anterior si existe
        if (window.casesChart) {
            window.casesChart.destroy();
        }
        
        const ctx = casesCanvas.getContext('2d');
        window.casesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.casesLast14Days.map(day => `Día ${day.day}`),
                datasets: [
                    {
                        label: 'Nuevos Casos',
                        data: data.casesLast14Days.map(day => day.cases),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Recuperados',
                        data: data.casesLast14Days.map(day => day.recovered),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Fallecidos',
                        data: data.casesLast14Days.map(day => day.deaths),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Tendencia de los últimos 14 días'
                    }
                }
            }
        });
    }
    
    // Gráfico de productos
    const productsCanvas = document.getElementById('covid-products-chart');
    if (productsCanvas) {
        // Destruir gráfico anterior si existe
        if (window.productsChart) {
            window.productsChart.destroy();
        }
        
        const ctx = productsCanvas.getContext('2d');
        window.productsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.productDistribution.map(product => product.name),
                datasets: [{
                    data: data.productDistribution.map(product => product.percentage),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Demanda por tipo de producto COVID-19'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                }
            }
        });
    }
};

// Actualizar recomendaciones basadas en los datos
const updateRecommendations = (data) => {
    // Para esta demo, simplemente actualizamos con recomendaciones genéricas
    // En un caso real, podríamos personalizar las recomendaciones basadas en los datos
    
    // Productos recomendados basados en la distribución
    const productsList = document.getElementById('covid-products-list');
    if (productsList) {
        // Ordenar productos por porcentaje (de mayor a menor)
        const sortedProducts = [...data.productDistribution].sort((a, b) => b.percentage - a.percentage);
        
        // Generar recomendaciones basadas en los productos más demandados
        let productsHtml = '';
        sortedProducts.forEach((product, index) => {
            if (index < 5) { // Mostrar solo los 5 más importantes
                const emphasis = index === 0 ? ' class="text-danger fw-bold"' : '';
                productsHtml += `<li${emphasis}>${product.name} (${product.percentage}% de demanda)</li>`;
            }
        });
        
        // Agregar recomendaciones adicionales
        productsHtml += '<li>Vitaminas y suplementos respiratorios</li>';
        
        if (data.fatalityRate > 3) {
            productsHtml += '<li class="text-danger">Equipos de oxigenación - ¡Alta prioridad!</li>';
        }
        
        productsList.innerHTML = productsHtml;
    }
    
    // Actualizar protocolos basados en las estadísticas
    const protocolsList = document.getElementById('covid-protocols-list');
    if (protocolsList) {
        let protocolsHtml = '';
        
        // Recomendaciones básicas
        protocolsHtml += '<li>Mantener ventilación adecuada en la farmacia</li>';
        protocolsHtml += '<li>Usar barreras físicas en el área de atención</li>';
        
        // Recomendaciones basadas en casos activos
        if (data.active > 1000) {
            protocolsHtml += '<li class="text-danger fw-bold">Implementar medidas estrictas de distanciamiento</li>';
            protocolsHtml += '<li>Ofrecer servicio a domicilio sin costo adicional para pacientes de riesgo</li>';
        } else {
            protocolsHtml += '<li>Mantener medidas de distanciamiento básicas</li>';
            protocolsHtml += '<li>Ofrecer servicio a domicilio para pacientes de riesgo</li>';
        }
        
        // Recomendaciones basadas en vacunación
        if (data.vaccinationRate < 50) {
            protocolsHtml += '<li class="text-danger">Priorizar información sobre centros de vacunación cercanos</li>';
        } else {
            protocolsHtml += '<li>Proporcionar información actualizada sobre refuerzos de vacunas</li>';
        }
        
        // Recomendaciones generales
        protocolsHtml += '<li>Verificar stock de medicamentos para tratamiento sintomático</li>';
        
        protocolsList.innerHTML = protocolsHtml;
    }
};

// Iniciar el módulo cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initCovidModule);

// Exponer funciones públicas
window.covidAPI = {
    refresh: fetchCovidData,
    toggleDashboard: () => {
        const dashboard = document.getElementById('covid-dashboard-container');
        if (dashboard) {
            if (dashboard.classList.contains('d-none')) {
                dashboard.classList.remove('d-none');
            } else {
                dashboard.classList.add('d-none');
            }
        }
    }
};