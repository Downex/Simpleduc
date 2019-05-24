<?php
class Competence{

    private $db;
    private $insert;
    private $selectAllId;
    private $selectAll;
    private $selectById;
    private $selectByDomaine;
    private $update;
    private $delete;
    private $countAll;
    private $countAllByDomaine;

    function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO competence(libelle, idDomaine) VALUES (:libelle, :idDomaine)");
        $this->selectAllId = $db->prepare("SELECT idCompetence, idDomaine, libelle FROM competence");
        $this->selectAll = $db->prepare("SELECT * FROM competence INNER JOIN domaine ON competence.idDomaine = domaine.idDomaine ORDER BY libelle");
        $this->selectById = $db->prepare("SELECT * FROM competence WHERE idCompetence=:idCompetence");
        $this->selectByDomaine = $db->prepare("SELECT * FROM competence WHERE idDomaine=:idDomaine");
        $this->update = $db->prepare("UPDATE competence SET libelle=:libelle, idDomaine=:idDomaine WHERE idCompetence=:idCompetence");
        $this->delete = $db->prepare("DELETE FROM competence WHERE idCompetence=:idCompetence");
        $this->countAll = $db->prepare("SELECT COUNT(1) FROM competence");
        $this->countAllByDomaine = $db->prepare("SELECT COUNT(1) FROM competence WHERE idDomaine=:idDomaine");
    }

    public function insert($libelle, $idDomaine){
        $this->insert->bindValue('libelle', $libelle, PDO::PARAM_STR);
        $this->insert->bindValue('idDomaine', $idDomaine, PDO::PARAM_INT);
        $this->insert->execute(array(':libelle'=>$libelle, ':idDomaine'=>$idDomaine));
        return gestionnaire($this->insert);
    }

    public function selectAllId(){
        $this->selectAllId->execute();
        gestionnaire($this->selectAllId);
        return $this->selectAllId->fetchAll();
    }

    public function selectAll(){
        $this->selectAll->execute();
        gestionnaire($this->selectAll);
        return $this->selectAll->fetchAll();
    }

    public function selectById($idCompetence){
        $this->selectById->execute(array(':idCompetence'=>$idCompetence));
        gestionnaire($this->selectById);
        return $this->selectById->fetch();
    }

    public function selectByDomaine($idDomaine){
        $this->selectByDomaine->execute(array(':idDomaine'=>$idDomaine));
        gestionnaire($this->selectByDomaine);
        return $this->selectByDomaine->fetchAll();
    }

    public function update($idCompetence, $libelle, $idDomaine){
        $this->update->execute(array(':idCompetence'=>$idCompetence, ':libelle'=>$libelle, ':idDomaine'=>$idDomaine));
        return gestionnaire($this->update);
    }

    public function delete($idCompetence){
        $this->delete->execute(array(':idCompetence'=>$idCompetence));
        return gestionnaire($this->delete);
    }

    public function countAll(){
        $this->countAll->execute();
        gestionnaire($this->countAll);
        return $this->countAll->fetch();
    }

    public function countAllByDomaine($idDomaine){
        $this->countAllByDomaine->execute(array(':idDomaine'=>$idDomaine));
        gestionnaire($this->countAllByDomaine);
        return $this->countAllByDomaine->fetch();
    }
}
?>