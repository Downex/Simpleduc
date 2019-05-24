<?php
function getPage($db){

// Inscrire vos contrôleurs ici

// $lesPages['nomDeLaPage'] = "FonctionDeLaPage;0"; 0<- tout le monde 1<- Admin

//controleur_index <--- François
$lesPages['accueil'] = "actionAccueil;0";
$lesPages['inscrire'] = "actionInscrire;0";
$lesPages['apropos'] = "actionApropos;0";
$lesPages['mentions'] = "actionMentions;0";
$lesPages['connexion'] = "actionConnexion;0";
$lesPages['deconnexion'] = "actionDeconnexion;0";
$lesPages['maintenance'] = "actionMaintenance;0";

//controleur_equipe <--- François
$lesPages['equipe'] = "actionEquipe;0";
$lesPages['equipeajout'] = "actionEquipeAjout;0";
$lesPages['equipemodif'] = "actionEquipeModif;0";
$lesPages['equipews'] = "actionEquipeWS;1"; // <---WebService


//controleur_utilisateur <--- François
$lesPages['utilisateur'] = "actionUtilisateur;0";
$lesPages['utilisateurmodif'] = "actionUtilisateurModif;0";
$lesPages['utilisateurajout'] = "actionUtilisateurAjout;1";
$lesPages['utilisateurinfo'] = "actionUtilisateurInfo;0";

//controleur_participation <--- François
$lesPages['equipage'] = "actionRejoindreUneEquipe;0";
$lesPages['equipemembre'] = "actionVoirLesMembres;0";
$lesPages['equipemembreajout'] = "actionAjouterMembre;0";

//controleur_projet <--- Nicolas
$lesPages['projets'] = "ListeProjets;0";
$lesPages['projetajout'] = "AjoutProjet;0";
$lesPages['projetdetails'] = "ProjetDetails;0";
$lesPages['projetsupp'] = "SupprimerProjet;0";
$lesPages['projetmodif'] = "ModifProjet;0";

//controleur_developpeur  <--- Nicolas
$lesPages['ajoutdeveloppeur'] = "AjoutDeveloppeur;0";
$lesPages['developpeurmodif'] = "DeveloppeurModif;0";
$lesPages['developpeur'] = "ActionDeveloppeur;0";

//controleur_competence  <--- Nicolas
$lesPages['competence'] = "AfficheCompetence;1";
$lesPages['competenceajout'] = "AjoutCompetence;1";
$lesPages['competencemodif'] = "ModifCompetence;1";
$lesPages['competenceList'] = "CompetenceList;0";

//controleur_domaine  <--- Nicolas
$lesPages['domaine'] = "AfficheDomaine;1";
$lesPages['domaineajout'] = "AjoutDomaine;1";
$lesPages['domainemodif'] = "ModifDomaine;1";

//recherche <--- Nicolas
$lesPages['search'] = "Search;0";

//controleur_entreprise <--- Simon
$lesPages['entreprise'] = "actionEntreprise;0";
$lesPages['entrepriseajout'] = "actionEntrepriseAjout;0";
$lesPages['entreprisemodif'] = "actionEntrepriseModif;0";
$lesPages['entreprisesupp'] = "actionEntrepriseSupprimer;0";
$lesPages['entrepriselist'] = "actionEntrepriseListe;0";

//controleur_tache <-- Simon
$lesPages['tacheajout'] = "actionTacheAjout;0";
$lesPages['tachemodif'] = "actionTacheModifier;0";
$lesPages['tachesupp'] = "actionTacheSupprimer;0";
$lesPages['tachelist'] = "actionTacheListe;0";

//controleur_contrat <-- Simon
$lesPages['contratajout'] = "actionContratAjout;0";
$lesPages['contratmodif'] = "actionContratModifier;0";
$lesPages['contratsupp'] = "actionContratSupprimer;0";


if ($db!=null){
  if(isset($_GET['page'])){
    // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
    $page = $_GET['page']; }
  else{
    // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page
    //par défaut
    $page = 'accueil';
  }

  if (!isset($lesPages[$page])){
    // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
    $page = 'accueil'; 
  }
  
  $explose = explode(";",$lesPages[$page]);
  $role = $explose[1];
  
  // Si le rôle nécessite de contrôler les droits
  if ($role != 0){
      // Nous vérifions que la personne est connectée
      if(isset($_SESSION['login'])){
        //Nous vérifions qu'elle a un rôle
        if(isset($_SESSION['role'])){
            
            if($role!=$_SESSION['role']){
               //Nous redigeons la personne vers la page d'acccueil car elle n'a pas le bon rôle 
               $contenu = 'actionAccueil';  
            }
            else{
               // La personne est autorisée à récupérer  
               $contenu = $explose[0]; 
            }
        }
        else{
           // Dans la session le rôle n'existe pas donc on va sur la page d'accueil 
           $contenu = 'actionAccueil'; 
        }
      }
      else{
        // La personne n'est pas connectée, donc on va sur la page d'accueil  
        $contenu = 'actionAccueil';  
      }
    }else{
      // Nous donnons du contenu non protégé  
      $contenu = $explose[0];   
    }
}
else{
   // La base de données n'est pas accessible
   $contenu = 'actionMaintenance';
}
// La fonction envoie le contenu
return $contenu; 

}
?>