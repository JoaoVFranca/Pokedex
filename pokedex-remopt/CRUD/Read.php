<?php 

require_once __DIR__.'/../database_methods\DbConection.php';

class Reader {

    private $Connector;

    private $Connection;
    private $MYSQL;
    private $TableData;
    private $TableName = 'tbpokemon';
    public $commandLine = null;

    // Construtor
    public function __construct(){    
        $this->Connector = new Connection();
        $this->Connection = $this->Connector->Connect();
    }

    // Essa função retorna todos os dados da tabela, sem nenhuma pesquisa
    public function ReadAll() {
        $this->commandLine = "SELECT * FROM {$this->TableName};";
        $this->MYSQL = $this->Connection->prepare($this->commandLine);
        $this->MYSQL->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $this->MYSQL->execute();
            $this->TableData = $this->MYSQL->fetchAll();
            return $this->TableData;
        } catch (PDOException $e) {
            $this->TableData = null;
            echo "<b>Erro ao Consultar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }

    // Essa função retorna todos os dados filtrados da tabela por alguns parâmetros como limite, coluna de 
    // ordenação e direção ordenação 
    public function ReadOrdered($Order) {

        $this->commandLine = "SELECT * FROM {$this->TableName}";

        $this->commandLine .= " ORDER BY ". $Order['column'] . " " . $Order['direction'] . " LIMIT ". $Order['start'].",". $Order['max'] . ";";

        $this->MYSQL = $this->Connection->prepare($this->commandLine);
        $this->MYSQL->setFetchMode(PDO::FETCH_ASSOC);
        try {
            $this->MYSQL->execute();
            $this->TableData = $this->MYSQL->fetchAll();
            return $this->TableData;
        } catch (PDOException $e) {
            $this->TableData = null;
            echo "<b>Erro ao Consultar:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }

    // Essa função retorna os dados ordenados e filtrados por um valor de pesquisa
    public function SearchOrdered($searchValue, $OrderInfo) {
        $this->commandLine = "SELECT * FROM {$this->TableName} WHERE 1=1 AND ";
        $this->commandLine .= " ( namePokemon LIKE '" . $searchValue . "%' OR idRefAPIPokemon LIKE '" .$searchValue."%' OR typesPokemon LIKE '%".$searchValue."%' )";

        $this->commandLine .= " ORDER BY ". $OrderInfo['column'] . " " . $OrderInfo['direction'] . " LIMIT ". $OrderInfo['start'].",". $OrderInfo['max'] . ";";

        $this->commandLine .= ";";
        $this->MYSQL = $this->Connection->prepare($this->commandLine);
        $this->MYSQL->setFetchMode(PDO::FETCH_ASSOC);
        try {
            $this->MYSQL->execute();
            $this->TableData = $this->MYSQL->fetchAll();
            return $this->TableData;
        } catch (PDOException $e) {
            $this->TableData = null;
            echo "<b>Erro ao Consultar com Filtro:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }

    // Essa função retorna apenas a linha do banco de dados onde o idRefAPIPokemon seja igual ao ID informado
    public function SearchPokemonById($id) {
        $this->commandLine = "SELECT * FROM {$this->TableName} WHERE idRefAPIPokemon = {$id};";

        $this->MYSQL = $this->Connection->prepare($this->commandLine);
        $this->MYSQL->setFetchMode(PDO::FETCH_ASSOC);
        try {
            $this->MYSQL->execute();
            $this->TableData = $this->MYSQL->fetchAll();
            return $this->TableData;
        } catch (PDOException $e) {
            $this->TableData = null;
            echo "<b>Erro ao Consultar com Filtro:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }

    // Retorna o número de linhas do resultado da última pesquisa
    public function get_row_count() {
        return $this->MYSQL->rowCount();
    }
}

?>