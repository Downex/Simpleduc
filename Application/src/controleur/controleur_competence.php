<?php
function AjoutCompetence($twig, $db){
    $form = array();
    $domaine = new Domaine($db);
    $unDomaine = $domaine->selectAll();
    $form['domaine'] = $unDomaine;
    if(isset($_POST['btAjoutCompetence']))
    {
        $libelle = $_POST['inputNom'];
        $idDomaine = $_POST['idDomaine'];
        $competence = new Competence($db);
        $unCompetence = $competence->insert($libelle, $idDomaine);
        if (!$unCompetence)
        {
            $form['valide'] = false;  
            $form['message'] = 'Problème d\'insertion dans la table utilisateur '; 
       
        }
        else
        {
            $form['valide'] = true;
            $form['message'] = 'Compétence ajoutée !';
        }
    }
    header("Location:index.php?page=competence");
}

function ModifCompetence($twig, $db){
    $form = array();
    if(isset($_GET['id']))
    {
        $competence = new Competence($db);
        $unCompetence = $competence->selectById($_GET['id']);
        if($unCompetence != null){
            $form['competence'] = $unCompetence;
            $domaine = new Domaine($db);
            $unDomaine = $domaine->selectAll();
            $form['domaine'] = $unDomaine;
        }
        else
        {
            $form['message'] = 'Competence incorrecte';
        }
    }
    else
    {
        if(isset($_POST['btModifCompetence']))
        {
            $competence = new Competence($db);
            $idCompetence = $_POST['idCompetence'];
            $libelle = $_POST['libelle'];
            $idDomaine = $_POST['idDomaine'];
            $unCompetence = $competence->update($idCompetence, $libelle, $idDomaine);
            if(!$unCompetence)
            {
                $form['valide'] = false;
                $form['message'] = 'Erreur lors de la modification';
            }
            else
            {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        }
        else
        {
            $form['message'] = 'Competence non précisée';
        }
    }
    echo $twig->render('competence/competence-modif.html.twig', array('form'=>$form));
}

function AfficheCompetence($twig, $db){
    $form = array(); 
    $competence = new Competence($db);
    $unCompetence = $competence->selectAll();
    $domaine = new Domaine($db);
    $unDomaine = $domaine->selectAll();
    $form['domaine']=$unDomaine;
    $countDom = $domaine->count();
    $competence = new Competence($db);
    $count = $competence->countAll();
    
     if(isset($_GET['id']))
     {
        $unCompetence=$competence->delete($_GET['id']);
        header("Location:index.php?page=competence");
        if (!$unCompetence)
        {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table compétence';
        }
        else
        {
            $form['valide'] = true;
            $form['message'] = 'Compétence supprimé avec succès';
        }
     }
     $form['competence'] = $unCompetence;
     echo $twig->render('competence/competence.html.twig', array('form'=>$form, 'count'=>$count, 'countDom'=>$countDom));
}

function CompetenceList($twig, $db){
    $form = array();
    $domaine = new Domaine($db);
    $form['domaine'] = $domaine->selectAll();
    // $competence = new Competence($db);
    // for($i=0; $i<count($form['domaine']); $i++){
    //     $form['competence'] = $competence->selectByDomaine($form['domaine'][$i]['idDomaine']);
    //     var_dump($form['competence']);
    // }
    // // $form['competence'] = $competence->selectByDomaine($idDomaine);
    // // var_dump($form['competence']);
    // // $form['cmptDomaine'] = $competence->selectAllByDomaine();
    // $maitrise = new Maitrise($db);
    // $my = $maitrise->selectMy($_SESSION['login']);
    // for($i = 0; $i < count($my); $i++){
    //     if($my[$i]['idDomaine'] == 1){
    //         $domaine1 = $my[$i];
    //     }
    // }

    $competence = new Competence($db);

    //Selection compétences par domaines 
    $array = [];
    for($i=0;$i<count($form['domaine']); $i++)
    {
        $compByDom = $competence->selectByDomaine($form['domaine'][$i]['idDomaine']);
        if($form['domaine'][$i]['idDomaine'] == 1)
        {
            // var_dump($compByDom);
        }
        array_push($array, $compByDom);
    }
    //fin

    $maitrise = new Maitrise($db);
    $my = $maitrise->selectAllId($_SESSION['login']);
    
    // $my=$maitrise->selectMy($_SESSION['login']);
    // var_dump($my);
    $array2 = $competence->selectAllId();
    
    $array3= [];

    $tab = array_column($my, 'idCompetence');
    $i=0;
    foreach($array2 as $d)
    {
        if(array_search($d['idCompetence'], $tab) !== false)
        {
            // echo '<p style="color: blue;">'.$d['idCompetence'].' = '.$i.' -> '.array_search($d['idCompetence'], $tab).'</p>';
            $d['result'] = "oui";
            array_push($array3, $d);
        }
        else
        {
            // echo '<p style="color: red;">'.$d['idCompetence'].' = '.$i.'</p>';
            $d['result'] = "non";
            array_push($array3, $d);
        }
        $i = $i +1;
    }

    // var_dump($array3);

    $myMaitrise = $maitrise->countMy($_SESSION['login']);
    if(isset($_POST['btAddCompetence']))
    {
        $unMaitrise = new Maitrise($db);
        if($myMaitrise[0] == 0){
            //Insert
            $liste = $_POST['cocher'];
            $idDev = $_SESSION['login'];
            foreach($liste as $idCompetence){
                $nb = $unMaitrise->insert($idCompetence, $idDev);
                echo 'insert : '.$liste;
            }
        }else{
            //Update
            $liste = $_POST['cocher'];
            $idDev = $_SESSION['login'];
            foreach($my as $idCompetence){
                // echo 'delete: '.$idCompetence['idCompetence'].' <br/>';
                $nb = $unMaitrise->delete($idDev, $idCompetence['idCompetence']);
            }
            foreach($liste as $idCompetence){
                // echo 'insert: '.$idCompetence.' <br/>';
                $nb = $unMaitrise->insert($idCompetence, $idDev);
            }
        }
        header("Location:index.php?page=utilisateurinfo&id=".$_SESSION['login']);
    }
    echo $twig->render('competence/competence-list.html.twig', array('form'=>$form, 'comp'=>$array, 'my'=>$my, 'array3'=>$array3));
}
?>