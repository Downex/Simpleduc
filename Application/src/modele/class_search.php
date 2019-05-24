<?php
class Search{

    private $selectProjets;
    private $selectUtilisateurs;
    private $selectEquipes;
    private $selectCompetences;

    public function __construct($db){
        $this->selectProjets = $db->prepare("SELECT libelle, description, responsable, date_lancement, date_livraison, etat FROM projet WHERE libelle LIKE :search OR description LIKE :search OR responsable LIKE :search OR date_lancement LIKE :search OR date_livraison LIKE :search OR etat LIKE :search ORDER BY date_livraison");
        $this->selectUtilisateurs = $db->prepare("SELECT email, nom, prenom, dev, libelle FROM utilisateur INNER JOIN role ON utilisateur.idRole = role.id WHERE email LIKE :search OR nom LIKE :search OR prenom LIKE :search");
        $this->selectEquipes = $db->prepare("SELECT id, libelle, idResponsable, nom, prenom FROM equipe INNER JOIN utilisateur ON equipe.idResponsable=utilisateur.email WHERE libelle LIKE :search OR email LIKE :search OR nom LIKE :search OR prenom LIKE :search");
        $this->selectCompetences = $db->prepare("SELECT libelle, nomDomaine FROM competence INNER JOIN domaine ON competence.idDomaine=domaine.idDomaine WHERE libelle LIKE :search OR nomDomaine LIKE :search");
    }

    public function selectProjets($search){
        $this->selectProjets->execute(array(':search'=>'%'.$search.'%'));
        gestionnaire($this->selectProjets);
        return $this->selectProjets->fetchAll();
    }

    public function selectUtilisateurs($search){
        $this->selectUtilisateurs->execute(array(':search'=>'%'.$search.'%'));
        gestionnaire($this->selectUtilisateurs);
        return $this->selectUtilisateurs->fetchAll();
    }

    public function selectEquipes($search){
        $this->selectEquipes->execute(array(':search'=>'%'.$search.'%'));
        gestionnaire($this->selectEquipes);
        return $this->selectEquipes->fetchAll();
    }

    public function selectCompetences($search){
        $this->selectCompetences->execute(array(':search'=>'%'.$search.'%'));
        gestionnaire($this->selectCompetences);
        return $this->selectCompetences->fetchAll();
    }
}
?>