const formularioVenta = document.getElementById('formVenta')
const divTabla = document.getElementById('divTabla')
const btnBuscar = document.getElementById('btnBuscar')
const tbody = document.getElementById('tabla_ventas')
const btnRegistrar = document.getElementById('btnRegistrar')

divTabla.classList.add('d-none')

const guardar = async() => {
    try {
        const formData = new FormData(formularioVenta)
        if (!formData.get('ventas_medicamento') || !formData.get('ventas_cantidad') || 
            !formData.get('ventas_cliente')) {
            alert("Todos los campos son obligatorios")
            return
        }
        
        const response = await fetch("../../controllers/ventas/guardar.php", {
            method: "POST",
            body: formData
        })
        
        const data = await response.json()
        console.log("Respuesta guardar:", data)
        
        if (data.codigo == 1) {
            alert(data.mensaje)
            formularioVenta.reset()
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
        
        const response = await fetch("../../controllers/ventas/buscar.php", {
            method: "POST"
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
            row.innerHTML = `<td colspan="4">No hay ventas registradas</td>`
            tbody.appendChild(row)
        }
    } catch(error) {
        console.error("Error:", error)
        alert("Ocurrió un error al buscar los datos")
    }
}

const actualizarTabla = (datos) => {
    datos.forEach((venta, index) => {
        const row = document.createElement("tr")
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${venta.medicamento_nombre}</td>
          <td>${venta.ventas_cantidad}</td>
          <td>${venta.cliente_nombre}</td>
        `
        tbody.appendChild(row)
    })
}

btnRegistrar.addEventListener('click', guardar)
btnBuscar.addEventListener('click', buscar)