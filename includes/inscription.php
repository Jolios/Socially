<?php
//Declaration des variables
$prenom="";
$nom="";
$em="";//Email
$em="";//Email 2
$mdp="";//Mot de passe
$mdp2="";//Mot de passe 2
$date=""; //Date d'inscription
$pesudo="";//Pseudo
$tab_err=array();//Tab d'erreurs


if(isset($_POST['reg_btn'])){    //si utl appuie sur inscription

	//prenom
	$prenom = strip_tags($_POST['reg_prenom']); //effacer les tags html
	$prenom=str_replace(' ', '', $prenom);//effacer les espaces
	$prenom=ucfirst(strtolower($prenom));//Capitaliser prenom 
	$_SESSION['reg_prenom']=$prenom;
	
	//nom
	$nom = strip_tags($_POST['reg_nom']); 
	$nom=str_replace(' ', '', $nom);
	$nom=ucfirst(strtolower($nom));
	$_SESSION['reg_nom']=$nom;
	
	//e-mail
	$em = strip_tags($_POST['reg_mail']); 
	$em = str_replace(' ', '', $em);
	$_SESSION['reg_mail']=$em;
	//e-mail 2
	$em2 = strip_tags($_POST['reg_mail2']); 
	$em2 = str_replace(' ', '', $em2);
	$_SESSION['reg_mail2']=$em2;
	//Mot de passe
	$mdp = strip_tags($_POST['reg_mdp']);
	$_SESSION['reg_mdp']=$mdp;
	//Mot de passe 2
	$mdp2 = strip_tags($_POST['reg_mdp2']);
	$_SESSION['reg_mdp2']=$mdp2;

	$date = date("Y-m-d"); //format accepté par mysql

	if($em==$em2){
		//Verifier le format de l'e-mail
		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
			//$em=filter_var($em, FILTER_VALIDATE_EMAIL)
			//verifier si l'email existe deja
			$req_em=mysqli_query($con,"SELECT email FROM utilisateur WHERE email='$em'");

			if(mysqli_num_rows($req_em)>0){
				array_push($tab_err, "l'e-mail existe déja<br>");  
			}

		}else{
			array_push($tab_err, "Format e-mail invalide<br>");
		}
	}
	else{
		array_push($tab_err, "Veuillez saisire le même e-mail<br>") ;
	}

	if(strlen($prenom)>25 || strlen($prenom)<2){
		array_push($tab_err, "La longeur de votre prénom doit être entre 2 et 25<br>");
	}
	if(strlen($nom)>25 || strlen($nom)<2){
		array_push($tab_err, "La longueur de votre nom doit être entre 2 et 25<br>");
	}
	if($mdp==$mdp2){
		if(preg_match('/[^A-Za-z0-9]/', $mdp)){
			array_push($tab_err, "Votre mot ne de passe doit contenir que des lettres et des chiffres<br>");
		}
	}else{
		array_push($tab_err, "Veuillez saisire le même mot de passe<br>");
	}
	if(strlen($mdp) > 30 || strlen($mdp) < 5){
		array_push($tab_err, "La longueur de votre mot de passe doit être entre 5 et 30<br>");
	}
	if(empty($tab_err)){
		//$mdp= md5($mdp) n'oublie pas de modifier taille de mdp dans la table 

		//Generer pseudo en concaténant nom et prenom
		$pseudo = strtolower($prenom."_".$nom);
		$req_pseudo = mysqli_query($con,"SELECT pseudo FROM utilisateur WHERE pseudo='$pseudo'");
		$i=0;
		//si le pseudo existe deja concatener un nombre;
		while(mysqli_num_rows($req_pseudo)!=0){
			$i++; 
			$pseudo=$pseudo."_".$i;
			$req_pseudo=mysqli_query($con,"SELECT pseudo FROM utilisateur WHERE pseudo='$pseudo'");
		}
		//Attribution photo profile
		$alea = rand(1,2);

		$photo_profile="res/image/photos_de_profile/".$alea.".png";
		$requete= mysqli_query($con,"INSERT INTO utilisateur VALUES(NULL,'$prenom','$nom','$pseudo','$em','$mdp','$date','$photo_profile','0','0',',robo_gray,robo_pat,robo_green,')");

		array_push($tab_err, "<span style='color:#14C800;'>Votre compte a été créé</span><br>");
	
	}

	//effacer var session
	$_SESSION['reg_prenom']="";
	$_SESSION['reg_nom']="";
	$_SESSION['reg_mail']="";
	$_SESSION['reg_mail2'  ]="";
}
?>