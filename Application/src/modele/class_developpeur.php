<?php
class Developpeur{

    private $db;
    private $insert;
    private $selectAll;
    private $selectById;
    private $update;
    private $delete;
    private $countDev;
    
    function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO developpeur(idDev, nom, prenom, tel, indiceRemuneration, idRem) VALUES (:idDev, :nom, :prenom, :tel, :indiceRemuneration, :idRem)");
        // Correction en dessous $this->selectAll = $db->prepare("SELECT idDev, nom, prenom, tel, indiceRemuneration, idRem FROM developpeur INNER JOIN remuneration ON developpeur.idRem = remuneration.idRem ORDER BY nom");
        $this->selectAll = $db->prepare("SELECT idDev, nom, prenom, tel, indiceRemuneration, coutHoraire FROM developpeur LEFT OUTER JOIN remuneration ON developpeur.idRem = remuneration.idRem ORDER BY nom");
        $this->selectById = $db->prepare("SELECT * FROM developpeur LEFT OUTER JOIN remuneration ON developpeur.idRem = remuneration.idRem WHERE idDev=:idDev");
        $this->update = $db->prepare("UPDATE developpeur SET nom=:nom, prenom=:prenom, tel=:tel, indiceRemuneration=:indiceRemuneration, idRem=:idRem WHERE idDev=:idDev");
        $this->delete = $db->prepare("DELETE FROM developpeur WHERE idDev=:idDev");
        $this->countDev = $db->prepare("SELECT COUNT(1) FROM developpeur");
    }
    
    public function insert($idDev,$nom, $prenom, $tel, $indiceRemuneration, $idRem){
        $this->insert->bindValue(':idDev', $idDev,PDO::PARAM_STR);
        $this->insert->bindValue(':nom', $nom,PDO::PARAM_STR);          
        $this->insert->bindValue(':prenom', $prenom,PDO::PARAM_STR);
        $this->insert->bindValue(':tel', $tel,PDO::PARAM_STR);
        $this->insert->bindValue(':indiceRemuneration', $indiceRemuneration,PDO::PARAM_INT);
        $this->insert->bindValue(':idRem', $idRem,PDO::PARAM_INT);
        $this->insert->execute(array(':idDev'=>$idDev, ':nom'=>$nom, ':prenom'=>$prenom, ':tel'=>$tel, ':indiceRemuneration'=>$indiceRemuneration, ':idRem'=>$idRem));        
        return gestionnaire($this->insert);
    }

    public function selectAll(){
        $this->selectAll->execute();
        gestionnaire($this->selectAll);
        return $this->selectAll->fetchAll();
    }

    public function selectById($idDev){
        $this->selectById->execute(array(':idDev'=>$idDev));
        gestionnaire($this->selectById);
        return $this->selectById->fetch();
    }

    public function update($idDev, $nom, $prenom, $tel, $indiceRemuneration, $idRem){
        $this->update->execute(array(':idDev'=>$idDev, ':nom'=>$nom, ':prenom'=>$prenom, ':tel'=>$tel, ':indiceRemuneration'=>$indiceRemuneration, ':idRem'=>$idRem));
        return gestionnaire($this->update);
    }

    public function delete($idDev){
        $this->delete->execute(array(':idDev'=>$idDev));
        return gestionnaire($this->delete);
    }

    public function countDev(){
        $this->countDev->execute();
        gestionnaire($this->countDev);
        return $this->countDev->fetch();
    }
    
}
?>