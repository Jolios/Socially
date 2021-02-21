 <?php 
class Utilisateur {
	private $user;
	private $con;

	public function __construct($con, $user){
		$this->con=$con;
		$info_utl=mysqli_query($con,"SELECT * FROM utilisateur WHERE pseudo='$user'");
		$this->user= mysqli_fetch_array($info_utl);
	}

	public function getNom(){
		return $this->user['prenom']." ".$this->user['nom'];
	}

	public function getPseudo(){
		return $this->user['pseudo'];
	}
	public function getNombrePosts(){
		return $this->user['nombre_posts'];
	}
	public function getTabAmis(){
		return $this->user['tab_amis'];
	}
	public function isFriend($pseudo_a_verifier){
		$pseudo_a_verifier_virgule = ",".$pseudo_a_verifier.",";
		if(strstr($this->user['tab_amis'], $pseudo_a_verifier_virgule) || $pseudo_a_verifier==$this->user['pseudo']) 
			return true;
		else
			return false;
	}
	public function getPhotoProfile(){
		return $this->user['photo_de_profile'];
	}
	//verifier si l'utilisateur a recu une invitation
	public function recuDemande($envoye_de){
		$envoye_a=$this->user['pseudo'];
		$req=mysqli_query($this->con,"SELECT * FROM invitation WHERE  envoye_a='$envoye_a' AND envoye_de='$envoye_de'");
		if(mysqli_num_rows($req) > 0){
			return true;
		}else{
			return false;
		}  

	}
	//verifier si l'utilisateur a envoyé une invitation
	public function envoieDemande($envoye_a){
		$envoye_de=$this->user['pseudo'];
		$req=mysqli_query($this->con,"SELECT * FROM invitation WHERE  envoye_a='$envoye_a' AND envoye_de='$envoye_de'");
		if(mysqli_num_rows($req) > 0){
			return true;
		}else{
			return false;
		}

	}
	public function retirerAmi($utl_a_ret){
		$utlConnecte=$this->user['pseudo'];
		$req=mysqli_query($this->con,"SELECT tab_amis FROM utilisateur WHERE pseudo='$utl_a_ret'");
		$tab=mysqli_fetch_array($req); 
		$tab_amis=$tab['tab_amis'];
		$tab_amis=str_replace($utl_a_ret.",", "", $this->user['tab_amis']);
		$req=mysqli_query($this->con,"UPDATE utilisateur SET tab_amis='$tab_amis' WHERE pseudo='$utlConnecte'"); 
		$tab_amis=str_replace($this->user['pseudo'].",", "", $tab['tab_amis'] );
		$req=mysqli_query($this->con,"UPDATE utilisateur SET tab_amis='$tab_amis' WHERE pseudo='$utl_a_ret'"); 
	}
	public function envoyerDemande($envoye_a){
		$envoye_de=$this->user['pseudo'];
		$req=mysqli_query($this->con, "INSERT INTO invitation VALUES(NULL,'$envoye_a','$envoye_de')");
	}
	public function getNombreAmisCommuns($utilisateur){
		$amisCommuns = 0;
		$tab_amis = $this->user['tab_amis'];
		//$tab_amis_implode = implode("", $tab_amis);
		$tab_amis=explode(",",$tab_amis);
		$req = mysqli_query($this->con, "SELECT tab_amis FROM utilisateur WHERE pseudo='$utilisateur'");
		$tab = mysqli_fetch_array($req);
		$tab_utl = $tab['tab_amis'];
		//$tab_utl_implode = implode("", $tab_utl);
		$tab_utl=explode(",",$tab_utl);

		foreach($tab_amis as $i) {

			foreach($tab_utl as $j) {

				if($i == $j && $i != "") {
					$amisCommuns++;
				}
			}
		}
		return $amisCommuns;

		
	}
}


?>