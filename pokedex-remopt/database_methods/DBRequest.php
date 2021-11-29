<?php

require_once '..\CRUD\Read.php';

$TableReader = new Reader();

// Esse arquivo retorna todos os registros da tabela caso um id não seja informado

if (!empty($_POST['id'])) {
    echo json_encode($TableReader->SearchPokemonById($_POST['id']));
} else {
    echo json_encode($TableReader->ReadAll());
}

?>