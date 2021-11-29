<?php 

require_once __DIR__.'/../database_methods\DbConection.php';

class Creator {

    // Essa é uma classe criada para inserir registros no banco de dados

    private $Connector;
    private $Connection;
    private $MYSQL;

    // Construtor que faz a conexão
    public function __construct(){    
        $this->Connector = new Connection();
        $this->Connection = $this->Connector->Connect();
    }

    // Executa o comando no banco de dados e retorna os dados desse último pokemon adicionado codificado como json
    private function CommandInsert($command, $lastPokeAdded) {
        
        $this->MYSQL = $this->Connection->prepare($command);

        try {
            $this->MYSQL->execute();
            echo json_encode($lastPokeAdded);
        } catch (PDOException $e) {
            echo "<b>Erro ao Inserir:</b> {$e->getMessage()} {$e->getCode()}";
        }
    }

    // Essa função trata os dados recebidos e chama ComandInsert() no final para criar o registro
    public function safeInsert($values) {

        $lastPokeAdded = $values;

        // Nome da table do MySQL
        $TableName = 'tbpokemon';

        // Colunas da tabela onde os dados serão armazenados 
        $params = 'namePokemon,idRefAPIPokemon,imagePokemon,typesPokemon';

        // Formatando o nome do pokemon 
        $values['name'] = '"' . $values['name'] . '"';

        // Convertendo o link da imagem para base 64
        $values['image'] =  base64_encode($values['image']);
        $values['image'] = '"' . $values['image'] . '"';
       
        // Transformando a array types do pokemon para uma string
        $values['types'] = implode(',',array_values($values['types']));
        $lastPokeAdded['types'] = $values['types'];
        $values['types'] = '"' . $values['types'] . '"';

        // Formatando o array para ficar compatível ao MySQL
        $values = implode(',', array_values($values));
        
        // Setando o comando
        $command = 'INSERT INTO ' . $TableName . ' VALUES (' . $values . ');';

        // Chamada da função de inserção ao banco de dados
        $this->CommandInsert($command, $lastPokeAdded);

    }
}

?>