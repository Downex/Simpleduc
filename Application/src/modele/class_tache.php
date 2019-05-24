<?php

class Tache{
    
    //Liste des fonctions
    private $db;
    private $insert;
    private $select;
    private $delete;


    //Requete SLQ de la Fonction
    // $this->NomDeFonction = $db->prepare("Requete SLQ" /!\ Après le WHERE variable=:variable");
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO tache(heures, cout, libelle) VALUES (:heures, :cout, :libelle");
		$this->select = $db->prepare("SELECT * FROM tache ORDER BY code");
        $this->delete = $db->prepare("DELETE FROM tache WHERE code=:code");
        $this->update = $db->prepare("UPDATE tache SET nomContact=:nomContact,
		ville=:ville, cp=:cp, adresse=:adresse, NomEntreprise=:NomEntreprise WHERE idEntreprise=:idEntreprise"); 
    }
    
    //Execution de la Fonction
    public function insert($nomContact, $ville, $heures, $cout, $libelle){
        if($libelle=="non"){
          $libelle=null;  
        }
        $this->insert->bindValue(':libelle', $libelle,PDO::PARAM_STR);  
        $this->insert->bindValue(':cout', $cout,PDO::PARAM_STR);
		$this->insert->bindValue(':heures', $heures,PDO::PARAM_STR); 
		$this->insert->bindValue(':ville', $ville,PDO::PARAM_STR); 
		$this->insert->bindValue(':nomContact', $nomContact,PDO::PARAM_STR); 
        $this->insert->execute();
        return gestionnaire($this->insert);
    }
    
    public function select(){
		$liste = $this->select->execute();
		if($this->select->errorCode()!=0){
			print_r($this->select->errorInfor());
		}
        return $this->select->fetchAll();
    }
    
    public function selectBylibelleTache($libelle){
        $this->selectBylibelleTache->execute(array(':libelle'=>$libelle));
        return $this->selectBylibelleTache->fetchAll();
    }
    
    public function update($code, $libelle, $cout, $heures){
        if($idDev=="non"){
          $idDev=null;  
        }
        $this->update->execute(array(':code'=>$code, ':libelle'=>$libelle, ':cout'=>$cout, ':heureus'=>$heures));
        return gestionnaire($this->update);
    }
    
    
    
}

?>



