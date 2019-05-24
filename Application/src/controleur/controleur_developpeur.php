<?php
function ActionDeveloppeur($twig, $db){
	$form = array(); 
    $developpeur = new Developpeur($db);
	$dev=$developpeur->selectAll();
	$count=$developpeur->countDev();
	$projet = new Projet($db);
	$countResp=$projet->countResponsable();
	$countProjet=$projet->countProjet();
	$countProjetEnd=$projet->countProjetEnd();
	echo $twig->render('developpeur/developpeur.html.twig', array('form'=>$form, 'dev'=>$dev, 'count'=>$count, 'countResp'=>$countResp, 'countProjet'=>$countProjet, 'ended'=>$countProjetEnd));
}
?>