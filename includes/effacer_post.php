<?php 
include('config.php');


	if(isset($_GET['id_post']))
		$id_post=$_GET['id_post'];
	$req=mysqli_query($con,"UPDATE post SET efface='oui' WHERE id='$id_post'");
	$pseudo=mysqli_fetch_array(mysqli_query($con,"SELECT ajoute_par FROM post WHERE id='$id_post'"));
	$pseudo=$pseudo['ajoute_par'];
	$req=mysqli_query($con,"UPDATE utilisateur SET nombre_posts=nombre_posts - 1 WHERE pseudo='$pseudo'");
	header("Location: ../index.php");  
 ?>