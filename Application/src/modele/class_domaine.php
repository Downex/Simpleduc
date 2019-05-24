<?php
class Domaine{

    private $db;
    private $insert;
    private $selectAll;
    private $selectOne;
    private $update;
    private $delete;
    private $count;

    function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO domaine(nomDomaine) VALUES (:nomDomaine)");
        $this->selectAll = $db->prepare("SELECT * FROM domaine ORDER BY idDomaine");
        $this->selectOne = $db->prepare("SELECT * FROM domaine WHERE idDomaine=:idDomaine");
        $this->update = $db->prepare("UPDATE domaine SET nomDomaine=:nomDomaine WHERE idDomaine=:idDomaine");
        $this->delete = $db->prepare("DELETE FROM domaine WHERE idDomaine=:idDomaine");
        $this->count = $db->prepare("SELECT COUNT(1) FROM domaine");
    }

    public function insert($nomDomaine){
        $this->insert->bindValue('nom', $nom, PDO::PARAM_STR);
        $this->insert->execute(array(':nomDomaine'=>$nomDomaine));
        return gestionnaire($this->select);
    }

    public function selectAll(){
        $this->selectAll->execute();
        gestionnaire($this->selectAll);
        return $this->selectAll->fetchAll();
    }

    public function selectOne($idDomaine){
        $this->selectOne->execute(array(':idDomaine'=>$idDomaine));
        gestionnaire($this->selectOne);
        return $this->selectOne->fetch();
    }

    public function update($idDomaine, $nomDomaine){
        $this->update->execute(array(':idDomaine'=>$idDomaine, ':nomDomaine'=>$nomDomaine));
        return gestionnaire($this->update);
    }

    public function delete($idDomaine){
        $this->delete->execute(array(':idDomaine'=>$idDomaine));
        return gestionnaire($this->delete);
    }

    public function count(){
        $this->count->execute();
        gestionnaire($this->count);
        return $this->count->fetch();
    }
}
?>