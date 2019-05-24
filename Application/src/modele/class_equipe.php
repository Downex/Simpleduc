<?php

class Equipe{
    
    //Liste des fonctions
    private $db;
    private $insert;
    private $select;
    private $delete;
    private $deleteMembre;
    private $update;
    private $selectById;
    private $selectByIdResponsable;
    private $selectByIdEquipe;

    private $countProjet;
    private $countProjetPerEquipe;
    private $countEquipe;
    private $countResponsable;
    private $countMembre;

    //Requete SLQ de la Fonction
    // $this->NomDeFonction = $db->prepare("Requete SLQ" /!\ Après le WHERE variable=:variable");
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO equipe(libelle, idresponsable) VALUES (:libelle, :idResponsable)");    
        $this->select = $db->prepare("SELECT id, libelle, idresponsable, u.nom, u.prenom, (SELECT COUNT(DISTINCT p.idDev) FROM participation p WHERE p.idEquipe = e.id) AS nbMembre, (SELECT COUNT(1) FROM projet pr WHERE pr.idequipe = e.id) AS nbProjet FROM equipe e LEFT OUTER JOIN utilisateur u ON e.idresponsable = u.email ORDER BY libelle");
        $this->delete = $db->prepare("DELETE FROM equipe WHERE id=:id");
        $this->deleteMembre = $db->prepare("DELETE FROM participation WHERE idEquipe=:idEquipe");
        $this->update = $db->prepare("UPDATE equipe SET libelle=:libelle, idresponsable=:idresponsable WHERE id=:id"); 
        $this->selectById = $db->prepare("SELECT id, libelle, idresponsable, utilisateur.nom, utilisateur.prenom FROM equipe LEFT JOIN utilisateur ON equipe.idresponsable = utilisateur.email WHERE id=:id ORDER BY libelle");
        $this->selectByIdResponsable = $db->prepare("SELECT e.id, e.libelle, e.idresponsable, p.idDev, u.prenom, u.nom FROM equipe e LEFT OUTER JOIN participation p ON e.id = p.idEquipe LEFT OUTER JOIN utilisateur u ON u.email = e.idresponsable WHERE e.idresponsable=:idresponsable OR p.idDev=:idresponsable GROUP BY e.id");
        $this->selectByIdEquipe = $db->prepare("SELECT id, utilisateur.email, utilisateur.nom, utilisateur.prenom FROM equipe LEFT JOIN utilisateur ON equipe.id = utilisateur.idEquipe WHERE utilisateur.idEquipe=:id");
        
        $this->countProjet = $db->prepare("SELECT COUNT(1) FROM projet");
        $this->countProjetPerEquipe = $db->prepare("SELECT COUNT(1) FROM projet WHERE idEquipe=:idEquipe");
        $this->countEquipe = $db->prepare("SELECT COUNT(1) FROM equipe");
        $this->countResponsable = $db->prepare("SELECT COUNT(DISTINCT idresponsable) FROM equipe WHERE idresponsable IS NOT NULL");
        $this->countMembre = $db->prepare("SELECT COUNT(DISTINCT idDev) FROM participation");
    }
    
    //Execution de la Fonction
    public function insert($libelle, $idResponsable){
        if($idResponsable=="non"){
          $idResponsable=null;  
        }
        $this->insert->bindValue(':idResponsable', $idResponsable,PDO::PARAM_STR);  
        $this->insert->bindValue(':libelle', $libelle,PDO::PARAM_STR); 
        $this->insert->execute();
        return gestionnaire($this->insert);
    }
    
    public function select(){
        $this->select->execute();
        gestionnaire($this->select);
        return $this->select->fetchAll();
    }
    
    public function selectByIdResponsable($idResponsable){
        $this->selectByIdResponsable->execute(array(':idresponsable'=>$idResponsable));
        gestionnaire($this->selectByIdResponsable);
        return $this->selectByIdResponsable->fetchAll();
    }
    
    public function update($id, $libelle, $idResponsable){
        if($idResponsable=="non"){
          $idResponsable=null;  
        }
        $this->update->execute(array(':id'=>$id, ':libelle'=>$libelle, ':idresponsable'=>$idResponsable));
        return gestionnaire($this->update);
    }
    
    public function selectById($id){ 
        $this->selectById->execute(array(':id'=>$id)); 
        gestionnaire($this->selectById);
        return $this->selectById->fetch(); 
    }
    public function deleteMembre($id){
        $this->deleteMembre->execute(array(':idEquipe'=>$id));
        return gestionnaire($this->deleteMembre);
    }

    public function delete($id){
        $this->delete->execute(array(':id'=>$id));
        return gestionnaire($this->delete);
    }

    public function selectByIdEquipe($id){
        $this->selectByIdEquipe->execute(array(':id'=>$id));
        gestionnaire($this->selectByIdEquipe);
        return $this->selectByIdEquipe->fetchAll();
    }

    public function countProjetPerEquipe($id){
        $this->countProjetPerEquipe->execute(array(':idEquipe'=>$id));
        gestionnaire($this->countProjetPerEquipe);
        return $this->countProjetPerEquipe->fetchAll();
    }

    public function countProjet(){
        $this->countProjet->execute();
        gestionnaire($this->countProjet);
        return $this->countProjet->fetchAll();
    }

    public function countEquipe(){
        $this->countEquipe->execute();
        gestionnaire($this->countEquipe);
        return $this->countEquipe->fetchAll();
    }

    public function countResponsable(){
        $this->countResponsable->execute();
        gestionnaire($this->countResponsable);
        return $this->countResponsable->fetchAll();
    }

    public function countMembre(){
        $this->countMembre->execute();
        gestionnaire($this->countMembre);
        return $this->countMembre->fetchAll();
    }
}

?>



