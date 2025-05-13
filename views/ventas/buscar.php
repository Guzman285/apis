<?php
include_once '../templates/header.php';
?>

<h1 class="text-center">Buscar Ventas</h1>
<div class="row justify-content-center">
    <form action="../../controllers/ventas/buscar.php" method="GET" class="border bg-light shadow rounded p-4 col-lg-6">

        <div class="row mb-3">
            <div class="col">
                <label for="venta_medicamento">Nombre del producto:</label>
                <input type="text" name="venta_medicamento" id="venta_medicamento" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-search me-2"></i>Buscar
                </button>
            </div>
        </div>

    </form>
</div>


<?php
include_once '../templates/footer.php';
?>