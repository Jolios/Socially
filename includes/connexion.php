<?php
if(isset($_POST['log_btn'])){    //si utl appuie sur connexion
	$email=$_POST['log_mail'];
	$_SESSION['log_mail']=$email;
	$mdp=$_POST['log_mdp'];
	$req_login=mysqli_query($con,"SELECT * FROM utilisateur WHERE email='$email' AND mot_de_passe='$mdp'");

	if(mysqli_num_rows($req_login)==1){
		$tab=mysqli_fetch_array($req_login);
		$pseudo=$tab[3];
		$_SESSION['pseudo']=$pseudo; //a utiliser pour verifier si l'utilasteur est connecté
		header('Location: index.php'); //redirection vers index.php
		exit();
	}else{
		array_push($tab_err, "Adresse e-mail ou mot de passe invalide<br>");
	}
}
?>