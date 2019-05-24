<?php
class Projet{

    private $db;
    private $insert;
    private $selectAll;
    private $selectAllMy;
    private $selectById;
    private $selectByidEquipe;
    private $selectEquipe;
    private $delete;
    private $update;
    private $countResponsable;
    private $countProjet;
    private $countProjetEnd;

    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO projet(libelle, description, responsable, budget, date_lancement, date_livraison, etat, idEquipe) VALUES (:libelle, :description, :responsable, :budget, :date_lancement, :date_livraison, :etat, :idEquipe)");    
        $this->selectAll = $db->prepare("SELECT * FROM projet INNER JOIN utilisateur ON projet.responsable=utilisateur.email");
        $this->selectAllMy = $db->prepare("SELECT DISTINCT(p.id) AS projet, p.libelle, p.description, p.responsable, p.budget, p.date_lancement, p.date_livraison, p.etat, e.id AS equipe FROM projet p INNER JOIN equipe e ON p.idEquipe=e.id INNER JOIN participation ptp ON e.id=ptp.idEquipe INNER JOIN utilisateur u ON ptp.idDev=u.email WHERE p.responsable=:responsable OR p.idEquipe=:idEquipe ORDER BY p.date_livraison");
        $this->selectById = $db->prepare("SELECT * FROM projet WHERE id=:id");
        $this->selectByidEquipe = $db->prepare("SELECT * FROM projet WHERE idEquipe=:id");
        $this->selectEquipe = $db->prepare("SELECT * FROM projet INNER JOIN equipe ON projet.idEquipe=equipe.id WHERE projet.id=:id");
        $this->delete = $db->prepare("DELETE FROM projet WHERE id=:id");
        $this->update = $db->prepare("UPDATE projet SET libelle=:libelle, description=:description, responsable=:responsable, budget=:budget, date_lancement=:date_lancement, date_livraison=:date_livraison, etat=:etat, idEquipe=:idEquipe WHERE id=:id");
        $this->countResponsable = $db->prepare("SELECT COUNT(DISTINCT responsable) FROM projet");
        $this->countProjet = $db->prepare("SELECT COUNT(1) FROM projet");
        $this->countProjetEnd = $db->prepare("SELECT COUNT(1) FROM projet WHERE etat='Terminé'");
    }

    public function insert($libelle, $description, $responsable, $budget, $date_lancement, $date_livraison, $etat, $idEquipe){
        $this->insert->bindValue('libelle', $libelle, PDO::PARAM_STR);
        $this->insert->bindValue('description', $description, PDO::PARAM_STR);
        $this->insert->bindValue('responsable', $responsable, PDO::PARAM_STR);
        $this->insert->bindValue('budget', $budget, PDO::PARAM_INT);
        $this->insert->bindValue('date_lancement', $date_lancement, PDO::PARAM_STR);
        $this->insert->bindValue('date_livraison', $date_livraison, PDO::PARAM_STR);
        $this->insert->bindValue('etat', $etat, PDO::PARAM_STR);
        $this->insert->bindValue('idEquipe', $idEquipe, PDO::PARAM_INT);
        $this->insert->execute(array(':libelle'=>$libelle, ':description'=>$description, ':responsable'=>$responsable, ':budget'=>$budget, ':date_lancement'=>$date_lancement, ':date_livraison'=>$date_livraison, ':etat'=>$etat, ':idEquipe'=>$idEquipe));
        return gestionnaire($this->insert); 
    }

    public function selectAll(){
        $this->selectAll->execute();
        gestionnaire($this->selectAll);
        return $this->selectAll->fetchAll();
    }

    public function selectAllMy($responsable, $idEquipe){
        $this->selectAllMy->execute(array(':responsable'=>$responsable, ':idEquipe'=>$idEquipe));
        gestionnaire($this->selectAllMy);
        return $this->selectAllMy->fetchAll();
    }
    

    public function selectById($id){
        $this->selectById->execute(array(':id'=>$id));
        gestionnaire($this->select);
        return $this->selectById->fetch();
    }

    public function selectByidEquipe($id){
        $this->selectByidEquipe->execute(array(':id'=>$id));
        gestionnaire($this->selectByidEquipe);
        return $this->selectByidEquipe->fetchAll();
    }

    public function selectEquipe($id){
        $this->selectEquipe->execute(array(':id'=>$id));
        gestionnaire($this->selectEquipe);
        return $this->selectEquipe->fetch();
    }

    public function update($id, $libelle, $description, $responsable, $budget, $date_lancement, $date_livraison, $etat, $idEquipe){
        $this->update->execute(array(':id'=>$id, ':libelle'=>$libelle, ':description'=>$description, ':responsable'=>$responsable, ':budget'=>$budget, ':date_lancement'=>$date_lancement, ':date_livraison'=>$date_livraison, ':etat'=>$etat, ':idEquipe'=>$idEquipe));
        return gestionnaire($this->update);
    }

    public function delete($id){
        $this->delete->execute(array(':id'=>$id));
        return gestionnaire($this->delete);
    }

    public function countResponsable(){
        $this->countResponsable->execute();
        gestionnaire($this->countResponsable);
        return $this->countResponsable->fetch();
    }

    public function countProjet(){
        $this->countProjet->execute();
        gestionnaire($this->countProjet);
        return $this->countProjet->fetch();
    }

    public function countProjetEnd(){
        $this->countProjetEnd->execute();
        gestionnaire($this->countProjetEnd);
        return $this->countProjetEnd->fetch();
    }
}
?>