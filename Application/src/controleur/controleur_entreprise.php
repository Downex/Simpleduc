<?php 


// Liste de toute les entreprises
function actionEntreprise($twig, $db){
    $form = array(); 
    $entreprise = new Entreprise($db);
    
    // Suppresion
    if(isset($_GET['id'])){
        $exec=$entreprise->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème avec la suppression dans la table Entreprise';
        }
        else {
            $form['valide'] = true; 
        }
        $form['message'] = 'Entreprise supprimée avec succès';
        
    }
    $liste = $entreprise->select();
    echo $twig->render('entreprise/entreprise.html.twig', array('form'=>$form,'liste'=>$liste));
}

// Ajout d'une entreprise
function actionEntrepriseAjout($twig, $db){
    $form = array();
    if (isset($_POST['btAjouter'])){
        $inputAdresse = $_POST['inputAdresse'];
        $inputCp = $_POST['inputCp'];
        $inputVille = $_POST['inputVille'];
        $inputNomContact = $_POST['inputNomContact'];
        $inputNomEntreprise = $_POST['inputNomEntreprise'];
        $form['valide'] = true; 
        $entreprise = new Entreprise($db);
        $exec = $entreprise->insert($inputAdresse, $inputCp, $inputVille, $inputNomContact, $inputNomEntreprise);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème l\'ors de la création d\' une entreprise ';
        }
    } 
    echo $twig->render('entreprise/entreprise-ajout.html.twig', array('form'=>$form));
}

// Modification d'une Entreprise
function actionEntrepriseModif($twig, $db){
    $form = array();
    if(isset($_GET['id'])){
        $entreprise = new Entreprise ($db);	
        $uneEntreprise = $entreprise->select($_GET['id']);
        if ($uneEntreprise!=null){
            $form['entreprise'] = $uneEntreprise;
        }
        else {
            $form['message'] = 'Entreprise incorrecte';
        }
        
    }
    else{
        if(isset($_POST['btnModifier'])){
            $id = $_POST['id'];
            $inputAdresse = $_POST['inputAdresse'];
            $inputCp = $_POST['inputCp'];
            $inputVille = $_POST['inputVille'];
            $inputNomContact = $_POST['inputNomContact'];
            $inputNomEntreprise = $_POST['inputNomEntreprise'];
            $exec = $entreprise->update($inputAdresse, $inputCp, $inputVille, $inputNomContact, $inputNomEntreprise);
            if(!$exec){
                $form['valide'] = true;  
                $form['message'] = 'Modification réussie';  
            }        
        }
        else{
            $form['valide'] = true;  
            $form['message'] = 'Modification réussie'; 
        } 
    }
    echo $twig->render('entreprise/entreprise-modif.html.twig',array('form'=>$form));
}

?>