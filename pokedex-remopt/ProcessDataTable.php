<?php

require_once 'CRUD/Read.php';

// BreakPoint para consulta no Banco de Dados
$FirstPokemonPosition = $_REQUEST['start'];

// Limite de registros por página na tabela
$PagePokemonLimit = $_REQUEST['length'];

// Coluna selecionada para ordenação
$OrderByColumn = $_REQUEST['order'][0]['column'];

// ordenação: (asc = crescente) ou (desc = decrescente)
$OrderDirection = $_REQUEST['order'][0]['dir'];

// Ordem correta das colunas na tabela e seus indices no banco de dados
$columns = array( 
    0 => 'imagePokemon',
    1 => 'idRefAPIPokemon',
	2 => 'namePokemon', 
    3 => 'typesPokemon'
);

// Array para setar o comando de pesquisa no MySQL
$Order = array(
    'column' => $columns[$OrderByColumn],
    'start' => $FirstPokemonPosition,
    'direction' => $OrderDirection,
    'max' => $PagePokemonLimit
);

// Objeto Leitor do banco de dados
$Reader = new Reader();
// Função para pegar TODO o banco de dados, sem nenhuma pesquisa ou ordenação
$AllPokemonData = $Reader->ReadAll();

// Quantidade total de registros
$qtnPokemons = $Reader->get_row_count();

// Inicializando as variáveis de registros filtrados
$FilteredPokemonData = $AllPokemonData;
$totalFiltered = 0;
if (!empty($_REQUEST['search']['value'])) { // Caso o parâmetro 'search' não esteja vazio, calcula os registros filtrados
    $FilteredPokemonData = $Reader->SearchOrdered($_REQUEST['search']['value'], $Order);
    $totalFiltered = $Reader->get_row_count();
}

$RecordsQuantity = 0;
if ($totalFiltered > 0) { // Se existir registros filtrados, eles se tornam o resultado final
    $FinalPokemonData = $FilteredPokemonData;
    $RecordsQuantity = $Reader->get_row_count();
} else { // Se não, os registros totais são ordenados e viram o resultado final
    $FinalPokemonData = $Reader->ReadOrdered($Order);
    $RecordsQuantity = $Reader->get_row_count();
}

$Data = array();
foreach ($FinalPokemonData as $key) {
    $row_data = array(); 
    // Aqui vem a inserção de cada botão único de pokemon, eles recebem no atributo 'value' o id do pokemon
    $row_data[] = '<button type="button" class="btn btn-success pokeInfoButton" value="'. $key["idRefAPIPokemon"] .'"><span class="material-icons">visibility</span></button>';
	$row_data[] = $key["idRefAPIPokemon"];
    $row_data[] = $key["namePokemon"];
    $row_data[] = $key['typesPokemon'];
	$Data[] = $row_data;
}

// Síntese dos dados obtidos para retornar
$FinalData = array(
	"draw" => intval( $_REQUEST['draw'] ),
	"recordsTotal" => intval( $RecordsQuantity ), 
	"recordsFiltered" => intval( $qtnPokemons ),
	"data" => $Data
);

// Retorna codificado como JSON
echo json_encode($FinalData);  
?>