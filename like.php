<?php
include("includes/config.php");
include("includes/classes/Utilisateur.php");
include("includes/classes/Post.php");

//empecher l'utilisateur d'acceder au site s'il n'est pas connecté
if(isset($_SESSION['pseudo'])){
	$utilisateurConnecte = $_SESSION['pseudo'];
		$reqUtl=mysqli_query($con,"SELECT * FROM utilisateur WHERE pseudo='$utilisateurConnecte'");
		$infoUtlConnecte=mysqli_fetch_array($reqUtl);
}else{
	header("Location: register.php");
}
	//get post id
if(isset($_GET['id_post'])){
	$id_post = $_GET['id_post'];
}
$get_likes=mysqli_query($con,"SELECT likes,ajoute_par FROM post WHERE id='$id_post'");
$tab=mysqli_fetch_array($get_likes);
$total_likes=$tab['likes'];

$req_info_utl = mysqli_query($con, "SELECT * FROM utilisateur WHERE pseudo='$utilisateurConnecte'");
$tab=mysqli_fetch_array($req_info_utl);
$total_likes_utl=$tab['nombre_likes'];
//Bouton like
if(isset($_POST['btn_like'])){
	$total_likes++;
	$req=mysqli_query($con,"UPDATE post SET likes='$total_likes' WHERE id='$id_post'");
	$total_likes_utl++;
	$user_likes=mysqli_query($con,"UPDATE utilisateur SET nombre_likes='$total_likes_utl' WHERE pseudo='$utilisateurConnecte'");
	$inserer_utl=mysqli_query($con,"INSERT INTO likes VALUES(NULL,'$utilisateurConnecte','$id_post')");
}
//Bouton unlike
if(isset($_POST['btn_unlike'])){
	$total_likes--;
	$req=mysqli_query($con,"UPDATE post SET likes='$total_likes' WHERE id='$id_post'");
	$total_likes_utl--;
	$user_likes=mysqli_query($con,"UPDATE utilisateur SET nombre_likes='$total_likes_utl' WHERE pseudo='$utilisateurConnecte'");
	$inserer_utl=mysqli_query($con,"DELETE FROM likes WHERE pseudo='$utilisateurConnecte' AND id_post='$id_post'");
}
//verifier likes recu
$req=mysqli_query($con,"SELECT * FROM likes WHERE pseudo='$utilisateurConnecte' AND id_post='$id_post'");
if(mysqli_num_rows($req) >0){
	echo '<form action="like.php?id_post='.$id_post.'" method="POST">
			<input type="submit" class="like_commnt" name="btn_unlike" value="J\'aime">
				<div class="like_value">'.$total_likes.' J\'aimes
				</div>
			</form>';  
}else{
	echo '<form action="like.php?id_post='.$id_post.'" method="POST">
			<input type="submit" class="like_commnt" name="btn_like" value="J\'aime">
				<div class="like_value">'.$total_likes.' J\'aimes
				</div>
			</form>'; 
} 
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="res/css/style.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	
</head>
<body>
	<style type="text/css">
		*{
			font-family: Arial ,Helvetica ,Sans-serif;
		}
		body{
			background-color: #fff;
		}
		form{
			position: absolute;
			top: 0;
		}
	</style>

</body>
</html>