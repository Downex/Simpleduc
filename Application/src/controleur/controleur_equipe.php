<?php


//Liste toute les équipe
function actionEquipe($twig, $db){
    $form = array(); 
    $equipe = new Equipe($db);
    
    //Suppression
    if(isset($_GET['id'])){
        $exec=$equipe->deleteMembre($_GET['id']);
        $exec=$equipe->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table équipe';
        }
        else{
            $form['valide'] = true;
        }
        $form['message'] = 'Equipe supprimée avec succès';
     }
    
    //Liste des utilisateurs (Pour le modal ajout)
    $Dev = new Developpeur($db);
    $listeResponsable = $Dev->selectAll();
    $form['liste'] = $listeResponsable;

    //Liste de toute les équipes + complément
    $liste = $equipe->select();
    $nbEquipe = $equipe->countEquipe();
    $nbResponsable = $equipe->countResponsable();
    $nbMembre = $equipe->countMembre();
    $countProjet = $equipe->countProjet();

    echo $twig->render('equipe/equipe.html.twig', array('form'=>$form,'liste'=>$liste,'listeResponsable'=>$listeResponsable, 'nbEquipe'=>$nbEquipe, 'nbResponsable'=>$nbResponsable, 'nbMembre'=>$nbMembre, 'countProjet'=>$countProjet));
}

//Ajout d'une équipe
function actionEquipeAjout($twig, $db){
    $form = array(); 
    if (isset($_POST['btAjouter'])){
      $inputLibelle = $_POST['inputLibelle'];
      $inputIdResponsable = $_POST['inputIdResponsable']; 
      $form['valide'] = true;
      $equipe = new Equipe($db); 
      $exec = $equipe->insert($inputLibelle, $inputIdResponsable);
      if (!$exec){
        $form['valide'] = false;  
        $form['message'] = 'Problème d\'insertion dans la table équipe ';  
      }
    }

    header("Location:index.php?page=equipe");
}

//Modification d'une équipe
function actionEquipeModif($twig, $db){
    $form = array();   
    if(isset($_GET['id'])){
        $equipe = new Equipe($db);
        $uneEquipe = $equipe->selectById($_GET['id']);  
        
        if ($uneEquipe!=null){
            $form['equipe'] = $uneEquipe;
           
            $utilisateur = new Developpeur($db);
            $liste = $utilisateur->selectAll();
            $form['liste'] = $liste;
            
        }
        else{
            $form['message'] = 'Equipe incorrecte';  
        }
    }
    else{
        if(isset($_POST['btModifier'])){
          $id = $_POST['id'];  
          $libelle = $_POST['inputLibelle'];  
          $idResponsable = $_POST['inputIdResponsable'];
          $equipe = new Equipe($db);
          $exec = $equipe->update($id, $libelle, $idResponsable);
          if(!$exec){
                $form['valide'] = false;  
                $form['message'] .= 'Echec de la modification del\'équipe'; 
          }
          else{
            $form['valide'] = true;  
            $form['message'] = 'Modification réussie';  
          } 
          
        }
        else{
            $form['message'] = 'Utilisateur non précisé';
        }    
     
    }
    
    echo $twig->render('equipe/equipe-modif.html.twig', array('form'=>$form));
}


//WebService
function actionEquipeWS($twig, $db){
   $equipe = new Equipe($db);
   $json = json_encode($liste = $equipe->select()); 
   echo $json; 
}

