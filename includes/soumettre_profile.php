<?php  
include("config.php");
include("classes/Utilisateur.php");
include("classes/Post.php");

if(isset($_POST['contenu_post'])) {
	$post = new Post($con, $_POST['envoye_de']);
	$post->publier($_POST['contenu_post'], $_POST['envoye_a'],"");
	header("Location: ../index.php");
}
	
?>