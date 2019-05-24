<?php
class Participation{

    
    //Liste des fonctions
    private $db;
    private $insert;
    private $selectEquipeById;
    private $deleteById;
    private $countMembre;

    //Preparation des requetes SQL
    function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO participation(idDev, idEquipe) VALUES (:idDev, :idEquipe)");
        $this->selectEquipeById = $db->prepare("SELECT e.id, e.libelle, e.idresponsable, d.idDev, d.nom, d.prenom FROM developpeur d INNER JOIN participation p ON d.idDev = p.idDev INNER JOIN equipe e ON e.id = p.idEquipe WHERE p.idEquipe=:id");
        $this->deleteById = $db->prepare("DELETE FROM participation WHERE idDev=:idDev AND idEquipe=:idEquipe");
        $this->countMembre = $db->prepare("SELECT COUNT(1) FROM participation where idEquipe=:idEquipe");
    }

    //Fonction
    public function insert($idDev, $idEquipe){
        $this->insert->bindValue(':idDev', $idDev,PDO::PARAM_STR);
        $this->insert->bindValue(':idEquipe', $idEquipe,PDO::PARAM_INT);          
        $this->insert->execute(array(':idDev'=>$idDev, ':idEquipe'=>$idEquipe));        
        return gestionnaire($this->insert);
    }

    public function selectEquipeById($id){
        $this->selectEquipeById->execute(array(':id'=>$id));
        gestionnaire($this->selectEquipeById);
        return $this->selectEquipeById->fetchAll(); //fetch renvoie la première ligne, fetchAll toute les lignes
    }

    public function deleteById($idDev, $idEquipe){
        $this->deleteById->bindValue(':idDev', $idDev,PDO::PARAM_STR);
        $this->deleteById->bindValue(':idEquipe', $idEquipe,PDO::PARAM_INT);
        $this->deleteById->execute(array(':idDev'=>$idDev, ':idEquipe'=>$idEquipe));  
        return gestionnaire($this->deleteById);
    }

    public function countMembre($idEquipe){
        $this->countMembre->execute(array(':idEquipe'=>$idEquipe));
        gestionnaire($this->countMembre);
        return $this->countMembre->fetchAll();
    }
}