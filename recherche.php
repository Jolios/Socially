<?php 

include("includes/header.php");

if(isset($_GET['q'])) {
	$requete = $_GET['q'];
}

if(isset($_GET['type'])){
	$type=$_GET['type'];
 }else {
 	$type = "nom";
 }
 ?>

 <div class="colonne_principale colonne" id="colonne_principale">
 	
 	<?php 
 	
	if($type == 'pseudo'){
		$resUtl = mysqli_query($con,"SELECT * FROM utilisateur WHERE pseudo LIKE '$requete%'");
	}else{
		$noms=explode(" ", $requete);

		if(count($noms) == 2){
		$resUtl = mysqli_query($con,"SELECT * FROM utilisateur WHERE prenom LIKE '%$noms[0]%' AND nom LIKE '%$noms[1]%'");
		}else{
		$resUtl = mysqli_query($con,"SELECT * FROM utilisateur WHERE prenom LIKE '%$noms[0]%' OR nom LIKE '%$noms[0]%'");
		}
	}

	//verifier si on trouve des reultats
	if(mysqli_num_rows($resUtl) == 0)
		echo "Aucun résultat n'a été trouvé pour cette recherche.";
	else 
		echo mysqli_num_rows($resUtl)." résultat(s) trouvé(s): <br><br>";

	echo "<p id='gris'>Essayez de rechercher:</p>";
	echo "<a href='recherche.php?q=".$requete."&type=nom'>Noms</a>, <a href='recherche.php?q=".$requete."&type=pseudo'>Pseudos</a><br><br> <hr id='hrRech'>";

	while($tab=mysqli_fetch_array($resUtl) ){
 	 	$obj_utl=new Utilisateur($con,$infoUtlConnecte['pseudo']);
 	 	$btn="";
 	 	$amis_commun="";

 	 	if($infoUtlConnecte['pseudo']!=$tab['pseudo']){
 	 			if($obj_utl->isFriend($tab['pseudo']))
					$btn ="<input type='submit' name='".$tab['pseudo']."' class='danger' value='Retirer Ami'><br>";
				else if($obj_utl->recuDemande($tab['pseudo']))
					$btn ="<input type='submit' name='".$tab['pseudo']."' class='warning' value='Répondre'><br>";
				else if($obj_utl->envoieDemande($tab['pseudo']))
					$btn ="<input type='submit' name='".$tab['pseudo']."' class='default' value='Demande envoyée'><br>";
				else
					$btn ="<input type='submit' name='".$tab['pseudo']."'class='success' value='Ajouter Ami'><br>";

				$amis_commun=$obj_utl->getNombreAmisCommuns($tab['pseudo'])." Ami(s) en commun";

				//boutons
				if(isset($_POST[$tab['pseudo']])){

					if($obj_utl->isFriend($tab['pseudo'])){
						$obj_utl->retirerAmi($tab['pseudo']);
						header("Refresh:0");
					}else if($obj_utl->recuDemande($tab['pseudo'])){
						header("Location: demandes.php");
					}else{
						$obj_utl->envoyerDemande($tab['pseudo']);
						header("Refresh:0");
					}
				}
				
		}

		echo "<div class='res_rech'>
				<div class='boutonsAmi'>
					<form action='' method='POST'>
					".$btn."
					</form>
				</div>

				<div class='photo_profil_res'>
					<a href='profile.php?pseudo_profile=".$tab['pseudo']."'>
						<img src='".$tab['photo_de_profile']."' style='height: 100px;'>
					</a>
				</div>
				<a href='profile.php?pseudo_profile=".$tab['pseudo']."'>".$tab['prenom']." ".$tab['nom']."<p id='gris'>". $tab['pseudo']."</p>
				</a>
				<br>
				".$amis_commun."<br>

			   </div>
			   <hr id='hrRech'>";
 	 	
	}
	?>
 </div>
