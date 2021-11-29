<?php

// Esse arquivo faz uma requisição à API de um id específico

$http_url = 'https://pokeapi.co/api/v2/pokemon/';

$pokedex = file_get_contents($http_url . $_POST["id"]);

echo json_encode($pokedex);

?>