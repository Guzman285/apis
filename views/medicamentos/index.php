<?php
include_once '../templates/header.php';
?>

<div class="container justify-content-center mt-3">
    <div class="row">
        <div class="col">
            <h1 class="text-center mb-2">REGISTRO DE MEDICAMENTOS</h1>
            <h1 class="text-center mb-2">INGRESE DATOS DE LOS MEDICAMENTOS</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <form id="formMedicamento" class="border bg-light shadow rounded p-4 col-lg-6">
            <div class="row mb-3">
                <label for="medicamento_nombre">Ingrese el nombre:</label>
                <input type="text" name="medicamento_nombre" class="form-control" placeholder="Nombre" required>
                <input type="hidden" name="medicamento_id" class="form-control">
            </div>

            <div class="row mb-3">
                <label for="medicamento_vencimiento">Ingrese la fecha de vencimiento:</label>
                <input type="text" name="medicamento_vencimiento" class="form-control" placeholder="vencimiento" required>
            </div>
            
            <div class="row mb-3">
                <label for="medicamento_descripcion">Descripcion:</label>
                <input type="text" name="medicamento_descripcion" class="form-control" placeholder="Descripcion..." required>
            </div>

            <div class="row mb-3">
                <label for="medicamento_presentacion">Presentacion:</label>
                <input type="text" name="medicamento_presentacion" class="form-control" placeholder="Presentacion" required>
            </div>
            
            <div class="row mb-3">
                <label for="medicamento_casa_matriz">Seleccione la marca</label>
                <select name="medicamento_casa_matriz" id="medicamento_casa_matriz" class="form-control" required>
                    <option value="" selected disabled>Marcas...</option>
                    <?php
                    require_once '../../models/casa_matriz.php';
                    $marca = new Casa();
                    $marcas = $marca->listarMarcas();
                    foreach($marcas as $marca){
                        echo "<option value='{$marca['casa_id']}'>{$marca['casa_nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="row mb-3">
                <label for="medicamento_cantidad">Ingrese la cantidad disponible:</label>
                <input type="number" name="medicamento_cantidad" class="form-control" placeholder="Cantidad..." required>
            </div>

            <div class="row mb-3">
                <label for="medicamento_precio">Ingrese el precio:</label>
                <input type="text" name="medicamento_precio" class="form-control" placeholder="Precio..." required>
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
    <h1 class="text-center">Medicamentos Registrados</h1>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Vencimiento</th>
                        <th>Descripcion</th>
                        <th>Presentacion</th>
                        <th>Casa</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_medicamentos"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="../../src/js/medicamentos/registrar_medicamentos.js"></script>

<?php
include_once '../templates/footer.php';
?>