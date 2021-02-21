<?php 
class Post {
	private $user;
	private $con;

	public function __construct($con, $user){
		$this->con=$con;
		$this->user= new Utilisateur($con, $user);
	}

	public function publier($contenu,$destine_a){
		$contenu=strip_tags($contenu);
		$contenu = mysqli_real_escape_string($this->con, $contenu);
		$test_vide=preg_replace('/\s+/', '', $contenu);//effacer les espaces

		if($test_vide != ""){

			$tab_contenu = preg_split("/\s+/", $contenu);//split by spaces
			//embed video youtube
			foreach ($tab_contenu as $key => $value) {
				if(strpos($value, "www.youtube.com/watch?v=") !== false){
					
					$lien=preg_split("!&!", $value);
					$value=preg_replace("!watch\?v=!", "embed/", $lien[0]);
					$value = "<br><iframe width=\'420\' height=\'315\' src=\'".$value."\'</iframe><br>";
					$tab_contenu[$key]=$value;
				}
			}
			$contenu=implode(" ", $tab_contenu);




			$date_ajout=date("Y-m-d H:i:s");

			$ajoute_par = $this->user->getPseudo();
			if($destine_a == $ajoute_par){
				$destine_a = "null";
			}
			//inserer post
			$requete = mysqli_query($this->con,"INSERT INTO post Values(NULL,'$contenu','$ajoute_par','$destine_a','$date_ajout','non','0')");

			//mettre a jour nbre posts
			$nombre_posts=$this->user->getNombrePosts();
			$nombre_posts++;
			$requete=mysqli_query($this->con,"UPDATE utilisateur SET nombre_posts='$nombre_posts' WHERE pseudo='$ajoute_par'");
		}
	}

	public function chargerPostAmis(){
		$utlConnecte=$this->user->getPseudo();
		$s="";
		$info = mysqli_query($this->con,"SELECT * FROM post WHERE efface='non' ORDER BY id DESC ");

		while($tab=mysqli_fetch_array($info)){
			$id= $tab['id'];
			$contenu = $tab['contenu'];
			$ajoute_par = $tab['ajoute_par'];
			$date_ajout = $tab['date_ajout'];

			if($tab['destine_a'] == "null"){
				$destine_a="";
			}else{
				$utl_dest = new Utilisateur($this->con,$tab['destine_a']);
				$nom_utl_dest=$utl_dest->getNom();
				$destine_a= "à <a href='".$tab['destine_a']."'>".$nom_utl_dest."</a>";
			}

			$obj_utl_connecte = new Utilisateur($this->con,$utlConnecte);
			if($obj_utl_connecte->isFriend($ajoute_par))
			{

				if($utlConnecte==$ajoute_par){
					$btn_supprime="<form action='includes/effacer_post.php?id_post=".$id."' method='POST' id='form_supprimer'>
										<button type='button' class='btn_supprimer btn-danger' id='post$id'>X</button>
									</form>";
				}else{
					$btn_supprime="";
				}

				$requete_info_utl= mysqli_query($this->con,"SELECT nom,prenom,photo_de_profile,pseudo FROM utilisateur WHERE pseudo='$ajoute_par'");
				$tab_utl=mysqli_fetch_array($requete_info_utl);
				$prenom=$tab_utl['prenom'];
				$nom=$tab_utl['nom'];
				$ajoute_par=$tab_utl['pseudo'];
				$photo=$tab_utl['photo_de_profile'];
?>
			<script >
			function afficher<?php echo $id; ?>(){
				var element = document.getElementById("afficherCommentaire<?php echo $id;?>");
				if(element.style.display == "block")
					element.style.display = "none";
				else 
					element.style.display = "block";
			}
			</script>
				<?php
				$test_commnt=mysqli_query($this->con,"SELECT * FROM commentaire WHERE id_post='$id'");
				$nbr_test_commnt=mysqli_num_rows($test_commnt);
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
						$date_message="Il y a ".$diff->m." mois et ".$jours;
				}
				else if ($diff->d>=1){
					if($diff->d==1)
						$date_message="Hier";
					else 
						$date_message="Il y a ".$diff->d." jours";
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
				$s.="<div class='status' onclick='javascript:afficher$id()'>
						<div class='photo_profile_post'>
							<img src='$photo' width='50'>
						</div>

						<div class='publier_par' style='color:#ACACAC;'>
							<a href='profile.php?pseudo_profile=$ajoute_par'>$prenom $nom </a> $destine_a &nbsp;&nbsp;&nbsp;&nbsp;$date_message $btn_supprime
								
						</div>
						<div id='contenu'>
							$contenu
							<br>
							<br>
							<br>
						</div>

						<div class='optionsPosts'>
							Commentaires($nbr_test_commnt)&nbsp;&nbsp;&nbsp;
							<iframe src='like.php?id_post=$id' scrolling='no'></iframe>
						</div>

					</div>

					<div class='post_comment' id='afficherCommentaire$id' style='display:none;'>
						<iframe src='frame_commentaire.php?id_post=$id' id='iframe_commentaire' frameborder=0></iframe>
					</div>
					<hr>";

			}
?>
			<script >
				$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Supprimer ce post?", function(result) {
								if(result){
									$('#form_supprimer').submit();
								}
							});
							});
						});
			</script>
<?php
		}//fin while

		echo $s;
	}



	public function chargerPostProfil($profileUtl){
		$utlConnecte=$this->user->getPseudo();
		$s="";
		$info = mysqli_query($this->con,"SELECT * FROM post WHERE efface='non' AND ((ajoute_par='$profileUtl' AND destine_a='null') OR destine_a='$profileUtl')  ORDER BY id DESC");

		while($tab=mysqli_fetch_array($info)){
			$id= $tab['id'];
			$contenu = $tab['contenu'];
			$ajoute_par = $tab['ajoute_par'];
			$date_ajout = $tab['date_ajout'];

			$obj_utl_connecte = new Utilisateur($this->con,$utlConnecte);

			if($utlConnecte==$ajoute_par){
					$btn_supprime="<form action='includes/effacer_post.php?id_post=".$id."' method='POST' id='form_supprimer'>
										<button type='button' class='btn_supprimer btn-danger' id='post$id'>X</button>
									</form>";
			}else{
					$btn_supprime="";
			}

			$requete_info_utl= mysqli_query($this->con,"SELECT nom,prenom,photo_de_profile,pseudo FROM utilisateur WHERE pseudo='$ajoute_par'");
			$tab_utl=mysqli_fetch_array($requete_info_utl);
			$prenom=$tab_utl['prenom'];
			$nom=$tab_utl['nom'];
			$ajoute_par=$tab_utl['pseudo'];
			$photo=$tab_utl['photo_de_profile'];
?>
			<script >
			function afficher<?php echo $id; ?>(){
				var element = document.getElementById("afficherCommentaire<?php echo $id;?>");
				if(element.style.display == "block")
					element.style.display = "none";
				else 
					element.style.display = "block";
			}
			</script>
				<?php
				$test_commnt=mysqli_query($this->con,"SELECT * FROM commentaire WHERE id_post='$id'");
				$nbr_test_commnt=mysqli_num_rows($test_commnt);
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
				
				$s.="<div class='status' onclick='javascript:afficher$id()'>
						<div class='photo_profile_post'>
							<img src='$photo' width='50'>
						</div>

						<div class='publier_par' style='color:#ACACAC;'>
							<a href='profile.php?pseudo_profile=$ajoute_par'>$prenom $nom </a> &nbsp;&nbsp;&nbsp;&nbsp;$date_message $btn_supprime
						</div>
						<div id='contenu'>
							$contenu
							<br>
							<br>
							<br>
						</div>

						<div class='optionsPosts'>
							Commentaires($nbr_test_commnt)&nbsp;&nbsp;&nbsp;
							<iframe src='like.php?id_post=$id' scrolling='no'></iframe>
						</div>

					</div>

					<div class='post_comment' id='afficherCommentaire$id' style='display:none;'>
						<iframe src='frame_commentaire.php?id_post=$id' id='iframe_commentaire' frameborder=0></iframe>
					</div>
					<hr>";
?>

			<script >
				$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Supprimer ce post?", function(result) {
								if(result){
									$('#form_supprimer').submit();
								}
							});
							});
						});
			</script>
<?php
		}//fin while

		echo $s;
	}



}


?>