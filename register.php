<?php
include('includes/config.php');
include('includes/inscription.php');
include('includes/connexion.php');
?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="res/css/register.css">	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="res/js/register.js"></script>

	<title>Welcome to Social.ly!</title>
</head>
<body>

	<?php
	//rester sur la meme page s'il ya un erreur
	if(isset($_POST['reg_btn'])){
		echo'<script>
		$(document).ready(function(){
			$("#premier").hide();
			$("#deuxieme").show();
			});

		</script>';} 
	?>


	<div class="wrapper">
		<!-- connexion !-->
		<div class="login_box">
			<div class="login_header">
			 <h1>Social.ly!</h1>
			 Rejoignez Social.ly aujourd'hui.   
			</div>

			<div id="premier">
				<form action="register.php" method="POST">
				<input type="email" name="log_mail" placeholder="E-mail" value="<?php
				if(isset($_SESSION['log_mail'])){
					echo $_SESSION['log_mail'];
				}?>" required>
				<br>
				<input type="password" name="log_mdp" placeholder="Mot de passe" required>
				<br>
				<?php if(in_array("Adresse e-mail ou mot de passe invalide<br>", $tab_err)) echo"Adresse e-mail ou mot de passe invalide<br>"; 
					?>
				<input type="submit" value="Connexion" name="log_btn"> 
				<br>
				<a href="#" id="inscription" class="inscription"> Nouveau sur Social.ly ? S'inscrire maintenant</a>
				
				</form>
			</div>

			<div id="deuxieme">
				<form action="register.php" method="POST">
					<input type="text" name="reg_prenom" placeholder="Votre prénom"value="<?php
					if(isset($_SESSION['reg_prenom'])){
						echo $_SESSION['reg_prenom'];
					}
					?>" required>
					<br>
					<?php if(in_array("La longeur de votre prénom doit être entre 2 et 25<br>", $tab_err)) echo "La longeur de votre prénom doit être entre 2 et 25<br>";?>
					
					
					<input type="text" name="reg_nom" placeholder="Votre nom" value="<?php
					if(isset($_SESSION['reg_nom'])){
						echo $_SESSION['reg_nom'];
					}
					?>" required>
					<br>
					<?php if(in_array("La longeur de votre nom doit être entre 2 et 2<br>", $tab_err)) echo "La longeur de votre nom doit être entre 2 et 25<br>";?>


					<input type="email" name="reg_mail" placeholder="E-mail" value="<?php
					if(isset($_SESSION['reg_mail'])){
						echo $_SESSION['reg_mail'];
					}
					?>"required>
					<br>
					
					<input type="email" name="reg_mail2" placeholder="Confirmer le mail" value="<?php
					if(isset($_SESSION['reg_mail2'])){
						echo $_SESSION['reg_mail2'];
					}
					?>"required>
					<br>
					<?php if(in_array("l'e-mail existe déja<br>", $tab_err)) echo  "l'e-mail existe déja<br>";
					else if(in_array("Format e-mail invalide<br>", $tab_err)) echo  "Format e-mail invalide<br>";
					else if(in_array("Veuillez saisire le même e-mail<br>", $tab_err)) echo  "Veuillez saisire le même e-mail<br>";?>

					<input type="password" name="reg_mdp" placeholder="Mot de passe" required>
					<br>
					<input type="password" name="reg_mdp2" placeholder="Confirmer le mot de passe" required>
					<br>
					<?php if(in_array("Votre mot de passe ne doit contenir que des lettres et des chiffres<br>", $tab_err)) echo  "Votre mot de passe ne doit contenir que des lettres et des chiffres<br>";
					else if(in_array("Veuillez saisire le même mot de passe<br>", $tab_err)) echo"Veuillez saisire le même mot de passe<br>";
					else if(in_array("La longueur de votre mot de passe doit être entre 5 et 30<br>", $tab_err)) echo  "La longueur de votre mot de passe doit être entre 5 et 30<br>";?>


					<input type="submit" name="reg_btn" value="Inscription">
					<br>
					<?php if(in_array("<span style='color:#14C800;'>Votre compte a été créé</span><br>", $tab_err)) echo "<span style='color:#14C800;'>Votre compte a été créé</span><br>";?>
					<a href="#" id="connx" class="connx"> Vous avez un compte ? Connectez-vous </a>
				</form>
			</div>
		</div>
	</div>
</body>
</html>