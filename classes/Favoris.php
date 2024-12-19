<?php
include_once 'classes/Database.php';

class Favoris{
    private $id;
    private $id_user;
    private $id_livre;
    private $pdo;

    public function __construct(){
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    public function getFavoris(){
        $stmt = $this->pdo->prepare("SELECT * FROM favoris WHERE utilisateur_id = :id");
        $stmt->execute(['id' => $_SESSION['user']['id']]);
        return $stmt->fetchAll();
    }

    public function addFavoris($id_livre){
        $stmt = $this->pdo->prepare("INSERT INTO favoris (utilisateur_id, livre_id) VALUES (:id_user, :id_livre)");
        $stmt->execute(['id_user' => $_SESSION['user']['id'], 'id_livre' => $id_livre]);
    }

    public function deleteFavoris($id_livre){
        $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE utilisateur_id = :id_user AND livre_id = :id_livre");
        $stmt->execute(['id_user' => $_SESSION['user']['id'], 'id_livre' => $id_livre]);
    }
}
?>