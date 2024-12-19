<?php 

class Database {
    private $servername = "localhost";
    private $username = "username";
    private $password = "password";
    private $dbname = "bibliotheque";
    private $charset = "utf8mb4";
    public $pdo;
    
    public function __construct() {
        $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}