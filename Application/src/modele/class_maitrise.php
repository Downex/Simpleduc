<?php
class Maitrise{

    private $db;
    private $insert;
    private $selectOne;
    private $selectAll;
    private $selectAllId;
    private $selectMy;
    private $update;
    private $delete;
    private $countMy;
    private $countMyByDomaine;

    function __construct($db){
        $this->db= $db;
        $this->insert = $db->prepare("INSERT INTO maitrise(idCompetence, idDev) VALUES (:idCompetence, :idDev)");
        $this->selectOne = $db->prepare("SELECT * FROM maitrise WHERE idCompetence=:idComptence AND idDev=:idDev");
        $this->selectAll = $db->prepare("SELECT * FROM maitrise ORDER BY idDev");
        $this->selectAllId = $db->prepare("SELECT competence.idCompetence, libelle FROM maitrise INNER JOIN competence ON maitrise.idCompetence = competence.idCompetence WHERE idDev=:idDev");
        $this->selectMy = $db->prepare("SELECT * FROM maitrise INNER JOIN competence ON maitrise.idCompetence = competence.idCompetence WHERE idDev=:idDev");
        $this->update = $db->prepare("UPDATE maitrise WHERE idDev=:idDev AND idCompetence=:idCompetence");
        $this->delete = $db->prepare("DELETE FROM maitrise WHERE idDev=:idDev AND idCompetence=:idCompetence");
        $this->countMy = $db->prepare("SELECT COUNT(1) FROM maitrise WHERE idDev=:idDev");
        $this->countMyByDomaine = $db->prepare("SELECT COUNT(1) FROM maitrise m INNER JOIN competence c ON m.idCompetence=c.idCompetence WHERE idDev=:idDev AND idDomaine=:idDomaine");
    }

    public function insert($idCompetence, $idDev){
        $this->insert->bindValue(':idCompetence', $idCompetence);
        $this->insert->bindValue(':idDev', $idDev);
        $this->insert->execute(array(':idCompetence'=>$idCompetence, ':idDev'=>$idDev));
        return gestionnaire($this->select);
    }

    public function selectOne($idDev, $idCompetence){
        $this->selectOne->execute(array(':idDev'=>$idDev, 'idCompetence'=>$idCompetence));
        gestionnaire($this->select);
        return $this->selectOne->fetch();
    }

    public function selectAll(){
        $this->selectAll->execute();
        gestionnaire($this->select);
        return $this->selectAll->fetchAll();
    }

    public function selectMy($idDev){
        $this->selectMy->execute(array(':idDev'=>$idDev));
        gestionnaire($this->selectMy);
        return $this->selectMy->fetchAll();
    }

    public function selectAllId($idDev){
        $this->selectAllId->execute(array(':idDev'=>$idDev));
        gestionnaire($this->selectAllId);
        return $this->selectAllId->fetchAll();
    }

    public function update($idDev, $idCompetence){
        $this->update->execute(array(':idDev'=>$idDev, ':idCompetence'=>$idCompetence));
        return gestionnaire($this->update);
    }

    public function delete($idDev, $idCompetence){
        $this->delete->execute(array(':idDev'=>$idDev, ':idCompetence'=>$idCompetence));
        return gestionnaire($this->delete);
    }

    public function countMy($idDev){
        $this->countMy->execute(array(':idDev'=>$idDev));
        gestionnaire($this->countMy);
        return $this->countMy->fetch();
    }

    public function countMyByDomaine($idDev, $idDomaine){
        $this->countMyByDomaine->execute(array(':idDev'=>$idDev, ':idDomaine'=>$idDomaine));
        gestionnaire($this->countMyByDomaine);
        return $this->countMyByDomaine->fetch();
    }
}
?>