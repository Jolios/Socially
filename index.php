<?php 
	include("includes/header.php"); 
	

	if(isset($_POST['post'])){
			$post = new Post($con,$utilisateurConnecte);
			$post->publier($_POST['post_text'],'null');
			header("Refresh:0");
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

	<div class="colonne_principale colonne">
		<form class="post_form" action="index.php" method="POST" >
			<!--<input type="file" name="fileUpload" id="fileUpload">-->
			<textarea name="post_text" id="post_text" placeholder="Quoi de neuf ?"></textarea>
			<input type="submit" name="post" id="btn_post" value="Publier">
			<hr>
		</form>
		<!--charger les posts-->
		<?php 
		$post = new Post($con,$utilisateurConnecte);
		$post->chargerPostAmis();
		?>

	</div>
	

	</div>
</body>
</html>