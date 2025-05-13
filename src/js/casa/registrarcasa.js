const formularioCasa = document.getElementById('formCasa')
const divTabla = document.getElementById('divTabla')
const btnBuscar = document.getElementById('btnBuscar')
const tbody = document.getElementById('tabla_casas')
const btnRegistrar = document.getElementById('btnRegistrar')
const btnModificar = document.getElementById('btnModificar')
const btnCancelar = document.getElementById('btnCancelar')

divTabla.classList.add('d-none')

const guardar = async() => {
    try {
        const formData = new FormData(formularioCasa)
        
        // Verificar si hay datos
        if (!formData.get('casa_nombre') || !formData.get('casa_direccion') || 
            !formData.get('casa_telefono') || !formData.get('casa_jefe')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/casa/guardar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta guardar:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioCasa.reset()
            // No actualizamos tabla después de guardar
        } else {
            alert(data.mensaje || "Error al guardar los datos")
        }
    } catch(error) {
        console.error("Error:", error)
        alert("Ocurrió un error al procesar la solicitud")
    }
}

// Función para buscar datos con alertas (usada por el botón BUSCAR)
const buscar = async() => {
    try {
        tbody.innerHTML = ''
        
        // Eliminamos la alerta de "Buscando datos..."
        // alert("Buscando datos...")
        
        const formData = new FormData(formularioCasa)
        
        const response = await fetch("../../controllers/casa/buscar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta búsqueda:", data)
        
        if (data.codigo == 1) {
            // Mostrar solo esta alerta
            alert(data.mensaje || "Datos encontrados")
            
            divTabla.classList.remove('d-none')
            
            actualizarTabla(data.datos)
        } else {
            alert(data.mensaje || "No se encontraron datos")
            divTabla.classList.remove('d-none')
            const row = document.createElement("tr")
            row.innerHTML = `<td colspan="6">No hay casas registradas</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error:", error)
        alert("Ocurrió un error al buscar los datos")
    }
}

// Función para actualizar la tabla sin alertas (usada internamente)
const actualizarTablaSilencioso = async() => {
    try {
        tbody.innerHTML = ''
        
        const response = await fetch("../../controllers/casa/buscar.php", {
            method: "POST"
        })
        
        const data = await response.json()
        console.log("Respuesta actualización silenciosa:", data)
        
        if (data.codigo == 1) {
            divTabla.classList.remove('d-none')
            actualizarTabla(data.datos)
        } else {
            divTabla.classList.remove('d-none')
            const row = document.createElement("tr")
            row.innerHTML = `<td colspan="6">No hay casas registradas</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error en actualización silenciosa:", error)
    }
}

// Función auxiliar para actualizar la tabla con datos
const actualizarTabla = (datos) => {
    datos.forEach((casa, index) => {
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${casa.casa_nombre}</td>
          <td>${casa.casa_direccion}</td>
          <td>${casa.casa_telefono}</td>
          <td>${casa.casa_jefe}</td>
          <td class="text-center">
            <div class="dropdown">
              <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Acciones
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="Asignar(${casa.casa_id})"><i class="bi bi-pencil-square me-2"></i>Modificar</a></li>
                <li><a class="dropdown-item" href="#" onclick="Eliminar(${casa.casa_id})"><i class="bi bi-trash me-2"></i>Eliminar</a></li>
              </ul>
            </div>
          </td>
        `
        tbody.appendChild(row)
    })
}

window.Asignar = async(casa_id) => {
    try {
        console.log("Asignando casa ID:", casa_id)
        let id_casa = btoa(casa_id)
        
        const response = await fetch(`../../controllers/casa/modificar.php?idcasa=${id_casa}`)
        
        const data = await response.json()
        console.log("Respuesta asignar:", data)
        
        if (data.codigo == 1) {
            formularioCasa.casa_id.value = casa_id
            formularioCasa.casa_nombre.value = data.datos.casa_nombre
            formularioCasa.casa_direccion.value = data.datos.casa_direccion
            formularioCasa.casa_telefono.value = data.datos.casa_telefono
            formularioCasa.casa_jefe.value = data.datos.casa_jefe
            
            btnRegistrar.classList.add('d-none')
            btnBuscar.classList.add('d-none')
            btnModificar.classList.remove('d-none')
            btnCancelar.classList.remove('d-none')
        } else {
            alert(data.mensaje || "No se pudo cargar la información")
        }
    } catch(error) {
        console.error("Error:", error)
        alert("Ocurrió un error al cargar los datos")
    }
}

const Modificar = async() => {
    try {
        console.log("Iniciando modificación...")
        
        const formData = new FormData(formularioCasa)
        if (!formData.get('casa_id') || !formData.get('casa_nombre') || !formData.get('casa_direccion') || 
            !formData.get('casa_telefono') || !formData.get('casa_jefe')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/casa/modificar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta modificación:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioCasa.reset()
            btnBuscar.classList.remove('d-none')
            btnRegistrar.classList.remove('d-none')
            btnModificar.classList.add('d-none')
            btnCancelar.classList.add('d-none')
            
            await actualizarTablaSilencioso()
        } else {
            alert(data.mensaje || "Error al modificar los datos")
        }
    } catch(error) {
        console.error("Error al modificar:", error)
        alert("Ocurrió un error al modificar los datos")
    }
}

window.Eliminar = async(casa_id) => {
    if(confirm('¿Está seguro de eliminar este registro?')) {
        try {
            console.log("Eliminando casa ID:", casa_id)
            let id_casa = btoa(casa_id)
            
            const response = await fetch(`../../controllers/casa/eliminar.php?idcasa=${id_casa}`)
            
            const data = await response.json()
            console.log("Respuesta eliminar:", data)
            
            if (data.codigo == 1) {
                alert(data.mensaje)
    
                await actualizarTablaSilencioso()
            } else {
                alert(data.mensaje || "Error al eliminar el registro")
            }
        } catch(error) {
            console.error("Error al eliminar:", error)
            alert("Ocurrió un error al eliminar el registro")
        }
    }
}
    
btnCancelar.addEventListener('click', () => {
    formularioCasa.reset()
    btnBuscar.classList.remove('d-none')
    btnRegistrar.classList.remove('d-none')
    btnModificar.classList.add('d-none')
    btnCancelar.classList.add('d-none')
})
btnRegistrar.addEventListener('click', guardar)
btnBuscar.addEventListener('click', buscar)
btnModificar.addEventListener('click', Modificar)