const formularioMedicamento = document.getElementById('formMedicamento')
const divTabla = document.getElementById('divTabla')
const btnBuscar = document.getElementById('btnBuscar')
const tbody = document.getElementById('tabla_medicamentos')
const btnRegistrar = document.getElementById('btnRegistrar')
const btnModificar = document.getElementById('btnModificar')
const btnCancelar = document.getElementById('btnCancelar')

divTabla.classList.add('d-none')

const guardar = async() => {
    try {
        const formData = new FormData(formularioMedicamento)
        
        if (!formData.get('medicamento_nombre') || !formData.get('medicamento_vencimiento') || 
            !formData.get('medicamento_descripcion') || !formData.get('medicamento_presentacion') ||
            !formData.get('medicamento_casa_matriz') || !formData.get('medicamento_cantidad') ||
            !formData.get('medicamento_precio')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/medicamentos/guardar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta guardar:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioMedicamento.reset()
        } else {
            alert(data.mensaje || "Error al guardar los datos")
        }
    } catch(error) {
        console.error("Error:", error)
        alert("Ocurrió un error al procesar la solicitud")
    }
}

const buscar = async() => {
    try {
        tbody.innerHTML = ''
        
        const formData = new FormData(formularioMedicamento)
        
        const response = await fetch("../../controllers/medicamentos/buscar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta búsqueda:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje || "Datos encontrados")
            
            divTabla.classList.remove('d-none')
            
            actualizarTabla(data.datos)
        } else {
            alert(data.mensaje || "No se encontraron datos")
            divTabla.classList.remove('d-none')
            const row = document.createElement("tr")
            row.innerHTML = `<td colspan="9">No hay medicamentos registrados</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error:", error)
        alert("Ocurrió un error al buscar los datos")
    }
}

const actualizarTablaSilencioso = async() => {
    try {
        tbody.innerHTML = ''
        
        const response = await fetch("../../controllers/medicamentos/buscar.php", {
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
            row.innerHTML = `<td colspan="9">No hay medicamentos registrados</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error en actualización silenciosa:", error)
    }
}

const actualizarTabla = (datos) => {
    datos.forEach((medicamento, index) => {
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${medicamento.medicamento_nombre}</td>
          <td>${medicamento.medicamento_vencimiento}</td>
          <td>${medicamento.medicamento_descripcion}</td>
          <td>${medicamento.medicamento_presentacion}</td>
          <td>${medicamento.casa_nombre || medicamento.medicamento_casa_matriz}</td>
          <td>${medicamento.medicamento_cantidad}</td>
          <td>${medicamento.medicamento_precio}</td>
          <td class="text-center">
            <div class="dropdown">
              <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Acciones
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="Asignar(${medicamento.medicamento_id})"><i class="bi bi-pencil-square me-2"></i>Modificar</a></li>
                <li><a class="dropdown-item" href="#" onclick="Eliminar(${medicamento.medicamento_id})"><i class="bi bi-trash me-2"></i>Eliminar</a></li>
              </ul>
            </div>
          </td>
        `
        tbody.appendChild(row)
    })
}

window.Asignar = async(medicamento_id) => {
    try {
        console.log("Asignando medicamento ID:", medicamento_id)
        let id_medicamento = btoa(medicamento_id)
        
        const response = await fetch(`../../controllers/medicamentos/modificar.php?idmedicamento=${id_medicamento}`)
        
        const data = await response.json()
        console.log("Respuesta asignar:", data)
        
        if (data.codigo == 1) {
            formularioMedicamento.medicamento_id.value = medicamento_id
            formularioMedicamento.medicamento_nombre.value = data.datos.medicamento_nombre
            formularioMedicamento.medicamento_vencimiento.value = data.datos.medicamento_vencimiento
            formularioMedicamento.medicamento_descripcion.value = data.datos.medicamento_descripcion
            formularioMedicamento.medicamento_presentacion.value = data.datos.medicamento_presentacion
            formularioMedicamento.medicamento_casa_matriz.value = data.datos.medicamento_casa_matriz
            formularioMedicamento.medicamento_cantidad.value = data.datos.medicamento_cantidad
            formularioMedicamento.medicamento_precio.value = data.datos.medicamento_precio
            
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
        
        const formData = new FormData(formularioMedicamento)
        if (!formData.get('medicamento_id') || !formData.get('medicamento_nombre') || 
            !formData.get('medicamento_vencimiento') || !formData.get('medicamento_descripcion') || 
            !formData.get('medicamento_presentacion') || !formData.get('medicamento_casa_matriz') || 
            !formData.get('medicamento_cantidad') || !formData.get('medicamento_precio')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/medicamentos/modificar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta modificación:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioMedicamento.reset()
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

window.Eliminar = async(medicamento_id) => {
    if(confirm('¿Está seguro de eliminar este registro?')) {
        try {
            console.log("Eliminando medicamento ID:", medicamento_id)
            let id_medicamento = btoa(medicamento_id)
            
            const response = await fetch(`../../controllers/medicamentos/eliminar.php?idmedicamento=${id_medicamento}`)
            
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
    formularioMedicamento.reset()
    btnBuscar.classList.remove('d-none')
    btnRegistrar.classList.remove('d-none')
    btnModificar.classList.add('d-none')
    btnCancelar.classList.add('d-none')
})

btnRegistrar.addEventListener('click', guardar)
btnBuscar.addEventListener('click', buscar)
btnModificar.addEventListener('click', Modificar)