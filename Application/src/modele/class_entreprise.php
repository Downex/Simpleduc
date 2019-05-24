<?php

class Entreprise{
    
    //Liste des fonctions
    private $db;
    private $insert;
    private $select;
    private $delete;


    //Requete SLQ de la Fonction
    // $this->NomDeFonction = $db->prepare("Requete SLQ" /!\ Après le WHERE variable=:variable");
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO entreprise(nomContact, ville, cp, adresse, NomEntreprise) VALUES (:nomContact, :ville, :cp, :adresse, :NomEntreprise");
		$this->select = $db->prepare("SELECT * FROM entreprise ORDER BY idEntreprise");
        $this->delete = $db->prepare("DELETE FROM entreprise WHERE idEntreprise=:idEntreprise");
        $this->update = $db->prepare("UPDATE entreprise SET nomContact=:nomContact,
		ville=:ville, cp=:cp, adresse=:adresse, NomEntreprise=:NomEntreprise WHERE idEntreprise=:idEntreprise"); 
    }
    
    //Execution de la Fonction
    public function insert($nomContact, $ville, $cp, $adresse, $NomEntreprise){
        if($NomEntreprise=="non"){
          $NomEntreprise=null;  
        }
        $this->insert->bindValue(':NomEntreprise', $NomEntreprise,PDO::PARAM_STR);  
        $this->insert->bindValue(':adresse', $adresse,PDO::PARAM_STR);
		$this->insert->bindValue(':cp', $cp,PDO::PARAM_STR); 
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
    
    public function selectByNomEntreprise($NomEntreprise){
        $this->selectByNomEntreprise->execute(array(':NomEntreprise'=>$NomEntreprise));
        return $this->selectByNomEntreprise->fetchAll();
    }
    
    public function update($idEntreprise, $NomEntreprise, $adresse, $cp, $ville, $nomContact){
        if($idEntreprise=="non"){
          $idEntreprise=null;  
        }
        $this->update->execute(array(':idEntreprise'=>$idEntreprise, ':NomEntreprise'=>$NomEntreprise, ':adresse'=>$adresse, ':cp'=>$cp, ':vilee'=>$ville, ':nomContact'=>$nomContact));
        return gestionnaire($this->update);
    }
    
	public function delete($idEntreprise){
        $this->delete->execute(array(':idEntreprise'=>$idEntreprise));
        return gestionnaire($this->select);
    }
    
    
}

?>



