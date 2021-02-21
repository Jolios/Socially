<?php
include("includes/config.php");
include("includes/classes/Utilisateur.php");
include("includes/classes/Post.php");

//empecher l'util d'acceder au site s'il n'est pas connecté
if(isset($_SESSION['pseudo'])){
	$utilisateurConnecte = $_SESSION['pseudo'];
		$reqUtl=mysqli_query($con,"SELECT * FROM utilisateur WHERE pseudo='$utilisateurConnecte'");
		$infoUtlConnecte=mysqli_fetch_array($reqUtl);
}else{
	header("Location: register.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="res/css/style.css">
</head>
<body>
	<style type="text/css">
		*{
			font-size: 12px;
			font-family: Arial, Helvetica, Sans-serif;
		}
	</style>

	<script >
		function afficher(){
			var element = document.getElementById("frameCommentaires");
			if(element.style.display == "block")
				element.style.display = "none";
			else 
				element.style.display = "block";
		}
	</script>
	<?php 
	//get post id
	if(isset($_GET['id_post'])){
		$id_post = $_GET['id_post'];
	}
	$req_utl = mysqli_query($con, "SELECT ajoute_par, destine_a FROM post WHERE id='$id_post'");
	$tab = mysqli_fetch_array($req_utl);
	$poste_a = $tab['ajoute_par'];

	if(isset($_POST['postComment' . $id_post])){
		$contenu_post=$_POST['contenu_post'];
		$date_courante = date("Y-m-d H:i:s");
		$inserer_post = mysqli_query($con,"INSERT INTO commentaire VALUES(NULL,'$contenu_post','$utilisateurConnecte','$poste_a','$date_courante','non','$id_post')");
		echo "<p>Commentaire publié</p>";
	}
	?>
	<form action="frame_commentaire.php?id_post=<?php echo $id_post; ?>" id="comment_form" method="POST" name="postComment<?php echo $id_post; ?>">
		<textarea name="contenu_post"></textarea>
		<input type="submit" name="postComment<?php echo $id_post; ?>" value="Publier">
	</form>
	<br>
	<!--afficher commentaires-->
	<?php 
	$req_commnt=mysqli_query($con,"SELECT * FROM commentaire WHERE id_post='$id_post' ORDER BY id ASC");
	$nbr= mysqli_num_rows($req_commnt);
	if($nbr!=0){
		while($commentaire=mysqli_fetch_array($req_commnt)){
			$contenut_commnt=$commentaire['contenu_post'];
			$poste_a=$commentaire['destine_a'];
			$poste_par=$commentaire['ajoute_par'];
			$date_ajout=$commentaire['date_ajout'];
			$efface=$commentaire['efface'];
			//date et heure
			$date_ajout=new DateTime($date_ajout);
			$date_courante=new DateTime(date("Y-m-d H:i:s"));
			$diff=$date_ajout->diff($date_courante);
			if($diff->y>=1){
				if($diff->y==1)
					$date_message="Il y a ". $diff->y . " an";
				else
					$date_message="Il y a ". $diff->y . " ans";
			}
			else if ($diff->m>=1){
				$jours="";				
				if($diff->d==1)
					$jours=$diff->d." jour";
				else if($diff->d>1)
					$jours=$diff->d." jours";
					$date_message="Il y a".$diff->m." mois et ".$jours;
			}
			else if ($diff->d>=1){
				if($diff->d==1)
					$date_message="Hier";
				else 
					$date_message="Il y a".$diff->d." jours";
			}
			else if($diff->h >= 1){
				if($diff->h == 1)
					$date_message = "Il y a ".$diff->h ." heure";
				else
					$date_message = "Il y a ".$diff->h." heures";

				
			}
			else if($diff->m >= 1){
				if($diff->m == 1)
					$date_message = "Il y a ".$diff->m ." minute";
				else
					$date_message = "Il y a ".$diff->m." minutes";

				
			}
			else {
				if($diff->s < 30)
					$date_message = "A l'instant";
				else
					$date_message = "Il y a ".$diff->s." secondes";

			}

			$obj_utl = new Utilisateur($con,$poste_par);
			?>
			<div class="frameCommentaires">
		<a href="<?php echo $poste_par;?>" target="_parent">
		<img src="<?php echo $obj_utl->getPhotoProfile();?>" title="<?php echo $poste_par?>" style="float:left;" height="30">
		</a>
		<a href="profile.php?pseudo_profile=$1" target="_parent"><b><?php echo $obj_utl->getNom()?></b></a>
		&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $date_message."<br>".$contenut_commnt ?>
		<hr>
		</div>
	<?php
	}//fin while
		
		
	}//fin if
	else {
		echo "<center><br><br>Aucun Commentaire à afficher</center>";
	}
?>
	




</body>
</html>