const formularioCliente = document.getElementById('formCliente')
const divTabla = document.getElementById('divTabla')
const btnBuscar = document.getElementById('btnBuscar')
const tbody = document.getElementById('tabla_clientes')
const btnRegistrar = document.getElementById('btnRegistrar')
const btnModificar = document.getElementById('btnModificar')
const btnCancelar = document.getElementById('btnCancelar')

divTabla.classList.add('d-none')

const guardar = async() => {
    try {
        const formData = new FormData(formularioCliente)
        if (!formData.get('cliente_nombre') || !formData.get('cliente_edad') || 
            !formData.get('cliente_dpi') || !formData.get('cliente_nit') ||
            !formData.get('cliente_telefono') || !formData.get('cliente_correo') ||
            !formData.get('cliente_sexo') || !formData.get('cliente_direccion')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/clientes/guardar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta guardar:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioCliente.reset()
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
        
        const formData = new FormData(formularioCliente)
        
        const response = await fetch("../../controllers/clientes/buscar.php", {
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
            row.innerHTML = `<td colspan="10">No hay clientes registrados</td>`
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
        
        const response = await fetch("../../controllers/clientes/buscar.php", {
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
            row.innerHTML = `<td colspan="10">No hay clientes registrados</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error en actualización silenciosa:", error)
    }
}

const actualizarTabla = (datos) => {
    datos.forEach((cliente, index) => {
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${cliente.cliente_nombre}</td>
          <td>${cliente.cliente_edad}</td>
          <td>${cliente.cliente_dpi}</td>
          <td>${cliente.cliente_nit}</td>
          <td>${cliente.cliente_telefono}</td>
          <td>${cliente.cliente_correo}</td>
          <td>${cliente.cliente_sexo}</td>
          <td>${cliente.cliente_direccion}</td>
          <td class="text-center">
            <div class="dropdown">
              <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Acciones
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="Asignar(${cliente.cliente_id})"><i class="bi bi-pencil-square me-2"></i>Modificar</a></li>
                <li><a class="dropdown-item" href="#" onclick="Eliminar(${cliente.cliente_id})"><i class="bi bi-trash me-2"></i>Eliminar</a></li>
              </ul>
            </div>
          </td>
        `
        tbody.appendChild(row)
    })
}

window.Asignar = async(cliente_id) => {
    try {
        console.log("Asignando cliente ID:", cliente_id)
        let id_cliente = btoa(cliente_id)
        
        const response = await fetch(`../../controllers/clientes/modificar.php?idcliente=${id_cliente}`)
        
        const data = await response.json()
        console.log("Respuesta asignar:", data)
        
        if (data.codigo == 1) {
            formularioCliente.cliente_id.value = cliente_id
            formularioCliente.cliente_nombre.value = data.datos.cliente_nombre
            formularioCliente.cliente_edad.value = data.datos.cliente_edad
            formularioCliente.cliente_dpi.value = data.datos.cliente_dpi
            formularioCliente.cliente_nit.value = data.datos.cliente_nit
            formularioCliente.cliente_telefono.value = data.datos.cliente_telefono
            formularioCliente.cliente_correo.value = data.datos.cliente_correo
            formularioCliente.cliente_sexo.value = data.datos.cliente_sexo
            formularioCliente.cliente_direccion.value = data.datos.cliente_direccion
            
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
        
        const formData = new FormData(formularioCliente)
        
        if (!formData.get('cliente_id') || !formData.get('cliente_nombre') || !formData.get('cliente_edad') || 
            !formData.get('cliente_dpi') || !formData.get('cliente_nit') ||
            !formData.get('cliente_telefono') || !formData.get('cliente_correo') ||
            !formData.get('cliente_sexo') || !formData.get('cliente_direccion')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/clientes/modificar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta modificación:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            
            formularioCliente.reset()
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

window.Eliminar = async(cliente_id) => {
    if(confirm('¿Está seguro de eliminar este registro?')) {
        try {
            console.log("Eliminando cliente ID:", cliente_id)
            let id_cliente = btoa(cliente_id)
            
            const response = await fetch(`../../controllers/clientes/eliminar.php?idcliente=${id_cliente}`)
            
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
    formularioCliente.reset()
    btnBuscar.classList.remove('d-none')
    btnRegistrar.classList.remove('d-none')
    btnModificar.classList.add('d-none')
    btnCancelar.classList.add('d-none')
})

btnRegistrar.addEventListener('click', guardar)
btnBuscar.addEventListener('click', buscar)
btnModificar.addEventListener('click', Modificar)