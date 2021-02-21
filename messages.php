<?php 
include("includes/header.php");

$msg=new Message($con,$utilisateurConnecte);

if(isset($_GET['u']))
	$envoye_a=$_GET['u'];
else{
	$envoye_a=$msg->getContactRecent();
	if($envoye_a==false)
		$envoye_a="nouveau";
}

if($envoye_a!="nouveau")
	$obj_envoye_a=new Utilisateur($con,$envoye_a);
if(isset($_POST['post_msg'])){
	if(isset($_POST['contenu_msg'])){
		$contenu=$_POST['contenu_msg'];
		$date=date("Y-m-d H:i:s");
		$msg->envoyerMessage($envoye_a,$contenu,$date);
	}
}
$s="";
if(isset($_POST['q'])){
	$requete=$_POST['q'];
	$noms=explode(" ", $requete);

	if(strpos($requete, "_") !== false){
		$resUtl = mysqli_query($con,"SELECT * FROM utilisateur WHERE pseudo LIKE '$requete%'");
	}else if(count($noms) == 2){
		$resUtl = mysqli_query($con,"SELECT * FROM utilisateur WHERE prenom LIKE '%$noms[0]%' AND nom LIKE '%$noms[1]%'");
	}else{
		$resUtl = mysqli_query($con,"SELECT * FROM utilisateur WHERE prenom LIKE '%$noms[0]%' OR nom LIKE '%$noms[0]%'");
	}
	if($requete != ""){
		while($tab = mysqli_fetch_array($resUtl)){
			$user = new Utilisateur($con,$utilisateurConnecte) ;
			if($tab['pseudo'] != $utilisateurConnecte){
				$amis_commun = $user->getNombreAmisCommuns($tab['pseudo'])." Amis en commun";
			}else{
				$amis_commun="";
			}
			if($user->isFriend($tab['pseudo'])){
				$s.="<div class='affichage'>
						<a href='messages.php?u=".$tab['pseudo']."' style='color:#000'>
							<div class='PhotoProfileRech'>
								<img src='".$tab['photo_de_profile']."'>
							</div>

							<div class='TextRech'> ".$tab['prenom']." ".$tab['nom']."
								<p style='margin:0;'>".$tab['pseudo']."</p>
								<p id='gris' >".$amis_commun."</p>
							</div>
						</a>
					</div>";
			}
		}
	}
}
?>





<div class="info_utl colonne">
		<a href="profile.php?pseudo_profile=<?php echo $infoUtlConnecte['pseudo'];?>"> <img src="<?php echo $infoUtlConnecte['photo_de_profile'] ?>"></a>
		<div class="info_utl_g_d">
			<a href="profile.php?pseudo_profile=<?php echo $infoUtlConnecte['pseudo'];?>">
				<?php 
				echo $infoUtlConnecte['prenom']." ".$infoUtlConnecte['nom']; ?>
			</a>
			<br>
			<?php 
			echo "Posts: ".$infoUtlConnecte['nombre_posts'].'<br>';
			echo "J'aime: ".$infoUtlConnecte['nombre_likes'];
			 ?>
		</div>
	</div>

	<div class="colonne_principale colonne" id="colonne_principale">
		<?php 
		if($envoye_a!="nouveau"){
			echo "<h4>Vous et <a href='profile.php?pseudo_profile=$envoye_a'>".$obj_envoye_a->getNom()."</a></h4><hr><br>";
			echo "<div class='messages_charge' id='scroll_msg'>";
			echo $msg->getMessages($envoye_a);
			echo "</div>";
		}else{
			echo "<h4>Nouveau Message</h4>";
		}

		 ?>

		 <div class="message_post">
		 	<form action="" method="POST" id='form_rech'>
		 		<?php 
		 		if($envoye_a == "nouveau"){
		 			echo "Sélectionnez l'utilisateur auquel vous souhaitez envoyer un message <br><br>";
		 			echo "À: <input type='text' name='q' placeholder='Nom' autocomplete='off' id='input_rechercher'>";
		 			echo "<div class='results'>$s</div>";
		 		}else{
		 			echo "<textarea name='contenu_msg' id='msg_textarea' placeholder='Écrivez un message...'></textarea>";
		 			echo "<input type='submit' name='post_msg' class='info' id='msg_sub' value='Envoyer' >";
		 		}

		 		 ?>
		 	</form>
		 	
		 </div>
		 <script >
		 	//aller au dernier msg
		 	var div=document.getElementById("scroll_msg");
		 	div.scrollTop=div.scrollHeight;
		 </script>
</div>
 


<div class="info_utl colonne" id="convos">
		 	<h4>Conversations</h4>
		 	<div class="convos_charge">
		 		<?php echo $msg->getConvos() ?>
		 	</div>
		 	<br>
		 	<a href="messages.php?u=nouveau">Nouveau Message</a>
		 	
</div>