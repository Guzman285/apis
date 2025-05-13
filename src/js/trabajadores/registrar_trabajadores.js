const formularioTrabajador = document.getElementById('formTrabajador')
const divTabla = document.getElementById('divTabla')
const btnBuscar = document.getElementById('btnBuscar')
const tbody = document.getElementById('tabla_trabajadores')
const btnRegistrar = document.getElementById('btnRegistrar')
const btnModificar = document.getElementById('btnModificar')
const btnCancelar = document.getElementById('btnCancelar')

divTabla.classList.add('d-none')

const guardar = async() => {
    try {
        const formData = new FormData(formularioTrabajador)
        
 
        if (!formData.get('trabajador_nombre') || !formData.get('trabajador_edad') || 
            !formData.get('trabajador_dpi') || !formData.get('trabajador_puesto') ||
            !formData.get('trabajador_telefono') || !formData.get('trabajador_correo') ||
            !formData.get('trabajador_sueldo') || !formData.get('trabajador_sexo') ||
            !formData.get('trabajador_direccion')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/trabajadores/guardar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta guardar:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioTrabajador.reset()

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
        
        const formData = new FormData(formularioTrabajador)
        
        const response = await fetch("../../controllers/trabajadores/buscar.php", {
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
            row.innerHTML = `<td colspan="11">No hay trabajadores registrados</td>`
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
        
        const response = await fetch("../../controllers/trabajadores/buscar.php", {
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
            row.innerHTML = `<td colspan="11">No hay trabajadores registrados</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error en actualización silenciosa:", error)
    }
}
const actualizarTabla = (datos) => {
    datos.forEach((trabajador, index) => {
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${trabajador.trabajador_nombre}</td>
          <td>${trabajador.trabajador_edad}</td>
          <td>${trabajador.trabajador_dpi}</td>
          <td>${trabajador.trabajador_puesto}</td>
          <td>${trabajador.trabajador_telefono}</td>
          <td>${trabajador.trabajador_correo}</td>
          <td>${trabajador.trabajador_sueldo}</td>
          <td>${trabajador.trabajador_sexo}</td>
          <td>${trabajador.trabajador_direccion}</td>
          <td class="text-center">
            <div class="dropdown">
              <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Acciones
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="Asignar(${trabajador.trabajador_id})"><i class="bi bi-pencil-square me-2"></i>Modificar</a></li>
                <li><a class="dropdown-item" href="#" onclick="Eliminar(${trabajador.trabajador_id})"><i class="bi bi-trash me-2"></i>Eliminar</a></li>
              </ul>
            </div>
          </td>
        `
        tbody.appendChild(row)
    })
}

window.Asignar = async(trabajador_id) => {
    try {
        console.log("Asignando trabajador ID:", trabajador_id)
        let id_trabajador = btoa(trabajador_id)
        
        const response = await fetch(`../../controllers/trabajadores/modificar.php?idtrabajador=${id_trabajador}`)
        
        const data = await response.json()
        console.log("Respuesta asignar:", data)
        
        if (data.codigo == 1) {
            formularioTrabajador.trabajador_id.value = trabajador_id
            formularioTrabajador.trabajador_nombre.value = data.datos.trabajador_nombre
            formularioTrabajador.trabajador_edad.value = data.datos.trabajador_edad
            formularioTrabajador.trabajador_dpi.value = data.datos.trabajador_dpi
            formularioTrabajador.trabajador_puesto.value = data.datos.trabajador_puesto
            formularioTrabajador.trabajador_telefono.value = data.datos.trabajador_telefono
            formularioTrabajador.trabajador_correo.value = data.datos.trabajador_correo
            formularioTrabajador.trabajador_sueldo.value = data.datos.trabajador_sueldo
            formularioTrabajador.trabajador_sexo.value = data.datos.trabajador_sexo
            formularioTrabajador.trabajador_direccion.value = data.datos.trabajador_direccion
            
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
        
        const formData = new FormData(formularioTrabajador)
 
        if (!formData.get('trabajador_id') || !formData.get('trabajador_nombre') || 
            !formData.get('trabajador_edad') || !formData.get('trabajador_dpi') || 
            !formData.get('trabajador_puesto') || !formData.get('trabajador_telefono') || 
            !formData.get('trabajador_correo') || !formData.get('trabajador_sueldo') || 
            !formData.get('trabajador_sexo') || !formData.get('trabajador_direccion')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/trabajadores/modificar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta modificación:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)

            formularioTrabajador.reset()
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

window.Eliminar = async(trabajador_id) => {
    if(confirm('¿Está seguro de eliminar este registro?')) {
        try {
            console.log("Eliminando trabajador ID:", trabajador_id)
            let id_trabajador = btoa(trabajador_id)
            
            const response = await fetch(`../../controllers/trabajadores/eliminar.php?idtrabajador=${id_trabajador}`)
            
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
    formularioTrabajador.reset()
    btnBuscar.classList.remove('d-none')
    btnRegistrar.classList.remove('d-none')
    btnModificar.classList.add('d-none')
    btnCancelar.classList.add('d-none')
})
btnRegistrar.addEventListener('click', guardar)
btnBuscar.addEventListener('click', buscar)
btnModificar.addEventListener('click', Modificar)