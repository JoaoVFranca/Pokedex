<?php

// Essa é a classe de conexão, é necessário instancia-la antes de fazer qualquer interação com o banco de dados

class Connection {

    private $connection;

    // Parâmetros para a conexão
    private const DbName = "remopt";
    private const user = "root";
    private const host = "localhost";
    private const pass = "";

    public function Connect() {
        try {
           $this->connection = new PDO('mysql:host='. self::host .';dbname='. self::DbName , self::user , self::pass);
           $this->connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: '.$e -> getMessage());
        }
        return $this->connection;
    }
}