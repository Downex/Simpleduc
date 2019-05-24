<?php


function actionVoirLesMembres($twig, $db)
{
    $form = array();
    $membre = new Participation($db);
    $equipe = new Equipe($db);
    $projet = new Projet($db);
    $id = $_GET['id'];

        //Suppression
        if(isset($_GET['email'])){
            $exec=$membre->deleteById($_GET['email'], $_GET['id']);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table participation';
            }
            else{
                $form['valide'] = true;
            }
            $form['message'] = 'Dev retiré avec succès';
         }
    $liste = $membre->selectEquipeById($id);
    $titre = $equipe->selectById($id);
    $projetInfo = $projet->selectByidEquipe($id);

    $countMembre = $membre->countMembre($id);
    $countProject = $equipe->countProjetPerEquipe($id);
    echo $twig->render('equipe/equipe-membre.html.twig', array('form' => $form, 'liste' => $liste, 'titre' => $titre, 'countMembre' => $countMembre, 'countProject' => $countProject, 'projet'=>$projetInfo));
}

function actionAjouterMembre($twig, $db)
{
    $form = array();
    $developpeur = new Developpeur($db);
    $participation = new Participation($db);
    $equipe = new Equipe($db);
    $liste = $developpeur->selectAll();
    $idEquipe = $_GET['id'];
    $titre = $equipe->selectById($idEquipe);

    if (isset($_POST['btAjouter'])) {
        if ($_POST['Developpeur']) {
            foreach ($_POST['Developpeur'] as $valeur) {
                $participation->insert($valeur, $idEquipe);
            }
            header("Location:index.php?page=equipemembre&id=$idEquipe");
        } else {
            echo "Aucune checkbox n'a été cochée";
        }
    }
    echo $twig->render('equipe/equipe-membre-ajout.html.twig', array('form' => $form, 'liste' => $liste, 'equipe' => $titre));
}


?>