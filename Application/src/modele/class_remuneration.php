<?php
class Remuneration{

    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;
    private $selectAll;

    function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO remuneration(coutHoraire) values (:coutHoraire)");
        $this->selectAll = $db->prepare("SELECT * FROM remuneration ORDER BY coutHoraire");
        $this->selectById = $db->prepare("SELECT * FROM remuneration WHERE idRem=:idRem");
        $this->update = $db->prepare("UPDATE remuneration set coutHoraire=:coutHoraire WHERE idRem=:idRem");
        $this->delete = $db->prepare("DELETE * FROM remuneration WHERE idRem=:idRem");
    }

    public function insert($coutHoraire){
        $this->insert->execute(array(':coutHoraire'=>$coutHoraire));       
        return gestionnaire($this->select);
    }

    public function selectAll(){
        $this->selectAll->execute();
        gestionnaire($this->select);
        return $this->selectAll->fetchAll();
    }

    public function selectById(){
        $this->selectById->execute(array(':idRem'=>$idRem));
        gestionnaire($this->select);
        return $this->selectById->fetch();
    }

    public function update($idRem, $coutHoraire){
        $this->update->execute(array('idRem'=>$idRem, ':coutHoraire'=>$coutHoraire));       
        return gestionnaire($this->select);
    }

    public function delete($idRem){
        $this->delete->execute(array(':idRem'=>$idRem));
        return gestionnaire($this->select);
    }
}
?>