<?php
include_once 'classes/Database.php';

class Utilisateur{
    private $pdo;

    public function __construct(){
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    public function register($username, $email, $password){
        $password = hash('sha512', $password);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (nom, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $password]);
    }

    public function login($username, $password){
        $password = hash('sha512', $password);
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE nom = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        if ($user && $user['password'] === $password) {
            return $user;
        } else {
            return false;
        }
    }

    public function checkPassword($id, $password){
        $password = hash('sha512', $password);
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = ? AND password = ?");
        $stmt->execute([$id, $password]);
        $user = $stmt->fetch();
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByUsername($username){
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE nom = ? OR email = ?");
        $stmt->execute([$username, $username]);
        return $stmt->fetch();
    }

    public function updateUser($id, $username, $email, $password){
        $password = hash('sha512', $password);
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, password = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $password, $id]);
    }

    public function deleteUser($id){
        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
}