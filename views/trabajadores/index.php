<?php
include_once '../templates/header.php';
?>

<div class="container justify-content-center mt-3">
    <div class="row">
        <div class="col">
            <h1 class="text-center mb-2">REGISTRO DE TRABAJADORES DE FARMACEUTICA</h1>
            <h1 class="text-center mb-2">INGRESE DATOS DE LOS TRABAJADORES</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <form id="formTrabajador" class="border bg-light shadow rounded p-4 col-lg-6">
            <div class="row mb-3">
                <label for="trabajador_nombre">Ingrese el nombre:</label>
                <input type="text" name="trabajador_nombre" class="form-control" placeholder="Nombre" required>
                <input type="hidden" name="trabajador_id" class="form-control">
            </div>

            <div class="row mb-3">
                <label for="trabajador_edad">Ingrese la edad:</label>
                <input type="number" name="trabajador_edad" class="form-control" placeholder="Edad" required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_dpi">Ingrese el dpi:</label>
                <input type="number" name="trabajador_dpi" class="form-control" placeholder="Dpi" required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_puesto">Ingrese el puesto:</label>
                <input type="text" name="trabajador_puesto" class="form-control" placeholder="Puesto" required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_telefono">Ingrese el numero de telefono:</label>
                <input type="number" name="trabajador_telefono" class="form-control" placeholder="Telefono..." required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_correo">Ingrese el correo:</label>
                <input type="email" name="trabajador_correo" class="form-control" placeholder="Correo" required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_sueldo">Ingrese el sueldo:</label>
                <input type="text" name="trabajador_sueldo" class="form-control" placeholder="Sueldo" required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_sexo">Ingrese el sexo:</label>
                <input type="text" name="trabajador_sexo" class="form-control" placeholder="Sexo" required>
            </div>

            <div class="row mb-3">
                <label for="trabajador_direccion">Ingrese la direccion:</label>
                <input type="text" name="trabajador_direccion" class="form-control" placeholder="Direccion" required>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-3">
                    <button type="button" id="btnRegistrar" class="btn btn-success">REGISTRAR</button>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="btnBuscar" class="btn btn-info">BUSCAR</button>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="btnModificar" class="btn btn-warning d-none">MODIFICAR</button>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="btnCancelar" class="btn btn-danger d-none">CANCELAR</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="divTabla" class="d-none">
    <h1 class="text-center">Trabajadores Registrados</h1>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>DPI</th>
                        <th>Puesto</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Sueldo</th>
                        <th>Sexo</th>
                        <th>Dirección</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_trabajadores"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="../../src/js/trabajadores/registrar_trabajadores.js"></script>

<?php
include_once '../templates/footer.php';
?>