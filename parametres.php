<?php 
include("includes/header.php");
include("includes/gest.php"); //gerer les form
?>


<div class="colonne_principale colonne">
	<h4>Paramètres du compte</h4>
	<?php 
	echo"<img src='".$infoUtlConnecte['photo_de_profile']."' id='photo_profil_s'>"
	?>
	<br>
	<a href="upload.php">Importer une photo</a><br><br><br>

	<h4>Modifier vos informations personnelles</h4>
	<?php 
	$req=mysqli_query($con,"SELECT prenom,nom,email FROM utilisateur WHERE pseudo='$utilisateurConnecte'");
	$tab=mysqli_fetch_array($req);
	$prenom=$tab['prenom'];
	$nom=$tab['nom'];
	$em=$tab['email'];
	?>
	<form action="parametres.php" method="POST">
		<table>
			<tr>
				<td>Prénom: </td> 
				<td><input type="text" name="prenom" value="<?php echo $prenom;?>" class="parmInput"></td>
			</tr>
			<tr>
				<td>Nom: </td> 
				<td><input type="text" name="nom" value="<?php echo $nom;?>" class="parmInput"></td>
			</tr>
			<tr>
				<td>Email: </td> 
				<td><input type="text" name="email" value="<?php echo $em;?>" class="parmInput"></td>
			</tr>
			<tr>
				<td><?php echo $msg;?></td>
			</tr>
			<tr>
				<td><input type="submit" name="parm_perso" value="Enregistrer" class="info parmSubmit"></td>
			</tr>
		</table>
	</form>
	<br>
	<h4>Modifier votre mot de passe</h4>

	<form action="parametres.php" method="POST">
		<table>
			<tr>
				<td>Mot de passe actuel: </td> 
				<td><input type="password" name="a_mdp" class="parmInput"></td>
			</tr>
			<tr>
				<td>Nouveau mot de passe: </td> 
				<td><input type="password" name="n_mdp_1" class="parmInput"></td>
			</tr>
			<tr>
				<td>Confirmer le mot de passe: </td> 
				<td><input type="password" name="n_mdp_2" class="parmInput"></td>
			</tr>
			<tr>
				<td><?php echo $msg_mdp;?></td>
			</tr>
			<tr>
				<td><input type="submit" name="parm_mdp" value="Enregistrer" class="info parmSubmit"></td>
			</tr>
		</table>
		
	</form>
</div>