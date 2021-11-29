<?php

require_once '..\CRUD\Create.php';

// Esse arquivo faz uma requisição de inserção no banco de dados com os seguintes parâmetros
$values = array(
    'name' => $_POST['name'],
    'id' => $_POST['id'],
    'image' => $_POST['image'],
    'types' => $_POST['types']
);

$TableCreator = new Creator();

echo $TableCreator->safeInsert($values);

?>