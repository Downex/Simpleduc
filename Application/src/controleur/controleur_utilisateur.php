<?php

function actionUtilisateur($twig, $db){
    $form = array(); 
    $utilisateur = new Utilisateur($db);
    $dev = new Developpeur($db);
    $role = new Role($db);
     if(isset($_GET['id'])){
        /* Nous vérifions que l'utilisateur n'est pas responsable d'une équipe
           Car nous n'avons pas souhaité faire un DELETE ON CASCADE  
         */ 
        $equipe = new Equipe($db);
        $liste = $equipe->selectByIdResponsable($_GET['id']);
        if($liste==null){
            $exec=$utilisateur->delete($_GET['id']);
            $exec=$dev->delete($_GET['id']);

            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table utilisateur';
            }
            else{
                $form['valide'] = true;
                $form['message'] = 'Utilisateur supprimé avec succès';
            }
        }
        else{
           $form['valide'] = false;
           $form['message'] = 'Impossible de supprimer l\'utilisateur, il est affecté à des équipes'; 
        }
       
     }
     $listeRole = $role->select();
     $liste = $utilisateur->select();
     
     $countAdmin = $utilisateur->countAdmin();
     $countDev = $utilisateur->countDev();
     $countUser = $utilisateur->countUser();
     $countAll = $utilisateur->countAll();
     echo $twig->render('utilisateur/utilisateur.html.twig', array('form'=>$form,'liste'=>$liste, 'listeRole'=>$listeRole, 'countAll'=>$countAll, 'countAdmin'=>$countAdmin, 'countDev'=>$countDev, 'countUser'=>$countUser));
}

function actionUtilisateurModif($twig, $db){
  $form = array();
  /*  Formulaire d'utilisateur */
  if(isset($_GET['id']))
  {
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_GET['id']);
    $remuneration = new Remuneration($db);
    $listeRemuneration = $remuneration->selectAll();
    $developpeur = new Developpeur($db);
    $unDeveloppeur = $developpeur->selectById($_GET['id']); 
    $dev = $unUtilisateur['dev'];
    $email = $unUtilisateur['email'];
    if(isset($_POST['btModifier'])){
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      if ($unUtilisateur['idRole'] == 2){
        $role = 2;
      }
      else
      {
        $role = $_POST['role'];
      }
      $exec=$utilisateur->update($email, $role, $nom, $prenom, $dev);
      if(!$exec){
        $form['valide'] = false;  
        $form['message'] = 'Echec de la modification des données. '; 
      }
      else
      {
        $form['valide'] = true;  
        $form['message'] = 'Modification des données réussie. ';  
      }
      if(!empty($_POST['inputPassword'])){
        $p1 = $_POST['inputPassword'];
        $p2 = $_POST['inputPassword2'];
        if ($p1==$p2){
          $p1 = password_hash($p1, PASSWORD_DEFAULT);
          $exec=$utilisateur->updateMdp($email, $p1);
          if(!$exec){
            $form['valide'] = false;  
            $form['message'] = 'Echec de la modification du mot de passe'; 
          }
          else
          {
            $form['valide'] = true;  
            $form['message'] = 'Modification réussie du mot de passe';  
          } 
        }
        else
        {
          $form['valide'] = false;  
          $form['message'] = 'Echec de la modification du mot de passe';   
        }    
      }
      header('Location:index.php?page=utilisateurinfo&id='.$email);
    }  
    if ($unDeveloppeur!=null)
    {
      $form['developpeur'] = $unDeveloppeur;
      $remuneration = new Remuneration($db);
      $form['remuneration']=$listeRemuneration;
      //$form['maRem'] = $maRemuneration;
    }
    else
    {
      $form['message'] = 'Développeur incorrect';  
    }
    if ($unUtilisateur!=null)
    {
      $form['utilisateur'] = $unUtilisateur;
      $role = new Role($db);
      $liste = $role->select();
      $form['roles']=$liste;
    }
    else
    {
      $form['message'] = 'Utilisateur incorrect';  
    }
  }
  else
  {
/*  Formulaire de développeur */
    if(isset($_POST['btAjoutDev'])){
      // if($unUtilisateur['dev'] == 1){
        $idDev = $_POST['idDev'];
        $nom = $_POST['nom'];
        $prenom = $_SESSION['prenom'];
        $tel = $_POST['tel'];
        if(!preg_match("#^0[1-7]([0-9]{2}){4}$#", $tel)){
          $form['valide'] = false;
          $form['message'] = 'Le numéro de téléphone n\'est pas valide ! (exemple : 0XXXXXXXXX)';
        }
        else
        {
          $indiceRemuneration = $_POST['indiceRemuneration'];
          $idRem = $_POST['idRem'];
          // echo' idDev = '.$idDev.' nom = '.$nom.' prenom = '.$prenom.' tel = '.$tel.' indiceRemuneration = '.$indiceRemuneration.' idRem = '.$idRem;
          if($idRem == 0){
            $form['valide'] = false;
            $form['message'] = 'Le coût horaire sélectionné n\'est pas valide ! Veuillez en sélectionner un autre. 2';
          }else{
            $form['valide'] = true;
            $developpeur = new Developpeur($db);
            $unDeveloppeur = $developpeur->insert($idDev, $nom, $prenom, $tel, $indiceRemuneration, $idRem);
            echo 'insert : '.$idDev.', '.$nom.', '.$prenom.', '.$tel.', '.$indiceRemuneration.', '.$idRem;
            $role = $_POST['idrole'];
            $dev = 1;
            $Utilisateur = new Utilisateur($db);
            $unUtilisateur = $Utilisateur->update($idDev, $role, $nom, $prenom, $dev);
            echo '  update user : idDev : '.$idDev.', nom : '.$nom.', prenom : '.$prenom.',  role : '.$role.', dev : '.$dev;
            
            if(!$unUtilisateur){
              $form['valide'] = false;
              $form['message'] = 'Problème de modification de la table utilisateur';
              if(!$unDeveloppeur){
                $form['valide'] = false;
                $form['message'] =  'Problème d\'insertion dans la table développeur '; 
              }
            }
            else
            {
              $form['valide'] = true;
              $form['message'] = 'Votre profil développeur a été modifié avec succès !';
            }
          }
        }
        header('Location:index.php?page=utilisateurinfo&id='.$idDev);
      }
      // }
      // else
      // {
    else if(isset($_POST['btModifDev'])){
        // $idDev = $_POST['idDev'];
        // $nom = $_POST['nom'];
        // $prenom = $_POST['prenom'];
        // $tel = $_POST['tel'];
        // if(!preg_match("#^0[1-7]([0-9]{2}){4}$#", $tel)){
        //   $form['valide'] = false;
        //   $form['message'] = 'Le numéro de téléphone n\'est pas valide ! (exemple : 0XXXXXXXXX)';
        // }
        // else
        // {
        //   $indiceRemuneration = $_POST['indiceRemuneration'];
        //   $idRem = $_POST['idRem'];
        //   if($idRem == 0){
        //     $form['valide'] = false;
        //     $form['message'] = 'Le coût horaire sélectionné n\'est pas valide ! Veuillez en sélectionner un autre. 3';
        //   }
        //   else
        //   {
        //     $form['valide'] = true;
        //     $Developpeur = new Developpeur($db);
        //     $unDeveloppeur = $Developpeur->insert($idDev, $nom, $prenom, $tel, $indiceRemuneration, $idRem);
        //     echo'1 : '.$idDev.' 2 : '.$nom.' 3 : '.$prenom.' 4 : '.$tel.' 5 : '.$indiceRemuneration.' 6 : '.$idRem;
        //     $email = $_SESSION['login'];
        //     $role = $_SESSION['role'];
        //     $dev = 1;
        //     $Utilisateur = new Utilisateur($db);
        //     $updateUser = $Utilisateur->update($email, $role, $nom, $prenom, $dev);
        //     // echo' user = 1 : '.$idDev.' 2 : '.$nom.' 3 : '.$nom.' 4 : '.$prenom.' 5 : '.$dev;            
        //     if(!$updateUser){
        //       $form['valide'] = false;
        //       $form['message'] = 'Problème de modification de la table utilisateur';
        //       if(!$unDeveloppeur){
        //         $form['valide'] = false;
        //         $form['message'] =  'Problème d\'insertion dans la table développeur '; 
        //       }
        //     }
        //     else
        //     {
        //       $form['valide'] = true;
        //       $form['message'] = 'Modification du profil développeur réussie !';
        //     }
        //     //  header('Location:index.php?page=utilisateurinfo&id='.$idDev);
        //   }
        // }
        $developpeur = new Developpeur($db);
        $idDev = $_POST['idDev'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $tel = $_POST['tel'];
        if(!preg_match("#^0[1-7]([0-9]{2}){4}$#", $tel)){
          $form['valide'] = false;
          $form['message'] = 'Le numéro de téléphone n\'est pas valide ! (exemple : 0XXXXXXXXX)';
        }
        else
        {
          $indiceRemuneration = $_POST['indiceRemuneration'];
          $idRem = $_POST['idRem'];
          if($idRem == 0){
            $form['valide'] = false;
            $form['message'] = 'Le coût horaire sélectionné n\'est pas valide ! Veuillez en sélectionner un autre. 3';
          }else{
            $form['valide'] = true;
            $Developpeur = new Developpeur($db);
            $unDeveloppeur = $Developpeur->update($idDev, $nom, $prenom, $tel, $indiceRemuneration, $idRem);
            if(!$unDeveloppeur){
              $form['valide'] = false;
              $form['message'] =  'Problème d\'insertion dans la table développeur '; 
            }
          }
        }
        header('Location:index.php?page=utilisateurinfo&id='.$idDev);
      }
    
  }
  echo $twig->render('utilisateur/utilisateur-modif.html.twig', array('form'=>$form,'listeRemuneration'=>$listeRemuneration));
}


function actionUtilisateurAjout($twig, $db){
  $form = array(); 
  if (isset($_POST['btInscrire'])){
    $inputEmail = $_POST['inputEmail'];
    $inputPassword = $_POST['inputPassword']; 
    $inputPassword2 =$_POST['inputPassword2']; 
    $nom = $_POST['nom']; 
    $prenom =$_POST['prenom']; 
    $role = $_POST['selectRole'];
    $form['valide'] = true;
    if ($inputPassword!=$inputPassword2){
      $form['valide'] = false;  
      $form['message'] = 'Les mots de passe sont différents';
    }
    else{
      $utilisateur = new Utilisateur($db); 
      $exec = $utilisateur->insert($inputEmail, password_hash($inputPassword, PASSWORD_DEFAULT), $role, $nom, $prenom);
      if (!$exec){
        $form['valide'] = false;  
        $form['message'] = 'Problème d\'insertion dans la table utilisateur '; 
     
      }
    }
    
    $form['email'] = $inputEmail;
    $form['role'] = $role;
    
  }
  header("Location:index.php?page=utilisateur");
}

function actionUtilisateurInfo($twig, $db){
  if(isset($_GET['id'])){
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_GET['id']);
    $developpeur = new Developpeur($db);
    $unDeveloppeur = $developpeur->selectById($_GET['id']);
    $equipe = new Equipe($db);
    $uneEquipe = $equipe->selectByIdResponsable($_GET['id']);
    $role = new Role($db);
    $unRole = $role->selectRoleByEmail($_GET['id']);
    $domaine = new Domaine($db);
    $unDomaine = $domaine->selectAll();
    $moy = [];
    $maitriseTab = 0;
    $toutRole = 0;
    for($i=0; $i<count($unDomaine); $i++){
        // Count comp/domaine d'un user
      $idDomaine = (int)$unDomaine[$i]['idDomaine'];
      $maitrise = new Maitrise($db);
      $unMaitrise=$maitrise->countMyByDomaine($_GET['id'], $idDomaine); //count nb comp/domaine d'un user
      // var_dump($unMaitrise['0']); <- Affichage simplifié
      $maitriseTab += $unMaitrise['0'];

        // Count comp/domaine
      $competence = new Competence($db);
      $unCompetence=$competence->countAllByDomaine($idDomaine);
      // var_dump($unCompetence['0']); <-Affichage simplifié

      for($j=0; $j<count($unCompetence); $j++){
        if($unCompetence[$j] != 0){
          // echo $unMaitrise[$j].'/'.$unCompetence[$j].' --- ';
          $unMoy = ceil(($unMaitrise[$j]/$unCompetence[$j])*100);
          array_push($moy, $unMoy);
        }
        $j = $j+1;
      }
    }
    // $i = 0;
    // foreach($unMaitrise as $m){
    //   // var_dump(($m/$unCompetence[$i])*100);
    //   $i++;
    // }
    // var_dump($unCompetence['0']);
    // var_dump(ceil(($unMaitrise/$unCompetence)*100));


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
    if ($unUtilisateur!=null){
      $form['utilisateur'] = $unUtilisateur;
      if($unUtilisateur['dev'] != null){
        $form['developpeur'] = $unDeveloppeur;
      }
    }
    else{
      $form['message'] = 'Utilisateur incorrect';  
    }
 }
  echo $twig->render('utilisateur/utilisateur-info.html.twig',array('form'=>$form, 'uneEquipe'=>$uneEquipe, 'session'=>$_SESSION, 'unRole'=>$unRole,'toutRole'=>$toutRole, 'domaine'=>$unDomaine, 'tab'=>$maitriseTab, 'moy'=>$moy));
}
?>