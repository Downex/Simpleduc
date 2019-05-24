<?php

class Role{
    
    private $db;
    private $select;
    private $insert;
    private $update;
    private $selectById;
    private $selectRoleByEmail;
    
    public function __construct($db){
        $this->db = $db;  
        $this->select = $db->prepare("SELECT id, libelle FROM role ORDER BY libelle");
        $this->insert = $db->prepare("INSERT INTO role(libelle) VALUES (:libelle)");
        $this->update = $db->prepare("UPDATE role SET libelle=:libelle WHERE id=:id");
        $this->selectById = $db->prepare("SELECT id, libelle FROM role WHERE id=:id");
        //Utiliser pour les informations de l'utilisateur <--- François
        $this->selectRoleByEmail = $db->prepare("SELECT r.id, r.libelle FROM utilisateur u INNER JOIN role r ON u.idRole = r.id WHERE u.email=:email");
    }
      
    public function select(){
        $this->select->execute();
        gestionnaire($this->select);
        return $this->select->fetchAll();
    }
    
    public function selectById($id){
        $this->selectById->execute(array(':id'=>$id));
        gestionnaire($this->select);
        return $this->selectById->fetch();
    }
    
    public function insert($libelle){
        $this->insert->execute(array(':libelle'=>$libelle));
        return gestionnaire($this->select);
    }
    public function update($id,$libelle){
        $this->update->execute(array(':id'=>$id, ':libelle'=>$libelle));
        return gestionnaire($this->select);
    }

    public function selectRoleByEmail($email){
        $this->selectRoleByEmail->execute(array(':email'=>$email));
        gestionnaire($this->select);
        return $this->selectRoleByEmail->fetch();
    }
    
}

?>
