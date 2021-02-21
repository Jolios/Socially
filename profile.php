<?php 
include("includes/header.php");

$msg=new Message($con, $utilisateurConnecte);

if(isset($_GET['pseudo_profile'])){
	$pseudo=$_GET['pseudo_profile'];
	$req_info_utl=mysqli_query($con,"SELECT * FROM utilisateur WHERE pseudo='$pseudo'");
	$tab=mysqli_fetch_array($req_info_utl);
	$nbre_amis=(substr_count($tab['tab_amis'], ","))-1;
}

if(isset($_POST['retirer_amis'])){
	$utl=new Utilisateur($con,$utilisateurConnecte);
	$utl->retirerAmi($pseudo);

}
if(isset($_POST['add_friend'])){
	$utl=new Utilisateur($con,$utilisateurConnecte);
	$utl->envoyerDemande($pseudo);
}
if(isset($_POST['rep_invit'])){
	header("Location: demandes.php");
	
}
if(isset($_POST['post_msg'])){
	if(isset($_POST['contenu_msg'])){
		$contenu=$_POST['contenu_msg'];
		$date=date("Y-m-d H:i:s");
		$msg->envoyerMessage($pseudo,$contenu,$date);
	}
	$lien='#myTab a[href="#msg_div"]';
}
 ?>
 	<style type="text/css">
 		.wrapper{
 			margin-left: 0px;
 			padding-left: 0px;
 		}
 	</style>

	<div class="profile_g">
		<img src="<?php echo $tab['photo_de_profile'];?>">
		<div class="info_profile">
			<p><?php echo "Posts: ".$tab['nombre_posts'];?></p>
			<p><?php echo "J'aimes: ".$tab['nombre_likes'];?></p>
			<p><?php echo "Amis: ".$nbre_amis;?></p>
		</div>

		<form action="<?php echo 'profile.php?pseudo_profile='.$pseudo;?>" method="POST">
			<?php 
			$obj_utl_connecte= new Utilisateur($con,$utilisateurConnecte);
			if($utilisateurConnecte != $pseudo){
				if($obj_utl_connecte->isFriend($pseudo)){
					echo '<input type="submit" name="retirer_amis" class="danger" value="Retirer Ami"><br>';
				}else if($obj_utl_connecte->recuDemande($pseudo)){
					echo '<input type="submit" name="rep_invit" class="warning" value="Répondre"><br>';
				}else if($obj_utl_connecte->envoieDemande($pseudo)){
					echo '<input type="submit" name="" class="default" value="Demande envoyée"><br>';
				}else{
					echo '<input type="submit" name="add_friend" class="success" value="Ajouter Ami"><br>';
				}
			}
			?>
			</form>
			<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Publier">

			<?php 
			if($utilisateurConnecte!=$pseudo) {
				echo '<div class="info_profile_b">';
				echo $obj_utl_connecte->getNombreAmisCommuns($pseudo)." Ami(s) en commun";
				echo '</div>';
			}
			?>
			
	</div>

	<div class="colonne_principale_profil colonne">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
		  <li class="nav-item">
		    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#filActu" role="tab" aria-controls="home" aria-selected="true">Fil d'actualité</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#msg_div" role="tab" aria-controls="contact" aria-selected="false">Messages</a>
		  </li>
		</ul>
		<div class="tab-content" id="myTabContent">
		  <div class="tab-pane fade show active" id="filActu" role="tabpanel" aria-labelledby="home-tab">
		  	<!--charger les posts-->
			<?php 
			$post = new Post($con,$utilisateurConnecte);
			$post->chargerPostProfil($pseudo);
			?>
		  </div>

			 <div class="tab-pane fade" id="msg_div" role="tabpanel" aria-labelledby="contact-tab">
			  	<?php 
			  		$msg=new Message($con, $utilisateurConnecte);
			  		$utl=new Utilisateur($con,$pseudo);
					echo "<h4>Vous et <a href='profile.php?pseudo_profile=".$pseudo."''>".$utl->getNom()."</a></h4><hr><br>";
					echo "<div class='messages_charge' id='scroll_msg'>";
					echo $msg->getMessages($pseudo);
					echo "</div>";
				?>

				<div class="message_post">
				 	<form action="" method="POST" id='form_rech'>
				 		<textarea name='contenu_msg' id='msg_textarea' placeholder='Écrivez un message...'></textarea>
				 		<input type='submit' name='post_msg' class='info' id='msg_sub' value='Envoyer' >

				 	</form>
				</div>

				<script>
				 	//aller au dernier msg
				 	var div=document.getElementById("scroll_msg");
				 	div.scrollTop=div.scrollHeight;
				</script>
			 </div>
		</div>
	</div>
	<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Exprimez-vous</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="profile_post" action="includes/soumettre_profile.php" method="POST" id="form_profile">
	      <div class="modal-body">
	      	<p>Ceci va appraître sur le profil de l'utilisateur!</p>
	      		<div class="form-group">
	      			<textarea class="form-control" name="contenu_post"></textarea>
	      			<input type="hidden" name="envoye_de" value="<?php echo $utilisateurConnecte;?>">
	      			<input type="hidden" name="envoye_a" value="<?php echo $pseudo;?>">
	      		</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
	        <button type="button" class="btn btn-primary" name="btn_post" id="submit_post_profile">Publier</button>
	      </div>
      </form>
    </div>
  </div>
</div>

</div>
</body>
</html>