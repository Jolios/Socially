<?php 
include("includes/header.php");
?>

<div class="colonne_principale colonne" id="colonne_principale">
	<h4>Demandes d'amitié</h4>
	<?php 
	$req=mysqli_query($con,"SELECT * FROM invitation WHERE envoye_a='$utilisateurConnecte'");
	if(mysqli_num_rows($req)==0){
		echo "Vous n'avez aucune demande d'amitié pour l'instant!";
	}else{
		while ($tab=mysqli_fetch_array($req)) {
			$envoye_de=$tab['envoye_de'];
			$obj_envoye_de=new Utilisateur($con,$envoye_de);

			echo $obj_envoye_de->getNom()." vous a envoyé une demande d'amitié";
			$tab_amis=$obj_envoye_de->getTabAmis();
			if(isset($_POST['accepter'.$envoye_de])){
				$req=mysqli_query($con, "UPDATE utilisateur SET tab_amis=CONCAT(tab_amis, '$envoye_de,') WHERE pseudo='$utilisateurConnecte'");
				$req=mysqli_query($con, "UPDATE utilisateur SET tab_amis=CONCAT(tab_amis, '$utilisateurConnecte,') WHERE pseudo='$envoye_de'");
				$req=mysqli_query($con,"DELETE FROM invitation WHERE envoye_a='$utilisateurConnecte' AND envoye_de='$envoye_de'");
				echo "Vous êtes désormais amis";
				header("Location: demandes.php");
			}
			if(isset($_POST['ignorer'.$envoye_de])){
				$req=mysqli_query($con,"DELETE FROM invitation WHERE envoye_a='$utilisateurConnecte' AND envoye_de='$envoye_de'");
				echo "Demande d'amitié ignorée!";
				header("Location: demandes.php");

			}
			?>
		<form action="demandes.php" method="POST">
		 	<input type="submit" name="accepter<?php echo $envoye_de;?>" id="btn_accepter" value="Accepter">
		 	<input type="submit" name="ignorer<?php echo $envoye_de;?>" id="btn_ignorer" value="Ignorer">
		</form>
<?php  
		
		}
	}

?>

	
</div>