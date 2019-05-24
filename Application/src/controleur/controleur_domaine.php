<?php
function AjoutDomaine($twig, $db){
    $form = array();
    $domaine = new Domaine($db);
    $unDomaine = $domaine->selectAll();
    $form['domaine'] = $unDomaine;
    if(isset($_POST['btAjoutDomaine'])){
        $nomDomaine = $_POST['nomDomaine'];
        $unDomaine = $domaine->insert($nomDomaine);
        if(!$unDomaine){
            $form['valide'] = false;
            $form['message'] = 'Erreur lors de l\insertion !';            
        }
        else
        {
            $form['valide'] = true;
            $form['message'] = 'Insertion réussie !';
        }
    }
    header("Location:index.php?page=domaine");
}

function ModifDomaine($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
        $domaine = new Domaine($db);
        $unDomaine = $domaine->selectOne($_GET['id']);
        if($unDomaine != null){
            $form['domaine'] = $unDomaine;
        }
        else
        {
            $form['message'] = 'Domaine incorrecte';
        }
    }
    else
    {
        if(isset($_POST['btModifDomaine'])){
            $domaine = new Domaine($db);
            $idDomaine = $_POST['idDomaine'];
            $nomDomaine = $_POST['nomDomaine'];
            $unDomaine = $domaine->update($idDomaine, $nomDomaine);
            if(!$unDomaine){
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
            $form['message'] = 'Domaine non précisé !';
        }
    }
    echo $twig->render('domaine/domaine-modif.html.twig', array('form'=>$form));
}

function AfficheDomaine($twig, $db){
    $form= array();
    $domaine = new Domaine($db);
    $unDomaine = $domaine->selectAll();
    $form['domaine'] = $unDomaine;
    $count = $domaine->count();
    $competence = new Competence($db);
    $countComp = $competence->countAll();
    if(isset($_GET['id'])){
        $unDomaine = $domaine->delete($_GET['id']);
        header("Location:index.php?page=domaine");
        if(!$unDomaine){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Domaine';
        }
        else
        {
            $form['valide'] = false;
            $form['message'] = 'Domaine supprimé avec succès !';
        }
    }
    echo $twig->render('domaine/domaine.html.twig', array('form'=>$form, 'count'=>$count, 'countComp'=>$countComp));
}
?>