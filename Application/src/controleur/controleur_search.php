<?php
function Search($twig, $db){
    $form = array();
    if(isset($_POST['search'])){
        $mySearch = $_POST['research'];
        $search = new Search($db);
        $searchProjets=$search->selectProjets($mySearch);
        $searchUtilisateurs=$search->selectUtilisateurs($mySearch);
        $searchEquipes=$search->selectEquipes($mySearch);
        $searchCompetences=$search->selectCompetences($mySearch);
        if(!$search){
            $form['valide'] = false;
            $form['message'] = 'Une erreur est survenue lors de la recherche !';
        }
    }
    echo $twig->render('index/search.html.twig', array('form'=>$form, 'projets'=>$searchProjets, 'user'=>$searchUtilisateurs, 'equipe'=>$searchEquipes, 'competence'=>$searchCompetences, 'search'=>$mySearch));
}
?>