<?php
include("includes/config.php");
include("includes/classes/Utilisateur.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
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
	<title>Social.ly</title>

	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="res/js/bootstrap.js"></script>
  <script src="res/js/bootbox.min.js"></script>
  <script src="res/js/projet.js"></script>
  <script src="res/js/jquery.jcrop.js"></script>
  <script src="res/js/jcrop_bits.js"></script>
  
	<!-- CSS-->
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="res/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="res/css/style.css">
  <link rel="stylesheet" href="res/css/jquery.Jcrop.css" type="text/css" />
</head>
<body>

  <div class="top_bar">
    <div class="logo">
      <a href="index.php">Social.ly!</a> 
    </div>

    <div class="rechercher">
      <form action="recherche.php" method="GET" name="form_rech_h">
        
        <input type="text" name="q" id="inputRech" placeholder="Rechercher..." autocomplete="off" >
        <div class='btn_holder'>
          <img src="res/image/icones/magnifying_glass.png">
        </div>

      </form>  
    </div>

    <nav>
      <a href="profile.php?pseudo_profile=<?php echo $infoUtlConnecte['pseudo'];?>">
        <?php 
          echo $infoUtlConnecte['prenom'];
        ?>
      </a>
      <a href="index.php">
        <i class="fa fa-home fa-lg"></i>
      </a>
      <a href="messages.php">
        <i class="fa fa-envelope fa-lg"></i>
      </a>
      <a href="demandes.php">
        <i class="fa fa-users fa-lg"></i>
      </a>
      <a href="parametres.php">
        <i class="fa fa-cog fa-lg"></i>
      </a>
       <a href="includes/deconnexion.php">
        <i class="fa fa-sign-out fa-lg"></i>
      </a>
    </nav>

  </div>	

  <div class="wrapper">
