<?php
include_once '../templates/header.php';
?>

<div class="container justify-content-center mt-3">
    <div class="row">
        <div class="col">
            <h1 class="text-center mb-2">REGISTRO DE VENTAS</h1>
            <h1 class="text-center mb-2">INGRESE DATOS DE LA VENTA</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <form id="formVenta" class="border bg-light shadow rounded p-4 col-lg-6">
            <div class="row mb-3">
                <label for="ventas_medicamento">Seleccione el producto:</label>
                <select name="ventas_medicamento" id="ventas_medicamento" class="form-control" required>
                    <option value="" selected disabled>Productos...</option>
                    <?php
                    require_once '../../models/medicamentos.php';
                    $util = new Medicamento();
                    $utiles = $util->listarUtiles();
                    foreach ($utiles as $util) {
                        echo "<option value='{$util['medicamento_id']}'>{$util['medicamento_nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="row mb-3">
                <label for="ventas_cantidad">Cantidad:</label>
                <input type="number" name="ventas_cantidad" id="ventas_cantidad" class="form-control" min="1" required>
            </div>

            <div class="row mb-3">
                <label for="ventas_cliente">Seleccione el cliente:</label>
                <select name="ventas_cliente" id="ventas_cliente" class="form-control" required>
                    <option value="" selected disabled>Clientes...</option>
                    <?php
                    require_once '../../models/clientes.php';
                    $cliente = new Cliente();
                    $clientes = $cliente->listarClientes();
                    foreach ($clientes as $cliente) {
                        echo "<option value='{$cliente['cliente_id']}'>{$cliente['cliente_nombre']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-3">
                    <button type="button" id="btnRegistrar" class="btn btn-success">REGISTRAR</button>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="btnBuscar" class="btn btn-info">BUSCAR</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="divTabla" class="d-none">
    <h1 class="text-center">Ventas Registradas</h1>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Cliente</th>
                    </tr>
                </thead>
                <tbody id="tabla_ventas"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="../../src/js/ventas/registrar_ventas.js"></script>

<?php
include_once '../templates/footer.php';
?>