<?php 
function ListeProjets($twig, $db){
    $form = array();
    $projet = new Projet($db);
    $equipe = new Equipe($db);
    $uneEquipe = $equipe->selectByIdResponsable($_SESSION['login']);
    for($i = 0; $i < count($uneEquipe); $i++){
      $idEquipe = (int)$uneEquipe[$i]['id'];
      $projet = new Projet($db);
      $liste = $projet->selectAllMy($_SESSION['login'], $idEquipe);
      for($j = 0; $j < count($liste); $j++){
        $id = $liste[$j]['projet'];
        if(!isset($form['myprojet'][$id])){
            $form['myprojet'][$id] = $liste[$j];
            // echo $id.'</br>';
        }
      }
    } 
    // var_dump($form['myprojet']);
    $form['projet'] = $projet->selectAll();
    // $form['myProjet'] = $projet->selectAllMy();
    echo $twig->render('projet/projet.html.twig', array('form'=>$form));
}

function AjoutProjet($twig, $db){
    $form = array();
    $date = new DateTime();
    $equipe = new Equipe($db);
    $form['equipe'] = $equipe->select();
    if(isset($_POST['btAjoutProjet'])){
        $libelle = $_POST['nom'];
        $description = $_POST['description'];
        $responsable = $_POST['responsable'];
        $budget = $_POST['budget'];
        $date_lancement = $date->format('d-m-Y');
        $date_livraison = $_POST['livraison'];
        if (!preg_match ( "#^[0-3][0-9]\-[0-1][0-9]\-[0-9]{4}# " , $date_livraison ))
        {
            $form['valide'] = false;  
            $form['message'] = 'Format de date non valide (dd-mm-yyyy)';
        }
        else
        {
            $etat = "Démarrage";
            $idEquipe = $_POST['equipe'];
            
            $projet = new Projet($db); 
            $exec = $projet->insert($libelle, $description, $responsable, $budget, $date_lancement, $date_livraison, $etat, $idEquipe);
            if (!$exec){
                $form['valide'] = false;  
                $form['message'] = 'Problème d\'insertion dans la table utilisateur '; 
            }else{
                header("Location:index.php?page=projets");
            }
        }
    }
    
    echo $twig->render('projet/ajout-projet.html.twig', array('form'=>$form));
}

function ModifProjet($twig, $db){
    $form = array();   
    if(isset($_GET['id'])){
       $projet = new Projet($db);
       $unProjet = $projet->selectById($_GET['id']);  
       if ($unProjet!=null){
         $form['projet'] = $unProjet;
         $equipe = new Participation($db);
         $form['equipe'] = $equipe->selectEquipeById($unProjet['idEquipe']);
       }
       else{
         $form['message'] = 'Erreur lors de la sélection du projet';  
       }
    }
    else{
        if(isset($_POST['btModifProjet'])){
          $projet = new Projet($db);
          $id = $_POST['id'];
          $libelle = $_POST['nom'];
          $description = $_POST['description'];
          $responsable = $_POST['responsable'];
          $budget = $_POST['budget'];
          $date_lancement = $_POST['date_lancement'];
          $date_livraison = $_POST['date_livraison'];
          $etat = $_POST['etat'];
          $idEquipe = $_POST['idEquipe'];
          $exec=$projet->update($id, $libelle, $description, $responsable, $budget, $date_lancement, $date_livraison, $etat, $idEquipe);
          if(!$exec){
            $form['valide'] = false;  
            $form['message'] = 'Echec de la modification des données. '; 
          }
          else{
            $form['valide'] = true;  
            $form['message'] = 'Modification des données réussie. ';
          }
        }
        else{
          $form['message'] = 'Erreur : La modification des données a échouée';
        }  
    }
    echo $twig->render('projet/projet-modif.html.twig', array('form'=>$form));
}

function SupprimerProjet($twig, $db){
    $form = array();
    $projet = new Projet($db);
    $unProjet = $projet->selectById($_GET['id']);
    if(isset($_POST['dltProjet'])){     
        $id = $_POST['idProjet']; 
        $exec=$projet->delete($id);
        var_dump($exec);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression du projet';
        }
        else{
            $form['valide'] = true;
            $form['message'] = 'Projet supprimé avec succès';
        }
        // if (!$exec){
        //     $form['valide'] = false;  
        //     $form['message'] = 'Problème d\'insertion dans la table utilisateur '; 
        // }else{
        //     header("Location:index.php?page=projets");
        // }
    }
    echo $twig->render('projet/projet-supp.html.twig'), array('form'=>$form, 'projet'=>$unProjet);
}

function ProjetDetails($twig, $db){
    if(isset($_GET['id'])){
        $projet = new Projet($db);
        $unProjet = $projet->selectById($_GET['id']);
        $projetequipe = $projet->selectEquipe($unProjet['id']);
        $user = new Utilisateur($db);
        $responsable = $user->selectByEmail($unProjet['responsable']);
        $equipe = new Participation($db);
        $form['equipe'] = $equipe->selectEquipeById($unProjet['idEquipe']);
    }
    else{
        header("Location:index.php");
    }

    echo $twig->render('projet/projet-details.html.twig', array('form'=>$form, 'responsable'=>$responsable, 'projet'=>$unProjet, 'equipe'=>$projetequipe));
}
?>