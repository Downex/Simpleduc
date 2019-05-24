<?php

class Utilisateur{
    
    private $db;
    private $insert;
    private $connect;
    private $select;
    private $selectByEmail;
    private $update;
    private $updateMdp;
    private $delete;
    private $deleteDev;
    private $countUser;
    private $countAdmin;
    private $countDev;
    private $countAll;

    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO utilisateur(email, mdp, nom, prenom, idRole) VALUES (:email, :mdp, :nom, :prenom, :role)");    
        $this->connect = $db->prepare("SELECT email, idRole, mdp, nom, prenom, dev FROM utilisateur WHERE email=:email");
        $this->select = $db->prepare("SELECT email, idRole, nom, prenom, r.libelle AS libellerole, r.id, dev FROM utilisateur u, role r WHERE u.idRole = r.id ORDER BY nom");
        $this->selectByEmail = $db->prepare("SELECT email, nom, prenom, idRole, dev, GROUP_CONCAT(SUBSTR(prenom, 1, 1), SUBSTR(nom, 1, 1)) AS initial FROM utilisateur WHERE email=:email");
        $this->update = $db->prepare("UPDATE utilisateur SET nom=:nom, prenom=:prenom, idRole=:role, dev=:dev WHERE email=:email");
        $this->updateMdp = $db->prepare("UPDATE utilisateur SET mdp=:mdp WHERE email=:email");
        $this->delete = $db->prepare("DELETE FROM utilisateur WHERE email=:id");
        $this->deleteDev = $db->prepare("DELETE FROM developpeur WHERE idDev=:id");
        $this->countUser = $db->prepare("SELECT COUNT(1) FROM utilisateur WHERE idRole = 2 AND dev is null");
        $this->countAdmin = $db->prepare("SELECT COUNT(1) FROM utilisateur WHERE idRole = 1");
        $this->countDev = $db->prepare("SELECT COUNT(1) FROM utilisateur WHERE dev = 1");
        $this->countAll = $db->prepare("SELECT COUNT(1) FROM utilisateur");
    }
    
    public function insert($email, $mdp, $role, $nom, $prenom){
        $this->insert->execute(array(':email'=>$email, ':mdp'=>$mdp, ':role'=>$role, ':nom'=>$nom, ':prenom'=>$prenom));
        return gestionnaire($this->insert); 
    }
    
    public function connect($email){  
        $this->connect->execute(array(':email'=>$email));      
        gestionnaire($this->connect);
        return $this->connect->fetch();
    }
    
    public function select(){
        $this->select->execute();
        gestionnaire($this->select);
        return $this->select->fetchAll();
    }
    
    public function selectByEmail($email){ 
        $this->selectByEmail->execute(array(':email'=>$email));        
        gestionnaire($this->selectByEmail);
        return $this->selectByEmail->fetch(); 
    }
    
    public function update($email, $role, $nom, $prenom, $dev){
        $this->update->execute(array(':email'=>$email, ':role'=>$role, ':nom'=>$nom, ':prenom'=>$prenom, ':dev'=>$dev));
        return gestionnaire($this->update);
    }
    
    public function updateMdp($email, $mdp){
        $this->updateMdp->execute(array(':email'=>$email, ':mdp'=>$mdp));
        return gestionnaire($this->updateMdp);
    }

    public function delete($id){
        $this->delete->execute(array(':id'=>$id));
        return gestionnaire($this->delete);
    }

    public function deleteDev($id){
        $this->deleteDev->execute(array(':id'=>$id));
        return gestionnaire($this->deleteDev);
    }
    
    public function countUser(){
        $this->countUser->execute();
        gestionnaire($this->countUser);
        return $this->countUser->fetch();
    }

    public function countAdmin(){
        $this->countAdmin->execute();
        gestionnaire($this->countAdmin);
        return $this->countAdmin->fetch();
    }

    public function countDev(){
        $this->countDev->execute();
        gestionnaire($this->countDev);
        return $this->countDev->fetch();
    }

    public function countAll(){
        $this->countAll->execute();
        gestionnaire($this->countAll);
        return $this->countAll->fetch();
    }
}

?>

