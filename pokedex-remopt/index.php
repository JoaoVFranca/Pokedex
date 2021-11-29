<?php 
include __DIR__.'\components\header.php';
?>
    <body>
        <div class="d-flex justify-content-center mt-3">
            <h1 style="color:black;">Pokedéx</h1>
        </div>
        <div class="container top-distance justify-content-center" style="width: 35%;">
            <div class="d-flex justify-content-center header-div">
                <button type="button" class="btn btn-danger" id="rand-button">Random Pokemon</button>
                <div class="input-group px-2" style="width:30%;">
                    <input type="number" class="form-control" id='visor' value='1' min='1' max='898'>
                </div>
                <button type="button" class="btn btn-success" id="add-pokemon"><span class="material-icons">done</span></button>
            </div>
            <div class="d-flex justify-content-center w-100">
                <button type="button" data-toggle="modal" data-target="#show-types" class="btn btn-dark border border-warning mt-3" id="ShowTypesButton">Show All Types</button>
            </div>
        </div>
        <div class="container mt-5">
            <table class="table table-stripe table-dark table-hover text-center align-middle" id='PokeTable'>
            <thead>
                <tr>
                    <th>@</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Types</th>
                </tr>
            </thead>
            </table>
        </div>

        <!-- Show types Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="show-types">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">All Pokemon Types</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id='modal-content-label'>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </body>
    <script src='scripts/Master.js'></script>
</html>