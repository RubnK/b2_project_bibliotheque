<?php 
include_once 'classes/Database.php';

class Livre{
    private $id;
    private $titre;
    private $auteur;
    private $annee;
    private $nbPages;
    private $resume;
    private $image;
    private $pdo;

    public function __construct(){
        $db = new Database();
        $this->pdo = $db->pdo;
    }

    public function getLivre($id){
        $stmt = $this->pdo->prepare("SELECT * FROM livres WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getLivres($query = null){
        if ($query) {
            $stmt = $this->pdo->prepare("SELECT * FROM livres WHERE titre LIKE ? OR auteur LIKE ?");
            $stmt->execute(["%$query%", "%$query%"]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->query("SELECT * FROM livres");
            return $stmt->fetchAll();
        }
    }

    public function addLivre($titre, $auteur){
        $stmt = $this->pdo->prepare("INSERT INTO livres (titre, auteur, utilisateur_id) VALUES (:titre, :auteur, :utilisateur_id)");
        $stmt->execute(['titre' => $titre, 'auteur' => $auteur, 'utilisateur_id' => $_SESSION['user']['id']]);
    }

    public function deleteLivre($id){
        $stmt = $this->pdo->prepare("DELETE FROM livres WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}