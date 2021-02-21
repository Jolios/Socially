<?php 
if(isset($_POST['parm_perso'])){
	$prenom=$_POST['prenom'];
	$nom=$_POST['nom'];
	$em=$_POST['email'];

	//verifier si le nouveau email n'existe pas deja
	$req = mysqli_query($con,"SELECT * FROM utilisateur WHERE email='$em'");
	$tab=mysqli_fetch_array($req);
	$utl_trouve=$tab['pseudo'];

	if($utl_trouve == "" || $utl_trouve == $utilisateurConnecte){
		$msg="Modifications enregistrées<br><br>";
		$req=mysqli_query($con,"UPDATE utilisateur SET prenom='$prenom',nom='$nom',email='$em' WHERE pseudo='$utilisateurConnecte'");
	}
	else
		$msg="l'e-mail existe déja!<br><br>";
}
else
	$msg= "";


//****************************************
if(isset($_POST['parm_mdp'])){
	$ancien_mdp= strip_tags($_POST['a_mdp']);
	$nouveau_mdp_1= strip_tags($_POST['n_mdp_1']);
	$nouveau_mdp_2=strip_tags($_POST['n_mdp_2']);
	$req=mysqli_query($con,"SELECT mot_de_passe FROM utilisateur WHERE pseudo='$utilisateurConnecte'");
	$tab=mysqli_fetch_array($req);
	$mdp_bd = $tab['mot_de_passe'];
	if($ancien_mdp == $mdp_bd){
		if($nouveau_mdp_1 == $nouveau_mdp_2){
			if(strlen($nouveau_mdp_1) > 30 || strlen($nouveau_mdp_1) < 5){
				$msg_mdp= "La longueur de votre mot de passe doit être entre 5 et 30<br><br>";
			}else{
				$req=mysqli_query($con,"UPDATE utilisateur SET mot_de_passe='$nouveau_mdp_1' WHERE pseudo='$utilisateurConnecte'");
				$msg_mdp="Modifications enregistrées<br><br>";
			}
		}
		else{
			$msg_mdp="Les mots de passe ne sont pas identiques!<br><br>";
		}
	}
	else{
			$msg_mdp="Votre mot de passe actuel est incorrect!<br><br>";
	}
}else{
	$msg_mdp="";
}

 ?>