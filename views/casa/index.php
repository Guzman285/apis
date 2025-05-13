<?php
include_once '../templates/header.php';
?>


<div class="container justify-content-center mt-3">
    <div class="row">
        <div class="col">
            <h1 class="text-center mb-2">REGISTRO CASA MATRIZ</h1>
            <h1 class="text-center mb-2">INGRESE DATOS DE LA CASA</h1>
        </div>
    </div>

    <div class="row justify-content-center">

            <form id="formCasa" class="border bg-light shadow rounded p-4 col-lg-6">

                <div class="row mb-3">
                    <label for="casa_nombre">Ingrese el nombre:</label>
                    <input type="text" name="casa_nombre" class="form-control" placeholder="Nombre..." required>
                    <input type="hidden" name="casa_id" class="form-control" >
                </div>

                <div class="row mb-3">
                    <label for="casa_direccion">Ingrese la direccion:</label>
                    <input type="text" name="casa_direccion" class="form-control" placeholder="Direccion..." required>
                </div>

                <div class="row mb-3">
                    <label for="casa_telefono">Ingrese el numero de telefono:</label>
                    <input type="number" name="casa_telefono" class="form-control" placeholder="Telefono..." required>
                </div>

                <div class="row mb-3">
                    <label for="casa_jefe">Ingrese el nombre del Jefe:</label>
                    <input type="text" name="casa_jefe" class="form-control" placeholder="Jefe..." required>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-3" >
                        <button type="button" id="btnRegistrar" class="btn btn-success">REGISTRAR</button>
                    </div>
                    <div class="col-lg-3">
                        <button type="button" id="btnBuscar" class="btn btn-info">BUSCAR</button>
                    </div>
                    <div class="col-lg-3" >
                        <button type="button" id="btnModificar" class="btn btn-warning d-none">MODIFICAR</button>
                    </div>
                    <div class="col-lg-3" >
                        <button type="button" id="btnCancelar" class="btn btn-danger d-none">CANCELAR</button>
                    </div>
                </div>
            </form>
    </div>
</div>

<div id="divTabla" class="d-none">
<h1 class="text-center">Casas Matriz Registradas</h1>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <table class="table table-bordered table-hover">
                <thead> 
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Telefono</th>
                        <th>Jefe</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_casas"></tbody>
                        
            </table>
        </div>        
    </div>        

</div>

<script src="../../src/js/casa/registrarcasa.js"></script>

<?php
include_once '../templates/footer.php';
?>