let medicamentosAPIContainer;


const initMedicamentosModule = () => {

    if (window.location.href.includes('medicamentos')) {

        if (!document.getElementById('medicamentos-api-container')) {
            medicamentosAPIContainer = document.createElement('div');
            medicamentosAPIContainer.id = 'medicamentos-api-container';
            medicamentosAPIContainer.className = 'container border bg-light shadow rounded p-4 mb-4';
          
            const title = document.createElement('h2');
            title.className = 'text-center mb-3';
            title.textContent = 'Catálogo Externo de Medicamentos';
            
            const searchDiv = document.createElement('div');
            searchDiv.className = 'row justify-content-center mb-3';
            searchDiv.innerHTML = `
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" id="search-medicamento" class="form-control" placeholder="Buscar medicamento externo...">
                        <button class="btn btn-primary" id="btn-search-medicamento" type="button">Buscar</button>
                    </div>
                </div>
            `;

            const content = document.createElement('div');
            content.id = 'medicamentos-content';
            content.className = 'row justify-content-center';
            content.innerHTML = '<div class="col-12 text-center"><p>Ingrese un término de búsqueda para consultar el catálogo externo.</p></div>';
 
            medicamentosAPIContainer.appendChild(title);
            medicamentosAPIContainer.appendChild(searchDiv);
            medicamentosAPIContainer.appendChild(content);

            const divTabla = document.getElementById('divTabla');
            if (divTabla) {
                divTabla.parentNode.insertBefore(medicamentosAPIContainer, divTabla);
            } else {
                const mainContainer = document.querySelector('.container');
                if (mainContainer) {
                    mainContainer.parentNode.appendChild(medicamentosAPIContainer);
                } else {
                    document.body.appendChild(medicamentosAPIContainer);
                }
            }

            document.getElementById('btn-search-medicamento').addEventListener('click', searchMedicamentos);
            document.getElementById('search-medicamento').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    searchMedicamentos();
                }
            });
            addImportButtons();
        }
    }
};

const searchMedicamentos = async () => {
    try {
        const searchTerm = document.getElementById('search-medicamento').value.trim();
        
        if (!searchTerm) {
            document.getElementById('medicamentos-content').innerHTML = '<div class="col-12 text-center"><div class="alert alert-warning">Por favor ingrese un término de búsqueda.</div></div>';
            return;
        }
        
        document.getElementById('medicamentos-content').innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        const response = await fetch(`https://jsonplaceholder.typicode.com/posts?title_like=${encodeURIComponent(searchTerm)}`);
        const data = await response.json();

        const medicamentos = data.map(item => ({
            id: item.id,
            nombre: `${searchTerm} ${item.id}`,
            principioActivo: `Principio Activo ${item.id}`,
            dosificacion: `${item.id * 10}mg`,
            presentacion: item.id % 2 === 0 ? 'Tabletas' : 'Jarabe',
            laboratorio: `Laboratorio ${Math.floor(item.id / 3) + 1}`,
            precio: (item.id * 100) / 3,
            indicaciones: item.body.substring(0, 100),
            contraindicaciones: item.body.substring(0, 50) + '...'
        }));
    
        if (medicamentos.length === 0) {
            document.getElementById('medicamentos-content').innerHTML = '<div class="col-12 text-center"><div class="alert alert-info">No se encontraron medicamentos con ese término de búsqueda.</div></div>';
            return;
        }
        let html = `<div class="col-12 mb-3"><h4 class="text-center">Resultados para "${searchTerm}"</h4></div>`;
        
        medicamentos.forEach(med => {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title text-center">${med.nombre}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Principio Activo:</strong> ${med.principioActivo}</p>
                            <p><strong>Dosificación:</strong> ${med.dosificacion}</p>
                            <p><strong>Presentación:</strong> ${med.presentacion}</p>
                            <p><strong>Laboratorio:</strong> ${med.laboratorio}</p>
                            <p><strong>Precio Referencial:</strong> Q${med.precio.toFixed(2)}</p>
                            <div class="accordion" id="accordion-${med.id}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${med.id}" aria-expanded="false">
                                            Ver detalles adicionales
                                        </button>
                                    </h2>
                                    <div id="collapse-${med.id}" class="accordion-collapse collapse" data-bs-parent="#accordion-${med.id}">
                                        <div class="accordion-body">
                                            <p><strong>Indicaciones:</strong> ${med.indicaciones}</p>
                                            <p><strong>Contraindicaciones:</strong> ${med.contraindicaciones}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success btn-sm w-100 importar-med" data-med-id="${med.id}">
                                Importar a Catálogo Local
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
   
        document.getElementById('medicamentos-content').innerHTML = html;
        addImportListeners();
    } catch (error) {
        console.error('Error al buscar medicamentos:', error);
        document.getElementById('medicamentos-content').innerHTML = '<div class="col-12 text-center"><div class="alert alert-danger">Error al conectar con el catálogo externo. Intente más tarde.</div></div>';
    }
};

const addImportListeners = () => {
    const importButtons = document.querySelectorAll('.importar-med');
    importButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const medicamentoId = e.target.getAttribute('data-med-id');
            importMedicamento(medicamentoId);
        });
    });
};

const importMedicamento = async (id) => {
    try {
        const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`);
        const data = await response.json();
        const med = {
            id: data.id,
            nombre: `Medicamento ${data.id}`,
            principioActivo: `Principio Activo ${data.id}`,
            dosificacion: `${data.id * 10}mg`,
            presentacion: data.id % 2 === 0 ? 'Tabletas' : 'Jarabe',
            laboratorio: `Laboratorio ${Math.floor(data.id / 3) + 1}`,
            precio: (data.id * 100) / 3,
            indicaciones: data.body.substring(0, 100),
            contraindicaciones: data.body.substring(0, 50) + '...'
        };
        
        if (document.getElementById('formMedicamento')) {
            document.querySelector('input[name="medicamento_nombre"]').value = med.nombre;
            document.querySelector('input[name="medicamento_vencimiento"]').value = '2026-12-31';
            document.querySelector('input[name="medicamento_descripcion"]').value = med.indicaciones;
            document.querySelector('input[name="medicamento_presentacion"]').value = med.presentacion;
            
            const marcaSelect = document.querySelector('select[name="medicamento_casa_matriz"]');
            if (marcaSelect && marcaSelect.options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (marcaSelect.options.length - 1)) + 1;
                marcaSelect.selectedIndex = randomIndex;
            }
            
            document.querySelector('input[name="medicamento_cantidad"]').value = Math.floor(Math.random() * 100) + 20;
            document.querySelector('input[name="medicamento_precio"]').value = med.precio.toFixed(2);
            

            const mensajeExito = document.createElement('div');
            mensajeExito.className = 'alert alert-success alert-dismissible fade show mt-3';
            mensajeExito.innerHTML = `
                <strong>¡Medicamento importado!</strong> Los datos han sido cargados en el formulario. Revise y haga clic en REGISTRAR para finalizar.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            document.getElementById('formMedicamento').prepend(mensajeExito);
            document.getElementById('formMedicamento').scrollIntoView({ behavior: 'smooth' });
            
            setTimeout(() => {
                if (mensajeExito.parentNode) {
                    mensajeExito.parentNode.removeChild(mensajeExito);
                }
            }, 5000);
        } else {
            alert('No se encontró el formulario de medicamentos.');
        }
    } catch (error) {
        console.error('Error al importar medicamento:', error);
        alert('Error al importar el medicamento. Por favor intente nuevamente.');
    }
};

const addImportButtons = () => {
    const buttonRow = document.createElement('div');
    buttonRow.className = 'row justify-content-center mt-3';
    buttonRow.innerHTML = `
        <div class="col-md-6 text-center">
            <button id="import-popular" class="btn btn-outline-primary mb-2 me-2">Importar Medicamentos Populares</button>
            <button id="import-antibiotics" class="btn btn-outline-success mb-2">Importar Antibióticos</button>
        </div>
    `;
    
    medicamentosAPIContainer.appendChild(buttonRow);
    document.getElementById('import-popular').addEventListener('click', () => importMedicamentosGroup('populares'));
    document.getElementById('import-antibiotics').addEventListener('click', () => importMedicamentosGroup('antibioticos'));
};

const importMedicamentosGroup = async (group) => {
    try {
        document.getElementById('medicamentos-content').innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
        
        let searchTerm = '';
        let title = '';
        
        if (group === 'populares') {
            searchTerm = 'popular';
            title = 'Medicamentos Más Vendidos';
        } else if (group === 'antibioticos') {
            searchTerm = 'antibiotico';
            title = 'Antibióticos Disponibles';
        }

        const response = await fetch(`https://jsonplaceholder.typicode.com/posts?_limit=6`);
        const data = await response.json();
        const medicamentos = data.map(item => ({
            id: item.id,
            nombre: group === 'populares' 
                ? `${['Paracetamol', 'Ibuprofeno', 'Amoxicilina', 'Loratadina', 'Omeprazol', 'Salbutamol'][item.id % 6]} ${item.id * 10}mg` 
                : `${['Azitromicina', 'Ciprofloxacino', 'Amoxicilina', 'Cefalexina', 'Doxiciclina', 'Ampicilina'][item.id % 6]} ${item.id * 50}mg`,
            principioActivo: group === 'populares'
                ? ['Paracetamol', 'Ibuprofeno', 'Amoxicilina', 'Loratadina', 'Omeprazol', 'Salbutamol'][item.id % 6]
                : ['Azitromicina', 'Ciprofloxacino', 'Amoxicilina', 'Cefalexina', 'Doxiciclina', 'Ampicilina'][item.id % 6],
            dosificacion: `${item.id * (group === 'populares' ? 10 : 50)}mg`,
            presentacion: item.id % 2 === 0 ? 'Tabletas' : 'Cápsulas',
            laboratorio: `Laboratorio ${Math.floor(item.id / 2) + 1}`,
            precio: group === 'populares' ? (item.id * 10) + 25 : (item.id * 15) + 40,
            indicaciones: item.body.substring(0, 100),
            contraindicaciones: item.body.substring(0, 50) + '...'
        }));
        
        let html = `<div class="col-12 mb-3"><h4 class="text-center">${title}</h4></div>`;
        
        medicamentos.forEach(med => {
            html += `
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title text-center">${med.nombre}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Principio Activo:</strong> ${med.principioActivo}</p>
                            <p><strong>Dosificación:</strong> ${med.dosificacion}</p>
                            <p><strong>Presentación:</strong> ${med.presentacion}</p>
                            <p><strong>Laboratorio:</strong> ${med.laboratorio}</p>
                            <p><strong>Precio Referencial:</strong> Q${med.precio.toFixed(2)}</p>
                            <div class="accordion" id="accordion-${med.id}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${med.id}" aria-expanded="false">
                                            Ver detalles adicionales
                                        </button>
                                    </h2>
                                    <div id="collapse-${med.id}" class="accordion-collapse collapse" data-bs-parent="#accordion-${med.id}">
                                        <div class="accordion-body">
                                            <p><strong>Indicaciones:</strong> ${med.indicaciones}</p>
                                            <p><strong>Contraindicaciones:</strong> ${med.contraindicaciones}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-success btn-sm w-100 importar-med" data-med-id="${med.id}">
                                Importar a Catálogo Local
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('medicamentos-content').innerHTML = html;

        addImportListeners();
    } catch (error) {
        console.error('Error al cargar grupo de medicamentos:', error);
        document.getElementById('medicamentos-content').innerHTML = '<div class="col-12 text-center"><div class="alert alert-danger">Error al cargar el catálogo. Intente más tarde.</div></div>';
    }
};
document.addEventListener('DOMContentLoaded', initMedicamentosModule);

window.medicamentosAPI = {
    search: searchMedicamentos,
    importar: importMedicamento,
    importarGrupo: importMedicamentosGroup
};